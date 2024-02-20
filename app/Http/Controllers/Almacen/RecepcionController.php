<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Models\CentroAtencion;
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

    public function getInfoModalRecep(Request $request)
    {
        $idRec = $request->id;
        if ($idRec > 0) {
            $recep = RecepcionPedido::with([
                'detalle_recepcion.producto.unidad_medida',
                'detalle_recepcion.producto_adquisicion',
                'det_doc_adquisicion.documento_adquisicion.tipo_documento_adquisicion',
                'estado_recepcion'
            ])->find($idRec);
            $item = DetDocumentoAdquisicion::find($recep->det_doc_adquisicion->id_det_doc_adquisicion);
            if (!$recep || !$item) {
                return response()->json([
                    'logical_error' => 'Error, no fue posible obtener la recepción consultada. Consulte con el administrador.',
                ], 422);
            } 
        } else {
            $recep = [];
            $item = DetDocumentoAdquisicion::find($request->detId);
        }

        if ($item->estado_det_doc_adquisicion == 0 && $recep != []) {
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
            // if ($idRec > 0) {
            //     $procedure = DB::select(
            //         'CALL PR_GET_PRODUCT_ACQUISITION_MINUS_CURRENT_RECEIPT(?, ?)',
            //         array($item->id_det_doc_adquisicion, $recep->id_recepcion_pedido)
            //     );
            // } else {
            //     $procedure = DB::select(
            //         'CALL PR_GET_PRODUCT_ACQUISITION(?)',
            //         array($item->id_det_doc_adquisicion)
            //     );
            // }
            $procedure = DB::select(
                'CALL PR_GET_PRODUCT_ACQUISITION(?)',
                array($item->id_det_doc_adquisicion)
            );
            $centers = CentroAtencion::selectRaw('id_centro_atencion as value, concat(codigo_centro_atencion," - ",nombre_centro_atencion) as label')->get();

            return response()->json([
                'recep'                         => $recep,
                'products'                      => $procedure,
                'centers'                       => $centers
            ]);
        }
    }

    public function getInitialInfoDoc(Request $request)
    {
        $documents = DB::table('documento_adquisicion as da')
            ->join('detalle_documento_adquisicion as dda', 'da.id_doc_adquisicion', 'dda.id_doc_adquisicion')
            ->select('da.id_doc_adquisicion as value', 'da.numero_doc_adquisicion as label', 'da.id_tipo_doc_adquisicion')
            ->where('dda.id_estado_doc_adquisicion', 2)
            ->groupBy('da.id_doc_adquisicion', 'da.numero_doc_adquisicion', 'da.id_tipo_doc_adquisicion')->get();
        $items = DetDocumentoAdquisicion::select('id_det_doc_adquisicion as value', 'nombre_det_doc_adquisicion as label', 'id_doc_adquisicion')
            ->where('estado_det_doc_adquisicion', 1)
            ->where('id_estado_doc_adquisicion', 2)->get();
        return response()->json([
            'documents'                  => $documents,
            'items'                      => $items,
        ]);
    }
}
