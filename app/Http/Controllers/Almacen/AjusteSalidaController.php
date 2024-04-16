<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Almacen\AjusteSalidaRequest;
use App\Models\CentroAtencion;
use App\Models\DetalleExistenciaAlmacen;
use App\Models\DetalleRequerimiento;
use App\Models\ExistenciaAlmacen;
use App\Models\LineaTrabajo;
use App\Models\MotivoAjuste;
use App\Models\ProyectoFinanciado;
use App\Models\Requerimiento;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AjusteSalidaController extends Controller
{
    public function getAjusteSalida(Request $request)
    {
        $columns = ['id_requerimiento', 'id_centro_atencion', 'motivo', 'id_proy_financiado', 'num_requerimiento', 'fecha_requerimiento', 'id_estado_req'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = Requerimiento::select('*')
            ->with([
                'centro_atencion',
                'proyecto_financiado',
                'estado_requerimiento',
                'motivo_ajuste',
                'detalles_requerimiento.producto.unidad_medida',
                'detalles_requerimiento.marca'
            ])
            ->where('id_tipo_req', 2)
            ->where('id_tipo_mov_kardex', 2);

        if ($column == 2) { //Order by reason
            $query->orderByRaw('
                    (SELECT nombre_motivo_ajuste FROM motivo_ajuste WHERE motivo_ajuste.id_motivo_ajuste = requerimiento.id_motivo_ajuste) ' . $dir);
        } else {
            $query->orderBy($columns[$column], $dir);
        }

        if ($search_value) {
            $query->where('id_requerimiento', 'like', '%' . $search_value['id_requerimiento'] . '%') //Search by requerimiento id
                ->where('id_centro_atencion', $search_value['id_centro_atencion']) //Search by Healthcare center
                ->where('id_proy_financiado', 'like', '%' . $search_value['id_proy_financiado'] . '%') //Search by financing source
                ->where('num_requerimiento', 'like', '%' . $search_value['num_requerimiento'] . '%') //Search by requerimiento code
                ->where('fecha_requerimiento', 'like', '%' . $search_value['fecha_requerimiento'] . '%') //Search by requerimiento date
                ->where('id_estado_req', 'like', '%' . $search_value['id_estado_req'] . '%'); //Search by requerimiento status
            //Search by reason
            if ($search_value['motivo']) {
                $query->whereHas(
                    'motivo_ajuste',
                    function ($query) use ($search_value) {
                        if ($search_value["motivo"] != '') {
                            $query->where('nombre_motivo_ajuste', 'like', '%' . $search_value['motivo'] . '%');
                        }
                    }
                );
            }
        }

        $data = $query->paginate($length)->onEachSide(1);
        return ['data' => $data, 'draw' => $request->input('draw')];
    }

    public function getInfoOutgoingAdjustment(Request $request)
    {
        $idAdjustment = $request->id; //Reception id from the view, if it's 0 that means we are creating a new reception
        if ($idAdjustment > 0) { //This means we are updating an existing reception
            $req = Requerimiento::with([
                'centro_atencion',
                'proyecto_financiado',
                'estado_requerimiento',
                'motivo_ajuste',
                'detalles_requerimiento.producto.unidad_medida',
                'detalles_requerimiento.marca'
            ])->find($idAdjustment);

            if (!$req) {
                return response()->json([
                    'logical_error' => 'Error, no fue posible obtener el ajuste seleccionado.',
                ], 422);
            }
        } else { //Creating a new one
            $req = [];
        }

        $centers = CentroAtencion::selectRaw('id_centro_atencion as value, concat(codigo_centro_atencion," - ",nombre_centro_atencion) as label')->get();
        $reasons = MotivoAjuste::select('id_motivo_ajuste as value', 'nombre_motivo_ajuste as label')->get();
        $financingSources = ProyectoFinanciado::selectRaw('id_proy_financiado as value, concat(codigo_proy_financiado," - ",nombre_proy_financiado) as label')->get();
        $lts = LineaTrabajo::selectRaw('id_lt as value, concat(codigo_up_lt," - ",nombre_lt) as label')->get();
        //$brands = Marca::select('id_marca as value', 'nombre_marca as label')->get();

        return response()->json([
            'req'                           => $req,
            'reasons'                       => $reasons,
            'centers'                       => $centers,
            'financingSources'              => $financingSources,
            'lts'                           => $lts,
            //'brands'                        => $brands
        ]);
    }

    public function searchProductsOutgoingAdjustment(Request $request)
    {
        $matchStock = DetalleExistenciaAlmacen::with([
            'centro_atencion',
            'existencia_almacen.producto',
            'existencia_almacen.proyecto_financiado',
            'linea_trabajo',
            'marca'
        ]);

        if ($request->financingSourceId != '' && $request->financingSourceId != null) {
            $matchStock->whereHas('existencia_almacen', function ($query) use ($request) {
                $query->where('id_proy_financiado', $request->financingSourceId);
            });
        }
        
        if ($request->centerId != '' && $request->centerId != null) {
            $matchStock->where('id_centro_atencion', $request->centerId);
        }

        if ($request->idLt != '' && $request->idLt != null) {
            $matchStock->where('id_lt', $request->idLt);
        }
        
        $match = $matchStock->get();
        
        $products = $match->map(function ($detailStock) {
            return [
                'value' => $detailStock->id_det_existencia_almacen,
                'label' => $detailStock->existencia_almacen->producto->codigo_producto 
                . ' — ' . $detailStock->existencia_almacen->proyecto_financiado->codigo_proy_financiado
                . ' — ' . $detailStock->existencia_almacen->producto->nombre_completo_producto  
                . ' — UP/LT: ' . ($detailStock->linea_trabajo->codigo_up_lt ?? 'Sin UP/LT')
                . ' — Centro: ' . $detailStock->centro_atencion->codigo_centro_atencion
                . ' — Marca: ' . ($detailStock->marca->nombre_marca ?? 'Sin marca')
                . ' — Vencimiento: ' . ($detailStock->fecha_vcto_det_existencia_almacen ? Carbon::createFromFormat('Y-m-d', $detailStock->fecha_vcto_det_existencia_almacen)->format('d/m/Y') : 'Sin fecha.'),
                'allInfo' => $detailStock
            ];
        });
        
        return response()->json([
            'products' => $products,
        ]);
    }

    public function storeShortageAdjustment(AjusteSalidaRequest $request)
    {
        DB::beginTransaction();
        try {
            $codeReq = "";
            $year = Carbon::now()->year;
            $lastReq = Requerimiento::whereYear('fecha_reg_requerimiento', $year)
                ->where('id_tipo_req', 2)
                ->orderBy('fecha_reg_requerimiento', 'desc')
                ->first();
            if (!$lastReq) {
                $codeReq = 'AJU-' . $year . '-1';
            } else {
                $correlative = intval(explode('-', $lastReq->num_requerimiento)[2]) + 1;
                $codeReq = 'AJU-' . $year . '-' . $correlative;
            }

            $req = new Requerimiento([
                'id_lt'                                 => $request->idLt,
                'id_centro_atencion'                    => $request->centerId,
                'id_motivo_ajuste'                      => $request->reasonId,
                'id_proy_financiado'                    => $request->financingSourceId,
                'id_tipo_mov_kardex'                    => 1, //INGRESO
                'id_estado_req'                         => 1, //CREADO
                'id_tipo_req'                           => 2, //AJUSTE
                'num_requerimiento'                     => $codeReq,
                'fecha_requerimiento'                   => Carbon::now(),
                'observacion_requerimiento'             => $request->observation,
                'fecha_reg_requerimiento'               => Carbon::now(),
                'usuario_requerimiento'                 => $request->user()->nick_usuario,
                'ip_requerimiento'                      => $request->ip()
            ]);
            $req->save();

            foreach ($request->prods as $prod) {
                $fecha = $prod['expDate'] != '' ? date('Y/m/d', strtotime($prod['expDate'])) : null;
                $newDet = new DetalleRequerimiento([
                    'id_producto'                               => $prod['prodId'],
                    'id_marca'                                  => $prod['brandId'],
                    'fecha_vcto_det_requerimiento'              => $fecha,
                    'id_requerimiento'                          => $req->id_requerimiento,
                    'cant_det_requerimiento'                    => $prod['qty'],
                    'costo_det_requerimiento'                   => $prod['cost'],
                    'estado_det_requerimiento'                  => 1,
                    'fecha_reg_det_requerimiento'               => Carbon::now(),
                    'usuario_det_requerimiento'                 => $request->user()->nick_usuario,
                    'ip_det_requerimiento'                      => $request->ip()
                ]);
                $newDet->save();
            }

            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'message'          => 'Guardado nuevo ajuste con éxito.',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack(); // En caso de error, revierte las operaciones anteriores
            return response()->json([
                'logical_error' => 'Ha ocurrido un error con sus datos.',
                'error' => $th->getMessage(),
            ], 422);
        }
    }
}
