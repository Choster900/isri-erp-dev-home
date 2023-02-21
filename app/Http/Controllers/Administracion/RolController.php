<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Rol;
use App\Models\Menu;
use App\Models\PermisoUsuario;
use Illuminate\Support\Facades\DB;

class RolController extends Controller
{
    public function getRoles(Request $request)
    {

        $columns = ['id_rol', 'nombre_sistema', 'nombre_rol','fecha_reg_rol','estado_rol'];

        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $searchValue = $request->input('search');
        
        $query = DB::table('rol')
                ->join('sistema', 'rol.id_sistema', '=', 'sistema.id_sistema')
                ->select('rol.id_rol as id_rol', 'sistema.nombre_sistema as nombre_sistema', 'rol.nombre_rol as nombre_rol', 'rol.fecha_reg_rol as fecha_reg_rol', 'rol.estado_rol as estado_rol')
                ->orderBy($columns[$column], $dir);

        if ($searchValue) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('nombre_rol', 'like', '%' . $searchValue . '%')
                    ->orWhere('id_rol', 'like', '%' . $searchValue . '%')
                    ->orWhere('fecha_reg_rol', 'like', '%' . $searchValue . '%')
                    ->orWhere('nombre_sistema', 'like', '%' . $searchValue . '%');
            });
        }

        $roles = $query->paginate($length)->onEachSide(1);
        $current_page=$request->input('currentPage');
        return ['data' => $roles, 'draw' => $request->input('draw'),'current'=>$current_page];
    }

    public function changeRolAll(Request $request){
        $id_rol = $request->input('id_rol');
        $estado_anterior = $request->input('estado_rol');
        $msg="";
        $rol = Rol::find($id_rol);
        $estado_anterior==1 ? $rol->estado_rol=0 : $rol->estado_rol=1;
        $permisox=PermisoUsuario::where('id_rol','=',$id_rol)->get();
        foreach($permisox as $permiso){
            $permiso->estado_permiso_usuario=0;
            $permiso->update();
        }
        $estado_anterior==1 ? $msg="Desactivado" : $msg="Activado";
        $rol->update();
        return ['mensaje' => $msg.' rol '.$rol->nombre_rol.' con exito'];
    }
    public function getMenusRol(Request $request){
        $id = $request->input('id_rol');
        $rol = Rol::find($id);
        $menux = Menu::find(8);
        $menuP = $menux->parentMenu->nombre_menu;

        return ['rolMenus' => $rol->menus->load('parentMenu'),'menu_padre' => $menuP];
    }
    
}
