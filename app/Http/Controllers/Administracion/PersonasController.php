<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Personas;
use Illuminate\Http\Request;
use App\Http\Requests\PersonasRequest;
use Illuminate\Support\Facades\DB;

class PersonasController extends Controller
{
    //
    public function getPersona(Request $request)
    {
        $columns = [
            'id_persona',
            'dui_persona',
            'pnombre_persona',
            'snombre_persona',
            'tnombre_persona',
            'papellido_persona',
            'sapellido_persona',
            'tapellido_persona',
            'fecha_nac_persona',
        ];

        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $searchValue = $request->input('search');

        $query = DB::table('persona')
            #->join('sistema', 'rol.id_sistema', '=', 'sistema.id_sistema')
            ->select('*')
            ->orderBy($columns[$column], $dir);

        if ($searchValue) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('id_persona', 'like', '%' . $searchValue . '%')
                    ->orWhere('dui_persona', 'like', '%' . $searchValue . '%')
                    ->orWhere('pnombre_persona', 'like', '%' . $searchValue . '%')
                    ->orWhere('snombre_persona', 'like', '%' . $searchValue . '%')
                    ->orWhere('tnombre_persona', 'like', '%' . $searchValue . '%')
                    ->orWhere('papellido_persona', 'like', '%' . $searchValue . '%')
                    ->orWhere('sapellido_persona', 'like', '%' . $searchValue . '%')
                    ->orWhere('tapellido_persona', 'like', '%' . $searchValue . '%');
            });
        }

        $roles = $query->paginate($length)->onEachSide(1);
        $current_page = $request->input('currentPage');
        return ['data' => $roles, 'draw' => $request->input('draw'), 'current' => $current_page];
    }

    public function getInformationToSelect(Request $request)
    {
        $civilStatus = DB::table('estado_civil')->select(
            'id_estado_civil as value',
            'nombre_estado_civil as label'
        )->get();

        $country = DB::table('pais')
            ->select(
                'id_pais as value',
                DB::raw("CONCAT(codigo_2digitos_pais,' - ',nombre_pais) AS label")
            )->get();

        $gender = DB::table('genero')->select('id_genero as value', 'nombre_genero as label')->get();

        $departament = DB::table('departamento')->select(
            'id_departamento as value',
            DB::raw("CONCAT(id_pais,' - ',nombre_departamento) AS label")
        )->get();

        $municipio = DB::table('municipio')->select(
            'id_municipio as value',
            'nombre_municipio as label'
        )->get();

        $levelEducation = DB::table('nivel_educativo')->select(
            'id_nivel_educativo as value',
            'nombre_nivel_educativo as label'
        )->get();

        $levelProfession = DB::table('profesion')->select(
            'id_profesion as value',
            DB::raw("CONCAT(id_profesion_rnpn,' - ',nombre_profesion) AS label")
        )->get();

        return [
            "gender"          => $gender,
            "civilStatus"     => $civilStatus,
            "country"         => $country,
            "departament"     => $departament,
            "municipio"       => $municipio,
            "levelEducation"  => $levelEducation,
            "levelProfession" => $levelProfession,
        ];
    }

    public function getInformationPersona(Request $request)
    {
        $id = $request->input("id_persona");
        $infoPersona = Personas::find($id);
        return $infoPersona;
    }
    public function AgregarPersona(PersonasRequest $request)
    {
        try {
            /*  DB::beginTransaction();
            $persona = Personas::create([
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
            return $persona; */

            if ((bool) preg_match('/(^\d{8})-(\d$)/', $request->input("dui_persona")) === true) {
                [$digits, $digit_veri] = explode('-', $request->input("dui_persona"));
                $sum = 0;

                for ($i = 0, $l = strlen($digits); $i < $l; $i++) {
                    $sum += (9 - $i) * (int) $digits[$i];
                }

                return (int) $digit_veri === ((10 - ($sum % 10)) % 10);
            }

            return false;

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }

    }
    public function EditarPersona(Request $request)
    {
        $persona = Personas::where("id_persona", $request->input("id_persona"))->update([
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
        return $persona;
    }
    public function deltePersona()
    {
        #TODO: pendiente   
    }
}