<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Familiar;
use App\Models\Persona;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BeneficiarioController extends Controller
{
    function getDataNemefocoarops(Request $request)
    {
        $columns = [
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
        $query = Persona::select('*')->with([
            "familiar.parentesco",

        ])->orderBy($columns[$column], $dir)->whereHas("familiar");
        $beneficiarios = $query->paginate($length)->onEachSide(1);
        return [
            'data' => $beneficiarios,
            'draw' => $request->input('draw'),
        ];
    }
    public function searchPeopleByName($name)
    {
        return Persona::select(
            'id_persona as value',
            DB::raw("CONCAT(pnombre_persona,' - ',snombre_persona) AS label")
        )->where('pnombre_persona', 'like', '%' . $name . '%')
            ->get();
    }
    function addRelatives(Request $request)
    {
        $relativesData = [];

        foreach ( $request->dataRow as $key => $value ) {
            $relatives = [
                'id_parentesco'        => $value["id_parentesco"],
                'id_persona'           => $request->id_persona,
                'nombre_familiar'      => $value["nombre_familiar"],
                'beneficiado_familiar' => 1,
                'porcentaje_familiar'  => $value["beneficiado_familiar"],
                'estado_familiar'      => 1,
                'fecha_reg_familiar'   => Carbon::now(),
                'usuario_familiar'     => $request->user()->nick_usuario,
                'ip_familiar'          => $request->ip(),

            ];
            $relativesData[] = $relatives;
        }
        $quedan = Familiar::insert($relativesData);
        return $quedan;
    }
}