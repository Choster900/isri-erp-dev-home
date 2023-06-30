<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;
use App\Models\Sistema;
use App\Models\PermisoUsuario;
use App\Models\Persona;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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

    //New methods
    public function getSelectsCreateUser()
    {
        $systems = Sistema::select('id_sistema as value', 'nombre_sistema as label')
            ->where('estado_sistema', '=', 1)
            ->has('roles')
            ->orderBy('nombre_sistema')
            ->get();
        return ['systems' => $systems];
    }

    public function getRolesUser(Request $request)
    {
        $user = User::find($request->id_usuario);
        return ['roles' => $user->roles->load('sistema')];
    }
    //until here


    public function getSystems(Request $request)
    {
        $id = $request->input('id_usuario');
        $user = User::find($id);
        $sistemas = Sistema::get();
        foreach ($user->roles as $rol) {
            if ($rol->pivot->estado_permiso_usuario == 1) {
                $array_id_sistema[] = $rol->sistema->id_sistema;
            }
        }
        if (isset($array_id_sistema)) {
            foreach ($sistemas as $sistema) {
                if (!(in_array($sistema->id_sistema, $array_id_sistema))) {
                    $array_sistemas['value'] = $sistema->id_sistema;
                    $array_sistemas['label'] = $sistema->nombre_sistema;
                    $array_sis[] = $array_sistemas;
                }
            }
            if (!(isset($array_sis))) {
                $array_sis = [];
            }
        } else {
            foreach ($sistemas as $sistema) {
                $array_sistemas['value'] = $sistema->id_sistema;
                $array_sistemas['label'] = $sistema->nombre_sistema;
                $array_sis[] = $array_sistemas;
            }
        }
        return ['userRoles' => $user->roles->load('sistema'), 'sistemas' => $array_sis, 'nombre_usuario' => $user->nick_usuario];
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

    public function saveRol(Request $request)
    {
        $id_usuario = $request->input('id_usuario');
        $id_rol = $request->input('id_rol');
        $rol = Rol::find($id_rol);
        $permiso_user = PermisoUsuario::where('id_usuario', '=', $id_usuario)->where('id_rol', '=', $id_rol)->first();
        if ($permiso_user) {
            $permiso_user->estado_permiso_usuario = 1;
            $permiso_user->ip_permiso_usuario = $request->ip();
            $permiso_user->fecha_mod_permiso_usuario = Carbon::now();
            $permiso_user->usuario_permiso_usuario = $request->user()->nick_usuario;
            $permiso_user->update();
        } else {
            $new_permiso_user = new PermisoUsuario();
            $new_permiso_user->id_rol = $id_rol;
            $new_permiso_user->id_usuario = $id_usuario;
            $new_permiso_user->estado_permiso_usuario = 1;
            $new_permiso_user->ip_permiso_usuario = $request->ip();
            $new_permiso_user->fecha_reg_permiso_usuario = Carbon::now();
            $new_permiso_user->usuario_permiso_usuario = $request->user()->nick_usuario;
            $new_permiso_user->save();
        }
        return ['mensaje' => 'Guardado rol ' . $rol->nombre_rol . ' con exito'];
    }
    public function desactiveRol(Request $request)
    {
        $id_usuario = $request->input('id_usuario');
        $id_rol = $request->input('id_rol');
        $rol = Rol::find($id_rol);
        $permiso_user = PermisoUsuario::where('id_usuario', '=', $id_usuario)->where('id_rol', '=', $id_rol)->first();
        $permiso_user->estado_permiso_usuario = 0;
        $permiso_user->ip_permiso_usuario = $request->ip();
        $permiso_user->fecha_mod_permiso_usuario = Carbon::now();
        $permiso_user->usuario_permiso_usuario = $request->user()->nick_usuario;
        $permiso_user->update();
        return ['mensaje' => 'Desactivado rol ' . $rol->nombre_rol . ' con exito'];
    }
    public function getRolesPerSystemEdit(Request $request)
    {
        $id_usuario = $request->input('id_usuario');
        $id_rol = $request->input('id_rol_edit');
        $id_sistema = $request->input('id_sistema_edit');
        $permiso_user = PermisoUsuario::where('id_usuario', '=', $id_usuario)->where('id_rol', '=', $id_rol)->first();
        $sistema = Sistema::find($id_sistema);
        foreach ($sistema->roles as $rol) {
            if ($rol->estado_rol == 1) {
                $array_sistemas['value'] = $rol->id_rol;
                $array_sistemas['label'] = $rol->nombre_rol;
                $array_sis[] = $array_sistemas;
            }
        }
        return ['roles' => $array_sis, 'permiso_usuario' => $permiso_user->id_permiso_usuario];
    }
    public function updateRol(Request $request)
    {
        $id_permiso_usuario = $request->input('permiso_usuario');
        $id_rol = $request->input('id_rol');
        $rol = Rol::find($id_rol);
        $permiso_user = PermisoUsuario::find($id_permiso_usuario);
        $permiso_user->id_rol = $id_rol;
        $permiso_user->ip_permiso_usuario = $request->ip();
        $permiso_user->fecha_mod_permiso_usuario = Carbon::now();
        $permiso_user->usuario_permiso_usuario = $request->user()->nick_usuario;
        $permiso_user->update();
        return ['mensaje' => 'Nuevo rol asignado ' . $rol->nombre_rol . ' con exito'];
    }

    //Methods to create a new user
    public function getDui(Request $request)
    {
        $person = Persona::select(DB::raw('CONCAT(pnombre_persona," ",IFNULL(snombre_persona,"")," ",IFNULL(tnombre_persona,"")," ",papellido_persona," ",IFNULL(sapellido_persona,"")," ",IFNULL(tapellido_persona,"")) AS nombre_persona'), 'id_persona', 'fecha_nac_persona', 'telefono_persona', 'nombre_municipio')
            ->join('municipio', function ($join) {
                $join->on('persona.id_municipio', '=', 'municipio.id_municipio');
            })
            ->where('dui_persona', '=', $request->input('dui'))
            ->first();
        if ($person) {
            $user = User::where('id_persona', '=', $person->id_persona)->first();
            if (!$user) {
                $user = '';
            }
        } else {
            $user = '';
        }
        return ['persona' => $person ? $person : '', 'usuario' => $user];
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
        return ['mensaje' => 'Guardado usuario ' . $new_user->nick_usuario . ' con exito.'];
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
}
