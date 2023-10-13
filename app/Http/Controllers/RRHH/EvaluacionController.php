<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Http\Requests\RRHH\EvaluacionRequest;
use App\Http\Requests\RRHH\EvaluacionRespuestasRequest;
use App\Models\CategoriaRendimiento;
use App\Models\DetalleEvaluacionPersonal;
use App\Models\Empleado;
use App\Models\EvaluacionPersonal;
use App\Models\EvaluacionRendimiento;
use App\Models\IncidenteEvaluacion;
use App\Models\Persona;
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
            'id_empleado',
            'id_persona',
            'id_tipo_pension',
            'id_banco',
            'id_titulo_profesional',
            'codigo_empleado',
            'nup_empleado',
            'isss_empleado',
            'cuenta_banco_empleado',
            'fecha_contratacion_empleado',
            'email_institucional_empleado',
            'email_alternativo_empleado',
            'estado_empleado',
            'fecha_reg_empleado',
            'fecha_mod_empleado',
            'usuario_empleado',
            'ip_empleado'
        ];

        $length = $request->input('length');
        $column = $request->input('column');
        $dir = $request->input('dir');
        $data = $request->input('search');

        // Construir la consulta base con las relaciones
        $query = Empleado::select('*')
            ->with([
                "persona",
                "evaluaciones_personal" => function ($query) {
                    return $query->orderBy("fecha_reg_evaluacion_personal", "desc");
                },
                "evaluaciones_personal.detalle_evaluaciones_personal",
                "evaluaciones_personal.incidentes_evaluacion",
                "plazas_asignadas.detalle_plaza.plaza",
                "plazas_asignadas.dependencia"
            ])->whereHas("evaluaciones_personal")->orderBy($columns[$column], $dir);

        if ($data) {
            $query->where('id_empleado', 'like', '%' . $data["id_empleado"] . '%')
                ->where('codigo_empleado', 'like', '%' . $data['codigo_empleado'] . '%')
                ->where('email_institucional_empleado', 'like', '%' . $data['email_institucional_empleado'] . '%')
                ->whereHas(
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
                );
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
        $searchQuery = $request->input('data');
        $query = Persona::select("*")->with([
            'empleado' => function ($query) {
                $query->where('estado_empleado', 1); // Traemos los empleados que esten activos
            },
            'empleado.evaluaciones_personal',
        ]);
        if (!empty($searchQuery)) { // El filtrado
            $query->where(function (Builder $query) use ($searchQuery) {
                $query->whereRaw("MATCH (pnombre_persona, snombre_persona, tnombre_persona, papellido_persona, sapellido_persona, tapellido_persona) AGAINST (?)", [$searchQuery]);
            });
        }
        $query->whereHas("empleado") // Las personas que esten en la tabla de empleados
            ->doesntHave("empleado.evaluaciones_personal"); // Los empleados que no tengan evaluaciones
        $result = $query->get();
        return $result;
    }

    function createNewEvaluation(EvaluacionRequest $request)
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
    }

    // Traer la version de evaluacion rendimiento para saber cual es la que tomo
    function getPersonalPerformanceEvaluationVersion(Request $request)
    {
        $query = EvaluacionRendimiento::select("*")->with([
            "categorias_rendimiento.rubricas_rendimiento"
        ])->where("id_evaluacion_rendimiento", $request->id_evaluacion_rendimiento)->first();
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
                    'observacion_incidente_personal'          => $request->observacion_incidente_personal
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
                        'fecha_reg_incidente_evaluacion'  =>  $value['fecha_reg_incidente_evaluacion'],
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
