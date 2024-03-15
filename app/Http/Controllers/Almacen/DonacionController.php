<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Almacen\DonacionRequest;
use App\Models\CentroAtencion;
use App\Models\DetalleKardex;
use App\Models\DetalleRecepcionPedido;
use App\Models\Empleado;
use App\Models\Kardex;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\RecepcionPedido;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class DonacionController extends Controller
{
    public function getDonaciones(Request $request)
    {
        $columns = ['id_recepcion_pedido', 'acta_recepcion_pedido', 'proveedor', 'monto_recepcion_pedido', 'fecha_recepcion_pedido', 'id_estado_recepcion_pedido'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = RecepcionPedido::select('*')
            ->with([
                'estado_recepcion',
                'proveedor'
            ])
            ->where('id_proy_financiado', 4);

        if ($column == 2) { //Order by supplier name
            $query->orderByRaw('
                    (SELECT razon_social_proveedor FROM proveedor WHERE proveedor.id_proveedor = recepcion_pedido.id_proveedor) ' . $dir);
        } else {
            $query->orderBy($columns[$column], $dir);
        }

        if ($search_value) {
            $query->where('id_recepcion_pedido', 'like', '%' . $search_value['id_recepcion_pedido'] . '%') //Search by reception id
                ->where('acta_recepcion_pedido', 'like', '%' . $search_value['acta_recepcion_pedido'] . '%') //Search by Acta
                ->where('id_estado_recepcion_pedido', 'like', '%' . $search_value['id_estado_recepcion_pedido'] . '%') //Search by reception status
                ->where('fecha_recepcion_pedido', 'like', '%' . $search_value['fecha_recepcion_pedido'] . '%') //Search by reception date
                ->where('monto_recepcion_pedido', 'like', '%' . $search_value['monto_recepcion_pedido'] . '%'); //Search by reception amount
            //Search by supplier
            if ($search_value['proveedor']) {
                $query->whereHas(
                    'proveedor',
                    function ($query) use ($search_value) {
                        if ($search_value["proveedor"] != '') {
                            $query->where('razon_social_proveedor', 'like', '%' . $search_value['proveedor'] . '%');
                        }
                    }
                );
            }
        }

        $data = $query->paginate($length)->onEachSide(1);
        return ['data' => $data, 'draw' => $request->input('draw')];
    }

    public function getInfoModalDonation(Request $request)
    {
        $idRec = $request->id; //Reception id from the view, if it's 0 that means we are creating a new reception
        if ($idRec > 0) { //This means we are updating an existing reception
            $recep = RecepcionPedido::with([
                'detalle_recepcion.producto.unidad_medida',
                'estado_recepcion',
                'proveedor'
            ])->find($idRec);

            if (!$recep) {
                return response()->json([
                    'logical_error' => 'Error, no fue posible obtener la recepción consultada. Consulte con el administrador.',
                ], 422);
            }
            $suppliers = Proveedor::select('id_proveedor as value', 'razon_social_proveedor as label', 'nit_proveedor')
                ->where('estado_proveedor', 1)->orWhere('id_proveedor', $recep->id_proveedor)->get();
        } else { //Creating a new one
            $recep = [];
            $suppliers = Proveedor::select('id_proveedor as value', 'razon_social_proveedor as label', 'nit_proveedor')
                ->where('estado_proveedor', 1)->get();
        }

        $centers = CentroAtencion::select('id_centro_atencion as value', 'codigo_centro_atencion as label')->get();

        return response()->json([
            'recep'                         => $recep,
            'suppliers'                     => $suppliers,
            'centers'                       => $centers
        ]);
    }

    public function searchProduct(Request $request)
    {
        $search = $request->busqueda;
        $prodIdToIgnore = $request->prodIdToIgnore;
        if ($search != '') {
            $matchProds = Producto::with('unidad_medida')
                ->where(function ($query) use ($search) {
                    $query->where('nombre_producto', 'like', '%' . $search . '%')
                        ->orWhere('id_ccta_presupuestal', 'like', '%' . $search . '%');
                })
                ->where('estado_producto', 1)
                ->whereNotIn('id_producto', $prodIdToIgnore)
                ->get();

            $products = $matchProds->map(function ($prod) {
                return [
                    'value'             => $prod->id_producto,
                    'label'             => $prod->id_ccta_presupuestal . " - " . $prod->nombre_producto,
                    'allInfo'           => $prod
                ];
            });
        }
        return response()->json(
            [
                'products'          => $search != '' ? $products : [],
            ]
        );
    }

    public function storeDonationInfo(DonacionRequest $request)
    {
        DB::beginTransaction();
        try {
            $codeActa = "";
            $year = Carbon::now()->year;
            $lastActa = RecepcionPedido::whereYear('fecha_reg_recepcion_pedido', $year)
                ->orderBy('fecha_reg_recepcion_pedido', 'desc')
                ->first();
            if (!$lastActa) {
                $codeActa = '1/' . $year;
            } else {
                $correlative = intval(explode('/', $lastActa->acta_recepcion_pedido)[0]) + 1;
                $codeActa = $correlative . '/' . $year;
            }

            $rec = new RecepcionPedido([
                'id_det_doc_adquisicion'                => $request->detDocId,
                'id_proy_financiado'                    => 4, //Donation
                'id_proveedor'                          => $request->supplierId,
                'monto_recepcion_pedido'                => $request->total,
                'id_estado_recepcion_pedido'            => 1,
                'factura_recepcion_pedido'              => 66666, //We should delete this line afterwards
                'fecha_recepcion_pedido'                => Carbon::now(),
                'acta_recepcion_pedido'                 => $codeActa,
                'observacion_recepcion_pedido'          => $request->observation,
                'fecha_reg_recepcion_pedido'            => Carbon::now(),
                'usuario_recepcion_pedido'              => $request->user()->nick_usuario,
                'ip_recepcion_pedido'                   => $request->ip(),
            ]);
            $rec->save();

            foreach ($request->prods as $prod) {
                $newDet = new DetalleRecepcionPedido([
                    'id_centro_atencion'                        => $prod['centerId'],
                    'id_producto'                               => $prod['prodId'],
                    'id_recepcion_pedido'                       => $rec->id_recepcion_pedido,
                    'cant_det_recepcion_pedido'                 => $prod['qty'],
                    'costo_det_recepcion_pedido'                => $prod['cost'],
                    'id_prod_adquisicion'                       => 41, //We should delete this line afterwards
                    'estado_det_recepcion_pedido'               => 1,
                    'fecha_reg_det_recepcion_pedido'            => Carbon::now(),
                    'usuario_det_recepcion_pedido'              => $request->user()->nick_usuario,
                    'ip_det_recepcion_pedido'                   => $request->ip()
                ]);
                $newDet->save();
            }

            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'message'          => 'Guardado nueva donación con éxito.',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack(); // En caso de error, revierte las operaciones anteriores
            return response()->json([
                'logical_error' => 'Ha ocurrido un error con sus datos.',
                'error' => $th->getMessage(),
            ], 422);
        }
    }

    public function updateDonationInfo(DonacionRequest $request)
    {
        $rec = RecepcionPedido::find($request->id);
        if (!$rec || $rec->id_estado_recepcion_pedido == 3) {
            return response()->json(['logical_error' => 'Error, la recepcion que intentas modificar no existe o ha sido eliminada.'], 422);
        } else {
            DB::beginTransaction();
            try {
                $rec->update([
                    'monto_recepcion_pedido'                => $request->total,
                    'id_proveedor'                          => $request->supplierId,
                    'observacion_recepcion_pedido'          => $request->observation,
                    'fecha_mod_recepcion_pedido'            => Carbon::now(),
                    'usuario_recepcion_pedido'              => $request->user()->nick_usuario,
                    'ip_recepcion_pedido'                   => $request->ip(),
                ]);

                foreach ($request->prods as $prod) {
                    if ($prod['detRecId'] != "" && $prod['deleted'] == false) {
                        $det = DetalleRecepcionPedido::find($prod['detRecId']);
                        $det->update([
                            'id_centro_atencion'                        => $prod['centerId'],
                            'id_producto'                               => $prod['prodId'],
                            'id_recepcion_pedido'                       => $request->id,
                            'cant_det_recepcion_pedido'                 => $prod['qty'],
                            'costo_det_recepcion_pedido'                => $prod['cost'],
                            'fecha_mod_det_recepcion_pedido'            => Carbon::now(),
                            'usuario_det_recepcion_pedido'              => $request->user()->nick_usuario,
                            'ip_det_recepcion_pedido'                   => $request->ip()
                        ]);
                    }

                    if ($prod['detRecId'] != "" && $prod['deleted'] == true) {
                        $det = DetalleRecepcionPedido::find($prod['detRecId']);
                        $det->update([
                            'estado_det_recepcion_pedido' => 0,
                            'fecha_mod_det_recepcion_pedido' => Carbon::now(),
                            'usuario_det_recepcion_pedido' => $request->user()->nick_usuario,
                            'ip_det_recepcion_pedido' => $request->ip()
                        ]);
                    }

                    if ($prod['detRecId'] == "" && $prod['deleted'] == false) {
                        $existDetail = DetalleRecepcionPedido::where('id_recepcion_pedido', $request->id)
                            ->where('id_producto', $prod['prodId'])
                            ->first();
                        if ($existDetail) {
                            $existDetail->update([
                                'id_centro_atencion'                        => $prod['centerId'],
                                'id_producto'                               => $prod['prodId'],
                                'cant_det_recepcion_pedido'                 => $prod['qty'],
                                'costo_det_recepcion_pedido'                => $prod['cost'],
                                'estado_det_recepcion_pedido'               => 1,
                                'fecha_mod_det_recepcion_pedido'            => Carbon::now(),
                                'usuario_det_recepcion_pedido'              => $request->user()->nick_usuario,
                                'ip_det_recepcion_pedido'                   => $request->ip()
                            ]);
                        } else {
                            $newDet = new DetalleRecepcionPedido([
                                'id_centro_atencion'                        => $prod['centerId'],
                                'id_producto'                               => $prod['prodId'],
                                'id_recepcion_pedido'                       => $request->id,
                                'cant_det_recepcion_pedido'                 => $prod['qty'],
                                'costo_det_recepcion_pedido'                => $prod['cost'],
                                'id_prod_adquisicion'                       => 41, //We should delete this line afterwards
                                'estado_det_recepcion_pedido'               => 1,
                                'fecha_reg_det_recepcion_pedido'            => Carbon::now(),
                                'usuario_det_recepcion_pedido'              => $request->user()->nick_usuario,
                                'ip_det_recepcion_pedido'                   => $request->ip()
                            ]);
                            $newDet->save();
                        }
                    }
                }

                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'message'          => 'Actualizada donacion con exito.',
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

    public function changeStatusDonation(Request $request)
    {
        $reception = RecepcionPedido::find($request->id);
        if ($reception->id_estado_recepcion_pedido == 1 && $request->status == 1) {
            DB::beginTransaction();
            try {
                $reception->update([
                    'id_estado_recepcion_pedido'            => 3,
                    'fecha_mod_recepcion_pedido'            => Carbon::now(),
                    'usuario_recepcion_pedido'              => $request->user()->nick_usuario,
                    'ip_recepcion_pedido'                   => $request->ip(),
                ]);
                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'message'          => "Donación eliminada con éxito.",
                ]);
            } catch (\Exception $e) {
                DB::rollBack(); // En caso de error, revierte las operaciones anteriores
                return response()->json([
                    'logical_error' => 'Ha ocurrido un error con sus datos.',
                    'error' => $e,
                ], 422);
            }
        } else {
            return response()->json(['logical_error' => 'Error, otro usuario ha cambiado el estado de esta donacion.',], 422);
        }
    }

    public function getInfoModalSendDonation(Request $request, $id)
    {
        // $recInfo = RecepcionPedido::with(
        //     [
        //         'det_doc_adquisicion.documento_adquisicion.administradores' => function ($query) {
        //             $query->where('estado_admon_adquisicion', 1);
        //         },
        //         'det_doc_adquisicion.documento_adquisicion.administradores.empleado.persona'
        //     ]
        // )->find($id);

        $empleados = Empleado::with('persona')->where('id_estado_empleado',1)->get();

        $empOptions = $empleados->map(function ($e) {
            return [
                'value'           => $e->id_empleado,
                'label'           => $e->persona->nombre_apellido
            ];
        });

        return response()->json([
            //'recInfo'                           => $recInfo,
            'empOptions'                        => $empOptions
        ]);
    }

    public function sendGoodsDonation(Request $request)
    {
        //Define the custom messages
        $customMessages = [
            'authorizeEmpId.required' => 'Debe seleccionar el empleado que autoriza la donación.',
            'receiveEmpId.required' => 'Debe seleccionar el empleado que recibe la donación.',
            'observation.required' => 'Debe agregar la descripción de la donación.'
        ];

        // Validate the request data with custom error messages and custom rule
        $validatedData = Validator::make($request->all(), [
            'authorizeEmpId' => 'required',
            'receiveEmpId' => 'required',
            'observation' => 'required',
        ], $customMessages)->validate();

        //Find the current products reception
        $reception = RecepcionPedido::with([
            'detalle_recepcion' => function ($query) {
                $query->where('estado_det_recepcion_pedido', 1);
            },
            'detalle_recepcion.producto'
        ])->find($request->id);
        //Find the user who stores the products reception
        $user = User::with('persona.empleado')->find($request->user()->id_usuario);
        //Find the name for the employee who receive the donation
        $receiveEmp = Empleado::with('persona')->find($request->receiveEmpId);

        if ($reception->id_estado_recepcion_pedido == 1) { //We must evaluate if the reception has the status 'CREADO'
            DB::beginTransaction(); //Start the transaction
            try {
                //We update the reception
                $reception->update([
                    'id_estado_recepcion_pedido'            => 2,
                    'id_empleado'                           => $request->authorizeEmpId,
                    'emp_id_empleado'                       => $user->persona->empleado->id_empleado,
                    'representante_prov_recepcion_pedido'   => $receiveEmp->persona->nombre_apellido, //Employee Name who receipt the donation
                    'fecha_mod_recepcion_pedido'            => Carbon::now(),
                    'usuario_recepcion_pedido'              => $request->user()->nick_usuario,
                    'ip_recepcion_pedido'                   => $request->ip(),
                ]);
                //Create a new Kardex object
                $kardex = new Kardex([
                    'id_recepcion_pedido'                   => $reception->id_recepcion_pedido,
                    'id_proy_financiado'                    => $reception->id_proy_financiado,
                    'id_tipo_mov_kardex'                    => 1,
                    'fecha_kardex'                          => Carbon::now(),
                    'fecha_reg_kardex'                      => Carbon::now(),
                    'usuario_kardex'                        => $request->user()->nick_usuario,
                    'ip_kardex'                             => $request->ip(),
                ]);
                $kardex->save();
                //Foreach 'detalle_reception' we create a 'detalle_kardex' instance
                foreach ($reception->detalle_recepcion as $det) {
                    $detKardex = new DetalleKardex([
                        'id_kardex'                         => $kardex->id_kardex,
                        'id_producto'                       => $det->producto->id_producto,
                        'id_centro_atencion'                => $det->id_centro_atencion,
                        'cant_det_kardex'                   => $det->cant_det_recepcion_pedido,
                        'costo_det_kardex'                  => $det->costo_det_recepcion_pedido,
                        'fecha_reg_det_kardex'              => Carbon::now(),
                        'usuario_det_kardex'                => $request->user()->nick_usuario,
                        'ip_det_kardex'                     => $request->ip(),
                    ]);
                    $detKardex->save();
                }

                //Missing call the procedure to update the stock

                // $resultados = DB::select("SELECT * FROM FN_UPDATE_EXISTENCIA_ALMACEN(?,?,?,?,?,?,?,?,?,?)",[
                //     $reception->id_proy_financiado,
                //     $
                // ]);

                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'message'          => "Donacion enviada al Kardex con éxito.",
                ]);
            } catch (\Exception $e) {
                DB::rollBack(); // En caso de error, revierte las operaciones anteriores
                return response()->json([
                    'logical_error' => 'Ha ocurrido un error con sus datos.',
                    'error' => $e->getMessage(),
                ], 422);
            }
        } else {
            return response()->json(['logical_error' => 'Error, otro usuario ha cambiado el estado de esta donacion.',], 422);
        }
    }
}
