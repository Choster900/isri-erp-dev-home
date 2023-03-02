<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;
use App\Models\Sistema;
use App\Models\PermisoUsuario;
use App\Http\Controllers\Controller;


class UserController extends Controller
{
    public function getUsers(Request $request)
    {
            $columns = ['id_usuario', 'nick_usuario', 'estado_usuario'];

            $length = $request->input('length');
            $column = $request->input('column'); //Index
            $dir = $request->input('dir');
            $search_value = $request->input('search');

            $query = User::select('id_usuario', 'nick_usuario', 'estado_usuario')->orderBy($columns[$column], $dir);

            if ($search_value) {
                $query->where(function ($query) use ($search_value) {
                    $query->where('nick_usuario', 'like', '%' . $search_value . '%')
                        ->orWhere('id_usuario', 'like', '%' . $search_value . '%');
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
                $permiso_user->update();
            }else{
                $new_permiso_user = new PermisoUsuario();
                $new_permiso_user->id_rol=$id_rol;
                $new_permiso_user->id_usuario=$id_usuario;
                $new_permiso_user->estado_permiso_usuario=1;
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
            $permiso_user->update();
            return ['mensaje' => 'Nuevo rol asignado '.$rol->nombre_rol.' con exito'];
    }
}
