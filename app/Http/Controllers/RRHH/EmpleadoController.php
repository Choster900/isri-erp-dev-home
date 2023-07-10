<?php

namespace App\Http\Controllers\RRHH;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActividadInstitucional;
use App\Models\Banco;
use App\Models\Departamento;
use App\Models\Dependencia;
use App\Models\Empleado;
use App\Models\EstadoCivil;
use App\Models\Genero;
use App\Models\LineaTrabajo;
use App\Models\Municipio;
use App\Models\NivelEducativo;
use App\Models\Persona;
use App\Models\Plaza;
use App\Models\Profesion;
use App\Models\ProyectoFinanciado;
use App\Models\TipoPension;
use App\Models\UpltDependencia;
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
                ->with(['residencias' => function ($query) {
                    $query->where('estado_residencia', 1)
                        ->orderBy('fecha_mod_residencia', 'desc')
                        ->orderBy('fecha_reg_residencia', 'desc')
                        ->first();
                }])
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
        //You should validate the state of each catalog
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
        $occupation = Profesion::select('id_profesion as value', 'nombre_profesion as label')
            ->get();
        $pension_type = TipoPension::select('id_tipo_pension as value', 'codigo_tipo_pension as label')
            ->get();
        $bank = Banco::select('id_banco as value', 'nombre_banco as label')
            ->get();
        $professional_title = DB::table('titulo_profesional')
            ->select('id_titulo_profesional as value', 'nombre_titulo_profesional as label')
            ->get();
        $dependencies = Dependencia::selectRaw("id_dependencia as value , concat(codigo_dependencia, ' - ', nombre_dependencia) as label")
            ->where('id_tipo_dependencia', '=', 1)
            ->orderBy('nombre_dependencia')
            ->get();
        $financing_sources = ProyectoFinanciado::select('id_proy_financiado as value', 'nombre_proy_financiado as label')
            ->where('estado_proy_financiado', '=', 1)
            ->orderBy('nombre_proy_financiado')
            ->get();
        $contract_types = DB::table('tipo_contrato')
            ->select('id_tipo_contrato as value', 'nombre_tipo_contrato as label')
            ->get();

        return [
            "marital_status"      => $marital_status,
            "gender"              => $gender,
            "municipality"        => $municipality,
            'educational_level'   => $educational_level,
            'occupation'          => $occupation,
            'pension_type'        => $pension_type,
            'bank'                => $bank,
            'professional_title'  => $professional_title,
            'dependencies'        => $dependencies,
            'financing_sources'   => $financing_sources,
            'contract_types'      => $contract_types
        ];
    }
    public function getUplt(Request $request)
    {
        $uplt = UpltDependencia::selectRaw("uplt_dependencia.id_linea_trabajo as value, concat(unidad_presupuestaria.codigo_unidad_ppto, linea_trabajo.codigo_linea_trabajo) as label")
            ->join('linea_trabajo', function ($join) {
                $join->on('uplt_dependencia.id_linea_trabajo', '=', 'linea_trabajo.id_linea_trabajo');
            })
            ->join('unidad_presupuestaria', function ($join) {
                $join->on('linea_trabajo.id_unidad_ppto', '=', 'unidad_presupuestaria.id_unidad_ppto');
            })
            ->where('id_dependencia', $request->dependency_id)
            ->get();
        return ['uplt' => $uplt];
    }
    public function getInstitutionalActivities(Request $request)
    {
        $activities = ActividadInstitucional::select('id_actividad_institucional as value', 'nombre_actividad_institucional as label')
            ->where('id_linea_trabajo', $request->field_work_id)
            ->get();
        return ['activities' => $activities];
    }
    public function getJobPositions(Request $request)
    {
        $job_positions = Plaza::select('id_plaza as value', 'nombre_plaza as label', 'salario_base_plaza', 'salario_tope_plaza')
            ->where('id_proy_financiado', $request->financing_source_id)
            ->get();
        return ['job_positions' => $job_positions];
    }
}
