<?php

namespace App\Http\Controllers\RRHH;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RRHH\PlazaAsignadaRequest;
use App\Http\Requests\RRHH\EmpleadoRequest;
use App\Models\Banco;
use App\Models\Dependencia;
use App\Models\DetallePlaza;
use App\Models\Empleado;
use App\Models\EstadoCivil;
use App\Models\Foto;
use App\Models\Genero;
use App\Models\MotivoDesvinculoLaboral;
use App\Models\NivelEducativo;
use App\Models\PeriodoLaboral;
use App\Models\PermisoUsuario;
use App\Models\Persona;
use App\Models\PlazaAsignada;
use App\Models\Profesion;
use App\Models\Residencia;
use App\Models\TipoPension;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;

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
                        ->where('estado_plaza_asignada', 1)
                        ->orderBy('dependencia.codigo_dependencia');
                },
                'plazas_asignadas.dependencia.parent_dependency',
                'persona.fotos'
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
                        if ($search_value["nombre_persona"] != '') {
                            $query->whereRaw("MATCH(pnombre_persona, snombre_persona, tnombre_persona, papellido_persona, sapellido_persona, tapellido_persona) AGAINST(?)", $search_value["nombre_persona"]);
                        }
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
        $dependencies = DB::table('dependencia')
            ->selectRaw("
                dependencia.id_dependencia as value,
                CASE
                    WHEN dependencia.dep_id_dependencia IS NOT NULL THEN CONCAT(dep.codigo_dependencia, ' - ', dependencia.nombre_dependencia,' (',dependencia.codigo_dependencia,')')
                    ELSE CONCAT(dependencia.codigo_dependencia, ' - ', dependencia.nombre_dependencia)
                END as label
            ")
            ->leftJoin('dependencia as dep', 'dependencia.dep_id_dependencia', '=', 'dep.id_dependencia')
            ->where('dependencia.id_dependencia', '!=', 1)
            ->get();
        $job_positions = DetallePlaza::selectRaw("detalle_plaza.id_det_plaza as value, 
                CASE WHEN detalle_plaza.id_puesto_sirhi_det_plaza IS NULL THEN CONCAT(plaza.nombre_plaza,' - ',tipo_contrato.codigo_tipo_contrato)
                ELSE CONCAT(detalle_plaza.id_puesto_sirhi_det_plaza, '  - ', plaza.nombre_plaza, '  - ', tipo_contrato.codigo_tipo_contrato)
                END AS label, 
                plaza.salario_base_plaza, plaza.salario_tope_plaza, linea_trabajo.id_lt")
            ->join('plaza', 'detalle_plaza.id_plaza', '=', 'plaza.id_plaza')
            ->join('tipo_contrato', 'detalle_plaza.id_tipo_contrato', '=', 'tipo_contrato.id_tipo_contrato')
            ->join('actividad_institucional', 'detalle_plaza.id_actividad_institucional', '=', 'actividad_institucional.id_actividad_institucional')
            ->join('linea_trabajo', 'actividad_institucional.id_lt', '=', 'linea_trabajo.id_lt')
            ->whereIn('detalle_plaza.id_estado_plaza', [1, 2])
            ->where('detalle_plaza.estado_det_plaza', 1)
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
        // $first_two_characters = substr($request->persona['papellido_persona'], 0, 2);
        // $last_employee_code = Empleado::where('codigo_empleado', 'like', $first_two_characters . '%')
        //     ->orderByDesc('codigo_empleado')
        //     ->first();
        // $last_correlative = $last_employee_code ? intval(substr($last_employee_code->codigo_empleado, 2)) : 0;
        // $correlative = str_pad($last_correlative + 1, 3, '0', STR_PAD_LEFT);
        // $employee_code = strtoupper($first_two_characters) . $correlative;

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

        DB::beginTransaction();
        try {
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

            //Save changes related to employee's residence
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

            //Save new employee
            $new_employee = new Empleado([
                'id_persona'                    => $request->persona['id_persona'] == '' ? $person->id_persona : $request->persona['id_persona'],
                'id_tipo_pension'               => $request->id_tipo_pension,
                'id_banco'                      => $request->id_banco,
                'id_titulo_profesional'         => $request->id_titulo_profesional,
                'codigo_empleado'               => $request->codigo_empleado,
                'nup_empleado'                  => $request->nup_empleado,
                'isss_empleado'                 => $request->isss_empleado,
                'id_estado_empleado'            => 1,
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

            //Update the attribute 'id_empleado' in 'persona'
            $person->update([
                'id_empleado'           => $new_employee->id_empleado,
                'fecha_mod_persona'     => Carbon::now(),
                'usuario_persona'       => $request->user()->nick_usuario,
                'ip_persona'            => $request->ip(),
            ]);

            //Create a new work period for the new employee
            $workPeriod = new PeriodoLaboral([
                'id_empleado'                           => $new_employee->id_empleado,
                'fecha_contratacion_periodo_laboral'    => $request->fecha_contratacion_empleado,
                'estado_periodo_laboral'                => 1,
                'fecha_reg_periodo_laboral'             => Carbon::now(),
                'usuario_periodo_laboral'               => $request->user()->nick_usuario,
                'ip_periodo_laboral'                    => $request->ip(),
            ]);
            $workPeriod->save();

            //Assign the job for the new employee
            $new_assigned_job_position = new PlazaAsignada([
                'id_empleado'                   => $new_employee->id_empleado,
                'id_lt'                         => $request->work_area_id,
                'id_dependencia'                => $request->dependency_id,
                'id_det_plaza'                  => $request->job_position_id,
                'salario_plaza_asignada'        => $request->salary,
                'partida_plaza_asignada'        => $request->account,
                'subpartida_plaza_asignada'     => $request->subaccount,
                'contrato_plaza_asignada'       => $request->contract,
                //This is the same date when the employee was hired, we don't ask for it in the form
                'fecha_plaza_asignada'          => $request->fecha_contratacion_empleado,
                'estado_plaza_asignada'         => 1,
                'fecha_reg_plaza_asignada'      => Carbon::now(),
                'usuario_plaza_asignada'        => $request->user()->nick_usuario,
                'ip_plaza_asignada'             => $request->ip(),
            ]);
            $new_assigned_job_position->save();

            //Change the state to unavailable
            $job_position_det = DetallePlaza::find($request->job_position_id);
            $job_position_det->update([
                'id_estado_plaza'           => 3, //Unavailable
                'fecha_mod_plaza_asignada'  => Carbon::now(),
                'usuario_plaza_asignada'    => $request->user()->nick_usuario,
                'ip_plaza_asignada'         => $request->ip(),
            ]);

            DB::commit(); // Confirm transactions in the database
            return response()->json([
                'mensaje'          => "Empleado guardado con éxito.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // In case of error, reverse the above operations.
            return response()->json([
                'logical_error' => 'Ha ocurrido un error con sus datos.',
                'error' => $e,
            ], 422);
        }
    }

    public function updateEmployee(EmpleadoRequest $request)
    {
        $person = Persona::find($request->persona['id_persona']);
        $residence = Residencia::find($request->persona['residencias'][0]['id_residencia']);
        $employee = Empleado::find($request->id_empleado);

        $hasChanged = ($residence->id_municipio != $request->persona['residencias'][0]['id_municipio'])
            || ($residence->direccion_residencia != $request->persona['residencias'][0]['direccion_residencia']);

        DB::beginTransaction();
        try {
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
                'codigo_empleado'               => $request->codigo_empleado,
                'cuenta_banco_empleado'         => $request->cuenta_banco_empleado,
                'email_institucional_empleado'  => $request->email_institucional_empleado,
                'email_alternativo_empleado'    => $request->email_alternativo_empleado,
                'fecha_mod_empleado'            => Carbon::now(),
                'usuario_empleado'              => $request->user()->nick_usuario,
                'ip_empleado'                   => $request->ip(),
            ]);

            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'mensaje'          => "Empleado actualizado con éxito.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // En caso de error, revierte las operaciones anteriores
            return response()->json([
                'logical_error' => 'Ha ocurrido un error con sus datos.',
                'error' => $e,
            ], 422);
        }
    }

    public function uploadEmployeePhoto(Request $request)
    {
        $files = $request->file;
        $code = $request->code;
        $id_person = $request->id_person;
        $count = 1;

        foreach ($files as $f) {
            if ($f['id'] == '') {
                $file = $f['file'];
                // Get the original file name and extension
                $extension = $file->getClientOriginalExtension();

                // Generate the date and time string
                $dateTimeString = now()->format('Ymd_His');

                // Create the unique file name by combining original file name and date-time
                $uniqueFileName = $code . '_' . $count . '_' . $dateTimeString . '.' . $extension;

                // Store the uploaded file using the unique file name
                $path = $file->storeAs('rrhh', $uniqueFileName, 'public');

                $imageUrl = Storage::url('rrhh/' . $uniqueFileName);

                $photo = new Foto([
                    'url_foto'                 => $imageUrl,
                    'id_persona'               => $id_person,
                    'estado_foto'              => 1,
                    'fecha_reg_foto'           => Carbon::now(),
                    'usuario_foto'             => $request->user()->nick_usuario,
                    'ip_foto'                  => $request->ip(),
                ]);
                $photo->save();
                $count++;
            } else {
                if ($f['id'] != '' && $f['deleted'] == 'true') {
                    $img = Foto::find($f['id']);
                    $data = [
                        'estado_foto'           => 0,
                        'fecha_mod_foto'        => Carbon::now(),
                        'usuario_foto'          => $request->user()->nick_usuario,
                        'ip_foto'               => $request->ip(),
                    ];
                    $img->update($data);
                }
            }
        }

        // Return the file path or other response as needed
        return response()->json(['message' => "Archivos subidos con éxito."]);
    }

    public function getJobPositionsByEmployee(Request $request)
    {
        //Get the job positions by employee
        $empleado = Empleado::with([
            'plazas_asignadas' => function ($query) {
                $query->with(['dependencia.parent_dependency', 'detalle_plaza', 'detalle_plaza.plaza', 'detalle_plaza.tipo_contrato', 'motivo_desvinculo_laboral']);
            }
        ])->find($request->id_empleado);
        //Get selects information
        $dependencies = DB::table('dependencia')
            ->selectRaw("
                dependencia.id_dependencia as value,
                CASE
                    WHEN dependencia.dep_id_dependencia IS NOT NULL THEN CONCAT(dep.codigo_dependencia, ' - ', dependencia.nombre_dependencia,' (',dependencia.codigo_dependencia,')')
                    ELSE CONCAT(dependencia.codigo_dependencia, ' - ', dependencia.nombre_dependencia)
                END as label
            ")
            ->leftJoin('dependencia as dep', 'dependencia.dep_id_dependencia', '=', 'dep.id_dependencia')
            ->where('dependencia.id_dependencia', '!=', 1)
            ->get();

        $jobPositionsToSelect = DetallePlaza::selectRaw("detalle_plaza.id_det_plaza as value, 
        CASE WHEN detalle_plaza.id_puesto_sirhi_det_plaza IS NULL THEN CONCAT(plaza.nombre_plaza,' - ',tipo_contrato.codigo_tipo_contrato)
        ELSE CONCAT(detalle_plaza.id_puesto_sirhi_det_plaza, '  - ', plaza.nombre_plaza, '  - ', tipo_contrato.codigo_tipo_contrato)
        END AS label, plaza.salario_base_plaza, plaza.salario_tope_plaza, linea_trabajo.id_lt")
            ->join('plaza', 'detalle_plaza.id_plaza', '=', 'plaza.id_plaza')
            ->join('tipo_contrato', 'detalle_plaza.id_tipo_contrato', '=', 'tipo_contrato.id_tipo_contrato')
            ->join('actividad_institucional', 'detalle_plaza.id_actividad_institucional', '=', 'actividad_institucional.id_actividad_institucional')
            ->join('linea_trabajo', 'actividad_institucional.id_lt', '=', 'linea_trabajo.id_lt')
            ->whereIn('detalle_plaza.id_estado_plaza', [1, 2])
            ->where('detalle_plaza.estado_det_plaza', 1)
            ->get();
        $reasonsForDissociate = MotivoDesvinculoLaboral::select('id_motivo_desvinculo_laboral as value', 'nombre_motivo_desvinculo_laboral as label')
            ->where('estado_motivo_desvinculo_laboral', 1)
            ->get();
        //We return the data to the view
        return response()->json([
            'jobPositions'          => $empleado,
            'dependencies'          => $dependencies,
            'jobPositionsToSelect'  => $jobPositionsToSelect,
            'reasonsForDissociate'  => $reasonsForDissociate
        ]);
    }
    public function storeJobPosition(PlazaAsignadaRequest $request)
    {
        $jobPosition = $request->jobPosition;

        DB::beginTransaction();
        try {
            $new_assigned_job_position = new PlazaAsignada([
                'id_empleado'                   => $request->employeeId,
                'id_lt'                         => $jobPosition['workAreaId'],
                'id_dependencia'                => $jobPosition['dependencyId'],
                'id_det_plaza'                  => $jobPosition['jobPositionId'],
                'salario_plaza_asignada'        => $jobPosition['salary'],
                'partida_plaza_asignada'        => $jobPosition['account'],
                'subpartida_plaza_asignada'     => $jobPosition['subaccount'],
                'fecha_plaza_asignada'          => $jobPosition['dateOfHired'],
                'contrato_plaza_asignada'       => $jobPosition['contrato_plaza'],
                'estado_plaza_asignada'         => 1,
                'fecha_reg_plaza_asignada'      => Carbon::now(),
                'usuario_plaza_asignada'        => $request->user()->nick_usuario,
                'ip_plaza_asignada'             => $request->ip(),
            ]);
            $new_assigned_job_position->save();

            $job_position_det = DetallePlaza::find($jobPosition['jobPositionId']);
            $job_position_det->update([
                'id_estado_plaza'           => 3,
                'fecha_mod_plaza_asignada'  => Carbon::now(),
                'usuario_plaza_asignada'    => $request->user()->nick_usuario,
                'ip_plaza_asignada'         => $request->ip(),
            ]);

            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'mensaje'          => "Puesto asignado con exito.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // En caso de error, revierte las operaciones anteriores
            return response()->json([
                'logical_error' => 'Ha ocurrido un error con sus datos.',
                'error' => $e,
            ], 422);
        }
    }
    public function updateJobPosition(PlazaAsignadaRequest $request)
    {
        $jobPosition = $request->jobPosition;
        $plazaAsignadaDB = PlazaAsignada::find($jobPosition['id']);

        DB::beginTransaction();
        try {
            if ($plazaAsignadaDB->id_det_plaza != $jobPosition['jobPositionId']) {
                $oldRole = DetallePlaza::find($plazaAsignadaDB->id_det_plaza);
                $oldRole->update([
                    'id_estado_plaza'      => 1,
                    'fecha_mod_det_plaza'  => Carbon::now(),
                    'usuario_det_plaza'    => $request->user()->nick_usuario,
                    'ip_det_plaza'         => $request->ip(),
                ]);
                $newRole = DetallePlaza::find($jobPosition['jobPositionId']);
                $newRole->update([
                    'id_estado_plaza'      => 3,
                    'fecha_mod_det_plaza'  => Carbon::now(),
                    'usuario_det_plaza'    => $request->user()->nick_usuario,
                    'ip_det_plaza'         => $request->ip(),
                ]);
            }
            $plazaAsignadaDB->update([
                'id_empleado'                   => $request->employeeId,
                'id_lt'                         => $jobPosition['workAreaId'],
                'id_dependencia'                => $jobPosition['dependencyId'],
                'id_det_plaza'                  => $jobPosition['jobPositionId'],
                'salario_plaza_asignada'        => $jobPosition['salary'],
                'partida_plaza_asignada'        => $jobPosition['account'],
                'subpartida_plaza_asignada'     => $jobPosition['subaccount'],
                'fecha_plaza_asignada'          => $jobPosition['dateOfHired'],
                'contrato_plaza_asignada'       => $jobPosition['contrato_plaza'],
                'estado_plaza_asignada'         => 1,
                'fecha_mod_plaza_asignada'      => Carbon::now(),
                'usuario_plaza_asignada'        => $request->user()->nick_usuario,
                'ip_plaza_asignada'             => $request->ip(),
            ]);

            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'mensaje'          => "Puesto actualizado con exito.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // En caso de error, revierte las operaciones anteriores
            return response()->json([
                'logical_error' => 'Ha ocurrido un error con sus datos.',
                'error' => $e,
            ], 422);
        }
    }
    public function getAvailableJobPositions(Request $request)
    {
        $jobPositionsToSelect = DetallePlaza::selectRaw("detalle_plaza.id_det_plaza as value,
        CASE WHEN detalle_plaza.id_puesto_sirhi_det_plaza IS NULL THEN CONCAT(plaza.nombre_plaza,' - ',tipo_contrato.codigo_tipo_contrato)
        ELSE CONCAT(detalle_plaza.id_puesto_sirhi_det_plaza, '  - ', plaza.nombre_plaza, '  - ', tipo_contrato.codigo_tipo_contrato)
        END AS label, plaza.salario_base_plaza, plaza.salario_tope_plaza, linea_trabajo.id_lt")
            ->join('plaza', 'detalle_plaza.id_plaza', '=', 'plaza.id_plaza')
            ->join('tipo_contrato', 'detalle_plaza.id_tipo_contrato', '=', 'tipo_contrato.id_tipo_contrato')
            ->join('actividad_institucional', 'detalle_plaza.id_actividad_institucional', '=', 'actividad_institucional.id_actividad_institucional')
            ->join('linea_trabajo', 'actividad_institucional.id_lt', '=', 'linea_trabajo.id_lt')
            ->whereIn('detalle_plaza.id_estado_plaza', [1, 2])
            ->where('detalle_plaza.estado_det_plaza', 1);

        $idsPuesto = $request->rolesExtraToInclude; //id de plazas asignadas que ya posee el empleado
        if ($request->rolesExtraToInclude != '') {
            $jobPositionsToSelect->orWhere(function ($query) use ($idsPuesto) {
                $query->where('detalle_plaza.id_estado_plaza', 3)
                    ->whereIn('detalle_plaza.id_det_plaza', $idsPuesto);
            });
        }

        $jobPositionsToSelect = $jobPositionsToSelect->get();

        return response()->json([
            'jobPositionsToSelect' => $jobPositionsToSelect,
        ]);
    }
    public function desactiveJobPosition(Request $request)
    {
        $jobPosition = $request->jobPosition;
        $plaza = PlazaAsignada::find($jobPosition['id']);
        $fechaInicio = $plaza->fecha_plaza_asignada;

        $customMessages = [
            'jobPosition.idDissociate.required' => 'Debe seleccionar el motivo.',
            'jobPosition.dateOfDissociate.required' => 'Debe agregar la fecha de finalización.',
            'jobPosition.dateOfDissociate' => 'La fecha de desvinculacion no puede ser menor que la fecha de nombramiento.'
        ];

        // Define a custom validation rule
        Validator::extend('date_after_start', function ($attribute, $value, $parameters) use ($fechaInicio) {
            $dateOfDissociate = \Carbon\Carbon::parse($value);
            return $dateOfDissociate->gte($fechaInicio);
        });

        // Validate the request data with custom error messages and custom rule
        $validatedData = Validator::make($request->all(), [
            'jobPosition.idDissociate' => 'required',
            'jobPosition.dateOfDissociate' => 'required|date|date_after_start',
        ], $customMessages)->validate();

        DB::beginTransaction();
        try {
            $plaza->update([
                'id_motivo_desvinculo_laboral'  => $jobPosition['idDissociate'],
                'fecha_renuncia_plaza_asignada' => $jobPosition['dateOfDissociate'],
                'estado_plaza_asignada'         => 0,
                'fecha_mod_plaza_asignada'      => Carbon::now(),
                'usuario_plaza_asignada'        => $request->user()->nick_usuario,
                'ip_plaza_asignada'             => $request->ip(),
            ]);

            $detPlaza = DetallePlaza::find($plaza->id_det_plaza);
            $detPlaza->update([
                'id_estado_plaza'          => 1,
                'fecha_mod_det_plaza'      => Carbon::now(),
                'usuario_det_plaza'        => $request->user()->nick_usuario,
                'ip_det_plaza'             => $request->ip(),
            ]);

            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'mensaje'          => "Puesto inhabilitado con exito.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // En caso de error, revierte las operaciones anteriores
            return response()->json([
                'logical_error' => 'Ha ocurrido un error con sus datos.',
                'error' => $e,
            ], 422);
        }
    }
    public function getDataEmpTermination()
    {
        $reasonsForDissociate = MotivoDesvinculoLaboral::select('id_motivo_desvinculo_laboral as value', 'nombre_motivo_desvinculo_laboral as label')
            ->where('estado_motivo_desvinculo_laboral', 1)
            ->get();
        return response()->json([
            'reasonsForDissociate' => $reasonsForDissociate,
        ]);
    }
    public function desactiveEmployee(Request $request)
    {
        $periodo = PeriodoLaboral::where('id_empleado', $request->id)->where('estado_periodo_laboral', 1)->first();
        if ($periodo) {
            $customMessages = [
                'idDissociate.required' => 'Debe seleccionar el motivo.',
                'dateOfDissociate.required' => 'Debe agregar la fecha de finalización.',
                'dateOfDissociate' => 'La fecha de desvinculacion no puede ser menor que la fecha de contratacion.',
                'fncCompensation'  => 'Debe seleccionar si recibio compensacion economica o no.',
                'observation'  => 'Debe escribir un comentario.'
            ];

            // Define a custom validation rule
            Validator::extend('date_after_start', function ($attribute, $value, $parameters) use ($periodo) {
                $dateOfDissociate = \Carbon\Carbon::parse($value);
                return $dateOfDissociate->gte($periodo->fecha_contratacion_periodo_laboral);
            });

            // Validate the request data with custom error messages and custom rule
            $validatedData = Validator::make($request->all(), [
                'idDissociate' => 'required',
                'dateOfDissociate' => 'required|date|date_after_start',
                'fncCompensation' => 'required',
                'observation' => 'required',
            ], $customMessages)->validate();

            $empleado = Empleado::with(['persona', 'plazas_asignadas'])->find($request->id);
            //$usuario = User::where('id_persona', $empleado->persona->id_persona)->first();
            // Cargar roles de usuario de antemano
            $usuarioConRoles = User::with('roles')->find($empleado->persona->id_usuario);

            DB::beginTransaction();
            try {
                $periodo->update([
                    'id_motivo_desvinculo_laboral'      => $request->idDissociate,
                    'fecha_desvinculo_periodo_laboral'  => $request->dateOfDissociate,
                    'compensacion_periodo_laboral'      => $request->fncCompensation,
                    'observacion_periodo_laboral'       => $request->observation,
                    'fecha_mod_periodo_laboral'         => Carbon::now(),
                    'usuario_periodo_laboral'           => $request->user()->nick_usuario,
                    'ip_periodo_laboral'                => $request->ip(),
                ]);

                foreach ($empleado->plazas_asignadas as $plaza) {
                    if ($plaza->estado_plaza_asignada == 1) {
                        $plazaToUpd = PlazaAsignada::find($plaza->id_plaza_asignada);
                        $plazaToUpd->update([
                            'id_motivo_desvinculo_laboral'      => $request->idDissociate,
                            'fecha_renuncia_plaza_asignada'     => $request->dateOfDissociate,
                            'estado_plaza_asignada'             => 0,
                            'fecha_mod_plaza_asignada'          => Carbon::now(),
                            'usuario_plaza_asignada'            => $request->user()->nick_usuario,
                            'ip_plaza_asignada'                 => $request->ip(),
                        ]);

                        $detPlazaToUpd = DetallePlaza::find($plazaToUpd->id_det_plaza);
                        $detPlazaToUpd->update([
                            'id_estado_plaza'       => 1,
                            'fecha_mod_det_plaza'   => Carbon::now(),
                            'usuario_det_plaza'     => $request->user()->nick_usuario,
                            'ip_det_plaza'          => $request->ip(),
                        ]);
                    }
                }

                if ($usuarioConRoles) {
                    PermisoUsuario::where('id_usuario', $usuarioConRoles->id_usuario)
                        ->whereIn('id_rol', $usuarioConRoles->roles->pluck('id_rol'))
                        ->update([
                            'estado_permiso_usuario'     => 0,
                            'fecha_mod_permiso_usuario'  => Carbon::now(),
                            'usuario_permiso_usuario'    => $request->user()->nick_usuario,
                            'ip_permiso_usuario'         => $request->ip(),
                        ]);
                    $usuarioConRoles->update([
                        'estado_usuario'    => 0,
                        'fecha_mod_usuario' => Carbon::now(),
                        'usuario_usuario'   => $request->user()->nick_usuario,
                        'ip_usuario'        => $request->ip(),
                    ]);
                }

                $empleado->update([
                    'id_estado_empleado'    => 2, //INACTIVO
                    'fecha_mod_empleado'    => Carbon::now(),
                    'usuario_empleado'      => $request->user()->nick_usuario,
                    'ip_empleado'           => $request->ip(),
                ]);

                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'mensaje'          => "Empleado dado de baja con exito.",
                ]);
            } catch (\Exception $e) {
                DB::rollBack(); // En caso de error, revierte las operaciones anteriores
                return response()->json([
                    'logical_error' => 'Ha ocurrido un error con sus datos.',
                    'error' => $e,
                ], 422);
            }
        } else {
            return response()->json([
                'logical_error' => 'Error, el empleado seleccionado no posee un registro de periodo laboral activo, consulte con el administrador.',
            ], 422);
        }
    }
    public function enableEmployee(EmpleadoRequest $request)
    {
        $person = Persona::find($request->persona['id_persona']);
        $residence = Residencia::find($request->persona['residencias'][0]['id_residencia']);
        $employee = Empleado::find($request->id_empleado);

        $hasChanged = ($residence->id_municipio != $request->persona['residencias'][0]['id_municipio'])
            || ($residence->direccion_residencia != $request->persona['residencias'][0]['direccion_residencia']);

        DB::beginTransaction();
        try {
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
                'fecha_contratacion_empleado'   => $request->fecha_contratacion_empleado,
                'codigo_empleado'               => $request->codigo_empleado,
                'id_estado_empleado'            => 1,
                'cuenta_banco_empleado'         => $request->cuenta_banco_empleado,
                'email_institucional_empleado'  => $request->email_institucional_empleado,
                'email_alternativo_empleado'    => $request->email_alternativo_empleado,
                'fecha_mod_empleado'            => Carbon::now(),
                'usuario_empleado'              => $request->user()->nick_usuario,
                'ip_empleado'                   => $request->ip(),
            ]);

            //Assign the job for the new employee
            $new_assigned_job_position = new PlazaAsignada([
                'id_empleado'                   => $employee->id_empleado,
                'id_lt'                         => $request->work_area_id,
                'id_dependencia'                => $request->dependency_id,
                'id_det_plaza'                  => $request->job_position_id,
                'salario_plaza_asignada'        => $request->salary,
                'partida_plaza_asignada'        => $request->account,
                'subpartida_plaza_asignada'     => $request->subaccount,
                'contrato_plaza_asignada'       => $request->contract,
                //This is the same date when the employee was hired, we don't ask for it in the form
                'fecha_plaza_asignada'          => $request->fecha_contratacion_empleado,
                'estado_plaza_asignada'         => 1,
                'fecha_reg_plaza_asignada'      => Carbon::now(),
                'usuario_plaza_asignada'        => $request->user()->nick_usuario,
                'ip_plaza_asignada'             => $request->ip(),
            ]);
            $new_assigned_job_position->save();

            PeriodoLaboral::where('id_empleado', $employee->id_empleado)->where('estado_periodo_laboral', 1)
                ->update([
                    'estado_periodo_laboral'     => 0,
                    'fecha_mod_periodo_laboral'  => Carbon::now(),
                    'usuario_periodo_laboral'    => $request->user()->nick_usuario,
                    'ip_periodo_laboral'         => $request->ip(),
                ]);

            //Create a new work period for the new employee
            $workPeriod = new PeriodoLaboral([
                'id_empleado'                           => $employee->id_empleado,
                'fecha_contratacion_periodo_laboral'    => $request->fecha_contratacion_empleado,
                'estado_periodo_laboral'                => 1,
                'fecha_reg_periodo_laboral'             => Carbon::now(),
                'usuario_periodo_laboral'               => $request->user()->nick_usuario,
                'ip_periodo_laboral'                    => $request->ip(),
            ]);
            $workPeriod->save();

            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'mensaje'          => "Empleado actualizado con éxito.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // En caso de error, revierte las operaciones anteriores
            return response()->json([
                'logical_error' => 'Ha ocurrido un error con sus datos.',
                'error' => $e,
            ], 422);
        }
    }
}
