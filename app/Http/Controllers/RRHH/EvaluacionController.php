<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Http\Requests\RRHH\EvaluacionRequest;
use App\Http\Requests\RRHH\EvaluacionRespuestasRequest;
use App\Http\Requests\RRHH\IncidentesRequest;
use App\Models\CategoriaRendimiento;
use App\Models\Dependencia;
use App\Models\DetalleEvaluacionPersonal;
use App\Models\Empleado;
use App\Models\EvaluacionPersonal;
use App\Models\EvaluacionRendimiento;
use App\Models\IncidenteEvaluacion;
use App\Models\Persona;
use App\Models\PlazaAsignada;
use App\Models\PlazaEvaluada;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EvaluacionController extends Controller
{
    // Obtenemos la evaluacion
    function getEvaluaciones(Request $request)
    {
        $columns = [
            'id_banco',
            'id_persona',
            'ip_empleado',
            'id_empleado',
            'nup_empleado',
            'isss_empleado',
            'id_tipo_pension',
            'codigo_empleado',
            'estado_empleado',
            'usuario_empleado',
            'fecha_reg_empleado',
            'fecha_mod_empleado',
            'id_titulo_profesional',
            'cuenta_banco_empleado',
            'email_alternativo_empleado',
            'fecha_contratacion_empleado',
            'email_institucional_empleado',
        ];

        $length = $request->input('length');
        $column = $request->input('column');
        $dir = $request->input('dir');
        $data = $request->input('search');

        $idPersona = $request->user()->id_persona;

        $idDependenciWhereIAm = Dependencia::where('id_persona', $idPersona)
            ->where('estado_dependencia', 1)
            ->pluck('id_dependencia')
            ->toArray();

        $idDependencies = Dependencia::whereIn('dep_id_dependencia', $idDependenciWhereIAm)
            ->pluck('id_dependencia')
            ->toArray();

        $idPersonas = Dependencia::whereIn('dep_id_dependencia', $idDependenciWhereIAm)
            ->pluck('id_persona')
            ->toArray();

        // Construir la consulta base con las relaciones
        $query = Empleado::with([
            "persona",
            "plazas_asignadas.centro_atencion.dependencias",
            "plazas_asignadas.dependencia.jefatura.empleado.plazas_asignadas.detalle_plaza.plaza",
            "plazas_asignadas.detalle_plaza.plaza",
            "evaluaciones_personal.incidentes_evaluacion" => function ($query) {
                return $query->where("estado_incidente_evaluacion", 1);
            },
            "evaluaciones_personal.detalle_evaluaciones_personal.categoria_rendimiento.evaluacion_rendimiento.tablas_rendimiento",
            "evaluaciones_personal.detalle_evaluaciones_personal.rubrica_rendimiento",
            "evaluaciones_personal.plaza_evaluada.plaza_asignada.detalle_plaza.plaza",
            "evaluaciones_personal.plaza_evaluada.plaza_asignada.centro_atencion",
            "evaluaciones_personal.plaza_evaluada.plaza_asignada.dependencia.jefatura.empleado.plazas_asignadas.detalle_plaza.plaza",
            "evaluaciones_personal.plaza_evaluada.plaza_asignada.dependencia.jefatura.empleado.plazas_asignadas.centro_atencion",
            "evaluaciones_personal.plaza_evaluada.plaza_asignada.dependencia.jefatura.empleado.plazas_asignadas.dependencia",
            "evaluaciones_personal.evaluacion_rendimiento",
            "evaluaciones_personal.tipo_evaluacion_personal",
            "evaluaciones_personal.estado_evaluacion_personal",
            "evaluaciones_personal.periodo_evaluacion",
            "evaluaciones_personal"                       => function ($query) use ($idDependencies) {
                $query->whereIn("id_estado_evaluacion_personal", !empty($idDependencies) ? [1, 5, 6] : [1, 4, 7])
                    ->orderBy("fecha_reg_evaluacion_personal", "asc");
                return $query;
            },

        ])->whereHas("evaluaciones_personal")->orderBy($columns[$column], $dir);


        // Aplicar el filtro de plazas_asignadas
        $query->whereHas("plazas_asignadas", function ($query) use ($idDependencies, $idDependenciWhereIAm) {
            if (!empty($idDependencies)) {
                $combinedDependencies = array_merge($idDependencies, $idDependenciWhereIAm);
                $query->whereIn("id_dependencia", $combinedDependencies);
            } else {
                $query->whereIn("id_dependencia", $idDependenciWhereIAm);
            }
        });

        if ($data) {
            $query->where('id_empleado', 'like', '%' . $data["id_empleado"] . '%')
                ->where('codigo_empleado', 'like', '%' . $data['codigo_empleado'] . '%');
            $query->whereHas(
                'persona',
                function ($query) use ($data) {

                    $searchNombres = $data["collecNombre"];
                    if (isset($searchNombres)) {
                        $query->whereRaw("MATCH ( pnombre_persona, snombre_persona, tnombre_persona ) AGAINST ( '" . $searchNombres . "')");
                    }
                    $searchApellidos = $data["collecApellido"];
                    if (isset($searchApellidos)) {
                        $query->whereRaw("MATCH ( papellido_persona, sapellido_persona, tapellido_persona ) AGAINST ( '" . $searchApellidos . "')");
                    }
                }
            );
            $query->whereHas('evaluaciones_personal', function ($query) use ($data) {
                $query->where('fecha_reg_evaluacion_personal', 'like', '%' . $data["fecha_reg_evaluacion_personal"] . '%');
            });
            $query->whereHas('evaluaciones_personal', function ($query) use ($data) {
                $query->where('id_periodo_evaluacion', 'like', '%' . $data["id_periodo_evaluacion"] . '%');
            });
            $query->whereHas('evaluaciones_personal', function ($query) use ($data) {
                $query->where('id_tipo_evaluacion_personal', 'like', '%' . $data["id_tipo_evaluacion_personal"] . '%');
            });
            $query->whereHas('evaluaciones_personal', function ($query) use ($data) {
                $query->where('puntaje_evaluacion_personal', 'like', '%' . $data["puntaje_evaluacion_personal"] . '%');
            });
        }

        $acuerdos = $query->paginate($length)->onEachSide(1);

        $formattedResults = $acuerdos->map(function ($item) use ($idDependenciWhereIAm, $idDependencies, $idPersonas) {
            if (!in_array($item->id_persona, $idPersonas)) { //Tomar personas que no están en la lista (porque los que están quiero que aparezcan)
                $item->evaluaciones_personal = $item->evaluaciones_personal->filter(function ($item2) use ($idDependenciWhereIAm) {
                    if (!in_array($item2->id_dependencia, $idDependenciWhereIAm)) {
                        if ($item2->id_estado_evaluacion_personal == 1) {
                            $item2->hasAnotherState = true;
                        }
                    }
                });
            }
            return $item;
        });


        $paginator = new LengthAwarePaginator(
            $formattedResults,
            $acuerdos->total(),
            $acuerdos->perPage(),
            $acuerdos->currentPage(),
            ['path' => url()->current()]
        );
        return [
            'data' => $paginator,
            'draw' => $request->input('draw'),
        ];
    }

    function searchEmployeesForNewEvaluationRequest(Request $request)
    {
        // Obtener el ID de la persona que realiza la solicitud y el nombre de búsqueda
        $idPersona = $request->user()->id_persona;
        $nombre = $request->nombre;


        // Obtener IDs de dependencias asociadas a la persona
        $depIds = Dependencia::where('id_persona', $idPersona)
            ->where('estado_dependencia', 1)
            ->pluck('id_dependencia')
            ->toArray();

        // Obtener dependencias con información de jefatura y empleados
        $dependencies = Dependencia::with(['jefatura.empleado.plazas_asignadas'])
            ->whereIn('dep_id_dependencia', $depIds)
            ->whereHas('jefatura', function ($query) use ($nombre) {
                /*  $query->where(function ($query) use ($nombre) {
                     $query->whereRaw(
                         "MATCH ( pnombre_persona, snombre_persona, tnombre_persona, papellido_persona, sapellido_persona, tapellido_persona ) AGAINST ( ?)",
                         [$nombre]
                     );
                 }); */
            })->get();

        // Filtrar y obtener jefaturas activas
        $personasJefeByDependencia = $dependencies
            ->filter(function ($dep) {
                return $dep->jefatura && $dep->estado_dependencia == 1;
            })
            ->pluck('jefatura');

        $results = Persona::with([
            'empleado'                  => function ($query) {
                $query->where('estado_empleado', 1); // Empleados activos
            },
            'empleado.evaluaciones_personal',
            'empleado.plazas_asignadas' => function ($query) use ($idPersona) {
                $query->where('estado_plaza_asignada', 1);
                $query->whereHas('dependencia', function ($query) use ($idPersona) {
                    $query->where('id_persona', $idPersona);
                });
            },
        ])
            ->whereHas('empleado', function ($query) use ($idPersona) {
                $query->where('estado_empleado', 1)
                    ->whereHas('plazas_asignadas', function ($query) use ($idPersona) {
                        $query->where('estado_plaza_asignada', 1);
                        $query->whereHas('dependencia', function ($query) use ($idPersona) {
                            $query->where('id_persona', $idPersona);
                        });
                    });
            })->where("id_persona", "!=" , $idPersona)
            /*  ->where(function ($query) use ($request) {
                 $query->whereRaw(
                     "MATCH ( pnombre_persona, snombre_persona, tnombre_persona, papellido_persona, sapellido_persona, tapellido_persona ) AGAINST ( ? )",
                     [$request->nombre]
                 );
             }) */
            ->get();
        // Fusionar resultados únicos de jefaturas y empleados
        $mergedResults = $personasJefeByDependencia->merge($results)->unique('id_persona');

        // Formatear resultados para respuesta JSON
        $formattedResults = $mergedResults->map(function ($item) {
            return [
                'value'           => $item->id_persona,
                'label'           => $item->nombre_completo,
                'allDataPersonas' => $item,
            ];
        });

        return response()->json($formattedResults);
    }
    /**
     * Obtener plazas asignadas según el usuario y la dependencia.
     *
     * @param Request $request
     * @return array
     */
    function getPlazaAsignadaByUserAndDependencia(Request $request)
    {
        try {

            // Obtener parámetros de la solicitud
            $employeeId = $request->employeeId;
            $idCentroAtencion = $request->centroAtencionId;
            $tipoEvaluacionId = $request->idTipoEvaluacion;

            $mensaje_debug = [];

            // Verificar si se proporcionaron fechas
            if (!empty($request->fechaInicioFechafin)) {
                // Separar las fechas proporcionadas
                [$fecha_inicio, $fecha_fin] = explode(" a ", $request->fechaInicioFechafin) + [null, null];

                try {
                    // Convertir fechas a objetos Carbon para comparaciones
                    $fechaInicioObj = Carbon::parse($fecha_inicio);
                    $fechaFinObj = Carbon::parse($fecha_fin);
                } catch (\Exception $e) {
                    // Manejar errores al parsear las fechas
                    throw new \Exception("Error al parsear las fechas. " . $e->getMessage(), 422);
                }
                // Obtener el año de las fechas
                $anio = $fechaInicioObj->year;

                // Definir límites para los periodos según el tipo de evaluación ( 1 == Evaluacion de desempeño, 2 == Evaluacion de periodo de prueba)
                if ($tipoEvaluacionId == 1) {
                    $limitePrimerPeriodo = Carbon::parse("{$anio}-06-30");
                    $limiteSegundoPeriodo = Carbon::parse("{$anio}-12-31");
                } else {
                    $limitePrimerPeriodo = $fechaInicioObj->copy();
                    $limiteSegundoPeriodo = $fechaInicioObj->copy()->addMonths(3);
                }

                // Determinar el periodo en base a las fechas
                if ($fechaInicioObj->lte($limitePrimerPeriodo) && $fechaFinObj->lte($limitePrimerPeriodo)) {
                    $id_periodo_evaluacion = 1; // Primer periodo
                    $mensaje_periodo = 'Primer periodo';
                } elseif ($fechaInicioObj->gte($limitePrimerPeriodo) && $fechaFinObj->lte($limiteSegundoPeriodo)) {
                    $id_periodo_evaluacion = 2; // Segundo periodo
                    $mensaje_periodo = 'Segundo periodo';
                } else {
                    // Manejar el caso en que las fechas no se ajusten a ninguno de los periodos

                    if ($tipoEvaluacionId == 1) {

                        return response()->json([
                            "error"           => "Las fechas no .",
                            "mensaje_periodo" => 'Las evaluaciones de desempeño deben estar dentro del primer periodo (del 1 de enero al 30 de junio) o del segundo periodo (del 1 de julio al 31 de diciembre).',
                        ], 422);
                    } else {

                        return response()->json([
                            "error"           => "Las fechas no .",
                            "mensaje_periodo" => 'Las evaluaciones de periodo de prueba deben tener un rango máximo de 3 meses.',
                        ], 422);
                    }
                }

                $mensaje_debug = [
                    "fechaInicio" => $fechaInicioObj->toDateString(),
                    "fechaFin"    => $fechaFinObj->toDateString(),
                    "periodo"     => ($fechaInicioObj->lte($limitePrimerPeriodo) && $fechaFinObj->lte($limitePrimerPeriodo)) ? 'Primer periodo' : 'Segundo periodo',
                ];
            } else {
                // Caso en que no se proporcionaron fechas - Devolver error 422
                return response()->json([
                    "error"           => "No se proporcionaron fechas.",
                    "mensaje_periodo" => 'No se proporcionaron fechas',
                ], 422);
            }

            // Obtener evaluaciones para el periodo y tipo de evaluación
            $objectEvaluation = EvaluacionPersonal::where("id_periodo_evaluacion", $id_periodo_evaluacion)
                ->where("id_tipo_evaluacion_personal", $tipoEvaluacionId)
                ->whereYear("fecha_inicio_evaluacion_personal", optional($fechaInicioObj)->year)
                ->where("id_empleado", $employeeId)
                ->get();

            // Obtener evaluaciones para pruebas
            $objectEvaluationByPrueba = EvaluacionPersonal::with(["plaza_evaluada.plaza_asignada"])
                ->where("id_tipo_evaluacion_personal", $tipoEvaluacionId)
                ->where("id_empleado", $employeeId)
                ->get();

            // Obtener una colección de todos los id_plaza_asignada
            $idPlazaAsignadaCollection = $objectEvaluationByPrueba->pluck('plaza_evaluada.*.plaza_asignada.id_plaza_asignada')->flatten();

            // Contar las evaluaciones por id_plaza_asignada
            $conteoPorPlazaAsignada = $idPlazaAsignadaCollection->countBy();

            // Filtra las plazas asignadas que tienen un conteo de 2 o más
            $plazasConDosOMasEvaluaciones = $conteoPorPlazaAsignada->filter(function ($conteo) {
                return $conteo >= 2;
            });

            // Obtiene las claves (id_plaza_asignada) del arreglo filtrado
            $plazasDosOMasEvaluaciones = $plazasConDosOMasEvaluaciones->keys()->toArray();

            if ($objectEvaluation->isEmpty()) {

                // Obtener todas las plazas asignadas
                $plazasAsignadas = ($tipoEvaluacionId == 1)
                    ? PlazaAsignada::with([
                        'detalle_plaza.plaza.tipo_plaza.evaluaciones_rendimientos',
                        'centro_atencion',
                        'dependencia',
                    ])->where('id_empleado', $employeeId)
                        ->where('id_centro_atencion', $idCentroAtencion)
                        ->get()
                    : PlazaAsignada::with([
                        'detalle_plaza.plaza.tipo_plaza.evaluaciones_rendimientos',
                        'centro_atencion',
                        'dependencia',
                    ])->where('id_empleado', $employeeId)
                        ->whereNotIn("id_plaza_asignada", $plazasDosOMasEvaluaciones)
                        ->where('id_centro_atencion', $idCentroAtencion)
                        ->get();
            } else {

                // Obtener plazas asignadas sin evaluaciones
                $plazasAsignadas = ($tipoEvaluacionId == 1)
                    ? PlazaAsignada::with([
                        'detalle_plaza.plaza.tipo_plaza.evaluaciones_rendimientos',
                        'centro_atencion',
                        'dependencia',
                    ])->where('id_empleado', $employeeId)
                        ->where('id_centro_atencion', $idCentroAtencion)
                        ->doesntHave("plaza_evaluada")
                        ->get()
                    : PlazaAsignada::with([
                        'detalle_plaza.plaza.tipo_plaza.evaluaciones_rendimientos',
                        'centro_atencion',
                        'dependencia',
                    ])->where('id_empleado', $employeeId)
                        ->whereNotIn("id_plaza_asignada", $plazasDosOMasEvaluaciones)
                        ->where('id_centro_atencion', $idCentroAtencion)
                        ->get();
            }

            // Obtener evaluaciones de rendimiento con información de tipo de plaza
            $evaluacionRendimiento = $plazasAsignadas->flatMap(function ($item) {
                $tipoPlaza = $item->detalle_plaza->plaza->tipo_plaza;

                return $tipoPlaza->evaluaciones_rendimientos->map(function ($evaluacion) use ($tipoPlaza) {
                    return [
                        'id_evaluacion_rendimiento'        => $evaluacion->id_evaluacion_rendimiento,
                        'codigo_evaluacion_rendimiento'    => $evaluacion->codigo_evaluacion_rendimiento,
                        'nombre_evaluacion_rendimiento'    => $evaluacion->nombre_evaluacion_rendimiento,
                        'version_evaluacion_rendimiento'   => $evaluacion->version_evaluacion_rendimiento,
                        'estado_evaluacion_rendimiento'    => $evaluacion->estado_evaluacion_rendimiento,
                        'fecha_reg_evaluacion_rendimiento' => $evaluacion->fecha_reg_evaluacion_rendimiento,
                        'fecha_mod_evaluacion_rendimiento' => $evaluacion->fecha_mod_evaluacion_rendimiento,
                        'usuario_evaluacion_rendimiento'   => $evaluacion->usuario_evaluacion_rendimiento,
                        'ip_evaluacion_rendimiento'        => $evaluacion->ip_evaluacion_rendimiento,
                        'tipo_plaza'                       => [
                            'id_tipo_plaza'     => $tipoPlaza->id_tipo_plaza,
                            'nombre_tipo_plaza' => $tipoPlaza->nombre_tipo_plaza,
                        ],
                    ];
                });
            })->unique('id_evaluacion_rendimiento');

            $cantidadEvaluacionesRendimiento = $evaluacionRendimiento->count();

            // Devolver el resultado
            return [
                //Data que uso en el front
                "plazasAsignadas"                 => $plazasAsignadas,
                "evaluacionRendimiento"           => $evaluacionRendimiento,
                'cantidadEvaluacionesRendimiento' => $cantidadEvaluacionesRendimiento,
                "mensaje_debug"                   => $mensaje_debug
            ];
        } catch (\Throwable $th) {
            // Manejar errores y devolver respuesta JSON con código de estado 500
            return response()->json([
                "error"   => "Error en la transacción.",
                "mensaje" => is_array($th->getMessage()) ? json_encode($th->getMessage()) : $th->getMessage(),
            ], 500);
        }
    }
    /**
     * Crear una nueva evaluación para un empleado.
     *
     * @param EvaluacionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    function createNewEvaluation(EvaluacionRequest $request)
    {

        try {
            DB::beginTransaction();
            // Variables para almacenar el resultado y mensajes
            $id_periodo_evaluacion = 1;
            $mensaje_periodo = '';
            $mensaje_debug = [];

            // Verificar si se proporcionaron fechas
            if (!empty($request->fechaInicioFechafin)) {
                // Separar las fechas proporcionadas
                $fechas = explode(" a ", $request->fechaInicioFechafin);
                $fecha_inicio = $fechas[0];
                $fecha_fin = isset($fechas[1]) ? $fechas[1] : $fechas[0];

                try {
                    // Convertir fechas a objetos Carbon para comparaciones
                    $fechaInicioObj = Carbon::parse($fecha_inicio);
                    $fechaFinObj = Carbon::parse($fecha_fin);
                } catch (\Exception $e) {
                    // Manejar errores al parsear las fechas
                    return response()->json([
                        "error"   => "Error al parsear las fechas.",
                        "mensaje" => $e->getMessage(),
                    ], 400);
                }

                $anio = $fechaInicioObj->year;

                $limitePrimerPeriodo = Carbon::parse("{$anio}-06-30");
                $limiteSegundoPeriodo = Carbon::parse("{$anio}-12-31");


                // Añadir mensajes de depuración para las fechas
                $mensaje_debug = [
                    "fechaInicio" => $fechaInicioObj->toDateString(),
                    "fechaFin"    => $fechaFinObj->toDateString(),
                ];

                // Determinar el periodo en base a las fechas
                if ($fechaInicioObj->lte($limitePrimerPeriodo) && $fechaFinObj->lte($limitePrimerPeriodo)) {
                    $id_periodo_evaluacion = 1; // Primer periodo
                    $mensaje_periodo = 'Primer periodo';
                } elseif ($fechaInicioObj->gte($limitePrimerPeriodo) && $fechaFinObj->lte($limiteSegundoPeriodo)) {
                    $id_periodo_evaluacion = 2; // Segundo periodo
                    $mensaje_periodo = 'Segundo periodo';
                }

                // Añadir mensajes de depuración para el periodo
                $mensaje_debug["periodo"] = $mensaje_periodo;
            } else {
                // Manejar el caso en que no se proporcionaron fechas
                $fecha_inicio = null;
                $fecha_fin = null;
                /* $id_periodo_evaluacion = 1; */
                $mensaje_periodo = 'No se proporcionaron fechas';
            }

            $evaluacionPersonalArray = [
                'id_evaluacion_rendimiento'        => $request->idEvaluacionRendimiento,
                'id_periodo_evaluacion'            => $id_periodo_evaluacion,
                'id_empleado'                      => $request->idEmpleado,
                'id_estado_evaluacion_personal'    => 1, // ** Se deja por default en 1
                'id_dependencia'                   => $request->plazasAsignadas[0]["dependencia"]["id_dependencia"], // ?Si el empleado tiene multiples plazas que dependencia voy a ingresar aqui
                'id_tipo_evaluacion_personal'      => $request->idTipoEvaluacion,
                'fecha_evaluacion_personal'        => Carbon::now(),
                'puntaje_evaluacion_personal'      => 0, // Inicialmente
                'fecha_inicio_evaluacion_personal' => $mensaje_debug["fechaInicio"],
                'fecha_fin_evaluacion_personal'    => $mensaje_debug["fechaFin"],
                'observacion_incidente_personal'   => '', // Sin observaciones al crear
                'fecha_reg_evaluacion_personal'    => Carbon::now(),
                'usuario_evaluacion_personal'      => $request->user()->nick_usuario,
                'ip_evaluacion_personal'           => $request->ip(),
            ];
            // Insertar la nueva evaluación personal
            $evaluacionInsertedId = EvaluacionPersonal::insertGetId($evaluacionPersonalArray);
            // Datos para las plazas evaluadas
            $plazas = [];
            foreach ( $request->plazasAsignadas as $key => $value ) {
                $plazaEvaluada = [
                    'id_plaza_asignada'        => $value["value"],
                    'id_evaluacion_personal'   => $evaluacionInsertedId,
                    'estado_plaza_evaluada'    => 1,
                    'fecha_reg_plaza_evaluada' => Carbon::now(),
                    'usuario_plaza_evaluada'   => $request->user()->nick_usuario,
                    'ip_plaza_evaluada'        => $request->ip(),

                ];
                $plazas[] = $plazaEvaluada;
            }
            // Insertar las plazas evaluadas
            $response = PlazaEvaluada::insert($plazas);
            // Obtener información adicional del empleado con las evaluaciones
            $query = Empleado::with([
                "persona",
                "plazas_asignadas.centro_atencion.dependencias",
                "plazas_asignadas.dependencia.jefatura.empleado.plazas_asignadas.detalle_plaza.plaza",
                "plazas_asignadas.detalle_plaza.plaza",
                "evaluaciones_personal"                       => function ($query) use ($evaluacionInsertedId) {
                    $query->find($evaluacionInsertedId)
                        ->orderBy("fecha_reg_evaluacion_personal", "asc");
                    return $query;
                },
                "evaluaciones_personal.incidentes_evaluacion" => function ($query) {
                    return $query->where("estado_incidente_evaluacion", 1);
                },
                "evaluaciones_personal.detalle_evaluaciones_personal.categoria_rendimiento.evaluacion_rendimiento.tablas_rendimiento",
                "evaluaciones_personal.detalle_evaluaciones_personal.rubrica_rendimiento",
                "evaluaciones_personal.plaza_evaluada.plaza_asignada.detalle_plaza.plaza",
                "evaluaciones_personal.plaza_evaluada.plaza_asignada.centro_atencion",
                "evaluaciones_personal.plaza_evaluada.plaza_asignada.dependencia.jefatura.empleado.plazas_asignadas.detalle_plaza.plaza",
                "evaluaciones_personal.plaza_evaluada.plaza_asignada.dependencia.jefatura.empleado.plazas_asignadas.centro_atencion",
                "evaluaciones_personal.plaza_evaluada.plaza_asignada.dependencia.jefatura.empleado.plazas_asignadas.dependencia",
                "evaluaciones_personal.evaluacion_rendimiento",
                "evaluaciones_personal.tipo_evaluacion_personal",
                "evaluaciones_personal.periodo_evaluacion",
                "evaluaciones_personal.estado_evaluacion_personal",
            ])->whereHas("evaluaciones_personal")->find($request->idEmpleado);

            /*  $query = EvaluacionPersonal::with([
                "incidentes_evaluacion" => function ($query) {
                    return $query->where("estado_incidente_evaluacion", 1);
                },
                "detalle_evaluaciones_personal.categoria_rendimiento.evaluacion_rendimiento.tablas_rendimiento",
                "detalle_evaluaciones_personal.rubrica_rendimiento",
                "plaza_evaluada.plaza_asignada.detalle_plaza.plaza",
                "plaza_evaluada.plaza_asignada.centro_atencion",
                "plaza_evaluada.plaza_asignada.dependencia.jefatura.empleado.plazas_asignadas.detalle_plaza.plaza",
                "plaza_evaluada.plaza_asignada.dependencia.jefatura.empleado.plazas_asignadas.centro_atencion",
                "plaza_evaluada.plaza_asignada.dependencia.jefatura.empleado.plazas_asignadas.dependencia",
                "evaluacion_rendimiento",
                "tipo_evaluacion_personal",
                "periodo_evaluacion",
                "estado_evaluacion_personal",
            ])->find($evaluacionInsertedId); */

            DB::commit();

            // Devolver el resultado
            return response()->json([
                "id_periodo_evaluacion" => $id_periodo_evaluacion,
                "mensaje_periodo"       => $mensaje_periodo,
                "mensaje_debug"         => $mensaje_debug,
                "evaluacionInsertedId"  => $evaluacionInsertedId,
                "response"              => $query
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                "error"   => "Error en la transacción.",
                "mensaje" => is_array($th->getMessage()) ? json_encode($th->getMessage()) : $th->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener la versión de la evaluación de rendimiento personal.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder|object|null
     */
    function getPersonalPerformanceEvaluationVersion(Request $request)
    {
        // Utilizar Eloquent para obtener la evaluación de rendimiento personal con sus categorías y rubricas asociadas
        $query = EvaluacionRendimiento::with([
            "categorias_rendimiento.rubricas_rendimiento"
        ])->where("id_evaluacion_rendimiento", $request->idEvaluacionRendimiento)->first();

        // Retornar el resultado de la consulta
        return $query;
    }


    // Guardamos la respuesta que se ha seleccionado en la evaluacion

    /**
     * Guarda las respuestas en una evaluación personal.
     *
     * @param EvaluacionRespuestasRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Request
     */
    function saveResponseInEvaluation(EvaluacionRespuestasRequest $request)
    {
        try {
            // Inicia una transacción de base de datos
            DB::beginTransaction();

            // Itera sobre las respuestas de rendimiento en la solicitud
            foreach ( $request->responseRendimiento as $key => $value ) {
                // Construye un arreglo con los detalles de la evaluación personal
                $detalleEvaluacionPersonal = [
                    'id_evaluacion_personal'       => $request->idEvaluacionPersonal,
                    'id_cat_rendimiento'           => $value["id_cat_rendimiento"],
                    'id_rubrica_rendimiento'       => $value["id_rubrica_rendimiento"],
                    'usuario_detalle_eva_personal' => $request->user()->nick_usuario,
                    'ip_detalle_eva_personal'      => $request->ip(),
                ];

                // Condiciones de búsqueda para verificar si ya existe una respuesta para esta categoría
                $conditions = [
                    'id_evaluacion_personal' => $request->idEvaluacionPersonal,
                    'id_cat_rendimiento'     => $value["id_cat_rendimiento"],
                ];
                // Verificar si ya existe una respuesta para esta categoría
                $existingResponse = DetalleEvaluacionPersonal::where($conditions)->first();

                // Verificar si ya existe una respuesta para esta categoría
                if ($existingResponse) {
                    // Si existe, añadir la fecha de modificación
                    $detalleEvaluacionPersonal['fecha_mod_detalle_eva_personal'] = Carbon::now();
                } else {
                    // Si no existe, añadir la fecha de creación
                    $detalleEvaluacionPersonal['fecha_reg_detalle_eva_personal'] = Carbon::now();
                }

                // Actualizar o insertar el registro
                DetalleEvaluacionPersonal::updateOrInsert($conditions, $detalleEvaluacionPersonal);
            }

            $collection = collect($request->responseRendimiento);
            $totalPuntajes = $collection->sum('puntaje_rubrica_rendimiento');

            EvaluacionPersonal::where("id_evaluacion_personal", $request->idEvaluacionPersonal)->update([
                'puntaje_evaluacion_personal'   => $totalPuntajes,
                'fecha_mod_evaluacion_personal' => Carbon::now(),
            ]);



            // Confirmar la transacción
            DB::commit();

            // Retornar la solicitud como confirmación (puedes ajustar esto según tus necesidades)
            return response()->json(['message' => 'Respuestas guardadas exitosamente'], 200);
        } catch (\Exception $e) {
            // En caso de error, revertir la transacción y manejar la excepción
            DB::rollBack();

            // Manejar el caso de que no se encuentre la evaluación personal o de rendimiento
            return response()->json(['error' => 'Evaluación no encontrada', "realError" => $e], 404);
        }
    }

    /**
     * Guardar o actualizar incidentes de evaluación.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function saveIncidentesEvaluacion(Request $request)
    {
        // Iniciar una transacción en la base de datos
        DB::beginTransaction();

        try {
            // Reglas de validación para los datos de entrada
            $rules = [
                'idCategoriaRendimiento'        => 'required',
                'resultadoIncidenteEvaluacion'  => 'required',
                'comentarioIncidenteEvaluacion' => 'required',
                'idEvaluacionPersonal'          => 'required',
            ];

            // Mensajes de error personalizados
            $messages = [
                'required' => 'El campo :attribute es obligatorio.',
            ];

            // Validar los datos de entrada
            $validator = Validator::make($request->dataIndiceEvaluacion, $rules, $messages);

            // Comprobar si la validación falla
            if ($validator->fails()) {
                // Enviar respuesta con mensajes de error
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            // Datos a ingresar o actualizar
            $data = [
                'id_cat_rendimiento'              => $request->dataIndiceEvaluacion['idCategoriaRendimiento'],
                'resultado_incidente_evaluacion'  => $request->dataIndiceEvaluacion['resultadoIncidenteEvaluacion'],
                'comentario_incidente_evaluacion' => $request->dataIndiceEvaluacion['comentarioIncidenteEvaluacion'],
                'estado_incidente_evaluacion'     => 1,
                'usuario_incidente_evaluacion'    => $request->user()->nick_usuario,
                'ip_incidente_evaluacion'         => $request->ip(),
                'id_evaluacion_personal'          => $request->dataIndiceEvaluacion['idEvaluacionPersonal'],
            ];

            // Condiciones de búsqueda
            $conditions = [
                'id_incidente_evaluacion' => (int) $request->dataIndiceEvaluacion['idIncidenteEvaluacion'],
                'id_evaluacion_personal'  => $request->dataIndiceEvaluacion['idEvaluacionPersonal'],
            ];

            // Verificar si ya existe un registro
            $existingResponse = IncidenteEvaluacion::where($conditions)->first();

            // Determinar si es una actualización o inserción
            if ($existingResponse) {
                // Si existe, añadir la fecha de modificación
                $data['fecha_mod_incidente_evaluacion'] = Carbon::now();

                if ($request->dataIndiceEvaluacion["isDeleted"]) {
                    // Actualizar el estado del incidente si es borrado
                    IncidenteEvaluacion::where($conditions)->update(['estado_incidente_evaluacion' => 0]);
                } else {
                    // Actualizar el registro existente
                    IncidenteEvaluacion::where($conditions)->update($data);
                }
            } else {
                if (!$request->dataIndiceEvaluacion["isDeleted"]) {
                    // Si no existe y no está marcado como eliminado, añadir la fecha de creación
                    $data['fecha_reg_incidente_evaluacion'] = Carbon::now();
                    // Insertar un nuevo registro y obtener el ID
                    $insertedId = IncidenteEvaluacion::insertGetId($data);
                }
            }

            // Confirmar la transacción
            DB::commit();

            // Respuesta exitosa con el ID del registro insertado (si es una inserción)
            return response()->json(['message' => 'Respuestas guardadas exitosamente', 'inserted_id' => $insertedId ?? null], 200);
        } catch (\Exception $e) {
            // En caso de error, revertir la transacción
            DB::rollBack();

            // Responder con un mensaje de error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtener la evaluación de un empleado por su ID.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse|null
     */
    function getEvaluationById(Request $request)
    {
        try {
            // Seleccionar todos los campos de la tabla 'empleados' y cargar relaciones necesarias
            $evaluation = Empleado::select('*')
                ->with([
                    "persona",  // Relación con la información personal del empleado
                    "evaluaciones_personal.detalle_evaluaciones_personal"  // Relación con los detalles de la evaluación personal
                ])
                ->whereHas("evaluaciones_personal")  // Filtrar empleados que tengan evaluaciones personales asociadas
                ->where("id_empleado", $request->id_empleado)  // Filtrar por el ID del empleado proporcionado
                ->first();  // Obtener el primer resultado

            // Verificar si se encontró una evaluación para el empleado
            if ($evaluation) {
                // Devolver la evaluación con sus relaciones cargadas
                return $evaluation;
            } else {
                // Devolver una respuesta JSON indicando que no se encontraron evaluaciones
                return response()->json(['message' => 'No se encontraron evaluaciones para el empleado.'], 404);
            }
        } catch (\Exception $e) {
            // Manejar cualquier excepción lanzada durante la ejecución
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
