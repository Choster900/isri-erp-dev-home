<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Almacen\AjusteSalidaRequest;
use App\Models\CentroAtencion;
use App\Models\DetalleExistenciaAlmacen;
use App\Models\DetalleKardex;
use App\Models\DetalleRequerimiento;
use App\Models\ExistenciaAlmacen;
use App\Models\Kardex;
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

    public function searchProductsOutgoingAdjustment(Request $request)
    {
        $matchStock = DetalleExistenciaAlmacen::with([
            'centro_atencion',
            'existencia_almacen.producto',
            'existencia_almacen.proyecto_financiado',
            'linea_trabajo',
            'marca'
        ]);

        $matchStock->when($request->financingSourceId, function ($query) use ($request) {
            $query->whereHas('existencia_almacen', function ($query) use ($request) {
                $query->where('id_proy_financiado', $request->financingSourceId);
            });
        });

        $matchStock->when($request->centerId, function ($query) use ($request) {
            $query->where('id_centro_atencion', $request->centerId);
        });

        $matchStock->when($request->idLt, function ($query) use ($request) {
            $query->where('id_lt', $request->idLt);
        });

        $products = $matchStock->get()->map(function ($detailStock) {
            $existencia = $detailStock->existencia_almacen;
            $producto = $existencia->producto;
            $proyectoFinanciado = $existencia->proyecto_financiado;
            $lineaTrabajo = $detailStock->linea_trabajo;
            $centroAtencion = $detailStock->centro_atencion;
            $marca = $detailStock->marca;

            return [
                'value' => $detailStock->id_det_existencia_almacen,
                'label' => "$producto->codigo_producto — $proyectoFinanciado->codigo_proy_financiado — $producto->nombre_completo_producto — UP/LT: " . ($lineaTrabajo->codigo_up_lt ?? 'Sin UP/LT') . " — Centro: $centroAtencion->codigo_centro_atencion — Marca: " . ($marca->nombre_marca ?? 'Sin marca') . ' — Vencimiento: ' . ($detailStock->fecha_vcto_det_existencia_almacen ? Carbon::createFromFormat('Y-m-d', $detailStock->fecha_vcto_det_existencia_almacen)->format('d/m/Y') : 'Sin fecha.'),
                'allInfo' => $detailStock
            ];
        });

        return response()->json([
            'products' => $products,
        ]);
    }


    public function storeOutgoingAdjustment(AjusteSalidaRequest $request)
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
                'id_tipo_mov_kardex'                    => 2, //SALIDA
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

    public function updateOutgoingAdjustment(AjusteSalidaRequest $request)
    {
        $req = Requerimiento::find($request->id);
        if (!$req || $req->id_estado_req != 1) {
            return response()->json(['logical_error' => 'Error, el ajuste que deseas modificar ha cambiado de estado.'], 422);
        } else {
            DB::beginTransaction();
            try {
                $req->update([
                    'id_lt'                                 => $request->idLt,
                    'id_centro_atencion'                    => $request->centerId,
                    'id_motivo_ajuste'                      => $request->reasonId,
                    'id_proy_financiado'                    => $request->financingSourceId,
                    'observacion_requerimiento'             => $request->observation,
                    'fecha_mod_requerimiento'               => Carbon::now(),
                    'usuario_requerimiento'                 => $request->user()->nick_usuario,
                    'ip_requerimiento'                      => $request->ip(),
                ]);

                foreach ($request->prods as $prod) {
                    if ($prod['id'] != "" && $prod['deleted'] == false) {
                        $det = DetalleRequerimiento::find($prod['id']);
                        $det->update([
                            'cant_det_requerimiento'                    => $prod['qty'],
                            'costo_det_requerimiento'                   => $prod['cost'],
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
                                'costo_det_requerimiento'                   => $prod['cost'],
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
                    'message'          => 'Actualizado ajuste con exito.',
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

    public function changeStatusOutgoingAdjustment(Request $request)
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
                    'message'          => "Ajuste eliminado con éxito.",
                ]);
            } catch (\Exception $e) {
                DB::rollBack(); // En caso de error, revierte las operaciones anteriores
                return response()->json([
                    'logical_error' => 'Ha ocurrido un error con sus datos.',
                    'error' => $e,
                ], 422);
            }
        } else {
            return response()->json(['logical_error' => 'Error, otro usuario ha cambiado el estado de este ajuste.',], 422);
        }
    }

    public function sendOutgoingAdjustment(Request $request)
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
                //Create a new Kardex object
                $kardex = new Kardex([
                    'id_requerimiento'                      => $req->id_requerimiento,
                    'id_proy_financiado'                    => $req->id_proy_financiado,
                    'id_tipo_mov_kardex'                    => 2, //SALIDA
                    'id_tipo_req'                           => 2, //AJUSTE
                    'fecha_kardex'                          => Carbon::now(),
                    'fecha_reg_kardex'                      => Carbon::now(),
                    'usuario_kardex'                        => $request->user()->nick_usuario,
                    'ip_kardex'                             => $request->ip(),
                ]);
                $kardex->save();
                //Foreach 'detalles_requerimiento' we create a 'detalle_kardex' instance
                foreach ($req->detalles_requerimiento as $det) {
                    $detKardex = new DetalleKardex([
                        'id_kardex'                         => $kardex->id_kardex,
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
                    $detKardex->save();

                    //Update the stock
                    $prevStock = DetalleExistenciaAlmacen::find($det->id_det_existencia_almacen);
                    $newStock = $prevStock->cant_det_existencia_almacen - $det->cant_det_requerimiento; //New specific stock
                    if ($newStock >= 0) {
                        $globalStock = ExistenciaAlmacen::find($prevStock->id_existencia_almacen);
                        //We update the specific stock
                        $prevStock->update([
                            'cant_det_existencia_almacen'               => $newStock,
                            'fecha_mod_det_existencia_almacen'          => Carbon::now(),
                            'usuario_det_existencia_almacen'            => $request->user()->nick_usuario,
                            'ip_det_existencia_almacen'                 => $request->ip(),
                        ]);
                        //We update the global stock (product)
                        $globalStock->update([
                            'cant_existencia_almacen'                   => $globalStock->cant_existencia_almacen - $det->cant_det_requerimiento, //New global stock
                            'fecha_mod_existencia_almacen'              => Carbon::now(),
                            'usuario_existencia_almacen'                => $request->user()->nick_usuario,
                            'ip_existencia_almacen'                     => $request->ip(),
                        ]);
                    } else {
                        $backEndError = true;
                    }
                }

                if ($backEndError) {
                    DB::rollBack(); // En caso de error, revierte las operaciones anteriores
                    return response()->json([
                        'logical_error' => 'Uno o más productos de los que estas intentando enviar al kardex no poseen existencias suficientes para procesar la salida, por favor revisa el ajuste nuevamente.',
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
                'message'          => "Ajuste enviado al Kardex con éxito.",
            ]);
        } else {
            return response()->json(['logical_error' => 'Error, otro usuario ha cambiado el estado de esta donacion.',], 422);
        }
    }
}
