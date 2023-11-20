<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;
use App\Models\Sistema;
use App\Models\PermisoUsuario;
use App\Models\Persona;
use App\Http\Controllers\Controller;
use App\Models\Empleado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {
        $columns = [
            'usuario.id_usuario', 'pnombre_persona',
            'dui_persona', 'nick_usuario', 'estado_usuario'
        ];

        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $search_value = $request->input('search');

        $query = User::select(
            'usuario.id_usuario',
            'persona.pnombre_persona',
            'persona.snombre_persona',
            'persona.tnombre_persona',
            'persona.papellido_persona',
            'persona.sapellido_persona',
            'persona.tapellido_persona',
            'usuario.estado_usuario',
            'persona.dui_persona',
            'usuario.nick_usuario',
            'persona.id_persona',
            'persona.fecha_nac_persona',
            'municipio.nombre_municipio',
            'persona.telefono_persona'
        )
            ->with('roles')
            ->with('persona.fotos')
            ->with('persona.empleado')
            ->join('persona', function ($join) {
                $join->on('usuario.id_persona', '=', 'persona.id_persona');
            })
            ->join('municipio', function ($join) {
                $join->on('persona.id_municipio', '=', 'municipio.id_municipio');
            })
            ->orderBy($columns[$column], $dir);

        if ($search_value) {
            $query->where([
                ['usuario.id_usuario', 'like', '%' . $search_value['id_usuario'] . '%'],
                ['dui_persona', 'like', '%' . $search_value['dui_persona'] . '%'],
                ['nick_usuario', 'like', '%' . $search_value['nick_usuario'] . '%'],
                ['estado_usuario', 'like', '%' . $search_value['estado_usuario'] . '%'],
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

        $users = $query->paginate($length)->onEachSide(1);
        return ['data' => $users, 'draw' => $request->input('draw')];
    }

    public function changeStateUser(Request $request)
    {
        $user = User::find($request->id_usuario);
        if ($user->estado_usuario == 1) {
            if ($request->estado_usuario == 1) {
                $user->update([
                    'estado_usuario' => 0,
                    'fecha_mod_usuario' => Carbon::now(),
                    'usuario_usuario' => $request->user()->nick_usuario,
                    'ip_usuario' => $request->ip(),
                ]);
                return ['mensaje' => 'Usuario ' . $user->nick_usuario . ' ha sido desactivado con exito.'];
            } else {
                return ['mensaje' => 'El usuario seleccionado ya ha sido activado por otro administrador.'];
            }
        } else {
            if ($user->estado_usuario == 0) {
                if ($request->estado_usuario == 0) {
                    $user->update([
                        'estado_usuario' => 1,
                        'fecha_mod_usuario' => Carbon::now(),
                        'usuario_usuario' => $request->user()->nick_usuario,
                        'ip_usuario' => $request->ip(),
                    ]);
                    return ['mensaje' => 'Usuario ' . $user->nick_usuario . ' ha sido activado con exito.'];
                } else {
                    return ['mensaje' => 'El usuario seleccionado ya ha sido desactivado por otro administrador.'];
                }
            }
        }
    }
    public function getRolesPerSystem(Request $request)
    {
        $id_sistema = $request->input('id_sistema');
        $sistema = Sistema::find($id_sistema);
        foreach ($sistema->roles as $rol) {
            if ($rol->estado_rol == 1) {
                $array_rol['value'] = $rol->id_rol;
                $array_rol['label'] = $rol->nombre_rol;
                $array_roles[] = $array_rol;
            }
        }
        return ['roles' => $array_roles];
    }
    //Methods to create a new user
    public function getDui(Request $request)
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
            $person = Persona::with([
                'usuario',
                'fotos',
                'usuario.roles.sistema',
                'residencias.municipio',
                'residencias'  => function ($query) {
                    $query->where('estado_residencia', 1);
                }
            ])
                ->where('dui_persona', '=', $request->input('dui'))
                ->first();
            return ['persona' => $person];
        } else {
            return response()->json([
                'logical_error' =>
                'Error, el numero de dui no existe.'
            ], 422);
        }
    }
    public function saveUser(Request $request)
    {
        $person = Persona::find($request->person_id);
        $first_name = $person->pnombre_persona;
        $last_name = $person->papellido_persona;

        $username = strtolower($first_name . '.' . $last_name);

        $count = 2;
        while (User::where('nick_usuario', $username)->exists()) {
            // If the username already exists, append a number to make it unique
            $username = strtolower($first_name . '.' . $last_name . $count);
            $count++;
        }

        $empleado = Empleado::where('id_persona', $person->id_persona)->first();
        if ($empleado) {
            $username = $empleado->codigo_empleado;
        }

        DB::beginTransaction();
        try {
            $new_user = new User([
                'id_persona' => $request->person_id,
                'nick_usuario' => $username,
                'password_usuario' => Hash::make($request->password),
                'estado_usuario' => 1,
                'fecha_reg_usuario' => Carbon::now(),
                'usuario_usuario' => $request->user()->nick_usuario,
                'ip_usuario' => $request->ip(),
            ]);
            $new_user->save();

            $person->update([
                'id_usuario' => $new_user->id_usuario,
                'fecha_mod_persona' => Carbon::now(),
                'usuario_persona' => $request->user()->nick_usuario,
                'ip_persona' => $request->ip(),
            ]);

            foreach ($request->roles as $rol) {
                $new_user_permission = new PermisoUsuario([
                    'id_rol' => $rol['id_rol'],
                    'id_usuario' => $new_user->id_usuario,
                    'estado_permiso_usuario' => 1,
                    'fecha_reg_permiso_usuario' => Carbon::now(),
                    'usuario_permiso_usuario' => $request->user()->nick_usuario,
                    'ip_permiso_usuario' => $request->ip(),
                ]);
                $new_user_permission->save();
            }

            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'mensaje'          => "Guardado usuario ' . $new_user->nick_usuario . ' con exito.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // En caso de error, revierte las operaciones anteriores
            return response()->json([
                'logical_error' => 'Ha ocurrido un error con sus datos.',
                'error' => $e,
            ], 422);
        }
    }

    public function changePasswordUser(Request $request)
    {
        $user = User::find($request->id_usuario);
        $user->password_usuario = Hash::make($request->password);
        $user->ip_usuario = $request->ip();
        $user->fecha_mod_usuario = Carbon::now();
        $user->usuario_usuario = $request->user()->nick_usuario;
        $user->update();
        return ['mensaje' => 'Contraseña actualizada con exito'];
    }

    //NEW METHODS
    public function standarizeUsername(Request $request)
    {
        $user = User::with('persona.empleado')->find($request->id);
        DB::beginTransaction();
        try {
            $user->update([
                'nick_usuario'          => $user->persona->empleado->codigo_empleado,
                'fecha_mod_usuario'     => Carbon::now(),
                'usuario_usuario'       => $request->user()->nick_usuario,
                'ip_usuario'            => $request->ip(),
            ]);
            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'mensaje'          => "Accion exitosa, nuevo nombre de usuario: " . $user->nick_usuario,
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // En caso de error, revierte las operaciones anteriores
            return response()->json([
                'logical_error' => 'Ha ocurrido un error con sus datos.',
                'error' => $e,
            ], 422);
        }
    }
    public function getSelectsAdminUser()
    {
        $systems = Sistema::select('id_sistema as value', 'nombre_sistema as label')
            ->where('estado_sistema', '=', 1)
            ->has('roles')
            ->orderBy('nombre_sistema')
            ->get();
        $roles = Rol::select('id_rol as value', 'nombre_rol as label', 'id_sistema')
            ->where('estado_rol', '=', 1)
            ->has('menus')
            ->orderBy('nombre_rol')
            ->get();
        return [
            'systems' => $systems,
            'roles'   => $roles
        ];
    }
    public function getUserInfo($id)
    {
        $allUserInfo = Persona::with([
            'usuario',
            'fotos',
            'usuario.roles.sistema',
            'residencias.municipio',
            'residencias'  => function ($query) {
                $query->where('estado_residencia', 1);
            }
        ])
            ->where('id_usuario', $id)
            ->first();

        $result = $this->getSelectsAdminUser();

        return [
            'allUserInfo' => $allUserInfo,
            'systems'     => $result['systems'],
            'roles'       => $result['roles'],
        ];
    }

    public function saveChangesAdminUser(Request $request)
    {
        $userId = $request->userId;
        DB::beginTransaction();
        try {
            if ($userId) {
                $usuario = User::find($request->userId);
                foreach ($request->roles as $rol) {
                    $permisoUserToValidate = PermisoUsuario::with([
                        'rol' => function ($query) use ($rol) {
                            $query->where('id_sistema', $rol['systemId']);
                        }
                    ])
                        ->where('estado_permiso_usuario', 1)
                        ->first();
                    if (!$permisoUserToValidate->rol) {
                        $userPermission = PermisoUsuario::find($rol['accessId']);
                        if ($userPermission) {
                            $userPermission->update([
                                'id_rol'                        => $rol['rolId'],
                                'estado_permiso_usuario'        => $rol['deletedVw'] ? 0 : 1,
                                'fecha_mod_permiso_usuario'     => Carbon::now(),
                                'usuario_permiso_usuario'       => $request->user()->nick_usuario,
                                'ip_permiso_usuario'            => $request->ip(),
                            ]);
                        } else {
                            $newUserPermission = new PermisoUsuario([
                                'id_rol'                        => $rol['rolId'],
                                'id_usuario'                    => $userId,
                                'estado_permiso_usuario'        => 1,
                                'fecha_reg_permiso_usuario'     => Carbon::now(),
                                'usuario_permiso_usuario'       => $request->user()->nick_usuario,
                                'ip_permiso_usuario'            => $request->ip(),
                            ]);
                            $newUserPermission->save();
                        }
                        $usuario->update([
                            'fecha_mod_usuario'     => Carbon::now(),
                            'usuario_usuario'       => $request->user()->nick_usuario,
                            'ip_permiso_usuario'    => $request->ip(),
                        ]);
                    }
                }
            } else {
            }
            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'message'          => "Roles actualizados.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // En caso de error, revierte las operaciones anteriores
            return response()->json([
                'logical_error' => 'Ha ocurrido un error con sus datos.',
                'error' => $e,
            ], 422);
        }
    }
    public function saveNewUser(Request $request){
        $customMessages = [
            'password.required' => 'Debe digitar la contraseña.',
        ];
        // Validate the request data with custom error messages
        $validatedData = Validator::make($request->all(), [
            'password' => 'required',
        ], $customMessages)->validate();

        $person = Persona::find($request->personId);
        $first_name = $person->pnombre_persona;
        $last_name = $person->papellido_persona;

        $username = strtolower($first_name . '.' . $last_name);

        $count = 2;
        while (User::where('nick_usuario', $username)->exists()) {
            // If the username already exists, append a number to make it unique
            $username = strtolower($first_name . '.' . $last_name . $count);
            $count++;
        }

        $empleado = Empleado::where('id_persona', $person->id_persona)->first();
        if ($empleado) {
            $username = $empleado->codigo_empleado;
        }

        DB::beginTransaction();
        try {
            $newUser = new User([
                'id_persona'                        => $request->personId,
                'nick_usuario'                      => $username,
                'password_usuario'                  => Hash::make($request->password),
                'estado_usuario'                    => 1,
                'fecha_reg_usuario'                 => Carbon::now(),
                'usuario_usuario'                   => $request->user()->nick_usuario,
                'ip_usuario'                        => $request->ip(),
            ]);
            $newUser->save();

            $person->update([
                'id_usuario'                        => $newUser->id_usuario,
                'fecha_mod_persona'                 => Carbon::now(),
                'usuario_persona'                   => $request->user()->nick_usuario,
                'ip_persona'                        => $request->ip(),
            ]);

            foreach ($request->userRoles as $rol) {
                $newUserPermission = new PermisoUsuario([
                    'id_rol'                        => $rol['rolId'],
                    'id_usuario'                    => $newUser->id_usuario,
                    'estado_permiso_usuario'        => 1,
                    'fecha_reg_permiso_usuario'     => Carbon::now(),
                    'usuario_permiso_usuario'       => $request->user()->nick_usuario,
                    'ip_permiso_usuario'            => $request->ip(),
                ]);
                $newUserPermission->save();
            }

            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'message'          => "Guardado usuario " . $newUser->nick_usuario . " con exito.",
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
