<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Almacen\RequerimientoRequest;
use App\Models\CentroAtencion;
use App\Models\CentroProduccion;
use App\Models\DetalleExistenciaAlmacen;
use App\Models\DetalleKardex;
use App\Models\DetallePlaza;
use App\Models\DetalleRequerimiento;
use App\Models\ExistenciaAlmacen;
use App\Models\Kardex;
use App\Models\LineaTrabajo;
use App\Models\Marca;
use App\Models\Permiso;
use App\Models\PermisoUsuario;
use App\Models\PlazaAsignada;
use App\Models\Producto;
use App\Models\ProyectoFinanciado;
use App\Models\Requerimiento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Else_;
use Illuminate\Support\Facades\Validator;

class RequerimientoAlmacenController extends Controller
{

    public function getRequerimientoAlmacen(Request $request)
    {
        $v_columns = [
            'id_requerimiento',
            'id_lt',
            'id_centro_atencion',
            'cen_id_centro_atencion',
            'id_motivo_ajuste',
            'req_id_requerimiento',
            'id_proy_financiado',
            'id_tipo_mov_kardex',
            'id_estado_req',
            'id_tipo_req',
            'num_requerimiento',
            'fecha_requerimiento',
            'observacion_requerimiento',
            'fecha_reg_requerimiento',
            'fecha_mod_requerimiento',
            'usuario_requerimiento',
            'ip_requerimiento',
        ];

        $v_length = $request->input('length');
        $v_column = $request->input('column'); //Index
        $v_dir = $request->input('dir');
        $data = $request->input('search');


        $object = PermisoUsuario::where('id_usuario', $request->user()->id_usuario)->get();
        $coleccion = collect($object);

        $plazasAsignadas = PlazaAsignada::where("id_empleado", $request->user()->id_persona)->where("estado_plaza_asignada", 1)
            ->with(["centro_atencion"])
            ->get();


        // Crear una colección para almacenar los centros de atención únicos
        $centrosUnicos = collect();
        // Iterar sobre las plazas asignadas y agregar los centros de atención únicos a la colección
        foreach ( $plazasAsignadas as $plazaAsignada ) {
            $centroAtencion = $plazaAsignada->centro_atencion;
            $centrosUnicos->push($centroAtencion);
        }

        $centrosUnicos = $centrosUnicos->unique('id_centro_atencion');

        $ids = $centrosUnicos->pluck('id_centro_atencion');

        $v_query = Requerimiento::with([
            "detalles_requerimiento.producto",
            "detalles_requerimiento" => function ($query) {
                $query->where('estado_det_requerimiento', 1);
            },
            "centro_atencion",
            "linea_trabajo",
            "proyecto_financiado",
            "estado_requerimiento"

        ])->where("id_tipo_req", 1)
            ->when($coleccion->contains('id_rol', 23) || $coleccion->contains('id_rol', 24), function ($query) {
                return $query->where('id_estado_req', 2)
                    ->orWhere('id_estado_req', 3)
                    ->orWhere('id_estado_req', 4)
                    ->where('id_tipo_req', 1);
            })
            ->when($coleccion->contains('id_rol', 25), function ($query) use ($ids) {
                return $query->whereIn('id_centro_atencion', $ids);
            })
            ->orderBy($v_columns[$v_column], $v_dir);

        if ($data) {
            $v_query->where('num_requerimiento', 'like', '%' . $data["num_requerimiento"] . '%')
                ->where('id_centro_atencion', 'like', '%' . $data["id_centro_atencion"] . '%')
                ->where('id_lt', 'like', '%' . $data["id_lt"] . '%')
                ->where('id_proy_financiado', 'like', '%' . $data["id_proy_financiado"] . '%')
                ->where('fecha_requerimiento', 'like', '%' . $data["fecha_requerimiento"] . '%')
                ->where('id_estado_req', 'like', '%' . $data["id_estado_req"] . '%');
        }

        $data = $v_query->paginate($v_length)->onEachSide(1);
        return [
            'data'       => $data,
            'draw'       => $request->input('draw'),
            "ids"        => $ids,
            'canSaveReq' => $coleccion->contains('id_rol', 23) || $coleccion->contains('id_rol', 24),
        ];
    }

