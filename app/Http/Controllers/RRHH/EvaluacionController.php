<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Http\Requests\RRHH\EvaluacionRequest;
use App\Http\Requests\RRHH\EvaluacionRespuestasRequest;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

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

        // Construir la consulta base con las relaciones
        $query = Empleado::
            with([
                "persona",
                "plazas_asignadas.centro_atencion.dependencias",
                "plazas_asignadas.detalle_plaza.plaza",
                "evaluaciones_personal.incidentes_evaluacion",
                "evaluaciones_personal.detalle_evaluaciones_personal.categoria_rendimiento.evaluacion_rendimiento.tablas_rendimiento",
                "evaluaciones_personal.detalle_evaluaciones_personal.rubrica_rendimiento",
                "evaluaciones_personal.plaza_evaluada.plaza_asignada.detalle_plaza.plaza",
                "evaluaciones_personal.evaluacion_rendimiento",
                "evaluaciones_personal.tipo_evaluacion_personal",
                "evaluaciones_personal.periodo_evaluacion",
                "evaluaciones_personal" => function ($query) {
                    return $query->orderBy("fecha_reg_evaluacion_personal", "asc");
                },
            ])->whereHas("evaluaciones_personal")->orderBy($columns[$column], $dir);

        if ($data) {
            $query->where('id_empleado', 'like', '%' . $data["id_empleado"] . '%')
                ->where('codigo_empleado', 'like', '%' . $data['codigo_empleado'] . '%');

            /*  if (isset($data['email_institucional_empleado'])) {
                $query->where('email_institucional_empleado', 'like', '%' . $data['email_institucional_empleado'] . '%');
            }
            $query->whereHas(
                'persona',
                function ($query) use ($data) {
                    $searchNombres = $data["collecNombre"];
                    $query->where(function ($query) use ($searchNombres) {
                        $query->where('pnombre_persona', 'like', '%' . $searchNombres . '%')
                            ->orWhere('snombre_persona', 'like', '%' . $searchNombres . '%')
                            ->orWhere('tapellido_persona', 'like', '%' . $searchNombres . '%');
                    });

                    $searchApellidos = $data["collecApellido"];
                    $query->where(function ($query) use ($searchApellidos) {
                        $query->where('papellido_persona', 'like', '%' . $searchApellidos . '%')
                            ->orWhere('sapellido_persona', 'like', '%' . $searchApellidos . '%')
                            ->orWhere('tapellido_persona', 'like', '%' . $searchApellidos . '%');
                    });
                }
            ); */
        }
        $acuerdos = $query->paginate($length)->onEachSide(1);
        return [
            'data' => $acuerdos,
            'draw' => $request->input('draw'),
        ];
    }
    // Metodo de busqueda de usuarios
    function searchEmployeesForNewEvaluationRequest(Request $request)
    {

        $query = Persona::query();

        if (!empty($request->nombre)) {
            $query->with([
                'empleado' => function ($query) {
                    $query->where('estado_empleado', 1); // Traemos los empleados que esten activos
                },
                'empleado.evaluaciones_personal',
                'empleado.plazas_asignadas'
            ]);
            $query->orWhere(function ($query) use ($request) {
                $query->whereRaw("MATCH ( pnombre_persona,
                 snombre_persona,
                  tnombre_persona, 
                  papellido_persona,
                   sapellido_persona,
                    tapellido_persona )
                     AGAINST ( '" . $request->nombre . "')");
            });
            $query->whereHas("empleado") // Las personas que esten en la tabla de empleados
                ->doesntHave("empleado.evaluaciones_personal"); // Los empleados que no tengan evaluaciones
        }
        $results = $query->get();

        $formattedResults = $results->map(function ($item) {
            return [
                'value'           => $item->id_persona,
                'label'           => $item->pnombre_persona . ' ' . ($item->snombre_persona ?? '') . ' ' . ($item->tnombre_persona ?? '') . ' ' . ($item->papellido_persona ?? '') . ' ' . ($item->sapellido_persona ?? '') . ' ' . ($item->tapellido_persona ?? ''),
                'allDataPersonas' => $item
                /* 'disabled' => true */
            ];
        });

        return response()->json($formattedResults);
    }


    function getPlazaAsignadaByUserAndDependencia(Request $request)
    {
        $employeeId = $request->employeeId;
        $idCentroAtencion = $request->centroAtencionId;
        $tipoEvaluacionId = $request->idTipoEvaluacion;

        $mensaje_debug = [];

        // Verificar si se proporcionaron fechas
        if (!empty($request->fechaInicioFechafin)) {
            [$fecha_inicio, $fecha_fin] = explode(" a ", $request->fechaInicioFechafin) + [null, null];

            try {
                // Convertir fechas a objetos Carbon para comparaciones
                $fechaInicioObj = Carbon::parse($fecha_inicio);
                $fechaFinObj = Carbon::parse($fecha_fin);
            } catch (\Exception $e) {
                return response()->json([
                    "error"   => "Error al parsear las fechas.",
                    "mensaje" => $e->getMessage(),
                ], 422);
            }
            // Obtener el año de las fechas
            $anio = $fechaInicioObj->year;

            if ($tipoEvaluacionId == 1) {
                // Caso en que idTipoEvaluacion es igual a 1
                $limitePrimerPeriodo = Carbon::parse("{$anio}-06-30");
                $limiteSegundoPeriodo = Carbon::parse("{$anio}-12-31");
            } else {
                // Caso en que idTipoEvaluacion no es igual a 1
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
                        "error" => "Las fechas no .",
                        "mensaje_periodo" => 'Las evaluaciones de desempeño deben estar dentro del primer periodo (del 1 de enero al 30 de junio) o del segundo periodo (del 1 de julio al 31 de diciembre).',
                    ], 422);
                } else {

                    return response()->json([
                        "error" => "Las fechas no .",
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
                "error" => "No se proporcionaron fechas.",
                "mensaje_periodo" => 'No se proporcionaron fechas',
            ], 422);
        }

        // Obtener evaluaciones
        $objectEvaluation = EvaluacionPersonal::where("id_periodo_evaluacion", optional($mensaje_debug)['periodo'] == 'Primer periodo' ? 1 : 2)
            ->where("id_tipo_evaluacion_personal", $tipoEvaluacionId)
            ->whereYear("fecha_inicio_evaluacion_personal", optional($fechaInicioObj)->year)
            ->where("id_empleado", $employeeId)
            ->get();


        $objectEvaluationByPrueba = EvaluacionPersonal::with(["plaza_evaluada.plaza_asignada"])
            ->where("id_tipo_evaluacion_personal", $tipoEvaluacionId)
            ->where("id_empleado", $employeeId)
            ->get();

        // Obtén una colección de todos los id_plaza_asignada
        $idPlazaAsignadaCollection = $objectEvaluationByPrueba->pluck('plaza_evaluada.*.plaza_asignada.id_plaza_asignada')->flatten();

        $conteoPorPlazaAsignada = $idPlazaAsignadaCollection->countBy();

        // Filtra las plazas asignadas que tienen un conteo de 2 o más
        $plazasConDosOMasEvaluaciones = $conteoPorPlazaAsignada->filter(function ($conteo) {
            return $conteo >= 2;
        });

        // Obtiene las claves (id_plaza_asignada) del arreglo filtrado
        $plazasDosOMasEvaluaciones = $plazasConDosOMasEvaluaciones->keys()->toArray();


        $plazasAsignadasPruebas = PlazaAsignada::with([
            'detalle_plaza.plaza.tipo_plaza.evaluaciones_rendimientos'
        ])->where('id_empleado', $employeeId)->whereNotIn("id_plaza_asignada", $plazasDosOMasEvaluaciones)
            ->where('id_centro_atencion', $idCentroAtencion)->get();


        if ($objectEvaluation->isEmpty()) {

            if ($tipoEvaluacionId == 1) {
                $plazasAsignadas = PlazaAsignada::with([
                    'detalle_plaza.plaza.tipo_plaza.evaluaciones_rendimientos'
                ])->where('id_empleado', $employeeId)->where('id_centro_atencion', $idCentroAtencion)->get();
            } else {
                $plazasAsignadas = PlazaAsignada::with([
                    'detalle_plaza.plaza.tipo_plaza.evaluaciones_rendimientos'
                ])->where('id_empleado', $employeeId)->whereNotIn("id_plaza_asignada", $plazasDosOMasEvaluaciones)
                    ->where('id_centro_atencion', $idCentroAtencion)->get();
            }
        } else {

            if ($tipoEvaluacionId == 1) {
                # code...
                $plazasAsignadas = PlazaAsignada::with([
                    'detalle_plaza.plaza.tipo_plaza.evaluaciones_rendimientos'
                ])->where('id_empleado', $employeeId)->where('id_centro_atencion', $idCentroAtencion)->doesntHave("plaza_evaluada")->get();
            } else {
                $plazasAsignadas = PlazaAsignada::with([
                    'detalle_plaza.plaza.tipo_plaza.evaluaciones_rendimientos'
                ])->where('id_empleado', $employeeId)->whereNotIn("id_plaza_asignada", $plazasDosOMasEvaluaciones)
                    ->where('id_centro_atencion', $idCentroAtencion)->get();
            }
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

        return [
            //Data que uso en el front
            "plazasAsignadas"                 => $plazasAsignadas,
            "evaluacionRendimiento"           => $evaluacionRendimiento,
            'cantidadEvaluacionesRendimiento' => $cantidadEvaluacionesRendimiento,
            "mensaje_debug" => $mensaje_debug
        ];
    }


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
                    return [
                        "error"   => "Error al parsear las fechas.",
                        "mensaje" => $e->getMessage(),
                    ];
                }

                // Definir fechas límite para determinar el periodo
                $limitePrimerPeriodo = Carbon::parse('2023-06-30');
                $limiteSegundoPeriodo = Carbon::parse('2023-12-31');

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
                } else {
                    // Manejar el caso en que las fechas no se ajusten a ninguno de los periodos
                    $id_periodo_evaluacion = 1;
                    $mensaje_periodo = 'Las fechas no corresponden a ningún periodo';
                }

                // Añadir mensajes de depuración para el periodo
                $mensaje_debug["periodo"] = $mensaje_periodo;
            } else {
                // Manejar el caso en que no se proporcionaron fechas
                $fecha_inicio = null;
                $fecha_fin = null;
                $id_periodo_evaluacion = 1;
                $mensaje_periodo = 'No se proporcionaron fechas';
            }

            $evaluacionPersonalArray = [
                'id_evaluacion_rendimiento'        => $request->idEvaluacionRendimiento,
                'id_periodo_evaluacion'            => $id_periodo_evaluacion,
                'id_empleado'                      => $request->idEmpleado,
                'id_dependencia'                   => $request->idCentroAtencion, //TODO: poner la dependencia y no el centro de atencion
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
            $evaluacionInsertedId = EvaluacionPersonal::insertGetId($evaluacionPersonalArray);

            $plazas = [];
            foreach ($request->plazasAsignadas as $key => $value) {
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
            $response = PlazaEvaluada::insert($plazas);

            $query = Empleado::with([
                "persona",
                "plazas_asignadas.centro_atencion.dependencias",
                "plazas_asignadas.detalle_plaza.plaza",
                "evaluaciones_personal.incidentes_evaluacion",
                "evaluaciones_personal.detalle_evaluaciones_personal.categoria_rendimiento.evaluacion_rendimiento.tablas_rendimiento",
                "evaluaciones_personal.detalle_evaluaciones_personal.rubrica_rendimiento",
                "evaluaciones_personal.plaza_evaluada.plaza_asignada.detalle_plaza.plaza",
                "evaluaciones_personal.evaluacion_rendimiento",
                "evaluaciones_personal.tipo_evaluacion_personal",
                "evaluaciones_personal.periodo_evaluacion",
                "evaluaciones_personal" => function ($query) {
                    return $query->orderBy("fecha_reg_evaluacion_personal", "asc");
                },
            ])->whereHas("evaluaciones_personal")->find($request->idEmpleado);

            DB::commit();


            // Devolver el resultado
            return response()->json([
                "id_periodo_evaluacion" => $id_periodo_evaluacion,
                "mensaje_periodo"       => $mensaje_periodo,
                "mensaje_debug"         => $mensaje_debug,
                "evaluacionInsertedId"  => $evaluacionInsertedId,
                "response"              => $query
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                "error"   => "Error en la transacción.",
                "mensaje" => is_array($th->getMessage()) ? json_encode($th->getMessage()) : $th->getMessage()
            ], 500);
        }
    }

    // Traer la version de evaluacion rendimiento para saber cual es la que tomo
    function getPersonalPerformanceEvaluationVersion(Request $request)
    {
        $query = EvaluacionRendimiento::select("*")->with([
            "categorias_rendimiento.rubricas_rendimiento"
        ])->where("id_evaluacion_rendimiento", $request->idEvaluacionRendimiento)->first();
        return $query;
    }

    // Guardamos la respuesta que se ha seleccionado en la evaluacion

    function saveResponseInEvaluation(EvaluacionRespuestasRequest $request)
    {
        try {
            // Buscar la evaluación personal
            $evaluationPersonal = EvaluacionPersonal::findOrFail($request->id_evaluacion_personal);

            // Buscar la evaluación de rendimiento
            $evaluationPerformance = EvaluacionRendimiento::findOrFail($evaluationPersonal->id_evaluacion_rendimiento);

            // Obtener las categorías de rendimiento asociadas
            $performanceCategories = CategoriaRendimiento::where("id_evaluacion_rendimiento", $evaluationPerformance->id_evaluacion_rendimiento)->get();

            DB::beginTransaction();

            try {

                EvaluacionPersonal::where("id_evaluacion_personal", $request->id_evaluacion_personal)->update([
                    'observacion_incidente_personal' => $request->observacion_incidente_personal
                ]);

                // Iterar sobre las respuestas

                foreach ($request->data as $value) {
                    $data = [
                        'id_evaluacion_personal'       => $request->id_evaluacion_personal,
                        'id_cat_rendimiento'           => $value['id_cat_rendimiento'],
                        'id_rubrica_rendimiento'       => $value['id_rubrica_rendimiento'],
                        'usuario_detalle_eva_personal' => $request->user()->nick_usuario,
                        'ip_detalle_eva_personal'      => $request->ip(),
                    ];

                    // Condiciones de búsqueda
                    $conditions = [
                        'id_evaluacion_personal' => $request->id_evaluacion_personal,
                        'id_cat_rendimiento'     => $value['id_cat_rendimiento'],
                    ];

                    // Verificar si ya existe una respuesta para esta categoría
                    $existingResponse = DetalleEvaluacionPersonal::where($conditions)->first();

                    if ($existingResponse) {
                        // Si existe, añadir la fecha de modificación
                        $data['fecha_mod_detalle_eva_personal'] = Carbon::now();
                    } else {
                        // Si no existe, añadir la fecha de creación
                        $data['fecha_reg_detalle_eva_personal'] = Carbon::now();
                    }

                    // Actualizar o insertar el registro
                    DetalleEvaluacionPersonal::updateOrInsert($conditions, $data);
                }

                EvaluacionPersonal::where("id_evaluacion_personal", $request->id_evaluacion_personal)->update([
                    'puntaje_evaluacion_personal'   => $request->puntaje_evaluacion_personal,
                    'fecha_mod_evaluacion_personal' => Carbon::now(),
                ]);


                $data1 = [];
                //INSERTANDO EN LA TABLA DE INCIDENTE EVALUACION
                foreach ($request->dataIncidenteEvaluacion as $key => $value) {

                    $data = [
                        'id_evaluacion_personal'          => $request->id_evaluacion_personal,
                        'id_cat_rendimiento'              => $value['id_cat_rendimiento'],
                        'resultado_incidente_evaluacion'  => $value['resultado_incidente_evaluacion'],
                        'comentario_incidente_evaluacion' => $value['comentario_incidente_evaluacion'],
                        'estado_incidente_evaluacion'     => 1,
                        'fecha_reg_incidente_evaluacion'  => $value['fecha_reg_incidente_evaluacion'],
                        'usuario_incidente_evaluacion'    => $request->user()->nick_usuario,
                        'ip_incidente_evaluacion'         => $request->ip(),
                    ];


                    // Condiciones de búsqueda
                    $conditions = [
                        'id_incidente_evaluacion' => $value['id_incidente_evaluacion'],
                        'id_evaluacion_personal'  => $request->id_evaluacion_personal,
                    ];

                    $existingResponse = IncidenteEvaluacion::where($conditions)->first();

                    if ($existingResponse) {
                        // Si existe, añadir la fecha de modificación
                        $data['fecha_mod_incidente_evaluacion'] = Carbon::now();
                    } else {
                        // Si no existe, añadir la fecha de creación
                        $data['fecha_reg_incidente_evaluacion'] = Carbon::now();
                    }

                    if ($value["isDelete"] && !empty($value["id_cat_rendimiento"])) {
                        IncidenteEvaluacion::destroy($value["id_incidente_evaluacion"]);
                    } else if (!$value["isDelete"]) {
                        IncidenteEvaluacion::updateOrInsert($conditions, $data);
                    }
                }

                // Commit de la transacción
                DB::commit();
                // Respuesta exitosa
                return response()->json(['message' => 'Respuestas guardadas exitosamente'], 200);
            } catch (\Exception $e) {
                // Si ocurre un error, hacer rollback de la transacción
                DB::rollback();
                return response()->json(['error' => $e], 500);
            }
        } catch (\Exception $e) {
            // Manejar el caso de que no se encuentre la evaluación personal o de rendimiento
            return response()->json(['error' => 'Evaluación no encontrada'], 404);
        }
    }


    function getEvaluationById(Request $request)
    {
        return Empleado::select('*')
            ->with([
                "persona",
                "evaluaciones_personal.detalle_evaluaciones_personal"
            ])->whereHas("evaluaciones_personal")
            ->where("id_empleado", $request->id_empleado)->first();
    }
}
