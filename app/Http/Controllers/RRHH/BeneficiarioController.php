<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Http\Requests\RRHH\BeneficiariosRequest;
use App\Models\Familiar;
use App\Models\Persona;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class BeneficiarioController extends Controller
{
    function getDataFromBeneficiarios(Request $request)
    { //La tabla familiar tiene diferentes usos uno de ellos es guardar los beneficiarios del seguro del empleado
        $columns = [
            'id_persona',
            'id_empleado',
            'id_nivel_educativo',
            'id_profesion',
            'id_usuario',
            'id_municipio',
            'id_aspirante',
            'id_genero',
            'id_estado_civil',
            'dui_persona',
            'pnombre_persona',
            'snombre_persona',
            'tnombre_persona',
            'papellido_persona',
            'sapellido_persona',
            'tapellido_persona',
            'fecha_nac_persona',
            'nombre_madre_persona',
            'nombre_padre_persona',
            'nombre_conyuge_persona',
            'telefono_persona',
            'email_persona',
            'peso_persona',
            'estatura_persona',
            'observacion_persona',
            'estado_persona',
            'fecha_reg_persona',
            'fecha_mod_persona',
            'usuario_persona',
            'ip_persona',
        ];

        $length = $request->input('length');
        $column = $request->input('column');
        $dir = $request->input('dir');
        $data = $request->input('search');

        // Construir la consulta base con las relaciones
        $query = Persona::select('*')->with(["familiar", "profesion", "estado_civil", "nivel_educativo", "genero", "familiar.parentesco", "municipio.departamento.pais", "residencias", "empleado.plazas_asignadas.detalle_plaza.plaza"])->whereHas("familiar", function ($query) {
            $query->where("estado_familiar", 1);
        })->where("estado_persona", 1)->orderBy($columns[$column], $dir);

        if ($data) {
            $query->where('id_persona', 'like', '%' . $data["id_persona"] . '%');

            /* $query->where(function ($query) use ($searchNombres) {
                $query->where('pnombre_persona', 'like', '%' . $searchNombres . '%')
                    ->orWhere('snombre_persona', 'like', '%' . $searchNombres . '%')
                    ->orWhere('tnombre_persona', 'like', '%' . $searchNombres . '%');
            }); */

            /*   $searchApellidos = $data["collecApellido"];
            $query->where(function ($query) use ($searchApellidos) {
                $query->where('papellido_persona', 'like', '%' . $searchApellidos . '%')
                    ->orWhere('sapellido_persona', 'like', '%' . $searchApellidos . '%')
                    ->orWhere('tapellido_persona', 'like', '%' . $searchApellidos . '%');
            }); */

            $searchNombres = $data["collecNombre"];
            if (isset($searchNombres)) {
                $query->whereRaw("MATCH ( pnombre_persona, snombre_persona, tnombre_persona ) AGAINST ( '" . $searchNombres . "')");
            }

            $searchApellidos = $data["collecApellido"];
            if (isset($searchApellidos)) {
                $query->whereRaw("MATCH ( papellido_persona, sapellido_persona, tapellido_persona ) AGAINST ( '" . $searchApellidos . "')");
            }

            $query->whereHas('familiar', function ($query) use ($data) {
                $query->where('nombre_familiar', 'like', '%' . $data["nombre_familiar"] . '%');
            });

            $query->whereHas('familiar', function ($query) use ($data) {
                $query->where('id_parentesco', 'like', '%' . $data["id_parentesco"] . '%');
            });

            $query->whereHas('familiar', function ($query) use ($data) {
                $query->where('porcentaje_familiar', 'like', '%' . $data["porcentaje_familiar"] . '%');
            });
        }

        $beneficiarios = $query->paginate($length)->onEachSide(1);

        return [
            'data' => $beneficiarios,
            'draw' => $request->input('draw'),
        ];
    }

    function searchPeopleByNameOrId(Request $request)
    {
        $query = Persona::query();

        if (!empty($request->nombre)) {

            $query->join('profesion', 'profesion.id_profesion', '=', 'persona.id_profesion')
                ->join('nivel_educativo', 'nivel_educativo.id_nivel_educativo', '=', 'persona.id_nivel_educativo')
                ->join('genero', 'genero.id_genero', '=', 'persona.id_genero')
                ->join('estado_civil', 'estado_civil.id_estado_civil', '=', 'persona.id_estado_civil')
                ->join('municipio', 'municipio.id_municipio', '=', 'persona.id_municipio')
                ->join('departamento', 'departamento.id_departamento', '=', 'municipio.id_departamento')
                ->join('pais', 'pais.id_pais', '=', 'departamento.id_pais')->where('estado_persona', 1)->where(function ($query) {
                    $query->doesntHave('familiar')
                        ->orWhereHas('familiar', function ($query) {
                            $query->where('estado_familiar', 0);
                        });
                })->where(function ($query) use ($request) {
                    $query->whereRaw("MATCH ( pnombre_persona,snombre_persona,tnombre_persona, papellido_persona,sapellido_persona,tapellido_persona ) AGAINST ( '" . $request->nombre . "')");
                });
        }

        $results = $query->get();

        $formattedResults = $results->map(function ($item) {
            $objectData = [
                'value' => $item->id_persona,
                'label' => $item->pnombre_persona . ' ' . ($item->snombre_persona ?? '') . ' ' . ($item->tnombre_persona ?? '') . '' . $item->papellido_persona . ' ' . ($item->sapellido_persona ?? '') . ' ' . ($item->tapellido_persona ?? ''),
                'ALL'   => $item
            ];

            return $objectData;
        });

        return response()->json($formattedResults);
    }
    /* public function searchPeopleByNameOrId(Request $request)
    {
        if ($request["by"] == 'name') {
            return Persona::select(
                'id_persona as value',
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
                ->join('profesion', 'profesion.id_profesion', '=', 'persona.id_profesion')
                ->join('nivel_educativo', 'nivel_educativo.id_nivel_educativo', '=', 'persona.id_nivel_educativo')
                ->join('genero', 'genero.id_genero', '=', 'persona.id_genero')
                ->join('estado_civil', 'estado_civil.id_estado_civil', '=', 'persona.id_estado_civil')
                ->join('municipio', 'municipio.id_municipio', '=', 'persona.id_municipio')
                ->join('departamento', 'departamento.id_departamento', '=', 'municipio.id_departamento')
                ->join('pais', 'pais.id_pais', '=', 'departamento.id_pais')

                ->where(function ($query) use ($request) {
                    $query->where('pnombre_persona', 'like', '%' . $request['query'] . '%')
                        ->orWhere('snombre_persona', 'like', '%' . $request['query'] . '%')
                        ->orWhere('tnombre_persona', 'like', '%' . $request['query'] . '%')
                        ->orWhere('papellido_persona', 'like', '%' . $request['query'] . '%')
                        ->orWhere('sapellido_persona', 'like', '%' . $request['query'] . '%')
                        ->orWhere('tapellido_persona', 'like', '%' . $request['query'] . '%');
                })->where('estado_persona', 1)->where(function ($query) {
                    $query->doesntHave('familiar')
                        ->orWhereHas('familiar', function ($query) {
                            $query->where('estado_familiar', 0);
                        });
                })
                ->get();
        } else {
            return Persona::select(
                'id_persona as value',
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
                ->join('profesion', 'profesion.id_profesion', '=', 'persona.id_profesion')
                ->join('nivel_educativo', 'nivel_educativo.id_nivel_educativo', '=', 'persona.id_nivel_educativo')
                ->join('genero', 'genero.id_genero', '=', 'persona.id_genero')
                ->join('estado_civil', 'estado_civil.id_estado_civil', '=', 'persona.id_estado_civil')
                ->join('municipio', 'municipio.id_municipio', '=', 'persona.id_municipio')
                ->join('departamento', 'departamento.id_departamento', '=', 'municipio.id_departamento')
                ->join('pais', 'pais.id_pais', '=', 'departamento.id_pais')
                ->where('estado_persona', 1)
                ->where("id_persona", $request['query'])->get();
        }
    } */

    function addRelatives(BeneficiariosRequest $request)
    {
        $relativesData = [];
        try {
            DB::beginTransaction();
            foreach ($request->dataRow as $key => $value) {
                if (!$value["isDelete"]) {
                    $relatives = [
                        'id_parentesco'        => $value["id_parentesco"],
                        'id_persona'           => $request->id_persona,
                        'nombre_familiar'      => $value["nombre_familiar"],
                        'beneficiado_familiar' => 1,
                        'porcentaje_familiar'  => $value["porcentaje_familiar"],
                        'estado_familiar'      => 1,
                        'fecha_reg_familiar'   => Carbon::now(),
                        'usuario_familiar'     => $request->user()->nick_usuario,
                        'ip_familiar'          => $request->ip(),

                    ];
                    $relativesData[] = $relatives;
                }
            }
            $familiares = Familiar::insert($relativesData);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }
    function updateRelatives(BeneficiariosRequest $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->dataRow as $value) {
                if (!$value["isDelete"]) {
                    $relatives = [
                        'id_parentesco'        => $value["id_parentesco"],
                        'id_persona'           => $request->id_persona,
                        'nombre_familiar'      => $value["nombre_familiar"],
                        'beneficiado_familiar' => 1,
                        'porcentaje_familiar'  => $value["porcentaje_familiar"],
                        'estado_familiar'      => 1,
                        'usuario_familiar'     => $request->user()->nick_usuario,
                        'ip_familiar'          => $request->ip(),
                    ];

                    if ($value["id_familiar"] == '') {
                        $relatives['fecha_reg_familiar'] = Carbon::now();
                        Familiar::create($relatives);
                    } else {
                        $relatives['fecha_mod_familiar'] = Carbon::now();
                        Familiar::where("id_familiar", $value["id_familiar"])->update($relatives);
                    }
                } elseif ($value["id_familiar"] != '') {
                    Familiar::where("id_familiar", $value["id_familiar"])->update(['estado_familiar' => 0]);
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
