<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\Request;
use App\Http\Requests\PersonaRequest;
use Illuminate\Support\Facades\DB;

class PersonaController extends Controller
{
    public function getPersona(Request $request)
    {
        $v_columns = [
            'id_persona',
            'dui_persona',
            'pnombre_persona',
            'snombre_persona',
            'tnombre_persona',
            'papellido_persona',
            'sapellido_persona',
            'tapellido_persona',
            'fecha_nac_persona',
            'estado_persona',
        ];
        $v_length = $request->input('length');
        $v_column = $request->input('column'); //Index
        $v_dir = $request->input('dir');
        $v_searchValue = $request->input('search');

        $v_query = DB::table('persona')
            #->join('sistema', 'rol.id_sistema', '=', 'sistema.id_sistema')
            ->select('*')
            ->orderBy($v_columns[$v_column], $v_dir);

        if ($v_searchValue) {
            $v_query->where(function ($v_query) use ($v_searchValue) {
                $v_query->where('id_persona', 'like', '%' . $v_searchValue . '%')
                    ->orWhere('dui_persona', 'like', '%' . $v_searchValue . '%')
                    ->orWhere('pnombre_persona', 'like', '%' . $v_searchValue . '%')
                    ->orWhere('snombre_persona', 'like', '%' . $v_searchValue . '%')
                    ->orWhere('tnombre_persona', 'like', '%' . $v_searchValue . '%')
                    ->orWhere('papellido_persona', 'like', '%' . $v_searchValue . '%')
                    ->orWhere('sapellido_persona', 'like', '%' . $v_searchValue . '%')
                    ->orWhere('tapellido_persona', 'like', '%' . $v_searchValue . '%')
                    ->orWhere('estado_persona', 'like', '%' . $v_searchValue . '%');
            });
        }
        $v_roles = $v_query->paginate($v_length)->onEachSide(1);
        $v_current_page = $request->input('currentPage');
        return ['data' => $v_roles, 'draw' => $request->input('draw'), 'current' => $v_current_page];
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
        return Persona::find($request->input("id_proveedor"));
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
}