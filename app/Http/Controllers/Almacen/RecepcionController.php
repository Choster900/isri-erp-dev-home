<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Models\DetDocumentoAdquisicion;
use App\Models\DocumentoAdquisicion;
use App\Models\Producto;
use App\Models\RecepcionPedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecepcionController extends Controller
{
    public function getRecepciones(Request $request)
    {
        $columns = ['id_recepcion_pedido', 'documento', 'item', 'acta_recepcion_pedido', 'fecha_recepcion_pedido', 'id_estado_recepcion_pedido'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = RecepcionPedido::select('*')
            ->with([
                'detalle_recepcion',
                'det_doc_adquisicion.documento_adquisicion.tipo_documento_adquisicion',
                'estado_recepcion'
            ]);

        $data = $query->paginate($length)->onEachSide(1);
        return ['data' => $data, 'draw' => $request->input('draw')];
    }

    public function getInfoModalRecep(Request $request, $id)
    {
        $recep = RecepcionPedido::with([
            'detalle_recepcion.producto',
            'det_doc_adquisicion.documento_adquisicion.tipo_documento_adquisicion',
            'estado_recepcion'
        ])->find($id);

        if ($recep->det_doc_adquisicion->estado_det_doc_adquisicion == 0) {
            $data = [
                'id_estado_recepcion_pedido'            => 3,
                'fecha_mod_permiso'                     => Carbon::now(),
                'usuario_permiso'                       => $request->user()->nick_usuario,
                'ip_permiso'                            => $request->ip(),
            ];
            $recep->update($data);
            return response()->json([
                'logical_error' => 'Error, el item del documento asociado ha sido eliminado.',
            ], 422);
        } else {
            $subQuery = DB::table('recepcion_pedido as rp')
                ->join('detalle_recepcion_pedido as drp', 'rp.id_recepcion_pedido', 'drp.id_recepcion_pedido')
                ->selectRaw(
                    'drp.id_producto,
                    SUM(drp.cant_det_recepcion_pedido) AS registrados'
                )
                ->where('rp.id_estado_recepcion_pedido', '!=', 3)
                ->where('drp.estado_det_recepcion_pedido', 1)
                ->where('drp.id_recepcion_pedido', '!=', $recep->id_recepcion_pedido)
                ->where('rp.id_det_doc_adquisicion', $recep->det_doc_adquisicion->id_det_doc_adquisicion)
                ->groupByRaw('drp.id_producto, drp.cant_det_recepcion_pedido');

            $productos = DB::table('detalle_documento_adquisicion as dda')
                ->join('producto_adquisicion as pa', 'dda.id_det_doc_adquisicion', 'pa.id_det_doc_adquisicion')
                ->join('producto as p', 'p.id_producto', 'pa.id_producto')
                ->leftJoinSub($subQuery, 'subQuery', function ($join) {
                    $join->on('p.id_producto', '=', 'subQuery.id_producto');
                })
                ->selectRaw(
                    '
                        p.id_producto as value,
                        p.nombre_producto as label,
                        pa.cant_prod_adquisicion, 
                        (pa.cant_prod_adquisicion-IFNULL(subQuery.registrados,0)) as disponible
                    '
                )
                ->where('dda.id_det_doc_adquisicion', $recep->det_doc_adquisicion->id_det_doc_adquisicion)
                ->where('pa.estado_prod_adquisicion', 1)
                ->get();

            return response()->json([
                'recep'                         => $recep ?? [],
                'products'                      => $productos,
            ]);
        }
    }

    public function getInitialInfoDoc(Request $request)
    {
        $documents = DB::table('documento_adquisicion as da')
            ->join('detalle_documento_adquisicion as dda','da.id_doc_adquisicion','dda.id_doc_adquisicion')
            ->select('da.id_doc_adquisicion as value', 'da.numero_doc_adquisicion as label', 'da.id_tipo_doc_adquisicion')
            ->where('dda.id_estado_doc_adquisicion', 2)
            ->groupBy('da.id_doc_adquisicion','da.numero_doc_adquisicion','da.id_tipo_doc_adquisicion')->get();
        $items = DetDocumentoAdquisicion::select('id_det_doc_adquisicion as value', 'nombre_det_doc_adquisicion as label', 'id_doc_adquisicion')
            ->where('estado_det_doc_adquisicion', 1)
            ->where('id_estado_doc_adquisicion', 2)->get();
        return response()->json([
            'documents'                  => $documents,
            'items'                      => $items,
        ]);
    }
}