    function getObject(Request $request): array
    {

        // Obtener todas las líneas de trabajo
        $lineaTrabajo = LineaTrabajo::all();

        // Obtener todos los centros de atención
        $centrosAtencion = CentroAtencion::all();

        // Obtener todos los proyectos financiados
        $proyectosFinanciados = ProyectoFinanciado::all();

        $plazasAsignadas = PlazaAsignada::where("id_empleado", $request->user()->id_persona)
            ->with([
                "centro_atencion.asignacion_centro_produccion" => function ($query) {
                    $query->where("id_centro_atencion", 1);
                },
                "centro_atencion.asignacion_centro_produccion.centro_produccion"
            ])->get();
        // Crear una colección para almacenar los centros de atención únicos
        $centrosUnicos = collect();
        // Iterar sobre las plazas asignadas y agregar los centros de atención únicos a la colección
        foreach ( $plazasAsignadas as $plazaAsignada ) {
            $centroAtencion = $plazaAsignada->centro_atencion;
            $centrosUnicos->push($centroAtencion);
        }

        $centrosUnicos = $centrosUnicos->unique('id_centro_atencion');

        $centroProduccionUnicos = collect();

        foreach ( $centrosUnicos as $centros ) {

            foreach ( $centros->asignacion_centro_produccion as $key => $value ) {
                $asignacion = $value->centro_produccion;
                $centroProduccionUnicos->push($asignacion);
            }
        }

        $centroProduccion = CentroProduccion::all();/* whereIn("id_tipo_centro_atencion",$idTipoCentroAtencionArray)->get(); */

        $marcas = Marca::all();

        // Obtener todos los productos
        $productos = Producto::all();
        return [
            'lineaTrabajo'         => $lineaTrabajo,
            'marcas'               => $marcas,
            'centrosAtencion'      => $centrosAtencion,
            'proyectosFinanciados' => $proyectosFinanciados,
            'productos'            => $productos,
            'centroProduccion'     => $centroProduccionUnicos,
            //'centroProduccionUnicos' => $centroProduccionUnicos
        ];
    }

    function getProductByNameOrCode(Request $request)
    {
        $stringBusqueda = $request->nombre;
        $data = Producto::where('nombre_producto', 'like', '%' . $stringBusqueda . '%')->get();

        return $data->map(function ($item) {
            return [
                'value'           => $item->id_producto,
                'label'           => $item->codigo_producto . ' - ' . $item->nombre_producto,
                'allDataPersonas' => $item,
            ];
        });
    }



