<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Almacen\TransferenciaRequest;
use App\Models\CentroAtencion;
use App\Models\DetalleExistenciaAlmacen;
use App\Models\DetalleKardex;
use App\Models\DetalleRequerimiento;
use App\Models\Kardex;
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
        $columns = ['id_requerimiento', 'id_centro_atencion', 'cen_id_centro_atencion', 'id_proy_financiado', 'num_requerimiento', 'fecha_requerimiento', 'id_estado_req'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = Requerimiento::select('*')
            ->with([
                'centro_atencion',
                'centro_destino',
                'proyecto_financiado',
                'estado_requerimiento',
                //'motivo_ajuste',
                'detalles_requerimiento.producto.unidad_medida',
                'detalles_requerimiento.marca'
            ])
            ->where('id_tipo_req', 3);

        $query->orderBy($columns[$column], $dir);

        if ($search_value) {
            $query->where('id_proy_financiado', 'like', '%' . $search_value['id_proy_financiado'] . '%')
                ->where('num_requerimiento', 'like', '%' . $search_value['num_requerimiento'] . '%')
                ->where('fecha_requerimiento', 'like', '%' . $search_value['fecha_requerimiento'] . '%')
                ->where('id_estado_req', 'like', '%' . $search_value['id_estado_req'] . '%');

            //Search by id
            if ($search_value['id_requerimiento']) {
                $query->where('id_requerimiento', $search_value['id_requerimiento']);
            }

            //Search by origin
            if ($search_value['id_centro_atencion']) {
                $query->where('id_centro_atencion', $search_value['id_centro_atencion']);
            }

            //Search by destination
            if ($search_value['cen_id_centro_atencion']) {
                $query->where('cen_id_centro_atencion', $search_value['cen_id_centro_atencion']);
            }
        }

        $data = $query->paginate($length)->onEachSide(1);
        return ['data' => $data, 'draw' => $request->input('draw')];
    }

    public function getInfoWarehouseTransfer(Request $request)
    {
        $idAdjustment = $request->id; //Reception id from the view, if it's 0 that means we are creating a new reception
        if ($idAdjustment > 0) { //This means we are updating an existing reception

            // Query the requirement and its related entities
            $req = Requerimiento::with([
                'centro_atencion',
                'proyecto_financiado',
                'estado_requerimiento',
                'linea_trabajo',
                'motivo_ajuste',
                'detalles_requerimiento.producto.unidad_medida',
                'detalles_requerimiento.marca',
                'detalles_requerimiento.detalle_existencia_almacen.existencia_almacen.producto.unidad_medida',
                'detalles_requerimiento.detalle_existencia_almacen.existencia_almacen.proyecto_financiado',
                'detalles_requerimiento.detalle_existencia_almacen.linea_trabajo',
                'detalles_requerimiento.detalle_existencia_almacen.centro_atencion',
                'detalles_requerimiento.detalle_existencia_almacen.marca'
            ])->find($idAdjustment);

            // Filter the stock details related to the requirement's details
            $matchStock = $req->detalles_requerimiento->map(function ($detalleReq) {
                return $detalleReq->detalle_existencia_almacen;
            });

            // Additional query to get stock details that meet the criteria and have quantity greater than zero
            $additionalStock = DetalleExistenciaAlmacen::with([
                'centro_atencion',
                'existencia_almacen.producto.unidad_medida',
                'existencia_almacen.proyecto_financiado',
                'linea_trabajo',
                'marca'
            ])
                ->where('cant_det_existencia_almacen', '>', 0); // Filter for quantity greater than zero

            // Apply the financing source filter if applicable
            if ($req->id_proy_financiado) {
                $additionalStock->whereHas('existencia_almacen', function ($query) use ($req) {
                    $query->where('id_proy_financiado', $req->id_proy_financiado);
                });
            }

            // Apply the center filter if applicable
            if ($req->id_centro_atencion) {
                $additionalStock->where('id_centro_atencion', $req->id_centro_atencion);
            }

            // Apply the work line filter if applicable
            if ($req->id_lt) {
                $additionalStock->where('id_lt', $req->id_lt);
            }

            // Retrieve the additional stock details that match the criteria
            $additionalStock = $additionalStock->get();

            // Combine the results of both queries
            $combinedStock = $matchStock->merge($additionalStock)->unique('id_det_existencia_almacen');

            // Map the combined stock details to the desired format
            $products = $combinedStock->map(function ($detailStock) {
                // Start with the basic label
                $label = $detailStock->existencia_almacen->producto->codigo_producto
                    . ' — ' . $detailStock->existencia_almacen->proyecto_financiado->codigo_proy_financiado
                    . ' — ' . $detailStock->existencia_almacen->producto->nombre_completo_producto
                    . ' — ' . $detailStock->existencia_almacen->producto->unidad_medida->nombre_unidad_medida
                    . ' — STOCK: ' . $detailStock->cant_det_existencia_almacen;

                // Conditionally concatenate the brand if it exists
                if ($detailStock->marca) {
                    $label .= ' — Marca: ' . $detailStock->marca->nombre_marca;
                }

                // Conditionally concatenate the expiration date if it exists
                if ($detailStock->fecha_vcto_det_existencia_almacen) {
                    $label .= ' — Vencimiento: ' . Carbon::createFromFormat('Y-m-d', $detailStock->fecha_vcto_det_existencia_almacen)->format('d/m/Y');
                }

                // Return the mapped product with the conditionally built label
                return [
                    'value'             => $detailStock->id_det_existencia_almacen,
                    'label'             => $label,
                    'allInfo'           => $detailStock
                ];
            });

            if (!$req) {
                return response()->json([
                    'logical_error' => 'Error, no fue posible obtener la transferencia seleccionado.',
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
                ->where('id_tipo_req', 3)
                ->orderBy('fecha_reg_requerimiento', 'desc')
                ->first();
            if (!$lastReq) {
                $codeReq = 'TRA-' . $year . '-1';
            } else {
                $correlative = intval(explode('-', $lastReq->num_requerimiento)[2]) + 1;
                $codeReq = 'TRA-' . $year . '-' . $correlative;
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
                'message'          => 'Guardada nueva transferencia con éxito.',
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
                    'cen_id_centro_atencion'                => $request->destCenterId,
                    'id_motivo_ajuste'                      => 3, //Missing this part
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

    public function changeStatusWarehouseTransfer(Request $request)
    {
        $req = Requerimiento::find($request->id);
        if ($req->id_estado_req == 1 && $request->status == 1) {
            DB::beginTransaction();
            try {
                $req->update([
                    'id_estado_req'                         => 4,
                    'fecha_mod_requerimiento'               => Carbon::now(),
                    'usuario_requerimiento'                 => $request->user()->nick_usuario,
                    'ip_requerimiento'                      => $request->ip(),
                ]);
                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'message'          => "Transferencia eliminada con éxito.",
                ]);
            } catch (\Exception $e) {
                DB::rollBack(); // En caso de error, revierte las operaciones anteriores
                return response()->json([
                    'logical_error' => 'Ha ocurrido un error con sus datos.',
                    'error' => $e,
                ], 422);
            }
        } else {
            return response()->json(['logical_error' => 'Error, otro usuario ha cambiado el estado de esta transferencia.',], 422);
        }
    }

    public function sendWarehouseTransfer(Request $request)
    {
        $backEndError = false;
        //Find the current adjustment
        $req = Requerimiento::with([
            'detalles_requerimiento' => function ($query) {
                $query->where('estado_det_requerimiento', 1);
            },
            'detalles_requerimiento.producto'
        ])->find($request->id);
        if ($req->id_estado_req == 1) { //We must evaluate if the adjustment has the status 'CREADO'
            DB::beginTransaction(); //Start the transaction
            try {
                //We update the adjustment
                $req->update([
                    'id_estado_req'                         => 2, //APROBADO
                    'fecha_mod_requerimiento'               => Carbon::now(),
                    'usuario_requerimiento'                 => $request->user()->nick_usuario,
                    'ip_requerimiento'                      => $request->ip(),
                ]);

                //Create a new Kardex object(Outgoing)
                $kardexOut = new Kardex([
                    'id_requerimiento'                      => $req->id_requerimiento,
                    'id_proy_financiado'                    => $req->id_proy_financiado,
                    'id_tipo_mov_kardex'                    => 2, //SALIDA
                    'id_tipo_req'                           => 3, //TRANSFERENCIA
                    'fecha_kardex'                          => Carbon::now(),
                    'fecha_reg_kardex'                      => Carbon::now(),
                    'usuario_kardex'                        => $request->user()->nick_usuario,
                    'ip_kardex'                             => $request->ip(),
                ]);
                $kardexOut->save();
                //Create a new Kardex object (Incoming)
                $kardexIn = new Kardex([
                    'id_requerimiento'                      => $req->id_requerimiento,
                    'id_proy_financiado'                    => $req->id_proy_financiado,
                    'id_tipo_mov_kardex'                    => 1, //ENTRADA
                    'id_tipo_req'                           => 3, //TRANSFERENCIA
                    'fecha_kardex'                          => Carbon::now(),
                    'fecha_reg_kardex'                      => Carbon::now(),
                    'usuario_kardex'                        => $request->user()->nick_usuario,
                    'ip_kardex'                             => $request->ip(),
                ]);
                $kardexIn->save();

                //Foreach 'detalles_requerimiento' we create a 'detalle_kardex' instance
                foreach ($req->detalles_requerimiento as $det) {
                    $detKardexOut = new DetalleKardex([
                        'id_kardex'                         => $kardexOut->id_kardex,
                        'id_producto'                       => $det->producto->id_producto,
                        'id_centro_atencion'                => $req->id_centro_atencion,
                        'id_marca'                          => $det->id_marca,
                        'id_lt'                             => $req->id_lt,
                        'cant_det_kardex'                   => $det->cant_det_requerimiento,
                        'costo_det_kardex'                  => $det->costo_det_requerimiento,
                        'fecha_vencimiento_det_kardex'      => $det->fecha_vcto_det_requerimiento,
                        'fecha_reg_det_kardex'              => Carbon::now(),
                        'usuario_det_kardex'                => $request->user()->nick_usuario,
                        'ip_det_kardex'                     => $request->ip(),
                    ]);
                    $detKardexOut->save();
                    $detKardexIn = new DetalleKardex([
                        'id_kardex'                         => $kardexIn->id_kardex,
                        'id_producto'                       => $det->producto->id_producto,
                        'id_centro_atencion'                => $req->cen_id_centro_atencion,
                        'id_marca'                          => $det->id_marca,
                        'id_lt'                             => $req->id_lt,
                        'cant_det_kardex'                   => $det->cant_det_requerimiento,
                        'costo_det_kardex'                  => $det->costo_det_requerimiento,
                        'fecha_vencimiento_det_kardex'      => $det->fecha_vcto_det_requerimiento,
                        'fecha_reg_det_kardex'              => Carbon::now(),
                        'usuario_det_kardex'                => $request->user()->nick_usuario,
                        'ip_det_kardex'                     => $request->ip(),
                    ]);
                    $detKardexIn->save();

                    //Update the stock
                    $prevStock = DetalleExistenciaAlmacen::find($det->id_det_existencia_almacen);
                    $newStock = $prevStock->cant_det_existencia_almacen - $det->cant_det_requerimiento; //New specific stock
                    if ($newStock >= 0) {
                        //We update the specific stock (Outgoing)
                        $prevStock->update([
                            'cant_det_existencia_almacen'               => $newStock,
                            'fecha_mod_det_existencia_almacen'          => Carbon::now(),
                            'usuario_det_existencia_almacen'            => $request->user()->nick_usuario,
                            'ip_det_existencia_almacen'                 => $request->ip(),
                        ]);
                        //We update de specific stock (Incoming)
                        //Check if the healthcare center has a stock that meets the conditions.
                        $existStock = DetalleExistenciaAlmacen::where('id_existencia_almacen', $prevStock->id_existencia_almacen)
                            ->where('id_marca', $det->id_marca)
                            ->where('id_centro_atencion', $req->cen_id_centro_atencion)
                            ->where('fecha_vcto_det_existencia_almacen', $det->fecha_vcto_det_requerimiento)
                            ->first();
                        if ($existStock) {
                            $existNewStock = $existStock->cant_det_existencia_almacen + $det->cant_det_requerimiento;
                            //We update the adjustment
                            $existStock->update([
                                'cant_det_existencia_almacen'               => $existNewStock,
                                'fecha_mod_det_existencia_almacen'          => Carbon::now(),
                                'usuario_det_existencia_almacen'            => $request->user()->nick_usuario,
                                'ip_det_existencia_almacen'                 => $request->ip(),
                            ]);
                        } else {
                            //If there is no any stock for those conditions, we must create a new one
                            $newExistingStock = new DetalleExistenciaAlmacen([
                                'id_lt'                                     => $req->id_lt,
                                'id_existencia_almacen'                     => $prevStock->id_existencia_almacen,
                                'id_marca'                                  => $det->id_marca,
                                'id_centro_atencion'                        => $req->cen_id_centro_atencion,
                                'cant_det_existencia_almacen'               => $det->cant_det_requerimiento,
                                'fecha_vcto_det_existencia_almacen'         => $det->fecha_vcto_det_requerimiento,
                                'fecha_reg_det_existencia_almacen'          => Carbon::now(),
                                'usuario_det_existencia_almacen'            => $request->user()->nick_usuario,
                                'ip_det_existencia_almacen'                 => $request->ip(),
                            ]);
                            $newExistingStock->save();
                        }
                    } else {
                        $backEndError = true;
                    }
                }

                if ($backEndError) {
                    DB::rollBack(); // En caso de error, revierte las operaciones anteriores
                    return response()->json([
                        'logical_error' => 'Uno o más productos de los que estas intentando enviar al kardex no poseen existencias suficientes para procesar la transferencia, por favor revisa la transferencia e intenta nuevamente.',
                    ], 422);
                }

                DB::commit(); // Confirma las operaciones en la base de datos
            } catch (\Exception $e) {
                DB::rollBack(); // En caso de error, revierte las operaciones anteriores
                return response()->json([
                    'logical_error' => 'Ha ocurrido un error con sus datos.',
                    'error' => $e->getMessage(),
                ], 422);
            }
            return response()->json([
                'message'          => "Transferencia enviado al Kardex con éxito.",
            ]);
        } else {
            return response()->json(['logical_error' => 'Error, otro usuario ha cambiado el estado de esta donacion.',], 422);
        }
    }
}
