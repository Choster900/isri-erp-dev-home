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
            $columns = ['id_usuario', 'nombre_persona','pnombre_persona','snombre_persona',
                        'tnombre_persona','papellido_persona','sapellido_persona','tapellido_persona',
                        'dui_persona','nick_usuario', 'estado_usuario'
                        ];

            $length = $request->input('length');
            $column = $request->input('column'); //Index
            $dir = $request->input('dir');
            $search_value = $request->input('search');

            $query = User::select('usuario.id_usuario AS id_usuario', 'persona.pnombre_persona AS pnombre_persona','persona.snombre_persona AS snombre_persona','persona.tnombre_persona AS tnombre_persona','persona.papellido_persona AS papellido_persona','persona.sapellido_persona AS sapellido_persona','persona.tapellido_persona AS tapellido_persona', 'persona.dui_persona AS dui_persona' , 'usuario.nick_usuario AS nick_usuario' , 'usuario.estado_usuario AS estado_usuario')
                           ->join('persona', function ($join) {
                                    $join->on('usuario.id_persona', '=', 'persona.id_persona');
                                })
                           ->orderBy($columns[$column], $dir);

            if ($search_value) {
                $query->where(function ($query) use ($search_value) {
                    $query->where('usuario.id_usuario', 'like', '%' . $search_value . '%')
                        ->orWhere('nick_usuario', 'like'. '%' . $search_value . '%')
                        ->orWhere('dui_persona', 'like', '%' . $search_value . '%')
                        ->orwhere('pnombre_persona', 'like','%' . $search_value . '%')
                        ->orwhere('snombre_persona', 'like','%' . $search_value . '%')
                        ->orwhere('tnombre_persona', 'like','%' . $search_value . '%')
                        ->orwhere('papellido_persona', 'like','%' . $search_value . '%')
                        ->orwhere('sapellido_persona', 'like','%' . $search_value . '%')
                        ->orwhere('tapellido_persona', 'like','%' . $search_value . '%');
                });
            }

            $users = $query->paginate($length)->onEachSide(1);
            return ['data' => $users, 'draw' => $request->input('draw')];
    }

    public function changeStateUser(Request $request){
        $id_user = $request->input('id_usuario');
        $previous_state = $request->input('estado_usuario');
        $msg="";
        $user = User::find($id_user);
        $previous_state==1 ? $user->estado_usuario=0 : $user->estado_usuario=1;
        $previous_state==1 ? $msg="Desactivado" : $msg="Activado";
        $user->ip_usuario=$request->ip();
        $user->fecha_mod_usuario=Carbon::now();
        $user->usuario_usuario=$request->user()->nick_usuario;
        $user->update();
        return ['message' => $msg.' usuario '.$user->nick_usuario.' con exito'];
    }

    public function getSystems(Request $request){
            $id = $request->input('id_usuario');
            $user = User::find($id);
            $sistemas=Sistema::get();
            foreach($user->roles as $rol){
                if($rol->pivot->estado_permiso_usuario==1){
                    $array_id_sistema[]=$rol->sistema->id_sistema;
                }
            }
            if(isset($array_id_sistema)){
                foreach($sistemas as $sistema){
                    if(!(in_array($sistema->id_sistema,$array_id_sistema))){
                        $array_sistemas['value']=$sistema->id_sistema;
                        $array_sistemas['label']=$sistema->nombre_sistema;
                        $array_sis[]=$array_sistemas;
                    }
                }
                if(!(isset($array_sis))){
                    $array_sis=[];
                }
            }else{
                foreach($sistemas as $sistema){
                    $array_sistemas['value']=$sistema->id_sistema;
                    $array_sistemas['label']=$sistema->nombre_sistema;
                    $array_sis[]=$array_sistemas;
                }
            }
            return ['userRoles' => $user->roles->load('sistema'), 'sistemas' => $array_sis, 'nombre_usuario' => $user->nick_usuario];
    }
    public function getRolesPerSystem(Request $request){
            $id_sistema = $request->input('id_sistema');
            $sistema = Sistema::find($id_sistema);
            foreach($sistema->roles as $rol){
                if($rol->estado_rol==1){
                    $array_rol['value']=$rol->id_rol;
                    $array_rol['label']=$rol->nombre_rol;
                    $array_roles[]=$array_rol;
                }
            }
            return ['roles' => $array_roles];
    }

    public function saveRol(Request $request){
            $id_usuario = $request->input('id_usuario');
            $id_rol = $request->input('id_rol');
            $rol = Rol::find($id_rol);
            $permiso_user=PermisoUsuario::where('id_usuario','=',$id_usuario)->where('id_rol','=',$id_rol)->first();
            if($permiso_user){
                $permiso_user->estado_permiso_usuario=1;
                $permiso_user->ip_permiso_usuario=$request->ip();
                $permiso_user->fecha_mod_permiso_usuario=Carbon::now();
                $permiso_user->usuario_permiso_usuario=$request->user()->nick_usuario;
                $permiso_user->update();
            }else{
                $new_permiso_user = new PermisoUsuario();
                $new_permiso_user->id_rol=$id_rol;
                $new_permiso_user->id_usuario=$id_usuario;
                $new_permiso_user->estado_permiso_usuario=1;
                $new_permiso_user->ip_permiso_usuario=$request->ip();
                $new_permiso_user->fecha_reg_permiso_usuario=Carbon::now();
                $new_permiso_user->usuario_permiso_usuario=$request->user()->nick_usuario;
                $new_permiso_user->save();
            }
            return ['mensaje' => 'Guardado rol '.$rol->nombre_rol.' con exito'];
    }
    public function desactiveRol(Request $request){
            $id_usuario = $request->input('id_usuario');
            $id_rol = $request->input('id_rol');
            $rol = Rol::find($id_rol);
            $permiso_user=PermisoUsuario::where('id_usuario','=',$id_usuario)->where('id_rol','=',$id_rol)->first();
            $permiso_user->estado_permiso_usuario=0;
            $permiso_user->ip_permiso_usuario=$request->ip();
            $permiso_user->fecha_mod_permiso_usuario=Carbon::now();
            $permiso_user->usuario_usuario=$request->user()->nick_usuario;
            $permiso_user->update();
            return ['mensaje' => 'Desactivado rol '.$rol->nombre_rol.' con exito'];
    }
    public function getRolesPerSystemEdit(Request $request){
            $id_usuario = $request->input('id_usuario');
            $id_rol = $request->input('id_rol_edit');
            $id_sistema = $request->input('id_sistema_edit');
            $permiso_user=PermisoUsuario::where('id_usuario','=',$id_usuario)->where('id_rol','=',$id_rol)->first();
            $sistema=Sistema::find($id_sistema);
            foreach($sistema->roles as $rol){
                if($rol->estado_rol==1){
                    $array_sistemas['value']=$rol->id_rol;
                    $array_sistemas['label']=$rol->nombre_rol;
                    $array_sis[]=$array_sistemas;
                }
            }
            return ['roles' => $array_sis,'permiso_usuario'=>$permiso_user->id_permiso_usuario];
    }
    public function updateRol(Request $request){
            $id_permiso_usuario = $request->input('permiso_usuario');
            $id_rol = $request->input('id_rol');
            $rol = Rol::find($id_rol);
            $permiso_user=PermisoUsuario::find($id_permiso_usuario);
            $permiso_user->id_rol=$id_rol;
            $permiso_user->ip_permiso_usuario=$request->ip();
            $permiso_user->fecha_mod_permiso_usuario=Carbon::now();
            $permiso_user->usuario_permiso_usuario=$request->user()->nick_usuario;
            $permiso_user->update();
            return ['mensaje' => 'Nuevo rol asignado '.$rol->nombre_rol.' con exito'];
    }

    //Methods to create a new user
    public function getDui(Request $request){
        $person = Persona::select(DB::raw('CONCAT(pnombre_persona," ",IFNULL(snombre_persona,"")," ",IFNULL(tnombre_persona,"")," ",papellido_persona," ",IFNULL(sapellido_persona,"")," ",IFNULL(tapellido_persona,"")) AS nombre_persona'),'id_persona','fecha_nac_persona')
            ->where('dui_persona','=',$request->input('dui'))
            ->first();
        if($person){
            $user = User::where('id_persona','=',$person->id_persona)->first();
            if(!$user){
                $user='';
            }
        }else{
            $user='';
        }
        return ['persona' => $person ? $person : '','usuario' => $user];
    }
    public function saveUser(Request $request){
        $existUser=false;
        $user = User::where('nick_usuario','=',$request->nick_usuario)->first();
        if($user){
            $existUser=true;
            $mensaje= 'El nombre de usuario ya existe, intente nuevamente';
            return response()->json($mensaje,422);
        }else{
            $person = Persona::find($request->id_persona);
            $ip=$request->ip();
            //Saving the new user
            $new_user = new User();
            $new_user->nick_usuario = $request->nick_usuario;
            $new_user->password_usuario = Hash::make($request->password);
            $new_user->id_persona = $request->id_persona;
            $new_user->estado_usuario=1;
            $new_user->fecha_reg_usuario=Carbon::now();
            $new_user->ip_usuario=$ip;
            $new_user->usuario_usuario=$request->user()->nick_usuario;
            $new_user->save();
            //Updating person table
            $person->id_usuario=$new_user->id_usuario;
            $person->fecha_mod_persona=Carbon::now();
            $person->ip_persona=$ip;
            $person->usuario_persona=$request->user()->nick_usuario;
            $person->update();
            //Creating the relationship between user and role.
            $new_permiso_user = new PermisoUsuario();
            $new_permiso_user->id_rol=$request->id_role;
            $new_permiso_user->id_usuario=$new_user->id_usuario;
            $new_permiso_user->estado_permiso_usuario=1;
            $new_permiso_user->ip_permiso_usuario=$ip;
            $new_permiso_user->fecha_reg_permiso_usuario=Carbon::now();
            $new_permiso_user->usuario_permiso_usuario=$request->user()->nick_usuario;
            $new_permiso_user->save();
            $mensaje = 'Guardado usuario '. $new_user->nick_usuario.' con exito';
            return ['mensaje' => $mensaje];
        }
    }

    public function changePasswordUser(Request $request){
        $user = User::find($request->id_usuario);
        $user->password_usuario = Hash::make($request->password);
        $user->ip_usuario=$request->ip();
        $user->fecha_mod_usuario=Carbon::now();
        $user->usuario_usuario=$request->user()->nick_usuario;
        $user->update();
        return ['mensaje' => 'Contraseña actualizada con exito'];
    }
}