    function addRequerimiento(Request $request) /* addRequerimiento(Request $request) */
    {


        try {
            DB::beginTransaction();

            $ultimoRequerimiento = Requerimiento::latest("fecha_reg_requerimiento")->where('id_tipo_req', 1)->first();

            if ($ultimoRequerimiento) {
                $currentNumberParts = explode('-', $ultimoRequerimiento->num_requerimiento);

                $currentYear = (int) now()->year;
                $secuencia = (int) $currentNumberParts[2];

                if ((int) $currentNumberParts[1] !== $currentYear) {
                    $currentYear += 1;
                    $secuencia = 1;
                } else {
                    // Incrementar la secuencia si el año actual coincide con el año del último requerimiento
                    $secuencia += 1;
                }
            } else {
                // Si no se encuentra ningún registro de requerimiento, establecer valores predeterminados
                $currentYear = (int) now()->year;
                $secuencia = 1;
            }

            // Construir el número de requerimiento
            $newNumber = 'REQ-' . $currentYear . '-' . $secuencia;


            /*  return $newNumber; */

            // Definir las reglas de validación
            $rules = [
                'idCentroAtencion' => 'required',
                'idProyFinanciado' => 'required',
            ];

            // Definir los mensajes de error personalizados
            $messages = [
                'required' => 'El campo :attribute es obligatorio.',
            ];

            // Validar los datos recibidos en la solicitud
            $validator = Validator::make($request->all(), $rules, $messages);

            // Agregar una regla de validación personalizada para idLt
            $validator->sometimes('idLt', 'required', function ($input) {
                return $input->idProyFinanciado != 4;
            });

            // Comprobar si la validación falla
            if ($validator->fails()) {
                // Manejar los errores de validación
                // Por ejemplo, puedes retornar una respuesta con los errores
                return response()->json(['errors' => $validator->errors()], 422);
            }


            $requerimiento = [
                'id_lt'                     => $request->idLt,
                'id_centro_atencion'        => $request->idCentroAtencion,
                'id_proy_financiado'        => $request->idProyFinanciado,
                'id_estado_req'             => 1,
                'id_tipo_mov_kardex'        => 2,
                'id_tipo_req'               => 1,
                'num_requerimiento'         => $newNumber,
                'fecha_requerimiento'       => Carbon::now(),
                'observacion_requerimiento' => $request->observacionRequerimiento,
                'fecha_reg_requerimiento'   => Carbon::now(),
                'usuario_requerimiento'     => $request->user()->nick_usuario,
                'ip_requerimiento'          => $request->ip(),

            ];
            $requerimientoId = Requerimiento::insertGetId($requerimiento);

            foreach ( $request->dataDetalleRequerimiento as $key => $value ) {

                foreach ( $value["productos"] as $key => $value2 ) {

                    collect($request->dataDetalleRequerimiento)->each(function ($prodReq, $key) use (&$rules, &$request) {
                        if ($prodReq["stateCentroProduccion"] == 0)
                            return;

                            collect($request["dataDetalleRequerimiento"][$key]["productos"]) // Ensure correct access
                            ->each(function ($det, $indice) use (&$rules, $key, $prodReq) {
                                if ($det["stateProducto"] == 0)
                                    return;

                                $rules["dataDetalleRequerimiento." . (string)$key . ".productos." . (string)$indice . ".cantDetRequerimiento"] = ['required'];
                                $rules["dataDetalleRequerimiento." . (string)$key . ".productos." . (string)$indice . ".idDetExistenciaAlmacen"] = ['required'];

                                if (!empty ($det["idDetExistenciaAlmacen"])) {

                                    $cantidadTotal = 0;

                                    $detalleExistenciaAlmacen = DetalleExistenciaAlmacen::select("cant_det_existencia_almacen")
                                        ->where('id_det_existencia_almacen', $det["idDetExistenciaAlmacen"])
                                        ->first();

                                    $cantidadDetalleExistencia = $detalleExistenciaAlmacen->cant_det_existencia_almacen;

                                    $totalDetalleRequerimiento = DetalleRequerimiento::whereHas('requerimiento', function ($query) {
                                        $query->whereIn('id_estado_req', [1, 2])
                                            ->where('id_tipo_req', 1);
                                    })
                                        ->where('id_det_existencia_almacen', $det["idDetExistenciaAlmacen"])
                                        ->sum('cant_det_requerimiento');

                                    $cantidadTotal = $cantidadDetalleExistencia - $totalDetalleRequerimiento;


                                    $rules["dataDetalleRequerimiento." . (string)$key . ".productos." . (string)$indice . ".cantDetRequerimiento"] = [
                                        'required',
                                        'numeric',
                                        "lte:$cantidadTotal",
                                    ];
                                }
                            });
                    });

                    $messages = [
                        'dataDetalleRequerimiento.*.productos.*.idDetExistenciaAlmacen.required' => 'El Producto es requerido para realizar esta acción.',
                        'dataDetalleRequerimiento.*.productos.*.cantDetRequerimiento.required'   => 'La cantidad del producto es requerida para realizar esta acción.',
                        'dataDetalleRequerimiento.*.productos.*.cantDetRequerimiento.numeric'    => 'El campo Cantidad de Requerimiento debe ser numérico.',
                        'dataDetalleRequerimiento.*.productos.*.cantDetRequerimiento.lte'        => 'La cantidad del producto seleccionado excede la cantidad disponible en el almacén (Cantidad disponible: :value)',
                    ];

                    $validator = Validator::make($request->all(), $rules, $messages);

                    if ($validator->fails()) {
                        $errors = $validator->errors()->toArray();
                        $message = 'The given data was invalid.';
                        return response()->json(['message' => $message, 'errors' => $errors], 422);
                    }
                    DetalleRequerimiento::insert(
                        [
                            'id_producto'                 => $value2["idProducto"],
                            'id_det_existencia_almacen'   => $value2["idDetExistenciaAlmacen"],
                            'id_centro_produccion'        => $value["idCentroProduccion"],
                            'id_marca'                    => $value2["idMarca"],
                            'id_requerimiento'            => $requerimientoId,
                            'cant_det_requerimiento'      => $value2["cantDetRequerimiento"],
                            'costo_det_requerimiento'     => 0,
                            'estado_det_requerimiento'    => 1,
                            'fecha_reg_det_requerimiento' => Carbon::now(),
                            'usuario_det_requerimiento'   => $request->user()->nick_usuario,
                            'ip_det_requerimiento'        => $request->ip(),
                        ]
                    );
                }
            }

            DB::commit();

            // Si llegamos aquí, todo se ejecutó correctamente, así que podemos devolver una respuesta o realizar cualquier otra acción necesaria.
            return response()->json(['message' => 'Requerimiento agregado correctamente'], 200);
        } catch (\Exception $e) {
            // Si ocurre un error, hacemos rollback de la transacción
            DB::rollback();

            // Retornamos una respuesta de error
            return response()->json($e->getMessage(), 422);
        }
    }

