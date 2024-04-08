<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Models\CentroAtencion;
use App\Models\DetalleRequerimiento;
use App\Models\LineaTrabajo;
use App\Models\Marca;
use App\Models\MotivoAjuste;
use App\Models\ProyectoFinanciado;
use App\Models\Requerimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AjusteEntradaController extends Controller
{
    public function getAjusteEntrada(Request $request)
    {
        $columns = ['id_requerimiento', 'centro', 'id_proy_financiado', 'num_requerimiento', 'fecha_requerimiento', 'id_estado_req'];

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
            ->where('id_tipo_req', 2);

        $data = $query->paginate($length)->onEachSide(1);
        return ['data' => $data, 'draw' => $request->input('draw')];
    }

    public function getInfoShortageAdjustment(Request $request)
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

        $centers = CentroAtencion::select('id_centro_atencion as value', 'codigo_centro_atencion as label')->get();
        $reasons = MotivoAjuste::select('id_motivo_ajuste as value', 'nombre_motivo_ajuste as label')->get();
        $financingSources = ProyectoFinanciado::select('id_proy_financiado as value', 'codigo_proy_financiado as label')->get();
        $lts = LineaTrabajo::select('id_lt as value', 'codigo_up_lt as label')->get();
        $brands = Marca::select('id_marca as value', 'nombre_marca as label')->get();

        return response()->json([
            'req'                           => $req,
            'reasons'                       => $reasons,
            'centers'                       => $centers,
            'financingSources'              => $financingSources,
            'lts'                           => $lts,
            'brands'                        => $brands
        ]);
    }

    public function storeShortageAdjustment(Request $request)
    {
        DB::beginTransaction();
        try {
            $codeReq = "";
            $year = Carbon::now()->year;
            $lastReq = Requerimiento::whereYear('fecha_reg_requerimiento', $year)
                ->where('id_tipo_req',2)
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
                $newDet = new DetalleRequerimiento([
                    'id_centro_atencion'                        => $prod['centerId'],
                    'id_producto'                               => $prod['prodId'],
                    'id_marca'                                  => $prod['brandId'],
                    'fecha_vcto_det_requerimiento'              => $prod['expDate'],
                    'id_requerimiento'                         => $req->id_requerimiento,
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

    // public function updateShortageAdjustment(Request $request)
    // {
    //     $req = Requerimiento::find($request->id);
    //     if (!$req || $req->id_estado_req != 1) {
    //         return response()->json(['logical_error' => 'Error, la recepcion que intentas modificar no existe o ha sido eliminada.'], 422);
    //     } else {
    //         DB::beginTransaction();
    //         try {
    //             $req->update([
    //                 'monto_recepcion_pedido'                => $request->total,
    //                 'id_proveedor'                          => $request->supplierId,
    //                 'fecha_mod_recepcion_pedido'            => Carbon::now(),
    //                 'usuario_recepcion_pedido'              => $request->user()->nick_usuario,
    //                 'ip_recepcion_pedido'                   => $request->ip(),
    //             ]);

    //             foreach ($request->prods as $prod) {
    //                 if ($prod['detRecId'] != "" && $prod['deleted'] == false) {
    //                     $det = DetalleRecepcionPedido::find($prod['detRecId']);
    //                     $det->update([
    //                         'id_centro_atencion'                        => $prod['centerId'],
    //                         'id_producto'                               => $prod['prodId'],
    //                         'id_recepcion_pedido'                       => $request->id,
    //                         'cant_det_recepcion_pedido'                 => $prod['qty'],
    //                         'costo_det_recepcion_pedido'                => $prod['cost'],
    //                         'fecha_mod_det_recepcion_pedido'            => Carbon::now(),
    //                         'usuario_det_recepcion_pedido'              => $request->user()->nick_usuario,
    //                         'ip_det_recepcion_pedido'                   => $request->ip()
    //                     ]);
    //                 }

    //                 if ($prod['detRecId'] != "" && $prod['deleted'] == true) {
    //                     $det = DetalleRecepcionPedido::find($prod['detRecId']);
    //                     $det->update([
    //                         'estado_det_recepcion_pedido' => 0,
    //                         'fecha_mod_det_recepcion_pedido' => Carbon::now(),
    //                         'usuario_det_recepcion_pedido' => $request->user()->nick_usuario,
    //                         'ip_det_recepcion_pedido' => $request->ip()
    //                     ]);
    //                 }

    //                 if ($prod['detRecId'] == "" && $prod['deleted'] == false) {
    //                     $existDetail = DetalleRecepcionPedido::where('id_recepcion_pedido', $request->id)
    //                         ->where('id_producto', $prod['prodId'])
    //                         ->first();
    //                     if ($existDetail) {
    //                         $existDetail->update([
    //                             'id_centro_atencion'                        => $prod['centerId'],
    //                             'id_producto'                               => $prod['prodId'],
    //                             'cant_det_recepcion_pedido'                 => $prod['qty'],
    //                             'costo_det_recepcion_pedido'                => $prod['cost'],
    //                             'estado_det_recepcion_pedido'               => 1,
    //                             'fecha_mod_det_recepcion_pedido'            => Carbon::now(),
    //                             'usuario_det_recepcion_pedido'              => $request->user()->nick_usuario,
    //                             'ip_det_recepcion_pedido'                   => $request->ip()
    //                         ]);
    //                     } else {
    //                         $newDet = new DetalleRecepcionPedido([
    //                             'id_centro_atencion'                        => $prod['centerId'],
    //                             'id_producto'                               => $prod['prodId'],
    //                             'id_recepcion_pedido'                       => $request->id,
    //                             'cant_det_recepcion_pedido'                 => $prod['qty'],
    //                             'costo_det_recepcion_pedido'                => $prod['cost'],
    //                             'estado_det_recepcion_pedido'               => 1,
    //                             'fecha_reg_det_recepcion_pedido'            => Carbon::now(),
    //                             'usuario_det_recepcion_pedido'              => $request->user()->nick_usuario,
    //                             'ip_det_recepcion_pedido'                   => $request->ip()
    //                         ]);
    //                         $newDet->save();
    //                     }
    //                 }
    //             }

    //             DB::commit(); // Confirma las operaciones en la base de datos
    //             return response()->json([
    //                 'message'          => 'Actualizada donacion con exito.',
    //             ]);
    //         } catch (\Throwable $th) {
    //             DB::rollBack(); // En caso de error, revierte las operaciones anteriores
    //             return response()->json([
    //                 'logical_error' => 'Ha ocurrido un error con sus datos.',
    //                 'error' => $th->getMessage(),
    //             ], 422);
    //         }
    //     }
    // }
}
