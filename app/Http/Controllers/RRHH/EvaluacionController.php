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

class EvaluacionController extends Controller {
    // Obtenemos la evaluacion
    function getEvaluaciones(Request $request) {
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
        $query = Empleado::select('*')
            ->with([
                "persona",
                "plazas_asignadas.dependencia",
                "plazas_asignadas.detalle_plaza.plaza",
                "evaluaciones_personal.incidentes_evaluacion",
                "evaluaciones_personal.detalle_evaluaciones_personal",
                "evaluaciones_personal" => function ($query) {
                    return $query->orderBy("fecha_reg_evaluacion_personal", "desc");
                },
            ])->whereHas("evaluaciones_personal")->orderBy($columns[$column], $dir);

        if($data) {
            $query->where('id_empleado', 'like', '%'.$data["id_empleado"].'%')
                ->where('codigo_empleado', 'like', '%'.$data['codigo_empleado'].'%');

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
    function searchEmployeesForNewEvaluationRequest(Request $request) {

        $query = Persona::query();

        if(!empty($request->nombre)) {
            $query->with([
                'empleado' => function ($query) {
                    $query->where('estado_empleado', 1); // Traemos los empleados que esten activos
                },
                'empleado.evaluaciones_personal',
            ]);
            $query->orWhere(function ($query) use ($request) {
                $query->whereRaw("MATCH ( pnombre_persona,
                 snombre_persona,
                  tnombre_persona, 
                  papellido_persona,
                   sapellido_persona,
                    tapellido_persona )
                     AGAINST ( '".$request->nombre."')");
            });
            /* $query->whereHas("empleado") // Las personas que esten en la tabla de empleados
                ->doesntHave("empleado.evaluaciones_personal"); // Los empleados que no tengan evaluaciones */
        }
        $results = $query->get();

        $formattedResults = $results->map(function ($item) {
            return [
                'value'           => $item->id_persona,
                'label'           => $item->pnombre_persona.' '.($item->snombre_persona ?? '').' '.($item->tnombre_persona ?? '').' '.($item->papellido_persona ?? '').' '.($item->sapellido_persona ?? '').' '.($item->tapellido_persona ?? ''),
                'allDataPersonas' => $item
                /* 'disabled' => true */
            ];
        });

        return response()->json($formattedResults);
    }

    function getPlazaAsignadaByUserAndDependencia(Request $request) {
        $idDependencia = $request->dependenciaId;
        $listDependencia = [];

        // Obtener el conteo de registros con dep_id_dependencia igual a $idDependencia
        $countDependencias = Dependencia::where("dep_id_dependencia", $idDependencia)->count();

        // Obtener las subdependencias si el conteo es mayor que 0
        if($countDependencias > 0) {
            $listDependencia = Dependencia::where("dep_id_dependencia", $idDependencia)
                ->pluck('id_dependencia')
                ->toArray();
        }

        // Obtener las PlazaAsignada utilizando una sola consulta
        $plazasAsignadas = PlazaAsignada::with([
            'detalle_plaza.plaza.tipo_plaza.evaluaciones_rendimientos'
        ])->where('id_empleado', $request->employeeId)
            ->where(function ($query) use ($idDependencia, $listDependencia) {
                $query->where('id_dependencia', $idDependencia);

                if(!empty($listDependencia)) {
                    $query->orWhereIn('id_dependencia', $listDependencia);
                }
            })->doesntHave("plaza_evaluada")
            ->get();

        $evaluacion = PlazaAsignada::with([
            'detalle_plaza.plaza.tipo_plaza.evaluaciones_rendimientos'
        ])->where('id_empleado', $request->employeeId)
            ->where(function ($query) use ($idDependencia, $listDependencia) {
                $query->where('id_dependencia', $idDependencia);

                if(!empty($listDependencia)) {
                    $query->orWhereIn('id_dependencia', $listDependencia);
                }
            })->doesntHave("plaza_evaluada")
            ->get();

        // Obtener evaluaciones de rendimiento sin repetir
        $evaluacionRendimiento = $evaluacion->flatMap(function ($item) {
            return $item->detalle_plaza->plaza->tipo_plaza->evaluaciones_rendimientos;
        })->unique('id_tipo_plaza');

        $cantidadEvaluacionesRendimiento = $evaluacionRendimiento->count();


        return [
            "plazasAsignadas"                 => $plazasAsignadas,
            "evaluacionRendimiento"           => $evaluacionRendimiento,
            'cantidadEvaluacionesRendimiento' => $cantidadEvaluacionesRendimiento,
        ];
    }

    function createNewEvaluation(Request $request) {

        try {
            DB::beginTransaction();

            if($request->fechaInicioFechafin != "null") {
                $fechas = explode(" a ", $request->fechaInicioFechafin);
                $fecha_inicio = $fechas[0];
                $fecha_fin = isset($fechas[1]) ? $fechas[1] : $fechas[0];

                // Convertir fechas a objetos Carbon para comparaciones
                $fechaInicioObj = Carbon::parse($fecha_inicio);
                $fechaFinObj = Carbon::parse($fecha_fin);

                // Definir fechas límite para determinar el periodo
                $limitePrimerPeriodo = Carbon::parse('2023-06-30');
                $limiteSegundoPeriodo = Carbon::parse('2023-12-31');

                // Determinar el periodo en base a las fechas
                if($fechaInicioObj <= $limitePrimerPeriodo && $fechaFinObj <= $limitePrimerPeriodo) {
                    $id_periodo_evaluacion = 1; // Primer periodo
                } elseif($fechaInicioObj >= $limiteSegundoPeriodo && $fechaFinObj >= $limiteSegundoPeriodo) {
                    $id_periodo_evaluacion = 2; // Segundo periodo
                } else {
                    // Manejar el caso en que las fechas no se ajusten a ninguno de los periodos
                    // Puedes ajustar esto según tus requisitos específicos
                    $id_periodo_evaluacion = null;
                }
            } else {
                $fecha_inicio = null;
                $fecha_fin = null;
                $id_periodo_evaluacion = null;
            }


            $evaluacionData = [
                'id_evaluacion_rendimiento'        => $request->idEvaluacionRendimiento,
                'id_periodo_evaluacion'            => $id_periodo_evaluacion,
                'id_empleado'                      => $request->idEmpleado,
                'id_dependencia'                   => $request->idDependencia,
                'id_tipo_evaluacion_personal'      => $request->idTipoEvaluacion,
                'fecha_evaluacion_personal'        => Carbon::now(),
                'puntaje_evaluacion_personal'      => 0, // Inicialmente
                'fecha_inicio_evaluacion_personal' => $fecha_inicio,
                'fecha_fin_evaluacion_personal'    => $fecha_fin,
                'observacion_incidente_personal'   => '', // Sin observaciones al crear
                'fecha_reg_evaluacion_personal'    => Carbon::now(),
                'usuario_evaluacion_personal'      => $request->user()->nick_usuario,
                'ip_evaluacion_personal'           => $request->ip(),
            ];
            $evaluacionId = EvaluacionPersonal::insertGetId($evaluacionData);


            $idDependencia = $request->idDependencia;
            $listDependencia = [];

            // Obtener el conteo de registros con dep_id_dependencia igual a $idDependencia
            $countDependencias = Dependencia::where("dep_id_dependencia", $idDependencia)->count();

            // Obtener las subdependencias si el conteo es mayor que 0
            if($countDependencias > 0) {
                $listDependencia = Dependencia::where("dep_id_dependencia", $idDependencia)
                    ->pluck('id_dependencia')
                    ->toArray();
            }

            // Obtener las PlazaAsignada utilizando una sola consulta
            $plazasAsignadas = PlazaAsignada::where('id_empleado', $request->idEmpleado)
                ->where(function ($query) use ($idDependencia, $listDependencia) {
                    $query->where('id_dependencia', $idDependencia);

                    if(!empty($listDependencia)) {
                        $query->orWhereIn('id_dependencia', $listDependencia);
                    }
                })
                ->get();

            $plazas = [];

            foreach( $plazasAsignadas as $key => $value ) {
                $plazaEvaluada = [
                    'id_plaza_asignada'        => $value->id_plaza_asignada,
                    'id_evaluacion_personal'   => $evaluacionId,
                    'estado_plaza_evaluada'    => 1,
                    'fecha_reg_plaza_evaluada' => Carbon::now(),
                    'usuario_plaza_evaluada'   => $request->user()->nick_usuario,
                    'ip_plaza_evaluada'        => $request->ip(),

                ];
                $plazas[] = $plazaEvaluada;

            }
            $response = PlazaEvaluada::insert($plazas);

            DB::commit();
            return $response;
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }


    /* function createNewEvaluation(EvaluacionRequest $request)
    {

        try {
            DB::beginTransaction();

            // Verificar si el periodo ya existe
            $periodoExiste = EvaluacionPersonal::where('periodo_evaluacion_personal', $request->periodo_evaluacion_personal)
                ->where('id_empleado', $request->id_empleado)
                ->exists();

            if ($periodoExiste) {
                return response()->json('El periodo de evaluación ya existe para este empleado', 400);
            }

            $evaluacionData = [
                'id_empleado'                   => $request->id_empleado,
                'id_evaluacion_rendimiento'     => $request->id_evaluacion_rendimiento,
                'fecha_evaluacion_personal'     => Carbon::now(),
                'periodo_evaluacion_personal'   => $request->periodo_evaluacion_personal,
                'puntaje_evaluacion_personal'   => 0,
                'fecha_reg_evaluacion_personal' => Carbon::now(),
            ];
            $evaluacionId = EvaluacionPersonal::insertGetId($evaluacionData);
            DB::commit();

            return Empleado::with([
                "persona",
                "evaluaciones_personal" => function ($query) {
                    return $query->orderBy("fecha_reg_evaluacion_personal", "desc");
                },
                "evaluaciones_personal.detalle_evaluaciones_personal",
                "evaluaciones_personal.incidentes_evaluacion",
                "plazas_asignadas.detalle_plaza.plaza",
                "plazas_asignadas.dependencia"
            ])->whereHas("evaluaciones_personal")->find($request->id_empleado);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    } */

    // Traer la version de evaluacion rendimiento para saber cual es la que tomo
    function getPersonalPerformanceEvaluationVersion(Request $request) {
        $query = EvaluacionRendimiento::select("*")->with([
            "categorias_rendimiento.rubricas_rendimiento"
        ])->where("id_evaluacion_rendimiento", $request->id_evaluacion_rendimiento)->first();
        return $query;
    }

    // Guardamos la respuesta que se ha seleccionado en la evaluacion

    function saveResponseInEvaluation(EvaluacionRespuestasRequest $request) {
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

                foreach( $request->data as $value ) {
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

                    if($existingResponse) {
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
                foreach( $request->dataIncidenteEvaluacion as $key => $value ) {

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

                    if($existingResponse) {
                        // Si existe, añadir la fecha de modificación
                        $data['fecha_mod_incidente_evaluacion'] = Carbon::now();
                    } else {
                        // Si no existe, añadir la fecha de creación
                        $data['fecha_reg_incidente_evaluacion'] = Carbon::now();
                    }

                    if($value["isDelete"] && !empty($value["id_cat_rendimiento"])) {
                        IncidenteEvaluacion::destroy($value["id_incidente_evaluacion"]);
                    } else if(!$value["isDelete"]) {
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


    function getEvaluationById(Request $request) {
        return Empleado::select('*')
            ->with([
                "persona",
                "evaluaciones_personal.detalle_evaluaciones_personal"
            ])->whereHas("evaluaciones_personal")
            ->where("id_empleado", $request->id_empleado)->first();
    }
}
