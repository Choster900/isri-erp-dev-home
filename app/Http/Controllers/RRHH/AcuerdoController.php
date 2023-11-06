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
                "acuerdo_laboral" => function ($query) {
                    $query->where('estado_acuerdo_laboral', 1);
                },
                "acuerdo_laboral.tipo_acuerdo_laboral",
                "persona"
            ])
            ->whereHas("acuerdo_laboral", function ($query) {
                $query->where("estado_acuerdo_laboral", 1);
            })->where("estado_empleado", 1)->orderBy($columns[$column], $dir);

        if ($data) {
            $query->where('id_empleado', 'like', '%' . $data["id_empleado"] . '%');
            $searchNombres = $data["collecNombre"];
            $searchApellidos = $data["collecApellido"];
            $duiPersona = $data["dui_persona"];
            $query->whereHas('persona', function ($query) use ($searchNombres, $searchApellidos,$duiPersona) {
                $query->where(function ($query) use ($searchNombres, $searchApellidos,$duiPersona) {
                    $query->whereRaw("MATCH ( pnombre_persona, snombre_persona, tnombre_persona ) AGAINST ( '" . $searchNombres . "')")
                        /* ->orWhere('dui_persona', 'like', '%' . $duiPersona . '%') */
                        ->orWhereRaw("MATCH ( papellido_persona, sapellido_persona, tapellido_persona ) AGAINST ( '" . $searchApellidos . "')");
                });
            });
        }
        $acuerdos = $query->paginate($length)->onEachSide(1);
        $tipo_acuerdo_laboral = DB::table('tipo_acuerdo_laboral')->select('id_tipo_acuerdo_laboral as value', 'nombre_tipo_acuerdo_laboral  as label','color_tipo_acuerdo_laboral  as color')->get("");


        return [
            'data'          => $acuerdos,
            'dataForSelect' => [
                'tipo_acuerdo_laboral' => $tipo_acuerdo_laboral
            ],
            'draw'          => $request->input('draw'),
        ];
    }


    public function searchEmployeByNameOrId(Request $request)
    {
        if ($request["by"] == 'name') { //FIXME: CHECK THIS QUERY
            return Empleado::select(
                'empleado.id_empleado as value',
                DB::raw("CONCAT_WS(' ', pnombre_persona, snombre_persona, tnombre_persona, papellido_persona, sapellido_persona, tapellido_persona) AS label"),
                DB::raw("CONCAT_WS('-', profesion.id_profesion_rnpn, profesion.nombre_profesion) AS nombre_profesion_rnpn"),
                'nivel_educativo.nombre_nivel_educativo',
                'genero.nombre_genero',
                'estado_civil.nombre_estado_civil',
                'dui_persona',
                'fecha_nac_persona',
                'fecha_reg_persona',
                'fecha_mod_persona',
                DB::raw("CONCAT(pais.id_pais,' - ',pais.nombre_pais, ' ',departamento.id_departamento,'-',departamento.nombre_departamento,' ',municipio.id_municipio,'-',municipio.nombre_municipio) AS localidad"),
            )
                ->join('persona', 'persona.id_persona', '=', 'empleado.id_persona')
                ->join('profesion', 'profesion.id_profesion', '=', 'persona.id_profesion')
                ->join('nivel_educativo', 'nivel_educativo.id_nivel_educativo', '=', 'persona.id_nivel_educativo')
                ->join('genero', 'genero.id_genero', '=', 'persona.id_genero')
                ->join('estado_civil', 'estado_civil.id_estado_civil', '=', 'persona.id_estado_civil')
                ->join('municipio', 'municipio.id_municipio', '=', 'persona.id_municipio')
                ->join('departamento', 'departamento.id_departamento', '=', 'municipio.id_departamento')
                ->join('pais', 'pais.id_pais', '=', 'departamento.id_pais')
                ->where(function ($query) use ($request) {
                    $query->where('empleado.codigo_empleado', 'like', '%' . $request['query'] . '%')
                        ->orWhere('persona.pnombre_persona', 'like', '%' . $request['query'] . '%')
                        ->orWhere('persona.snombre_persona', 'like', '%' . $request['query'] . '%')
                        ->orWhere('persona.tnombre_persona', 'like', '%' . $request['query'] . '%')
                        ->orWhere('persona.papellido_persona', 'like', '%' . $request['query'] . '%')
                        ->orWhere('persona.sapellido_persona', 'like', '%' . $request['query'] . '%')
                        ->orWhere('persona.tapellido_persona', 'like', '%' . $request['query'] . '%');
                })->where('estado_empleado', 1)->where(function ($query) {
                    $query->doesntHave('acuerdo_laboral')
                        ->orWhereHas('acuerdo_laboral', function ($query) {
                            $query->where('estado_acuerdo_laboral', 0);
                        });
                })
                ->get();
        } else {
            return Empleado::select(
                'empleado.id_empleado as value',
                DB::raw("CONCAT_WS(' ', pnombre_persona, snombre_persona, tnombre_persona, papellido_persona, sapellido_persona, tapellido_persona) AS label"),
                DB::raw("CONCAT_WS('-', profesion.id_profesion_rnpn, profesion.nombre_profesion) AS nombre_profesion_rnpn"),
                'nivel_educativo.nombre_nivel_educativo',
                'genero.nombre_genero',
                'estado_civil.nombre_estado_civil',
                'dui_persona',
                'fecha_nac_persona',
                'fecha_reg_persona',
                'fecha_mod_persona',
                DB::raw("CONCAT(pais.id_pais,' - ',pais.nombre_pais, ' ',departamento.id_departamento,'-',departamento.nombre_departamento,' ',municipio.id_municipio,'-',municipio.nombre_municipio) AS localidad"),
            )
                ->join('persona', 'persona.id_persona', '=', 'empleado.id_persona')
                ->join('profesion', 'profesion.id_profesion', '=', 'persona.id_profesion')
                ->join('nivel_educativo', 'nivel_educativo.id_nivel_educativo', '=', 'persona.id_nivel_educativo')
                ->join('genero', 'genero.id_genero', '=', 'persona.id_genero')
                ->join('estado_civil', 'estado_civil.id_estado_civil', '=', 'persona.id_estado_civil')
                ->join('municipio', 'municipio.id_municipio', '=', 'persona.id_municipio')
                ->join('departamento', 'departamento.id_departamento', '=', 'municipio.id_departamento')
                ->join('pais', 'pais.id_pais', '=', 'departamento.id_pais')
                ->where('estado_persona', 1)
                ->where("empleado.id_empleado", $request['query'])->get();
        }
    }

    function addDeals(Request $request)
    {

        $deals = [];
        try {
            DB::beginTransaction();
           /*  foreach ($request->deals as $key => $value) {
                if (!$value["isDelete"]) {
                    $deal = [
                        'id_tipo_acuerdo_laboral' => $value["id_tipo_acuerdo_laboral"],
                        'id_empleado' => $request->id_empleado,
                        'fecha_acuerdo_laboral' => $value["fecha_acuerdo_laboral"],
                        'oficio_acuerdo_laboral' => $value["oficio_acuerdo_laboral"],
                        'comentario_acuerdo_laboral'  =>  $value["comentario_acuerdo_laboral"],
                        'estado_acuerdo_laboral' => 1,
                        'fecha_inicio_acuerdo_laboral' => strpos($value["fecha_inicio_fecha_fin_acuerdo_laboral"], "to") != '' ? explode("to", $value["fecha_inicio_fecha_fin_acuerdo_laboral"])[0] : $value["fecha_inicio_fecha_fin_acuerdo_laboral"],
                        'fecha_fin_acuerdo_laboral' => strpos($value["fecha_inicio_fecha_fin_acuerdo_laboral"], "to") != '' ? explode("to", $value["fecha_inicio_fecha_fin_acuerdo_laboral"])[1] : $value["fecha_inicio_fecha_fin_acuerdo_laboral"],
                        'fecha_reg_acuerdo_laboral' => Carbon::now(),
                        'usuario_acuerdo_laboral' => $request->user()->nick_usuario,
                        'ip_acuerdo_laboral' => $request->ip(),
                    ];
                    $deals[] = $deal;
                }
            } */
            foreach ($request->deals as $key => $value) {
                if (!$value["isDelete"]) {
                    if ($value["fecha_inicio_fecha_fin_acuerdo_laboral"] !== "null to null") {
                        $fechas = explode(" to ", $value["fecha_inicio_fecha_fin_acuerdo_laboral"]);
                        $fecha_inicio = $fechas[0];
                        $fecha_fin = $fechas[1];
                    } else {
                        $fecha_inicio = null;
                        $fecha_fin = null;
                    }
                    
                    $deal = [
                        'id_tipo_acuerdo_laboral' => $value["id_tipo_acuerdo_laboral"],
                        'id_empleado' => $request->id_empleado,
                        'fecha_acuerdo_laboral' => $value["fecha_acuerdo_laboral"],
                        'oficio_acuerdo_laboral' => $value["oficio_acuerdo_laboral"],
                        'comentario_acuerdo_laboral'  =>  $value["comentario_acuerdo_laboral"],
                        'estado_acuerdo_laboral' => 1,
                        'fecha_inicio_acuerdo_laboral' => $fecha_inicio,
                        'fecha_fin_acuerdo_laboral' => $fecha_fin,
                        'fecha_reg_acuerdo_laboral' => Carbon::now(),
                        'usuario_acuerdo_laboral' => $request->user()->nick_usuario,
                        'ip_acuerdo_laboral' => $request->ip(),
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
                        'id_tipo_acuerdo_laboral' => $value["id_tipo_acuerdo_laboral"],
                        'id_empleado' => $request->id_empleado,
                        'fecha_acuerdo_laboral' => $value["fecha_acuerdo_laboral"],
                        'oficio_acuerdo_laboral' => $value["oficio_acuerdo_laboral"],
                        'comentario_acuerdo_laboral'  =>  $value["comentario_acuerdo_laboral"],
                        'estado_acuerdo_laboral' => 1,
                        'usuario_acuerdo_laboral' => $request->user()->nick_usuario,
                        'ip_acuerdo_laboral' => $request->ip(),
                    ];
            
                    if ($value["fecha_inicio_fecha_fin_acuerdo_laboral"] != "null to null") {
                        $fechas = explode(" to ", $value["fecha_inicio_fecha_fin_acuerdo_laboral"]);
                        $fecha_inicio = $fechas[0];
                        $fecha_fin = $fechas[1];
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
            return true;
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }
}
