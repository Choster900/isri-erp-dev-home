<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\Request;
use App\Http\Requests\PersonaRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PersonaController extends Controller
{
    public function getPersona(Request $request)
    {
        $columns = ['id_persona', 'dui_persona', 'pnombre_persona', 'fecha_nac_persona', 'estado_persona',];
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $search_value = $request->input('search');

        $query = Persona::select('*')
            ->orderBy($columns[$column], $dir);

        if ($search_value) {
            $query->where([
                ['id_persona', 'like', '%' . $search_value['id_persona'] . '%'],
                ['dui_persona', 'like', '%' . $search_value['dui_persona'] . '%'],
                ['fecha_nac_persona', 'like', '%' . $search_value['fecha_nac_persona'] . '%'],
                ['estado_persona', 'like', '%' . $search_value['estado_persona'] . '%'],
                [function ($query) use ($search_value) {
                    $query->where('pnombre_persona', 'like', '%' . $search_value['nombre_persona'] . '%')
                        ->orWhere('snombre_persona', 'like', '%' . $search_value['nombre_persona'] . '%')
                        ->orWhere('tnombre_persona', 'like', '%' . $search_value['nombre_persona'] . '%')
                        ->orWhere('papellido_persona', 'like', '%' . $search_value['nombre_persona'] . '%')
                        ->orWhere('sapellido_persona', 'like', '%' . $search_value['nombre_persona'] . '%')
                        ->orWhere('tapellido_persona', 'like', '%' . $search_value['nombre_persona'] . '%');
                }],
            ]);
        }
        $personas = $query->paginate($length)->onEachSide(1);
        $current_page = $request->input('currentPage');
        return ['data' => $personas, 'draw' => $request->input('draw'), 'current' => $current_page];
    }


    public function getInformationToSelect(Request $request)
    { //Retorna un arreglo de diferentes tablas para llenar los select del modal
        $v_civilStatus = DB::table('estado_civil')
            ->select(
                'id_estado_civil as value',
                'nombre_estado_civil as label'
            )->get();
        $v_gender = DB::table('genero')->select('id_genero as value', 'nombre_genero as label')->get();
        $v_location = DB::table("departamento")
            ->select('municipio.id_municipio as value', DB::raw("CONCAT(pais.id_pais,' - ',departamento.nombre_departamento,' - ',municipio.nombre_municipio ) AS label"))
            ->join('municipio', 'departamento.id_departamento', '=', 'municipio.id_departamento')
            ->join('pais', 'departamento.id_pais', '=', 'pais.id_pais')
            ->get();
        $v_levelEducation = DB::table('nivel_educativo')
            ->select(
                'id_nivel_educativo as value',
                'nombre_nivel_educativo as label'
            )->get();
        $v_levelProfession = DB::table('profesion')
            ->select(
                'id_profesion as value',
                DB::raw("CONCAT(id_profesion_rnpn,' - ',nombre_profesion) AS label")
            )->get();
        return [
            "gender"          => $v_gender,
            "civilStatus"     => $v_civilStatus,
            "levelEducation"  => $v_levelEducation,
            "levelProfession" => $v_levelProfession,
            "location"        => $v_location
        ];
    }

    public function getInformationPersona(Request $request)
    {
        return Persona::find($request->input("id_persona"));
    }
    public function AgregarPersona(PersonaRequest $request)
    {
        try {
            DB::beginTransaction();
            $v_persona = Persona::create([
                'pnombre_persona'        => $request->input("pnombre_persona"),
                'snombre_persona'        => $request->input("snombre_persona"),
                'tnombre_persona'        => $request->input("tnombre_persona"),
                'papellido_persona'      => $request->input("papellido_persona"),
                'sapellido_persona'      => $request->input("sapellido_persona"),
                'tapellido_persona'      => $request->input("tapellido_persona"),
                'telefono_persona'       => $request->input("telefono_persona"),
                'dui_persona'            => $request->input("dui_persona"),
                'email_persona'          => $request->input("email_persona"),
                'id_genero'              => $request->input("id_genero"),
                'id_estado_civil'        => $request->input("id_estado_civil"),
                'nombre_conyuge_persona' => $request->input("nombre_conyuge_persona"),
                'nombre_madre_persona'   => $request->input("nombre_madre_persona"),
                'nombre_padre_persona'   => $request->input("nombre_padre_persona"),
                'fecha_nac_persona'      => $request->input("fecha_nac_persona"),
                'id_municipio'           => $request->input("id_municipio"),
                'id_profesion'           => $request->input("id_profesion"),
                'id_nivel_educativo'     => $request->input("id_nivel_educativo"),
                'estado_persona'         => 1,
                'fecha_reg_persona'      => Carbon::now(),
            ]);
            DB::commit();
            return $v_persona;
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }
    public function EditarPersona(PersonaRequest $request)
    {
        try {
            DB::beginTransaction();
            $v_persona = Persona::where("id_persona", $request->input("id_persona"))->update([
                'pnombre_persona'        => $request->input("pnombre_persona"),
                'snombre_persona'        => $request->input("snombre_persona"),
                'tnombre_persona'        => $request->input("tnombre_persona"),
                'papellido_persona'      => $request->input("papellido_persona"),
                'sapellido_persona'      => $request->input("sapellido_persona"),
                'tapellido_persona'      => $request->input("tapellido_persona"),
                'telefono_persona'       => $request->input("telefono_persona"),
                'dui_persona'            => $request->input("dui_persona"),
                'email_persona'          => $request->input("email_persona"),
                'id_genero'              => $request->input("id_genero"),
                'id_estado_civil'        => $request->input("id_estado_civil"),
                'nombre_conyuge_persona' => $request->input("nombre_conyuge_persona"),
                'nombre_madre_persona'   => $request->input("nombre_madre_persona"),
                'nombre_padre_persona'   => $request->input("nombre_padre_persona"),
                'fecha_nac_persona'      => $request->input("fecha_nac_persona"),
                'id_municipio'           => $request->input("id_municipio"),
                'id_profesion'           => $request->input("id_profesion"),
                'id_nivel_educativo'     => $request->input("id_nivel_educativo"),
                'fecha_mod_persona'      => Carbon::now(),
            ]);
            DB::commit();
            return $v_persona;
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }
    public function changeStatePerson(Request $request)
    { //Cambiando el estado de la persona
        $v_infoPersona = Persona::find($request->id_persona);
        Persona::where("id_persona", $request->input("id_persona"))->update([
            'estado_persona' => $v_infoPersona["estado_persona"] != 1 ? 1 : 0, //si es diferente de 1 ingresamos 1 => Activo de lo contrario 0 =>inactivo
        ]);
        return $v_infoPersona["estado_persona"];
    }

    // Traer las personas por nombre

    function getPersonByCompleteName(Request $request)
    {
        $query = Persona::query();

        if (!empty($request->nombre)) {
            $query->orWhere(function ($query) use ($request) {
                $query->whereRaw("MATCH ( pnombre_persona,
                 snombre_persona,
                  tnombre_persona, 
                  papellido_persona,
                   sapellido_persona,
                    tapellido_persona )
                     AGAINST ( '" . $request->nombre . "')");
            });
        }
        $query->doesntHave("archivo_anexo");
        $results = $query->get();

        $formattedResults = $results->map(function ($item) {
            return [
                'value' => $item->id_persona,
                'label' => $item->pnombre_persona . ' ' . ($item->snombre_persona ?? '') . ' ' . ($item->tnombre_persona ?? ''). ' ' . ($item->papellido_persona ?? ''). ' ' . ($item->sapellido_persona ?? ''). ' ' . ($item->tapellido_persona ?? ''),
                'allDataPersonas' => $item
                /* 'disabled' => true */
            ];
        });

        return response()->json($formattedResults);
    }
}