    function updateRequerimientoAlmacen(Request $request)
    {


        try {
            DB::beginTransaction();


            // Definir las reglas de validación
            $rules = [
                'idCentroAtencion' => 'required',
                'idProyFinanciado' => 'required',
            ];

            // Definir los mensajes de error personalizados
            $messages = [
                'required' => 'El campo :attribute es obligatorio.',
            ];

            // Validar los datos recibidos en la solicitud
            $validator = Validator::make($request->all(), $rules, $messages);

            // Agregar una regla de validación personalizada para idLt
            $validator->sometimes('idLt', 'required', function ($input) {
                return $input->idProyFinanciado != 4;
            });

            // Comprobar si la validación falla
            if ($validator->fails()) {
                // Manejar los errores de validación
                // Por ejemplo, puedes retornar una respuesta con los errores
                return response()->json(['errors' => $validator->errors()], 422);
            }


            $requerimiento = [
                'id_requerimiento'          => $request->idRequerimiento,
                'id_lt'                     => $request->idLt,
                'id_centro_atencion'        => $request->idCentroAtencion,
                'id_proy_financiado'        => $request->idProyFinanciado,
                'id_estado_req'             => 1,
                'num_requerimiento'         => $request->numRequerimiento,
                'fecha_requerimiento'       => Carbon::now(),
                'observacion_requerimiento' => $request->observacionRequerimiento,
                'fecha_mod_requerimiento'   => Carbon::now(),
                'usuario_requerimiento'     => $request->user()->nick_usuario,
                'ip_requerimiento'          => $request->ip(),

            ];

            Requerimiento::where("id_requerimiento", $request->idRequerimiento)->update($requerimiento);


            foreach ( $request->dataDetalleRequerimiento as $key => $value ) {


                foreach ( $value["productos"] as $key => $value2 ) {

                    if (!isset($value2["idDetRequerimiento"]) || !empty($value2["idDetRequerimiento"])) {

                        if ($value2["stateProducto"] == 1) {

                            $rules = [];



                            collect($request->dataDetalleRequerimiento)->each(function ($prodReq, $key) use (&$rules, &$request, &$value2) {
                                if ($prodReq["stateCentroProduccion"] == 0)
                                    return;

                                collect($request->input("dataDetalleRequerimiento")[$key]["productos"])
                                    ->each(function ($det, $indice) use (&$rules, $key, $prodReq, $value2) {
                                        if ($det["stateProducto"] == 0)
                                        return;
                                        $rules["dataDetalleRequerimiento." . $key . ".productos." . $indice . ".cantDetRequerimiento"] = ['required'];
                                        $rules["dataDetalleRequerimiento." . $key . ".productos." . $indice . ".idDetExistenciaAlmacen"] = ['required'];

                                        if (!empty ($det["idDetExistenciaAlmacen"])) {

                                            $cantidadTotal = 0;

                                            $detalleExistenciaAlmacen = DetalleExistenciaAlmacen::select("cant_det_existencia_almacen")
                                                ->where('id_det_existencia_almacen', $det["idDetExistenciaAlmacen"])
                                                ->first();

                                            $cantidadDetalleExistencia = $detalleExistenciaAlmacen->cant_det_existencia_almacen;


                                            $totalDetalleRequerimiento = DetalleRequerimiento::whereHas('requerimiento', function ($query) {
                                                $query->whereIn('id_estado_req', [1, 2])
                                                    ->where('id_tipo_req', 1);
                                            })->whereNotIn("id_det_requerimiento", [$value2["idDetRequerimiento"]])
                                                ->where('id_det_existencia_almacen', $det["idDetExistenciaAlmacen"])
                                                ->sum('cant_det_requerimiento');

                                            $cantidadTotal = $cantidadDetalleExistencia - $totalDetalleRequerimiento; // Sumamos


                                            $rules["dataDetalleRequerimiento." . (string)$key . ".productos." . (string)$indice . ".cantDetRequerimiento"] = [
                                                'required',
                                                'numeric',
                                            ];
                                        }
                                    });
                            });

                            $messages = [
                                'dataDetalleRequerimiento.*.productos.*.idDetExistenciaAlmacen.required' => 'El Producto es requerido para realizar esta acción.',
                                'dataDetalleRequerimiento.*.productos.*.cantDetRequerimiento.required'   => 'La cantidad del producto es requerida para realizar esta acción.',
                                'dataDetalleRequerimiento.*.productos.*.cantDetRequerimiento.numeric'    => 'El campo Cantidad de Requerimiento debe ser numérico.',
                                'dataDetalleRequerimiento.*.productos.*.cantDetRequerimiento.lte'        => 'La cantidad del producto seleccionado excede la cantidad disponible en el almacén (Cantidad disponible: :value)',
                            ];
                            $validator = Validator::make($request->all(), $rules, $messages);

                            if ($validator->fails()) {
                                $errors = $validator->errors()->toArray();
                                $message = 'The given data was invalid.';
                                return response()->json(['message' => $message, 'errors' => $errors], 422);
                            }


                            DetalleRequerimiento::where("id_det_requerimiento", $value2["idDetRequerimiento"])->update(
                                [
                                    'id_producto'                 => $value2["idProducto"],
                                    'id_marca'                    => $value2["idMarca"],
                                    'id_centro_produccion'        => $value["idCentroProduccion"],
                                    'id_det_existencia_almacen'   => $value2["idDetExistenciaAlmacen"],
                                    'id_requerimiento'            => $request->idRequerimiento,
                                    'cant_det_requerimiento'      => $value2["cantDetRequerimiento"],
                                    'costo_det_requerimiento'     => 0,
                                    'fecha_reg_det_requerimiento' => Carbon::now(),
                                    'usuario_det_requerimiento'   => $request->user()->nick_usuario,
                                    'ip_det_requerimiento'        => $request->ip(),
                                ]
                            );
                        } else {
                            // eliminar
                            DetalleRequerimiento::where("id_det_requerimiento", $value2["idDetRequerimiento"])->update([
                                "estado_det_requerimiento" => 0
                            ]);
                        }
                    } else {

                        if ($value2["stateProducto"] == 1) {

                            $rules = [];
                            collect($request->dataDetalleRequerimiento)->each(function ($prodReq, $key) use (&$rules, &$request) {
                                if ($prodReq["stateCentroProduccion"] == 0)
                                    return;

                                collect($request->input("dataDetalleRequerimiento." . (string)$key . ".productos"))
                                    ->each(function ($det, $indice) use (&$rules, $key, $prodReq) {
                                        if ($det["stateProducto"] == 0)
                                            return;

                                        $rules["dataDetalleRequerimiento." . (string)$key . ".productos." . (string)$indice . ".cantDetRequerimiento"] = ['required'];
                                        $rules["dataDetalleRequerimiento." . (string)$key . ".productos." . (string)$indice . ".idDetExistenciaAlmacen"] = ['required'];

                                        if (!empty ($det["idDetExistenciaAlmacen"])) {

                                            $cantidadTotal = 0;

                                            $detalleExistenciaAlmacen = DetalleExistenciaAlmacen::select("cant_det_existencia_almacen")
                                                ->where('id_det_existencia_almacen', $det["idDetExistenciaAlmacen"])
                                                ->first();

                                            $cantidadDetalleExistencia = $detalleExistenciaAlmacen->cant_det_existencia_almacen;


                                            $totalDetalleRequerimiento = DetalleRequerimiento::whereHas('requerimiento', function ($query) {
                                                $query->whereIn('id_estado_req', [1, 2])
                                                    ->where('id_tipo_req', 1);
                                            })
                                                ->where('id_det_existencia_almacen', $det["idDetExistenciaAlmacen"])
                                                ->sum('cant_det_requerimiento');

                                            $cantidadTotal = $cantidadDetalleExistencia - $totalDetalleRequerimiento;


                                            $rules["dataDetalleRequerimiento." . (string)$key . ".productos." . (string)$indice . ".cantDetRequerimiento"] = [
                                                'required',
                                                'numeric',
                                            ];
                                        }
                                    });
                            });

                            $messages = [
                                'dataDetalleRequerimiento.*.productos.*.idDetExistenciaAlmacen.required' => 'El Producto es requerido para realizar esta acción.',
                                'dataDetalleRequerimiento.*.productos.*.cantDetRequerimiento.required'   => 'La cantidad del producto es requerida para realizar esta acción.',
                                'dataDetalleRequerimiento.*.productos.*.cantDetRequerimiento.numeric'    => 'El campo Cantidad de Requerimiento debe ser numérico.',
                                'dataDetalleRequerimiento.*.productos.*.cantDetRequerimiento.lte'        => 'La cantidad del producto seleccionado excede la cantidad disponible en el almacén (Cantidad disponible: :value)',
                            ];
                            $validator = Validator::make($request->all(), $rules, $messages);


                            $totalDetalleRequerimiento = DetalleRequerimiento::whereHas('requerimiento', function ($query) {
                                $query->whereIn('id_estado_req', [1, 2])
                                    ->where('id_tipo_req', 1);
                            })
                                ->where('id_det_existencia_almacen', 1)
                                ->sum('cant_det_requerimiento');

                            if ($validator->fails()) {
                                $errors = $validator->errors()->toArray();
                                $message = 'The given data was invalid.';
                                return response()->json(['message' => $message, 'errors' => $errors, 'tot' => $totalDetalleRequerimiento], 422);
                            }

                            // Agregar
                            DetalleRequerimiento::insert(
                                [
                                    'id_producto'                 => $value2["idProducto"],
                                    'id_marca'                    => $value2["idMarca"],
                                    'id_centro_produccion'        => $value["idCentroProduccion"],
                                    'id_requerimiento'            => $request->idRequerimiento,
                                    'cant_det_requerimiento'      => $value2["cantDetRequerimiento"],
                                    'costo_det_requerimiento'     => 0,
                                    'estado_det_requerimiento'    => 1,
                                    'id_det_existencia_almacen'   => $value2["idDetExistenciaAlmacen"],
                                    'fecha_reg_det_requerimiento' => Carbon::now(),
                                    'usuario_det_requerimiento'   => $request->user()->nick_usuario,
                                    'ip_det_requerimiento'        => $request->ip(),
                                ]
                            );
                        }
                    }
                }
            }


            DB::commit();

            // Si llegamos aquí, todo se ejecutó correctamente, así que podemos devolver una respuesta o realizar cualquier otra acción necesaria.
            return response()->json(['message' => 'Requerimiento actualizado correctamente'], 200);
        } catch (\Exception $e) {
            // Si ocurre un error, hacemos rollback de la transacción
            DB::rollback();
            return response()->json($e->getMessage(), 422);
        }
    }

