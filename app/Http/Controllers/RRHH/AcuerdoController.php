<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\AcuerdoLaboral;
use App\Models\Empleado;
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
        $query = Empleado::select('*')->with(["acuerdo_laboral"])->whereHas("acuerdo_laboral", function ($query) {
            $query->where("estado_acuerdo_laboral", 1);
        })->where("estado_empleado", 1)->orderBy($columns[$column], $dir);

        if ($data) {
            $query->where('id_empleado', 'like', '%' . $data["id_empleado"] . '%');

        }
        $acuerdos = $query->paginate($length)->onEachSide(1);

        $tipo_acuerdo_laboral = DB::table('tipo_acuerdo_laboral')
            ->select(
                'id_tipo_acuerdo_laboral as value',
                'nombre_tipo_acuerdo_laboral  as label',
            )->get();


        return [
            'data'          => $acuerdos,
            'dataForSelect' => ['tipo_acuerdo_laboral' => $tipo_acuerdo_laboral],
            'draw'          => $request->input('draw'),
        ];
    }


    public function searchEmployeByNameOrId(Request $request)
    {
        if ($request["by"] == 'name') {
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
                    $query->where('persona.pnombre_persona', 'like', '%' . $request['query'] . '%')
                        ->orWhere('persona.snombre_persona', 'like', '%' . $request['query'] . '%')
                        ->orWhere('persona.tnombre_persona', 'like', '%' . $request['query'] . '%')
                        ->orWhere('persona.papellido_persona', 'like', '%' . $request['query'] . '%')
                        ->orWhere('persona.sapellido_persona', 'like', '%' . $request['query'] . '%')
                        ->orWhere('persona.tapellido_persona', 'like', '%' . $request['query'] . '%');
                })->where('estado_empleado', 1)->get();
        } else {
            /*  return Persona::select(
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
                 ->where("id_persona", $request['query'])->get(); */
        }

    }

    function createDeals(Request $request)
    {
        
    }

}