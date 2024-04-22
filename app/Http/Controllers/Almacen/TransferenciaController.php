<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Almacen\TransferenciaRequest;
use App\Models\CentroAtencion;
use App\Models\DetalleExistenciaAlmacen;
use App\Models\DetalleRequerimiento;
use App\Models\LineaTrabajo;
use App\Models\MotivoAjuste;
use App\Models\ProyectoFinanciado;
use App\Models\Requerimiento;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransferenciaController extends Controller
{
    public function getTransferencias(Request $request)
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
            ->where('id_tipo_req', 3);



        $data = $query->paginate($length)->onEachSide(1);
        return ['data' => $data, 'draw' => $request->input('draw')];
    }

    public function getInfoWarehouseTransfer(Request $request)
    {
        $idAdjustment = $request->id; //Reception id from the view, if it's 0 that means we are creating a new reception
        if ($idAdjustment > 0) { //This means we are updating an existing reception
            $req = Requerimiento::with([
                'centro_atencion',
                'proyecto_financiado',
                'estado_requerimiento',
                'linea_trabajo',
                'motivo_ajuste',
                'detalles_requerimiento.producto.unidad_medida',
                'detalles_requerimiento.marca',
                'detalles_requerimiento.detalle_existencia_almacen.existencia_almacen'
            ])->find($idAdjustment);

            $matchStock = DetalleExistenciaAlmacen::with([
                'centro_atencion',
                'existencia_almacen.producto',
                'existencia_almacen.proyecto_financiado',
                'linea_trabajo',
                'marca'
            ]);

            if ($req->id_proy_financiado != '' && $req->id_proy_financiado != null) {
                $matchStock->whereHas('existencia_almacen', function ($query) use ($req) {
                    $query->where('id_proy_financiado', $req->id_proy_financiado);
                });
            }

            if ($req->id_centro_atencion != '' && $req->id_centro_atencion != null) {
                $matchStock->where('id_centro_atencion', $req->id_centro_atencion);
            }

            if ($req->id_lt != '' && $req->id_lt != null) {
                $matchStock->where('id_lt', $req->id_lt);
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

            if (!$req) {
                return response()->json([
                    'logical_error' => 'Error, no fue posible obtener el ajuste seleccionado.',
                ], 422);
            }
        } else { //Creating a new one
            $req = [];
            $products = [];
        }

        $centers = CentroAtencion::selectRaw('id_centro_atencion as value, concat(codigo_centro_atencion," - ",nombre_centro_atencion) as label')->get();
        $reasons = MotivoAjuste::select('id_motivo_ajuste as value', 'nombre_motivo_ajuste as label')->get();
        $financingSources = ProyectoFinanciado::selectRaw('id_proy_financiado as value, concat(codigo_proy_financiado," - ",nombre_proy_financiado) as label')->get();
        $lts = LineaTrabajo::selectRaw('id_lt as value, concat(codigo_up_lt," - ",nombre_lt) as label')->get();

        return response()->json([
            'req'                           => $req,
            'reasons'                       => $reasons,
            'centers'                       => $centers,
            'financingSources'              => $financingSources,
            'lts'                           => $lts,
            'products'                      => $products
        ]);
    }

    public function storeWarehouseTransfer(TransferenciaRequest $request)
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
                'id_centro_atencion'                    => $request->centerId, //ORIGEN
                'cen_id_centro_atencion'                => $request->destCenterId, //DESTINO
                //'id_motivo_ajuste'                      => $request->reasonId,
                'id_proy_financiado'                    => $request->financingSourceId,
                'id_tipo_mov_kardex'                    => 1, //ENTRADA-SALIDA
                'id_estado_req'                         => 1, //CREADO
                'id_tipo_req'                           => 3, //TRANSFERENCIA
                'num_requerimiento'                     => $codeReq,
                'fecha_requerimiento'                   => Carbon::now(),
                'observacion_requerimiento'             => $request->observation,
                'fecha_reg_requerimiento'               => Carbon::now(),
                'usuario_requerimiento'                 => $request->user()->nick_usuario,
                'ip_requerimiento'                      => $request->ip()
            ]);
            $req->save();

            foreach ($request->prods as $prod) {
                $detExistencia = DetalleExistenciaAlmacen::with('existencia_almacen')->find($prod['detId']);
                $newDet = new DetalleRequerimiento([
                    'id_producto'                               => $detExistencia->existencia_almacen->id_producto,
                    'id_marca'                                  => $detExistencia->id_marca,
                    'fecha_vcto_det_requerimiento'              => $detExistencia->fecha_vcto_det_existencia_almacen,
                    'id_requerimiento'                          => $req->id_requerimiento,
                    'id_det_existencia_almacen'                 => $prod['detId'],
                    'cant_det_requerimiento'                    => $prod['qty'],
                    'costo_det_requerimiento'                   => $detExistencia->existencia_almacen->costo_existencia_almacen,
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

    public function updateWarehouseTransfer(TransferenciaRequest $request)
    {
        $req = Requerimiento::find($request->id);
        if (!$req || $req->id_estado_req != 1) {
            return response()->json(['logical_error' => 'Error, la transferencia que deseas modificar ha cambiado de estado.'], 422);
        } else {
            DB::beginTransaction();
            try {
                $req->update([
                    'id_lt'                                 => $request->idLt,
                    'id_centro_atencion'                    => $request->centerId,
                    //'id_motivo_ajuste'                      => $request->reasonId,
                    'id_proy_financiado'                    => $request->financingSourceId,
                    'observacion_requerimiento'             => $request->observation,
                    'fecha_mod_requerimiento'               => Carbon::now(),
                    'usuario_requerimiento'                 => $request->user()->nick_usuario,
                    'ip_requerimiento'                      => $request->ip(),
                ]);

                foreach ($request->prods as $prod) {
                    $detExistencia = DetalleExistenciaAlmacen::with('existencia_almacen')->find($prod['detId']);
                    if ($prod['id'] != "" && $prod['deleted'] == false) {
                        $det = DetalleRequerimiento::find($prod['id']);
                        $det->update([
                            'id_det_existencia_almacen'                 => $prod['detId'],
                            'cant_det_requerimiento'                    => $prod['qty'],
                            'id_producto'                               => $detExistencia->existencia_almacen->id_producto,
                            'costo_det_requerimiento'                   => $detExistencia->existencia_almacen->costo_existencia_almacen,
                            'id_marca'                                  => $detExistencia->id_marca,
                            'fecha_vcto_det_requerimiento'              => $detExistencia->fecha_vcto_det_existencia_almacen,
                            'fecha_mod_det_requerimiento'               => Carbon::now(),
                            'usuario_det_requerimiento'                 => $request->user()->nick_usuario,
                            'ip_det_requerimiento'                      => $request->ip()
                        ]);
                    }

                    if ($prod['id'] != "" && $prod['deleted'] == true) {
                        $det = DetalleRequerimiento::find($prod['id']);
                        $det->update([
                            'estado_det_requerimiento'                  => 0,
                            'fecha_mod_det_requerimiento'               => Carbon::now(),
                            'usuario_det_requerimiento'                 => $request->user()->nick_usuario,
                            'ip_det_requerimiento'                      => $request->ip()
                        ]);
                    }

                    if ($prod['id'] == "" && $prod['deleted'] == false) {
                        $existDetail = DetalleRequerimiento::where('id_requerimiento', $request->id)
                            ->where('id_det_existencia_almacen', $prod['detId'])
                            ->first();
                        if ($existDetail) {
                            $existDetail->update([
                                'cant_det_requerimiento'                    => $prod['qty'],
                                'estado_det_requerimiento'                  => 1,
                                'fecha_mod_det_requerimiento'               => Carbon::now(),
                                'usuario_det_requerimiento'                 => $request->user()->nick_usuario,
                                'ip_det_requerimiento'                      => $request->ip()
                            ]);
                        } else {
                            $detExistencia = DetalleExistenciaAlmacen::with('existencia_almacen')->find($prod['detId']);
                            $newDet = new DetalleRequerimiento([
                                'id_producto'                               => $detExistencia->existencia_almacen->id_producto,
                                'id_marca'                                  => $detExistencia->id_marca,
                                'fecha_vcto_det_requerimiento'              => $detExistencia->fecha_vcto_det_existencia_almacen,
                                'id_requerimiento'                          => $req->id_requerimiento,
                                'id_det_existencia_almacen'                 => $prod['detId'],
                                'cant_det_requerimiento'                    => $prod['qty'],
                                'costo_det_requerimiento'                   => $detExistencia->existencia_almacen->costo_existencia_almacen,
                                'estado_det_requerimiento'                  => 1,
                                'fecha_reg_det_requerimiento'               => Carbon::now(),
                                'usuario_det_requerimiento'                 => $request->user()->nick_usuario,
                                'ip_det_requerimiento'                      => $request->ip()
                            ]);
                            $newDet->save();
                        }
                    }
                }

                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'message'          => 'Actualizada transferencia con exito.',
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

    public function sendWarehouseTransfer(Request $request)
    {

    }
}