    /**
     * Actualiza el estado de un requerimiento y realiza acciones adicionales según el estado.
     *
     * @param  Request $request - La solicitud HTTP que contiene los datos necesarios para actualizar el requerimiento.
     * @return array            - Un arreglo vacío como respuesta.
     */
    function updateStateRequerimiento(Request $request)
    {
        try {
            // Datos a actualizar en el requerimiento
            $objectUpdated = [
                "id_estado_req"           => $request->idEstado,
                'fecha_mod_requerimiento' => Carbon::now(),
            ];

            // Si el estado es "Despachado" (idEstado == 3)
                if ( $request->idEstado == 3) {
                // Obtener los detalles del requerimiento
                $detReq = DetalleRequerimiento::where("id_requerimiento", $request->idRequerimiento)->get();
                $existencias = [];

                // Iterar sobre los detalles del requerimiento
                foreach ( $detReq as $key => $value ) {


                    $cantidadTotal = 0;

                    // Obtenemos la cantidad del producto que hay en almacen
                    $detalleExistenciaAlmacen = DetalleExistenciaAlmacen::select("cant_det_existencia_almacen")
                        ->where('id_det_existencia_almacen', $value["id_det_existencia_almacen"])
                        ->first();

                    // Almacenamos la cantidad de ese producto
                    $cantidadDetalleExistencia = $detalleExistenciaAlmacen->cant_det_existencia_almacen;

                    // Obtenemos las cantidades de el producto que hay en requerimientos y ajustes pendientes por aprobar o despachar
                    $totalDetalleRequerimiento = DetalleRequerimiento::whereHas('requerimiento', function ($query) {
                        $query->whereIn('id_estado_req', [1, 2])
                            ->whereIn('id_tipo_req', [1, 2]);
                    })
                        ->where('id_det_existencia_almacen', $value["id_det_existencia_almacen"])
                        ->sum('cant_det_requerimiento');

                    // Operamos
                    $cantidadTotal = $cantidadDetalleExistencia - $totalDetalleRequerimiento;

                    // Verificamos si la cantidad total es negativa
                    if ($cantidadTotal < 0) {
                        // Si es negativa, devolvemos un error 422
                        return response()->json(['message' => 'No hay suficiente stock para este producto en el almacenadomient'], 422);
                    }


                    if ($request->idEstado == 3) {
                        // Buscar la existencia en el almacén
                        $existenciaAlmacen = ExistenciaAlmacen::where([
                            "id_producto"        => $value["id_producto"],
                            "id_proy_financiado" => $request->idProyectoFinanciado
                        ])->first();

                        // Buscar el detalle de existencia en el almacén
                        $detalleExistencia = DetalleExistenciaAlmacen::find($value["id_det_existencia_almacen"]);

                        // Obtener la cantidad actual del detalle de existencia en el almacén
                        $cantidadActual = $detalleExistencia->cant_det_existencia_almacen;

                        // Calcular la nueva cantidad restando la cantidad requerida
                        $nuevaCantidad = $cantidadActual - $value->cant_det_requerimiento;

                        // Actualizar la cantidad actual del detalle de existencia en el almacén
                        $detalleExistencia->update([
                            'cant_det_existencia_almacen' => $nuevaCantidad
                        ]);

                        // Calcular y actualizar el total del stock en el almacén
                        $totalNuevoStock = DetalleExistenciaAlmacen::where('id_existencia_almacen', $detalleExistencia->id_existencia_almacen)
                            ->sum('cant_det_existencia_almacen');

                        ExistenciaAlmacen::where("id_existencia_almacen", $detalleExistencia->id_existencia_almacen)->update([
                            "cant_existencia_almacen" => $totalNuevoStock
                        ]);

                        // Actualizar el costo del detalle del requerimiento con el costo de la existencia en el almacén
                        DetalleRequerimiento::where("id_det_requerimiento", $value["id_det_requerimiento"])->update([
                            "costo_det_requerimiento" => $existenciaAlmacen["costo_existencia_almacen"]
                        ]);
                    }
                }

                if ($request->idEstado == 3) {
                    // Crear un registro en el kardex
                    $requerimiento = Requerimiento::find($request->idRequerimiento);

                    $kardexArray = [
                        'id_proy_financiado' => $requerimiento->id_proy_financiado,
                        'id_tipo_req'        => 1,
                        'id_tipo_mov_kardex' => 2,
                        'id_requerimiento'   => $request->idRequerimiento,
                        'fecha_kardex'       => Carbon::now(),
                        'observacion_kardex' => 'Despacho del requerimiento: ' . $requerimiento->num_requerimiento,
                        'fecha_reg_kardex'   => Carbon::now(),
                        'usuario_kardex'     => $request->user()->nick_usuario,
                        'ip_kardex'          => $request->ip(),
                    ];
                    $cardexId = Kardex::insertGetId($kardexArray);

                    // Registrar detalles en el kardex
                    $detalleRequerimiento = DetalleRequerimiento::where("id_requerimiento", $request->idRequerimiento)->get();

                    foreach ( $detalleRequerimiento as $key => $value ) {
                        DetalleKardex::insert([
                            'id_kardex'            => $cardexId,
                            'id_producto'          => $value['id_producto'],
                            'id_lt'                => $requerimiento->id_lt,
                            'id_centro_atencion'   => $requerimiento->id_centro_atencion,
                            'id_marca'             => $value['id_marca'],
                            'cant_det_kardex'      => $value['cant_det_requerimiento'],
                            'costo_det_kardex'     => $value['costo_det_requerimiento'],
                            'fecha_reg_det_kardex' => Carbon::now(),
                            'usuario_det_kardex'   => $request->user()->nick_usuario,
                            'ip_det_kardex'        => $request->ip(),
                        ]);
                    }
                }
            }

            // Actualizar el requerimiento con los datos actualizados
            Requerimiento::where("id_requerimiento", $request->idRequerimiento)->update($objectUpdated);

            // Devolver "ok" como respuesta para indicar que la operación se realizó correctamente
            return response()->json(['message' => 'ok'], 200);
        } catch (\Exception $e) {
            // Manejar el error adecuadamente
            return response()->json(['error' => 'Error al actualizar el estado del requerimiento: ' . $e->getMessage()], 500);
        }
    }

