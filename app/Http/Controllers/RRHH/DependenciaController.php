<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Http\Requests\RRHH\DependenciaRequest;
use App\Models\CentroAtencion;
use App\Models\Dependencia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DependenciaController extends Controller
{
    public function getDependencias(Request $request)
    {
        $columns = ['id_dependencia', 'nombre_dependencia', 'codigo_dependencia', 'jefatura', 'estado_dependencia'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = Dependencia::select('*')
            ->with([
                'jefatura'
            ])
            ->where('id_dependencia', '<>', 1);

        if ($column == 3) {
            $query->orderByRaw('(SELECT pnombre_persona FROM persona WHERE persona.id_persona = dependencia.id_persona) ' . $dir);
        } else {
            $query->orderBy($columns[$column], $dir);
        }

        if ($search_value) {
            $query->where('id_dependencia', 'like', '%' . $search_value['id_dependencia'] . '%')
                ->where('nombre_dependencia', 'like', '%' . $search_value["nombre_dependencia"] . '%')
                ->where('codigo_dependencia', 'like', '%' . $search_value["codigo_dependencia"] . '%')
                ->where('estado_dependencia', 'like', '%' . $search_value["estado_dependencia"] . '%')
                ->where(function ($query) use ($search_value) {
                    if ($search_value["jefatura"]) {
                        if ($search_value['jefatura'] == 'N/Asign.' || $search_value['jefatura'] == 'N/A') {
                            $query->where('id_persona', null);
                        } else {
                            $query->whereHas('jefatura', function ($query) use ($search_value) {
                                $query->whereRaw("MATCH(pnombre_persona, snombre_persona, tnombre_persona, papellido_persona, sapellido_persona, tapellido_persona) AGAINST(?)", $search_value["jefatura"]);
                            });
                        }
                    }
                });
        }

        $dependencies = $query->paginate($length)->onEachSide(1);
        return ['data' => $dependencies, 'draw' => $request->input('draw')];
    }

    public function getInfoModalDependencias(Request $request, $id)
    {
        $dependency =  Dependencia::with([
            'jefatura',
            'dependencias_inferiores' => function ($query) {
                $query->withCount(['plazas_asignadas as count_plazas' => function ($q) {
                    $q->where('estado_plaza_asignada', 1);
                }]);
            },
            'dependencias_inferiores.jefatura',
            'dependencia_superior.jefatura',
            'centro_atencion'
        ])
            ->find($id);

        $dependencies = Dependencia::selectRaw("id_dependencia as value, concat(nombre_dependencia,' (',codigo_dependencia,')') as label, id_centro_atencion")
        ->where('estado_dependencia', 1)->get();
        $mainCenters = CentroAtencion::selectRaw("id_centro_atencion as value, concat(nombre_centro_atencion,' (',codigo_centro_atencion,' )') as label")
        ->where('estado_centro_atencion', 1)->get();

        return response()->json([
            'dependency'      => $dependency ?? [],
            'dependencies'    => $dependencies ?? [],
            'mainCenters'     => $mainCenters ?? []
        ]);
    }


    public function searchEmployee(Request $request)
    {
        $search = $request->busqueda;
        if ($search != '') {
            $empleados = DB::table('empleado as e')
                ->selectRaw('
                    CONCAT_WS(" ", 
                    COALESCE(p.pnombre_persona, ""), 
                    COALESCE(p.snombre_persona, ""), 
                    COALESCE(p.tnombre_persona, ""), 
                    COALESCE(p.papellido_persona, ""), 
                    COALESCE(p.sapellido_persona, ""), 
                    COALESCE(p.tapellido_persona, "")
                ) AS label,
                e.id_empleado as value')
                ->join('persona as p', 'e.id_persona', '=', 'p.id_persona')
                ->where(function ($query) use ($search) {
                    $query->whereRaw("MATCH(p.pnombre_persona, p.snombre_persona, p.tnombre_persona, p.papellido_persona, p.sapellido_persona, p.tapellido_persona) AGAINST(?)", $search);
                })
                ->where('e.id_estado_empleado', 1)
                ->get();
        }
        /* The above code is returning a JSON response in PHP. It includes an array called 'employees'
        which contains the value of the variable  if the variable  is not empty. If
         is empty, the 'employees' array will be empty as well. */
        return response()->json(
            [
                'employees'          => $search != '' ? $empleados : [],
            ]
        );
    }

    public function storeDependency(DependenciaRequest $request)
    {
        DB::beginTransaction();
        try {
            $dependency = new Dependencia([
                'id_centro_atencion'                        => $request->centerId,
                'id_tipo_dependencia'                       => 2,
                'id_persona'                                => $request->personId,
                'jerarquia_organizacion_dependencia'        => $request->parentId,
                'nombre_dependencia'                        => $request->depName,
                'codigo_dependencia'                        => $request->code,
                'telefono_dependencia'                      => $request->phoneNumber,
                'email_dependencia'                         => $request->email,
                'direccion_dependencia'                     => $request->address,
                'estado_dependencia'                        => 1,
                'fecha_reg_dependencia'                     => Carbon::now(),
                'usuario_dependencia'                       => $request->user()->nick_usuario,
                'ip_dependencia'                            => $request->ip(),
            ]);
            $dependency->save();

            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'message'          => "Dependencia creada con éxito.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // En caso de error, revierte las operaciones anteriores
            return response()->json([
                'logical_error' => 'Ha ocurrido un error con sus datos.',
                'error' => $e,
            ], 422);
        }
    }

    public function updateDependency(DependenciaRequest $request)
    {
        $dependency = Dependencia::find($request->id);

        DB::beginTransaction();
        try {
            $dependency->update([
                'id_centro_atencion'                        => $request->centerId,
                //'id_tipo_dependencia'                       => 2, we don't manage the dependency type
                'id_persona'                                => $request->personId,
                'jerarquia_organizacion_dependencia'        => $request->parentId,
                'nombre_dependencia'                        => $request->depName,
                'codigo_dependencia'                        => $request->code,
                'telefono_dependencia'                      => $request->phoneNumber,
                'email_dependencia'                         => $request->email,
                'direccion_dependencia'                     => $request->address,
                'fecha_mod_dependencia'                     => Carbon::now(),
                'usuario_dependencia'                       => $request->user()->nick_usuario,
                'ip_dependencia'                            => $request->ip(),
            ]);

            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'message'          => "Dependencia actualizada con éxito.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // En caso de error, revierte las operaciones anteriores
            return response()->json([
                'logical_error' => 'Ha ocurrido un error con sus datos.',
                'error' => $e,
            ], 422);
        }
    }

    public function changeStatusDependency(Request $request)
    {
        $dependency = Dependencia::find($request->id);
        if ($dependency && $dependency->estado_dependencia != $request->status) {
            return response()->json(['logical_error' => 'Error, otro usuario ha modificado la dependencia.',], 422);
        } else {
            DB::beginTransaction();
            try {
                if ($dependency->estado_dependencia == 1) {
                    if ($dependency->jerarquia_organizacion_dependencia == null) {
                        return response()->json(['logical_error' => 'Error, no puedes desactivar una dependencia sin jerarquia superior.',], 422);
                    } else {
                        if ($dependency->plazas_asignadas()->where('estado_plaza_asignada', 1)->exists()) {
                            return response()->json(['logical_error' => 'Error, esta dependencia tiene personal asignado, reasigna primero a todo el personal e intenta nuevamente.',], 422);
                        } else {
                            if ($dependency->dependencias_inferiores()->where('estado_dependencia', 1)->exists()) {
                                $parentDep = Dependencia::find($dependency->jerarquia_organizacion_dependencia);
                                if ($parentDep) {
                                    foreach ($dependency->dependencias_inferiores as $dep) {
                                        if ($dep->estado_dependencia == 1) {
                                            $dep->update([
                                                'jerarquia_organizacion_dependencia'        => $parentDep->id_dependencia,
                                                'fecha_mod_dependencia'                     => Carbon::now(),
                                                'usuario_dependencia'                       => $request->user()->nick_usuario,
                                                'ip_dependencia'                            => $request->ip(),
                                            ]);
                                        }
                                    }
                                }
                                $dependency->update([
                                    'estado_dependencia'                        => 0,
                                    'fecha_mod_dependencia'                     => Carbon::now(),
                                    'usuario_dependencia'                       => $request->user()->nick_usuario,
                                    'ip_dependencia'                            => $request->ip(),
                                ]);
                            } else {
                                $dependency->update([
                                    'estado_dependencia'                        => 0,
                                    'fecha_mod_dependencia'                     => Carbon::now(),
                                    'usuario_dependencia'                       => $request->user()->nick_usuario,
                                    'ip_dependencia'                            => $request->ip(),
                                ]);
                            }
                        }
                    }
                } else {
                    $dependency->update([
                        'estado_dependencia'                        => 1,
                        'fecha_mod_dependencia'                     => Carbon::now(),
                        'usuario_dependencia'                       => $request->user()->nick_usuario,
                        'ip_dependencia'                            => $request->ip(),
                    ]);
                }
                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'message'          => "Acción ejecutada con éxito.",
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

    public function changeDepBoss(Request $request){
        $customMessages = [
            'personId.required' => 'Debe seleccionar empleado a cargo.',
        ];

        // Validate the request data with custom error messages and custom rule
        $validatedData = Validator::make($request->all(), [
            'personId' => 'required',
        ], $customMessages)->validate();

        $dependency = Dependencia::find($request->id);
        DB::beginTransaction();
        try {
            $dependency->update([
                'id_persona'                                => $request->personId,
                'fecha_mod_dependencia'                     => Carbon::now(),
                'usuario_dependencia'                       => $request->user()->nick_usuario,
                'ip_dependencia'                            => $request->ip(),
            ]);

            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'message'          => "Dependencia actualizada con éxito.",
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
