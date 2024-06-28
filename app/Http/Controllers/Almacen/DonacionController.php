<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Almacen\DonacionRequest;
use App\Models\CentroAtencion;
use App\Models\DetalleKardex;
use App\Models\DetalleRecepcionPedido;
use App\Models\Empleado;
use App\Models\Kardex;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\RecepcionPedido;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Luecano\NumeroALetras\NumeroALetras;

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
                'detalle_recepcion.marca',
                'estado_recepcion',
                'proveedor'
            ])->find($idRec);

            if (!$recep) {
                return response()->json([
                    'logical_error' => 'Error, no fue posible obtener la recepción consultada. Consulte con el administrador.',
                ], 422);
            }
            $suppliers = Proveedor::select('id_proveedor as value', 'razon_social_proveedor as label', 'nit_proveedor', 'dui_proveedor')
                ->where('estado_proveedor', 1)->orWhere('id_proveedor', $recep->id_proveedor)->get();
        } else { //Creating a new one
            $recep = [];
            $suppliers = Proveedor::select('id_proveedor as value', 'razon_social_proveedor as label', 'nit_proveedor', 'dui_proveedor')
                ->where('estado_proveedor', 1)->get();
        }

        $centers = CentroAtencion::selectRaw('id_centro_atencion as value, concat(codigo_centro_atencion," - ",nombre_centro_atencion) as label')->get();
        $brands = Marca::select('id_marca as value', 'nombre_marca as label')->get();

        return response()->json([
            'recep'                         => $recep,
            'suppliers'                     => $suppliers,
            'centers'                       => $centers,
            'brands'                        => $brands
        ]);
    }

    public function searchProduct(Request $request)
    {
        $search = $request->busqueda;
        if ($search != '') {
            $matchProds = Producto::with('unidad_medida')
                ->where(function ($query) use ($search) {
                    $query->where('nombre_completo_producto', 'like', '%' . $search . '%')
                        ->orWhere('codigo_producto', 'like', '%' . $search . '%');
                })
                ->where('estado_producto', 1)
                //->whereNotIn('id_producto', $prodIdToIgnore)
                ->get();

            $products = $matchProds->map(function ($prod) {
                return [
                    'value'             => $prod->id_producto,
                    'label'             => $prod->codigo_producto.' — '.$prod->nombre_completo_producto.' — '.$prod->unidad_medida->nombre_unidad_medida,
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
                'fecha_recepcion_pedido'                => Carbon::now(),
                'acta_recepcion_pedido'                 => $codeActa,
                'observacion_recepcion_pedido'          => $request->observation,
                'fecha_reg_recepcion_pedido'            => Carbon::now(),
                'usuario_recepcion_pedido'              => $request->user()->nick_usuario,
                'ip_recepcion_pedido'                   => $request->ip(),
            ]);
            $rec->save();

            foreach ($request->prods as $prod) {
                $fecha = $prod['expDate'] != '' ? date('Y/m/d', strtotime($prod['expDate'])) : null;

                $existDetail = DetalleRecepcionPedido::where('id_recepcion_pedido', $rec->id_recepcion_pedido)
                    ->where('id_producto', $prod['prodId'])
                    ->where('id_marca', $prod['brandId'])
                    ->where('fecha_vcto_det_recepcion_pedido', $fecha)
                    ->where('costo_det_recepcion_pedido', number_format($prod['cost'], 2))
                    ->first();

                if ($existDetail) {
                    $existDetail->update([
                        'id_centro_atencion'                        => $request->centerId,
                        'cant_det_recepcion_pedido'                 => $existDetail->cant_det_recepcion_pedido + $prod['qty'],
                        'estado_det_recepcion_pedido'               => 1,
                        'fecha_mod_det_recepcion_pedido'            => Carbon::now(),
                        'usuario_det_recepcion_pedido'              => $request->user()->nick_usuario,
                        'ip_det_recepcion_pedido'                   => $request->ip()
                    ]);
                } else {
                    $newDet = new DetalleRecepcionPedido([
                        'id_centro_atencion'                        => $request->centerId,
                        'id_producto'                               => $prod['prodId'],
                        'id_recepcion_pedido'                       => $rec->id_recepcion_pedido,
                        'fecha_vcto_det_recepcion_pedido'           => $fecha,
                        'id_marca'                                  => $prod['brandId'],
                        'cant_det_recepcion_pedido'                 => $prod['qty'],
                        'costo_det_recepcion_pedido'                => $prod['cost'],
                        'estado_det_recepcion_pedido'               => 1,
                        'fecha_reg_det_recepcion_pedido'            => Carbon::now(),
                        'usuario_det_recepcion_pedido'              => $request->user()->nick_usuario,
                        'ip_det_recepcion_pedido'                   => $request->ip()
                    ]);
                    $newDet->save();
                }
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
                    'fecha_mod_recepcion_pedido'            => Carbon::now(),
                    'usuario_recepcion_pedido'              => $request->user()->nick_usuario,
                    'ip_recepcion_pedido'                   => $request->ip(),
                ]);

                foreach ($request->prods as $prod) {
                    $fecha = $prod['expDate'] != '' ? date('Y/m/d', strtotime($prod['expDate'])) : null;
                    if ($prod['detRecId'] != "" && $prod['deleted'] == false) {
                        $det = DetalleRecepcionPedido::find($prod['detRecId']);
                        $det->update([
                            'id_centro_atencion'                        => $request->centerId,
                            'id_producto'                               => $prod['prodId'],
                            'id_recepcion_pedido'                       => $request->id,
                            'fecha_vcto_det_recepcion_pedido'           => $fecha,
                            'id_marca'                                  => $prod['brandId'],
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
                            'id_centro_atencion'                        => $request->centerId,
                            'estado_det_recepcion_pedido'               => 0,
                            'fecha_mod_det_recepcion_pedido'            => Carbon::now(),
                            'usuario_det_recepcion_pedido'              => $request->user()->nick_usuario,
                            'ip_det_recepcion_pedido'                   => $request->ip()
                        ]);
                    }

                    if ($prod['detRecId'] == "" && $prod['deleted'] == false) {
                        $existDetail = DetalleRecepcionPedido::where('id_recepcion_pedido', $rec->id_recepcion_pedido)
                            ->where('id_producto', $prod['prodId'])
                            ->where('id_marca', $prod['brandId'])
                            ->where('fecha_vcto_det_recepcion_pedido', $fecha)
                            ->where('costo_det_recepcion_pedido', number_format($prod['cost'], 2))
                            ->first();
                        if ($existDetail) {
                            //Let's check if a detalle_recepcion_pedido exists, if it exist we update the quantity attribute
                            //If the existing detail is inactive, we set the quantity that we are sending from the view, otherwise we add the two quantities together.
                            $cant = $existDetail->estado_det_recepcion_pedido == 0 ? $prod['qty'] : $existDetail->cant_det_recepcion_pedido + $prod['qty'];
                            $existDetail->update([
                                'id_centro_atencion'                        => $request->centerId,
                                'cant_det_recepcion_pedido'                 => $cant,
                                'estado_det_recepcion_pedido'               => 1,
                                'fecha_mod_det_recepcion_pedido'            => Carbon::now(),
                                'usuario_det_recepcion_pedido'              => $request->user()->nick_usuario,
                                'ip_det_recepcion_pedido'                   => $request->ip()
                            ]);
                        } else {
                            $newDet = new DetalleRecepcionPedido([
                                'id_centro_atencion'                        => $request->centerId,
                                'id_producto'                               => $prod['prodId'],
                                'id_recepcion_pedido'                       => $request->id,
                                'cant_det_recepcion_pedido'                 => $prod['qty'],
                                'costo_det_recepcion_pedido'                => $prod['cost'],
                                'fecha_vcto_det_recepcion_pedido'           => $fecha,
                                'id_marca'                                  => $prod['brandId'],
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
        $empleados = Empleado::with('persona')->where('id_estado_empleado', 1)->get();

        $empOptions = $empleados->map(function ($e) {
            return [
                'value'           => $e->id_empleado,
                'label'           => $e->persona->nombre_apellido
            ];
        });

        return response()->json([
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
                    'observacion_recepcion_pedido'          => $request->observation,
                    'fecha_mod_recepcion_pedido'            => Carbon::now(),
                    'usuario_recepcion_pedido'              => $request->user()->nick_usuario,
                    'ip_recepcion_pedido'                   => $request->ip(),
                ]);
                //Create a new Kardex object
                $kardex = new Kardex([
                    'id_recepcion_pedido'                   => $reception->id_recepcion_pedido,
                    'id_proy_financiado'                    => $reception->id_proy_financiado,
                    'id_tipo_mov_kardex'                    => 1,
                    //'id_tipo_req'                           => 4, //DONACION
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
                        'id_marca'                          => $det->id_marca,
                        'fecha_vencimiento_det_kardex'      => $det->fecha_vcto_det_recepcion_pedido,
                        'cant_det_kardex'                   => $det->cant_det_recepcion_pedido,
                        'costo_det_kardex'                  => $det->costo_det_recepcion_pedido,
                        'fecha_reg_det_kardex'              => Carbon::now(),
                        'usuario_det_kardex'                => $request->user()->nick_usuario,
                        'ip_det_kardex'                     => $request->ip(),
                    ]);
                    $detKardex->save();
                    //We update the stock
                    $resultados = DB::select(" SELECT FN_UPDATE_EXISTENCIA_ALMACEN(?, ?, ?, NULL, ?, ?, ?, ?, ?, ?, ?)", [
                        $reception->id_proy_financiado,
                        $det->producto->id_producto,
                        $det->id_centro_atencion,
                        $det->id_marca,
                        $det->cant_det_recepcion_pedido,
                        $det->costo_det_recepcion_pedido,
                        $det->fecha_vcto_det_recepcion_pedido,
                        Carbon::now(),
                        $request->user()->nick_usuario,
                        $request->ip()
                    ]);
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
                'message'          => "Donacion enviada al Kardex con éxito.",
            ]);
        } else {
            return response()->json(['logical_error' => 'Error, otro usuario ha cambiado el estado de esta donacion.',], 422);
        }
    }

    public function printDonation(Request $request, $id)
    {
        $recToPrint = RecepcionPedido::with([
            'detalle_recepcion' => function ($query) {
                $query->where('estado_det_recepcion_pedido', 1);
            },
            'detalle_recepcion.centro_atencion',
            'detalle_recepcion.marca',
            'fuente_financiamiento',
            'proveedor',
            'detalle_recepcion.producto.unidad_medida',
            'administrador_contrato.persona',
            'guarda_almacen.persona'
        ])->find($id);
        if ($recToPrint->id_estado_recepcion_pedido == 2) {
            $numeroLetras = new NumeroALetras();
            $monto_letras = $numeroLetras->toInvoice($recToPrint->monto_recepcion_pedido, 2, 'DÓLARES');

            $recToPrint->monto_letras = $monto_letras;
            return response()->json([
                'recToPrint'                        => $recToPrint,
            ]);
        } else {
            return response()->json(['logical_error' => 'Error, la recepcion ha cambiado de estado.',], 422);
        }
    }
}
