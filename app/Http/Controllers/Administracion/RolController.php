<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AccesoMenu;
use App\Models\Rol;
use App\Models\Menu;
use App\Models\Sistema;
use App\Models\PermisoUsuario;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        return ['data' => $roles, 'draw' => $request->input('draw')];
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
        $sistema = Sistema::find($rol->id_sistema);
        
        $id_menus_asignados=[];
        $menus_asignados = $rol->menus->load('parentMenu')
            ->where('estado_menu','=',1)
            ->where('id_menu_padre','<>',null)
            ->where('pivot.estado_acceso_menu','=',1);

        if($menus_asignados!=null){
            //Arreglo de ids de menus asignados
            foreach($menus_asignados as $menu){
                $id_menus_asignados[]=$menu->id_menu;
            }
            //Menus padre
            foreach($rol->menus as $menu){
                if($menu->pivot->estado_acceso_menu==1 && $menu->id_menu_padre==null){
                    $menusxrol[]=$menu->id_menu;
                }
            }
            //Llenando select de menus padre
            foreach($sistema->menus->load('childrenMenus') as $menu){
                $var=false;
                foreach($menu->childrenMenus as $hijo){
                    if(!(in_array($hijo->id_menu,$id_menus_asignados)) && $menu->estado_menu==1 && $hijo->estado_menu==1){
                        $var=true;
                    }
                }
                if($var==true){
                    $arrayRoles['id']=$menu->id_menu;
                    $arrayRoles['text']=$menu->nombre_menu;
                    $arrayRol[]=$arrayRoles;
                }
            }
            if(!(isset($arrayRol))){
                $arrayRol=[];
            }
        }else{
            foreach($sistema->menus as $menu){
                if($menu->id_menu_padre==null){
                    $arrayRoles['id']=$menu->id_menu;
                    $arrayRoles['text']=$menu->nombre_menu;
                    $arrayRol[]=$arrayRoles;
                }
            }
        }
        return ['rolMenus' => $menus_asignados, 'menus' => $arrayRol, 'id_menus_asignados' => $id_menus_asignados, 'nombre_rol' =>$rol->nombre_rol];
    }
    public function getChildrenMenus(Request $request){
        $id_menu = $request->input('id_menu');
        $menus_asignados = $request->input('id_menus_rol');
        $menu_padre = Menu::find($id_menu);
        if(isset($menus_asignados)){
            foreach($menu_padre->childrenMenus as $menu){
                if(!in_array($menu->id_menu,$menus_asignados)){
                    $arrayRoles['id']=$menu->id_menu;
                    $arrayRoles['text']=$menu->nombre_menu;
                    $arrayRol[]=$arrayRoles;
                }
            }
        }else{
            foreach($menu_padre->childrenMenus as $menu){
                    $arrayRoles['id']=$menu->id_menu;
                    $arrayRoles['text']=$menu->nombre_menu;
                    $arrayRol[]=$arrayRoles;
            }
        }
        return ['childrenMenus' => $arrayRol];
    }
    
    public function saveMenu(Request $request){
        $id_parentMenu = $request->input('id_menu');
        $id_childrenMenu = $request->input('id_childrenMenu');
        $id_rol = $request->input('id_rol');
        $rol = Rol::find($id_rol);
        $acceso_menu_padre=AccesoMenu::where('id_rol','=',$id_rol)->where('id_menu','=',$id_parentMenu)->first();
        $acceso_menu_hijo=AccesoMenu::where('id_rol','=',$id_rol)->where('id_menu','=',$id_childrenMenu)->first();
        if($acceso_menu_padre){
            if($acceso_menu_padre->estado_acceso_menu==0){
                $acceso_menu_padre->estado_acceso_menu=1;
                $acceso_menu_padre->update();
            }
        }else{
            $menuParent = new AccesoMenu();
            $menuParent->id_rol=$id_rol;
            $menuParent->id_menu=$id_parentMenu;
            $menuParent->estado_acceso_menu=1;
            $menuParent->fecha_reg_acceso_menu=Carbon::now();
            $menuParent->save();
        }
        if($acceso_menu_hijo){
            if($acceso_menu_hijo->estado_acceso_menu==0){
                $acceso_menu_hijo->estado_acceso_menu=1;
                $acceso_menu_hijo->update();
            }
        }else{
            $menuChildren = new AccesoMenu();
            $menuChildren->id_rol=$id_rol;
            $menuChildren->id_menu=$id_childrenMenu;
            $menuChildren->estado_acceso_menu=1;
            $menuChildren->fecha_reg_acceso_menu=Carbon::now();
            $menuChildren->save();
        }
        $menu=Menu::find($id_childrenMenu);
        return ['mensaje' => 'Guardado menu '.$menu->nombre_menu.' con exito'];
    }
    public function desactiveMenu(Request $request){
        $id_rol = $request->input('id_rol');
        $id_childrenMenu = $request->input('id_menu');
        //$rol = Rol::find($id_rol);
        $acceso_menu=AccesoMenu::where('id_menu','=',$id_childrenMenu)->where('id_rol','=',$id_rol)->first();
        $acceso_menu->estado_acceso_menu=0;
        $acceso_menu->update();
        $menu=Menu::find($id_childrenMenu);
        return ['mensaje' => 'Desactivado menu '.$menu->nombre_menu.' con exito'];
    }
}
