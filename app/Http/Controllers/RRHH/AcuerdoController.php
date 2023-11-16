<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\AcuerdoLaboral;
use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcuerdoController extends Controller
{
    //
    function getAcuerdos(Request $request)
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
        $dir = $request->input('dir');
        $data = $request->input('search');
        $length = $request->input('length');
        $column = $request->input('column');

        // Construir la consulta base con las relaciones
        $query = Empleado::select('*')
            ->with([
                "persona",
                "acuerdo_laboral.tipo_acuerdo_laboral",
                "acuerdo_laboral" => function ($query) {
                    $query->where('estado_acuerdo_laboral', 1);
                },
            ])->whereHas("acuerdo_laboral", function ($query) {
                $query->where("estado_acuerdo_laboral", 1);
            })->where("estado_empleado", 1)->orderBy($columns[$column], $dir);

        if ($data) {
            if (isset($data['id_empleado'])) {
                $query->where('id_empleado', 'like', '%' . $data["id_empleado"] . '%');
            }

            if (isset($data['dui_persona'])) {
                $dui = $data["dui_persona"];
                $query->whereHas('persona', function ($query) use ($dui) {
                    $query->where('dui_persona', 'like', '%' . $dui . '%');
                });
            }

            if (isset($data['codigo_empleado'])) {
                $query->where('codigo_empleado', 'like', '%' . $data["codigo_empleado"] . '%');
            }


            if (isset($data['collecNombre'])) {
                $searchNombres = $data["collecNombre"];

                $query->whereHas('persona', function ($query) use ($searchNombres,) {
                    $query->where(function ($query) use ($searchNombres,) {
                        $query->whereRaw("MATCH ( pnombre_persona, snombre_persona, tnombre_persona ) AGAINST ( '" . $searchNombres . "')");
                    });
                });
            }

            if (isset($data['collecApellido'])) {
                $searchApellidos = $data["collecApellido"];

                $query->whereHas('persona', function ($query) use ($searchApellidos) {
                    $query->where(function ($query) use ($searchApellidos) {
                        $query->whereRaw("MATCH ( papellido_persona, sapellido_persona, tapellido_persona ) AGAINST ( '" . $searchApellidos . "')");
                    });
                });
            }
        }
        $acuerdos = $query->paginate($length)->onEachSide(1);
        $tipo_acuerdo_laboral = DB::table('tipo_acuerdo_laboral')->select('id_tipo_acuerdo_laboral as value', 'nombre_tipo_acuerdo_laboral  as label', 'color_tipo_acuerdo_laboral  as color')->get("");


        return [
            'data'          => $acuerdos,
            'dataForSelect' => [
                'tipo_acuerdo_laboral' => $tipo_acuerdo_laboral
            ],
            'draw'          => $request->input('draw'),
        ];
    }

    function searchEmployeByNameOrId(Request $request)
    {
        $query = Empleado::query();

        if (!empty($request->nombre)) {

            $query
                ->join('persona', 'persona.id_persona', '=', 'empleado.id_persona')
                ->join('profesion', 'profesion.id_profesion', '=', 'persona.id_profesion')
                ->join('nivel_educativo', 'nivel_educativo.id_nivel_educativo', '=', 'persona.id_nivel_educativo')
                ->join('genero', 'genero.id_genero', '=', 'persona.id_genero')
                ->join('estado_civil', 'estado_civil.id_estado_civil', '=', 'persona.id_estado_civil')
                ->join('municipio', 'municipio.id_municipio', '=', 'persona.id_municipio')
                ->join('departamento', 'departamento.id_departamento', '=', 'municipio.id_departamento')
                ->join('pais', 'pais.id_pais', '=', 'departamento.id_pais')
                ->where('estado_empleado', 1)
                ->where(function ($query) {
                    $query->doesntHave('acuerdo_laboral')
                        ->orWhereHas('acuerdo_laboral', function ($query) {
                            $query->where('estado_acuerdo_laboral', 0);
                        });
                })->where(function ($query) use ($request) {
                    $query->whereRaw("MATCH ( pnombre_persona,snombre_persona,tnombre_persona, papellido_persona,sapellido_persona,tapellido_persona ) AGAINST ( '" . $request->nombre . "')");
                });
        }

        $results = $query->get();

        $formattedResults = $results->map(function ($item) {
            $objectData = [
                'value' => $item->id_empleado,
                'label' => $item->pnombre_persona . ' ' . ($item->snombre_persona ?? '') . ' ' . ($item->tnombre_persona ?? '') . '' . $item->papellido_persona . ' ' . ($item->sapellido_persona ?? '') . ' ' . ($item->tapellido_persona ?? ''),
                'ALL'   => $item
            ];

            return $objectData;
        });

        return response()->json($formattedResults);
    }

    function addDeals(Request $request)
    {

        $deals = [];
        try {
            DB::beginTransaction();
            foreach ($request->deals as $key => $value) {
                if (!$value["isDelete"]) {
                    if ($value["fecha_inicio_fecha_fin_acuerdo_laboral"] !== "null to null") {
                        $fechas = explode(" to ", $value["fecha_inicio_fecha_fin_acuerdo_laboral"]);
                        $fecha_inicio = $fechas[0];
                        $fecha_fin = isset($fechas[1]) ? $fechas[1] : $fechas[0];
                    } else {
                        $fecha_inicio = null;
                        $fecha_fin = null;
                    }

                    $deal = [
                        'estado_acuerdo_laboral' => 1,
                        'id_empleado' => $request->id_empleado,
                        'ip_acuerdo_laboral' => $request->ip(),
                        'fecha_fin_acuerdo_laboral' => $fecha_fin,
                        'fecha_reg_acuerdo_laboral' => Carbon::now(),
                        'fecha_inicio_acuerdo_laboral' => $fecha_inicio,
                        'fecha_acuerdo_laboral' => $value["fecha_acuerdo_laboral"],
                        'usuario_acuerdo_laboral' => $request->user()->nick_usuario,
                        'oficio_acuerdo_laboral' => $value["oficio_acuerdo_laboral"],
                        'id_tipo_acuerdo_laboral' => $value["id_tipo_acuerdo_laboral"],
                        'comentario_acuerdo_laboral'  =>  $value["comentario_acuerdo_laboral"],
                    ];
                    $deals[] = $deal;
                }
            }

            AcuerdoLaboral::insert($deals);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }

    function updateDeals(Request $request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->deals as $value) {
                if (!$value["isDelete"]) {
                    $deal = [
                        'estado_acuerdo_laboral' => 1,
                        'id_empleado' => $request->id_empleado,
                        'ip_acuerdo_laboral' => $request->ip(),
                        'fecha_acuerdo_laboral' => $value["fecha_acuerdo_laboral"],
                        'usuario_acuerdo_laboral' => $request->user()->nick_usuario,
                        'oficio_acuerdo_laboral' => $value["oficio_acuerdo_laboral"],
                        'id_tipo_acuerdo_laboral' => $value["id_tipo_acuerdo_laboral"],
                        'comentario_acuerdo_laboral'  =>  $value["comentario_acuerdo_laboral"],
                    ];

                    if ($value["fecha_inicio_fecha_fin_acuerdo_laboral"] != "null to null") {
                        $fechas = explode(" to ", $value["fecha_inicio_fecha_fin_acuerdo_laboral"]);
                        $fecha_inicio = $fechas[0];
                        $fecha_fin = isset($fechas[1]) ? $fechas[1] : $fechas[0];
                        $deal['fecha_inicio_acuerdo_laboral'] = $fecha_inicio;
                        $deal['fecha_fin_acuerdo_laboral'] = $fecha_fin;
                    }

                    if ($value["id_acuerdo_laboral"] == '') {
                        $deal['fecha_reg_acuerdo_laboral'] = Carbon::now();
                        AcuerdoLaboral::create($deal);
                    } else {
                        $deal['fecha_mod_acuerdo_laboral'] = Carbon::now();
                        AcuerdoLaboral::where("id_acuerdo_laboral", $value["id_acuerdo_laboral"])->update($deal);
                    }
                } elseif ($value["id_acuerdo_laboral"] != '' && $value["isDelete"]) {
                    AcuerdoLaboral::where("id_acuerdo_laboral", $value["id_acuerdo_laboral"])->update(['estado_acuerdo_laboral' => 0]);
                }
            }


            DB::commit();
            /*  if ( $request->deals[0]["fecha_inicio_fecha_fin_acuerdo_laboral"] != "null to null") {
                $fechas = explode(" to ",  $request->deals[0]["fecha_inicio_fecha_fin_acuerdo_laboral"]);
                $fecha_inicio = $fechas[0];
                $fecha_fin = isset($fechas[1]) ? $fechas[1] : $fechas[0];
                $deal['fecha_inicio_acuerdo_laboral'] = $fecha_inicio;
                $deal['fecha_fin_acuerdo_laboral'] = $fecha_fin;

                $fechas = [$deal['fecha_inicio_acuerdo_laboral'],$deal['fecha_fin_acuerdo_laboral']];
                return $fechas;
            } */

            return true;
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }
}