    function getCentroProduccionByUsersCentro(Request $request)
    {
        $plazasAsignadas = PlazaAsignada::where("id_empleado", $request->user()->id_persona)
            ->with([
                "centro_atencion.asignacion_centro_produccion" => function ($query) use ($request) {
                    if ($request->idCentroAtencion != '') {
                        $query->where("id_centro_atencion", $request->idCentroAtencion);
                    }
                },
                "centro_atencion.asignacion_centro_produccion.centro_produccion"
            ])->get();
        // Crear una colección para almacenar los centros de atención únicos
        $centrosUnicos = collect();
        // Iterar sobre las plazas asignadas y agregar los centros de atención únicos a la colección
        foreach ( $plazasAsignadas as $plazaAsignada ) {
            $centroAtencion = $plazaAsignada->centro_atencion;
            $centrosUnicos->push($centroAtencion);
        }
        $centrosUnicos = $centrosUnicos->unique('id_centro_atencion');
        $centroProduccionUnicos = collect();
        foreach ( $centrosUnicos as $centros ) {
            foreach ( $centros->asignacion_centro_produccion as $key => $value ) {
                $asignacion = $value->centro_produccion;
                $centroProduccionUnicos->push($asignacion);
            }
        }
        return $centroProduccionUnicos;
    }

