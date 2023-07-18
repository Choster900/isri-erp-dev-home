<?php

namespace App\Http\Controllers\RRHH;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RRHH\EmpleadoRequest;
use App\Models\Banco;
use App\Models\Dependencia;
use App\Models\DetallePlaza;
use App\Models\Empleado;
use App\Models\EstadoCivil;
use App\Models\Genero;
use App\Models\NivelEducativo;
use App\Models\Persona;
use App\Models\PlazaAsignada;
use App\Models\Profesion;
use App\Models\Residencia;
use App\Models\TipoPension;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{
    public function getEmployees(Request $request)
    {
        $columns = ['id_empleado', 'codigo_empleado', 'nombre_persona', 'dui_persona', 'dependencia', 'estado_empleado'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = Empleado::select('*')
            ->with([
                'persona.residencias' => function ($query) {
                    $query->where('estado_residencia', 1)
                        ->orderBy('fecha_reg_residencia', 'desc');
                },
                'plazas_asignadas' => function ($query) {
                    $query->join('dependencia', 'plaza_asignada.id_dependencia', '=', 'dependencia.id_dependencia')
                        ->orderBy('dependencia.codigo_dependencia');
                },
                'plazas_asignadas.dependencia'
            ]);

        if ($column == 2) {
            $query->orderByRaw('(SELECT pnombre_persona FROM persona WHERE persona.id_empleado = empleado.id_empleado) ' . $dir);
        } else {
            if ($column == 3) {
                $query->orderByRaw('(SELECT dui_persona FROM persona WHERE persona.id_empleado = empleado.id_empleado) ' . $dir);
            } else {
                if ($column == 4) {
                    $query->orderByRaw('(SELECT MIN(codigo_dependencia) FROM dependencia WHERE dependencia.id_dependencia IN (SELECT id_dependencia FROM plaza_asignada WHERE plaza_asignada.id_empleado = empleado.id_empleado) ) ' . $dir);
                } else {
                    $query->orderBy($columns[$column], $dir);
                }
            }
        }

        if ($search_value) {
            $query->where('id_empleado', 'like', '%' . $search_value['id_empleado'] . '%')
                ->where('codigo_empleado', 'like', '%' . $search_value['codigo_empleado'] . '%')
                ->where('estado_empleado', 'like', '%' . $search_value['estado_empleado'] . '%')
                ->whereHas('persona', function ($query) use ($search_value) {
                    $query->where('dui_persona', 'like', '%' . $search_value["dui_persona"] . '%');
                })
                ->whereHas('plazas_asignadas.dependencia', function ($query) use ($search_value) {
                    $query->where('codigo_dependencia', 'like', '%' . $search_value["dependencia"] . '%');
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
                        //->orderBy('fecha_mod_residencia', 'desc')
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
            ->where('estado_dependencia', '=', 1)
            ->orderBy('nombre_dependencia')
            ->get();
        $job_positions = DetallePlaza::selectRaw("detalle_plaza.id_det_plaza as value, concat(detalle_plaza.codigo_det_plaza,' - ',plaza.nombre_plaza,' - ',tipo_contrato.codigo_tipo_contrato)  as label, plaza.salario_base_plaza, plaza.salario_tope_plaza, linea_trabajo.id_lt")
            ->join('plaza', 'detalle_plaza.id_plaza', '=', 'plaza.id_plaza')
            ->join('tipo_contrato', 'detalle_plaza.id_tipo_contrato', '=', 'tipo_contrato.id_tipo_contrato')
            ->join('actividad_institucional', 'detalle_plaza.id_actividad_institucional', '=', 'actividad_institucional.id_actividad_institucional')
            ->join('linea_trabajo', 'actividad_institucional.id_lt', '=', 'linea_trabajo.id_lt')
            ->whereIn('detalle_plaza.id_estado_plaza', [1, 2])
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
            'job_positions'       => $job_positions
        ];
    }

    public function storeEmployee(EmpleadoRequest $request)
    {
        $first_two_characters = substr($request->persona['papellido_persona'], 0, 2);
        $last_employee_code = Empleado::where('codigo_empleado', 'like', $first_two_characters . '%')
            ->orderByDesc('codigo_empleado')
            ->first();
        $last_correlative = $last_employee_code ? intval(substr($last_employee_code->codigo_empleado, 2)) : 0;
        $correlative = str_pad($last_correlative + 1, 3, '0', STR_PAD_LEFT);
        $employee_code = strtoupper($first_two_characters) . $correlative;

        $personId = $request->persona['id_persona'] ?? '';
        $person = $personId ? Persona::find($personId) : new Persona();

        $residenceId = $request->persona['residencias'][0]['id_residencia'] ?? '';
        $residence = $residenceId ? Residencia::find($residenceId) : new Residencia();

        // Set common attributes for Persona
        $personAttributes = [
            'id_genero' => $request->persona['id_genero'],
            'id_estado_civil' => $request->persona['id_estado_civil'],
            'id_nivel_educativo' => $request->persona['id_nivel_educativo'],
            'id_municipio' => $request->persona['id_municipio'],
            'id_profesion' => $request->persona['id_profesion'],
            'pnombre_persona' => $request->persona['pnombre_persona'],
            'snombre_persona' => $request->persona['snombre_persona'],
            'tnombre_persona' => $request->persona['tnombre_persona'],
            'papellido_persona' => $request->persona['papellido_persona'],
            'sapellido_persona' => $request->persona['sapellido_persona'],
            'tapellido_persona' => $request->persona['tapellido_persona'],
            'telefono_persona' => $request->persona['telefono_persona'],
            'dui_persona' => $request->persona['dui_persona'],
            'email_persona' => $request->persona['email_persona'],
            'nombre_conyuge_persona' => $request->persona['nombre_conyuge_persona'],
            'nombre_madre_persona' => $request->persona['nombre_madre_persona'],
            'nombre_padre_persona' => $request->persona['nombre_padre_persona'],
            'fecha_nac_persona' => $request->persona['fecha_nac_persona'],
            'usuario_persona' => $request->user()->nick_usuario,
            'ip_persona' => $request->ip(),
        ];

        // Set attributes based on the condition
        if ($request->persona['id_persona'] == '') {
            $person->fill($personAttributes);
            $person->fecha_reg_persona = Carbon::now();
            $person->estado_persona = 1;
            $person->save();
        } else {
            $person->update($personAttributes);
            $person->fecha_mod_persona = Carbon::now();
            $person->save();
        }

        // Set common attributes for Residencia
        $residenceAttributes = [
            'id_persona' => $person->id_persona,
            'id_municipio' => $request->persona['residencias'][0]['id_municipio'],
            'direccion_residencia' => $request->persona['residencias'][0]['direccion_residencia'],
            'estado_residencia' => 1,
            'fecha_reg_residencia' => Carbon::now(),
            'usuario_residencia' => $request->user()->nick_usuario,
            'ip_residencia' => $request->ip(),
        ];

        if ($residenceId != '') {
            $hasChanged = ($residence->id_municipio != $request->persona['residencias'][0]['id_municipio'])
                || ($residence->direccion_residencia != $request->persona['residencias'][0]['direccion_residencia']);

            if ($hasChanged) {
                $newResidence = new Residencia($residenceAttributes);
                $newResidence->save();

                $residence->estado_residencia = 0;
                $residence->update();
            }
        } else {
            $newResidence = new Residencia($residenceAttributes);
            $newResidence->save();
        }

        $new_employee = new Empleado([
            'id_persona'                    => $request->persona['id_persona'] == '' ? $person->id_persona : $request->persona['id_persona'],
            'id_tipo_pension'               => $request->id_tipo_pension,
            'id_banco'                      => $request->id_banco,
            'id_titulo_profesional'         => $request->id_titulo_profesional,
            'codigo_empleado'               => $employee_code,
            'nup_empleado'                  => $request->nup_empleado,
            'isss_empleado'                 => $request->isss_empleado,
            'cuenta_banco_empleado'         => $request->cuenta_banco_empleado,
            'fecha_contratacion_empleado'   => $request->fecha_contratacion_empleado,
            'email_institucional_empleado'  => $request->email_institucional_empleado,
            'email_alternativo_empleado'    => $request->email_alternativo_empleado,
            'estado_empleado'               => 1,
            'fecha_reg_empleado'            => Carbon::now(),
            'usuario_empleado'              => $request->user()->nick_usuario,
            'ip_empleado'                   => $request->ip(),
        ]);
        $new_employee->save();

        $person->update([
            'id_empleado'           => $new_employee->id_empleado,
            'fecha_mod_persona'     => Carbon::now(),
            'usuario_persona'       => $request->user()->nick_usuario,
            'ip_persona'            => $request->ip(),
        ]);

        $new_assigned_job_position = new PlazaAsignada([
            'id_empleado'                   => $new_employee->id_empleado,
            'id_lt'                         => $request->work_area_id,
            'id_dependencia'                => $request->dependency_id,
            'id_det_plaza'                  => $request->job_position_id,
            'salario_plaza_asignada'        => $request->salary,
            'partida_plaza_asignada'        => $request->account,
            'subpartida_plaza_asignada'     => $request->subaccount,
            //This is the same date when the employee was hired, we don't ask for it in the form
            'fecha_plaza_asignada'          => $request->fecha_contratacion_empleado,
            'estado_plaza_asignada'         => 1,
            'fecha_reg_plaza_asignada'      => Carbon::now(),
            'usuario_plaza_asignada'        => $request->user()->nick_usuario,
            'ip_plaza_asignada'             => $request->ip(),
        ]);
        $new_assigned_job_position->save();

        $job_position_det = DetallePlaza::find($request->job_position_id);
        $job_position_det->update([
            'id_estado_plaza'           => 3,
            'fecha_mod_plaza_asignada'  => Carbon::now(),
            'usuario_plaza_asignada'    => $request->user()->nick_usuario,
            'ip_plaza_asignada'         => $request->ip(),
        ]);

        return ['mensaje' => 'Empleado guardado con éxito'];
    }

    public function updateEmployee(EmpleadoRequest $request)
    {
        $person = Persona::find($request->persona['id_persona']);
        $residence = Residencia::find($request->persona['residencias'][0]['id_residencia']);
        $employee = Empleado::find($request->id_empleado);

        $person->update([
            'id_genero'                     => $request->persona['id_genero'],
            'id_estado_civil'               => $request->persona['id_estado_civil'],
            'id_nivel_educativo'            => $request->persona['id_nivel_educativo'],
            'id_municipio'                  => $request->persona['id_municipio'],
            'id_profesion'                  => $request->persona['id_profesion'],
            'pnombre_persona'               => $request->persona['pnombre_persona'],
            'snombre_persona'               => $request->persona['snombre_persona'],
            'tnombre_persona'               => $request->persona['tnombre_persona'],
            'papellido_persona'             => $request->persona['papellido_persona'],
            'sapellido_persona'             => $request->persona['sapellido_persona'],
            'tapellido_persona'             => $request->persona['tapellido_persona'],
            'telefono_persona'              => $request->persona['telefono_persona'],
            'dui_persona'                   => $request->persona['dui_persona'],
            'email_persona'                 => $request->persona['email_persona'],
            'nombre_conyuge_persona'        => $request->persona['nombre_conyuge_persona'],
            'nombre_madre_persona'          => $request->persona['nombre_madre_persona'],
            'nombre_padre_persona'          => $request->persona['nombre_padre_persona'],
            'fecha_nac_persona'             => $request->persona['fecha_nac_persona'],
            'fecha_mod_persona'             => Carbon::now(),
            'usuario_persona'               => $request->user()->nick_usuario,
            'ip_persona'                    => $request->ip(),
        ]);

        $hasChanged = ($residence->id_municipio != $request->persona['residencias'][0]['id_municipio'])
            || ($residence->direccion_residencia != $request->persona['residencias'][0]['direccion_residencia']);

        if ($hasChanged) {
            $newResidence = new Residencia([
                'id_persona' => $person->id_persona,
                'id_municipio' => $request->persona['residencias'][0]['id_municipio'],
                'direccion_residencia' => $request->persona['residencias'][0]['direccion_residencia'],
                'estado_residencia' => 1,
                'fecha_reg_residencia' => Carbon::now(),
                'usuario_residencia' => $request->user()->nick_usuario,
                'ip_residencia' => $request->ip(),
            ]);
            $newResidence->save();

            $residence->estado_residencia = 0;
            $residence->update();
        }

        $employee->update([
            'id_tipo_pension'               => $request->id_tipo_pension,
            'id_banco'                      => $request->id_banco,
            'id_titulo_profesional'         => $request->id_titulo_profesional,
            'nup_empleado'                  => $request->nup_empleado,
            'isss_empleado'                 => $request->isss_empleado,
            'cuenta_banco_empleado'         => $request->cuenta_banco_empleado,
            'email_institucional_empleado'  => $request->email_institucional_empleado,
            'email_alternativo_empleado'    => $request->email_alternativo_empleado,
            'fecha_mod_empleado'            => Carbon::now(),
            'usuario_empleado'              => $request->user()->nick_usuario,
            'ip_empleado'                   => $request->ip(),
        ]);

        return ['mensaje' => 'Empleado actualizado con éxito'];
    }
}