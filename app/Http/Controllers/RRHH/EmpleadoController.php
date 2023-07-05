<?php

namespace App\Http\Controllers\RRHH;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\EstadoCivil;
use App\Models\Genero;
use App\Models\Municipio;
use App\Models\NivelEducativo;
use App\Models\Persona;
use App\Models\Profesion;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{
    public function getEmployees(Request $request)
    {
        $columns = ['id_empleado', 'codigo_empleado', 'nombre_persona', 'dui_persona', 'email_persona', 'estado_empleado'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = Empleado::select('*')
            ->with('persona')
            ->orderBy($columns[$column], $dir);

        if ($search_value) {
            $query->where('id_empleado', 'like', '%' . $search_value['id_empleado'] . '%')
                ->where('codigo_empleado', 'like', '%' . $search_value['codigo_empleado'] . '%')
                ->where('estado_empleado', 'like', '%' . $search_value['estado_empleado'] . '%')
                ->whereHas('persona', function ($query) use ($search_value) {
                    $query->where('dui_persona', 'like', '%' . $search_value["dui_persona"] . '%')
                        ->where('email_persona', 'like', '%' . $search_value["email_persona"] . '%');
                })
                ->whereHas(
                    'persona',
                    function ($query) use ($search_value) {
                        $query->where('pnombre_persona', 'like', '%' . $search_value["nombre_persona"] . '%')
                            ->orWhere('snombre_persona', 'like', '%' . $search_value["nombre_persona"] . '%')
                            ->orWhere('tnombre_persona', 'like', '%' . $search_value["nombre_persona"] . '%')
                            ->orWhere('papellido_persona', 'like', '%' . $search_value["nombre_persona"] . '%')
                            ->orWhere('sapellido_persona', 'like', '%' . $search_value["nombre_persona"] . '%')
                            ->orWhere('tapellido_persona', 'like', '%' . $search_value["nombre_persona"] . '%');
                    }
                );
        }
        $employees = $query->paginate($length)->onEachSide(1);
        return ['data' => $employees, 'draw' => $request->input('draw')];
    }

    public function searchPersonByDUI(Request $request)
    {
        $dui = $request->dui;
        $correct_dui = false;
        if ((bool) preg_match('/(^\d{8})-(\d$)/', $dui) === true) {
            [$digits, $digit_veri] = explode('-', $dui);
            $sum = 0;
            for ($i = 0, $l = strlen($digits); $i < $l; $i++) {
                $sum += (9 - $i) * (int) $digits[$i];
            }
            if ((int) $digit_veri === ((10 - ($sum % 10)) % 10)) {
                $correct_dui = true;
            }
        } else {
            $correct_dui = false;
        }

        if ($correct_dui) {
            $person = Persona::select('*')
                ->where('dui_persona', $dui)
                ->first();
            return ['person' => $person ?? ''];
        } else {
            return response()->json([
                'logical_error' =>
                'Error, el numero de dui no existe.'
            ], 422);
        }
    }

    public function getSelectOptionsEmployee(Request $request)
    {
        $marital_status = EstadoCivil::select('id_estado_civil as value', 'nombre_estado_civil as label')
            //->where('estado_estado_civil',1)
            ->get();
        $gender = Genero::select('id_genero as value', 'nombre_genero as label')
            //->where('estado_genero',1)
            ->get();
        $municipality = DB::table("departamento")
            ->select('municipio.id_municipio as value', DB::raw("CONCAT(pais.id_pais,' - ',departamento.nombre_departamento,' - ',municipio.nombre_municipio ) AS label"))
            ->join('municipio', 'departamento.id_departamento', '=', 'municipio.id_departamento')
            ->join('pais', 'departamento.id_pais', '=', 'pais.id_pais')
            ->get();
        $educational_level = NivelEducativo::select('id_nivel_educativo as value', 'nombre_nivel_educativo as label')
            ->get();
        $occupation = Profesion::select('id_profesion as value','nombre_profesion as label')
            ->get();

        return [
            "marital_status"    => $marital_status,
            "gender"            => $gender,
            "municipality"      => $municipality,
            'educational_level' => $educational_level,
            'occupation'        => $occupation
        ];
    }
}