    function getProductByFundedProjectCenterAndWorkLine(Request $request): object
    {
        return DetalleExistenciaAlmacen::with(['existencia_almacen.productos', 'marca'])
            ->select(
                'detalle_existencia_almacen.*',
                DB::raw('(SELECT SUM(detalle_requerimiento.cant_det_requerimiento) FROM detalle_requerimiento
            INNER JOIN requerimiento ON detalle_requerimiento.id_requerimiento = requerimiento.id_requerimiento
            WHERE detalle_requerimiento.id_det_existencia_almacen = detalle_existencia_almacen.id_det_existencia_almacen
            AND requerimiento.id_estado_req IN (1, 2)
            AND detalle_requerimiento.estado_det_requerimiento = 1
            AND requerimiento.id_tipo_req = 1) AS solicitado_en_req')
            )
            ->whereHas('existencia_almacen', function ($query) use ($request) {
                $query->where('id_proy_financiado', $request->idProyFinanciado);
            })
            ->where('id_centro_atencion', $request->idCentroAtencion)
            ->where('id_lt', $request->idLt)
            ->get();
    }

    function getProductionCenterByCenter(Request $request): array
    {
        $centrosInDb = CentroAtencion::where("id_centro_atencion", $request->idCentroAtencion)->with(["asignacion_centro_produccion.centro_produccion"])->get();
        $centroProduccionUnicos = collect();
        foreach ( $centrosInDb as $centros ) {
            foreach ( $centros->asignacion_centro_produccion as $key => $value ) {
                $asignacion = $value->centro_produccion;
                $centroProduccionUnicos->push($asignacion);
            }
        }
        return ["centroProduccion" => $centroProduccionUnicos, "centrosAtencion" => $centrosInDb];
    }
    function getAttentionCentersByUser(Request $request): object
    {
        $plazasAsignadas = PlazaAsignada::where("id_empleado", $request->user()->id_persona)->with("centro_atencion")->get();
        // Crear una colección para almacenar los centros de atención únicos
        $centrosUnicos = collect();
        // Iterar sobre las plazas asignadas y agregar los centros de atención únicos a la colección
        foreach ( $plazasAsignadas as $plazaAsignada ) {
            $centroAtencion = $plazaAsignada->centro_atencion;
            $centrosUnicos->push($centroAtencion);
        }
        // Filtrar la colección para obtener solo centros de atención únicos
        $centrosUnicos = $centrosUnicos->unique('id_centro_atencion');
        return $centrosUnicos;

    }
}
