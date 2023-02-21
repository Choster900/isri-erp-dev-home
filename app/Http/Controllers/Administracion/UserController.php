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
        $searchValue = $request->input('search');

        $query = User::select('id_usuario', 'nick_usuario', 'estado_usuario')->orderBy($columns[$column], $dir);

        if ($searchValue) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('nick_usuario', 'like', '%' . $searchValue . '%')
                    ->orWhere('id_usuario', 'like', '%' . $searchValue . '%');
            });
        }

        $users = $query->paginate($length)->onEachSide(1);
        return ['data' => $users, 'draw' => $request->input('draw')];
    }

    public function getSistemas(Request $request){
        $id = $request->input('id_usuario');
        $user = User::find($id);
        $sistemas=Sistema::get();
        foreach($user->roles as $rol){
            if($rol->pivot->estado_permiso_usuario==1){
                $rolesxuser[]=$rol->sistema->id_sistema;
            }
        }
        if(isset($rolesxuser)){
            foreach($sistemas as $sistema){
                if(!(in_array($sistema->id_sistema,$rolesxuser))){
                    $arraySistemas['value']=$sistema->id_sistema;
                    $arraySistemas['label']=$sistema->nombre_sistema;
                    $arraySis[]=$arraySistemas;
                }
            }
            if(!(isset($arraySis))){
                $arraySis=[];
            }
        }else{
            foreach($sistemas as $sistema){
                $arraySistemas['value']=$sistema->id_sistema;
                $arraySistemas['label']=$sistema->nombre_sistema;
                $arraySis[]=$arraySistemas;
            }
        }
        return ['userRoles' => $user->roles->load('sistema'), 'sistemas' => $arraySis, 'nombre_usuario' => $user->nick_usuario];
    }
    public function getRoles(Request $request){
        $id_sistema = $request->input('id_sistema');
        $sistema = Sistema::find($id_sistema);
        foreach($sistema->roles as $rol){
            $arrayRol['value']=$rol->id_rol;
            $arrayRol['label']=$rol->nombre_rol;
            $arrayRoles[]=$arrayRol;
        }
        return ['roles' => $arrayRoles];
    }

    public function saveRol(Request $request){
        $id_usuario = $request->input('id_usuario');
        $id_rol = $request->input('id_rol');
        $rol = Rol::find($id_rol);
        $permisox=PermisoUsuario::where('id_usuario','=',$id_usuario)->where('id_rol','=',$id_rol)->first();
        if($permisox){
            $permisox->estado_permiso_usuario=1;
            $permisox->update();
        }else{
            $permisoxusuario = new PermisoUsuario();
            $permisoxusuario->id_rol=$id_rol;
            $permisoxusuario->id_usuario=$id_usuario;
            $permisoxusuario->estado_permiso_usuario=1;
            $permisoxusuario->save();
        }
        return ['mensaje' => 'Guardado rol '.$rol->nombre_rol.' con exito'];
    }
    public function desactiveRol(Request $request){
        $id_usuario = $request->input('id_usuario');
        $id_rol = $request->input('id_rol');
        $rol = Rol::find($id_rol);
        $permisox=PermisoUsuario::where('id_usuario','=',$id_usuario)->where('id_rol','=',$id_rol)->first();
        $permisox->estado_permiso_usuario=0;
        $permisox->update();
        return ['mensaje' => 'Desactivado rol '.$rol->nombre_rol.' con exito'];
    }
    public function getRolesxSistema(Request $request){
        $id_usuario = $request->input('id_usuario');
        $id_rol = $request->input('id_rol_edit');
        $id_sistema = $request->input('id_sistema_edit');
        $permisox=PermisoUsuario::where('id_usuario','=',$id_usuario)->where('id_rol','=',$id_rol)->first();
        $sistema=Sistema::find($id_sistema);
        foreach($sistema->roles as $rol){
            $arraySistemas['value']=$rol->id_rol;
            $arraySistemas['label']=$rol->nombre_rol;
            $arraySis[]=$arraySistemas;
        }
        return ['roles' => $arraySis,'permiso_usuario'=>$permisox->id_permiso_usuario];
    }
    public function editRol(Request $request){
        $id_permiso_usuario = $request->input('permiso_usuario');
        $id_rol = $request->input('id_rol');
        $rol = Rol::find($id_rol);
        $permisoxusuario=PermisoUsuario::find($id_permiso_usuario);
        $permisoxusuario->id_rol=$id_rol;
        $permisoxusuario->update();
        return ['mensaje' => 'Nuevo rol asignado '.$rol->nombre_rol.' con exito'];
    }
}
