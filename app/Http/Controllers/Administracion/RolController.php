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
            $estado_anterior==1 ? $permiso->estado_permiso_usuario=0 : $permiso->estado_permiso_usuario=1;
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
            //Arreglo de ids de menus hijos asignados
            foreach($menus_asignados as $menu){
                $id_menus_asignados[]=$menu->id_menu;
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
                    $arrayRoles['value']=$menu->id_menu;
                    $arrayRoles['label']=$menu->nombre_menu;
                    $arrayRol[]=$arrayRoles;
                }
            }
            if(!(isset($arrayRol))){
                $arrayRol=[];
            }
        }else{
            foreach($sistema->menus as $menu){
                if($menu->id_menu_padre==null){
                    $arrayRoles['value']=$menu->id_menu;
                    $arrayRoles['label']=$menu->nombre_menu;
                    $arrayRol[]=$arrayRoles;
                }
            }
        }
        return ['rolMenus' => $menus_asignados, 'menus' => $arrayRol, 'id_menus_asignados' => $id_menus_asignados, 'nombre_rol' =>$rol->nombre_rol];
    }
    //Mismo metodo para Crear Rol y Editar Rol, el array id_menus_rol es utilizado en el modal ModalRoles
    public function getChildrenMenus(Request $request){
        $id_menu = $request->input('id_menu');
        $menus_asignados = $request->input('id_menus_rol');
        $menu_padre = Menu::find($id_menu);
        if(isset($menus_asignados)){
            foreach($menu_padre->childrenMenus as $menu){
                if(!in_array($menu->id_menu,$menus_asignados)){
                    $arrayRoles['value']=$menu->id_menu;
                    $arrayRoles['label']=$menu->nombre_menu;
                    $arrayRol[]=$arrayRoles;
                }
            }
        }else{
            foreach($menu_padre->childrenMenus as $menu){
                    $arrayRoles['value']=$menu->id_menu;
                    $arrayRoles['label']=$menu->nombre_menu;
                    $arrayRol[]=$arrayRoles;
            }
        }
        return ['childrenMenus' => $arrayRol];
    }
    
    public function saveMenu(Request $request){
        $id_parentMenu = $request->input('id_menu');
        $id_childrenMenu = $request->input('id_childrenMenu');
        $id_rol = $request->input('id_rol');
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
        //Activate rol if it's necessary
        $rol = Rol::find($id_rol);
        if($rol->estado_rol==0){
            $rol->estado_rol=1;
            $updateTable=true;
            foreach($rol->usuarios as $user){
                $user->pivot->estado_permiso_usuario=1;
                $user->pivot->update();
            }
        }else{
            $updateTable=false;
        }
        $rol->update();
        $menu=Menu::find($id_childrenMenu);
        return ['mensaje' => 'Guardado menu '.$menu->nombre_menu.' con exito','update' => $updateTable];
    }
    public function desactiveMenu(Request $request){
        $id_rol = $request->input('id_rol');
        $id_childrenMenu = $request->input('id_menu');
        $acceso_menu=AccesoMenu::where('id_menu','=',$id_childrenMenu)->where('id_rol','=',$id_rol)->first();
        $acceso_menu->estado_acceso_menu=0;
        $acceso_menu->update();
        $childrenMenu=Menu::find($id_childrenMenu);
        $parentMenu=$childrenMenu->parentMenu;
        $isStillParent=false;
        foreach($parentMenu->childrenMenus as $child){
            $access_child = AccesoMenu::where('id_menu','=',$child->id_menu)->where('id_rol','=',$id_rol)->first();
            if($access_child && $access_child->estado_acceso_menu==1){
                $isStillParent=true;
            }
        }
        if(!$isStillParent){
            $access_parent = AccesoMenu::where('id_menu','=',$parentMenu->id_menu)->where('id_rol','=',$id_rol)->first();
            $access_parent->estado_acceso_menu=0;
            $access_parent->update();
        }

        $rol=Rol::find($id_rol);
        $menus_disponibles=false;
        $updateTable=false;
        foreach($rol->menus as $menu){
            if($menu->pivot->estado_acceso_menu==1){
                $menus_disponibles=true;
            }
        }
        if(!$menus_disponibles){
            $rol->estado_rol=0;
            $rol->update();
            //Desactivate rol if it's necessary
            $updateTable=true;
        }
        $menu=Menu::find($id_childrenMenu);
        return ['mensaje' => 'Desactivado menu '.$menu->nombre_menu.' con exito','update' => $updateTable];
    }

    //Methods for create Role
    public function getSistemasAll(){
        $sistemas = Sistema::get();
        $arraySistemas=[];
        foreach($sistemas as $sistema){
            $arraySis['value']=$sistema->id_sistema;
            $arraySis['label']=$sistema->nombre_sistema;
            $arraySistemas[]=$arraySis;
        }
        return ['sistemas'=>$arraySistemas];
    }
    public function getParentsMenuAll(Request $request){
        $id_sistema = $request->input('id_sistema');
        $sistema=Sistema::find($id_sistema);
        foreach($sistema->menus as $menu){
            if($menu->id_menu_padre==null && $menu->estado_menu==1){
                $arrayParent['value']=$menu->id_menu;
                $arrayParent['label']=$menu->nombre_menu;
                $arrayParents[]=$arrayParent;
            }
        }
        return['parentsMenu'=>$arrayParents];
    }
    public function createRol(Request $request){
        $nombre_rol = $request->input('nombre_rol');
        $id_sistema = $request->input('id_sistema');
        $menus = $request->input('menus');
        $rol = new Rol();
        $rol->id_sistema=$id_sistema;
        $rol->nombre_rol=$nombre_rol;
        $rol->estado_rol=1;
        $rol->fecha_reg_rol=Carbon::now();
        $rol->save();
        
        foreach($menus as $menu){
            $acceso_menu = new AccesoMenu();
            $acceso_menu->id_rol=$rol->id_rol;
            $acceso_menu->id_menu=$menu['id'];
            $acceso_menu->estado_acceso_menu=1;
            $acceso_menu->insertar_acceso_menu=1;
            $acceso_menu->actualizar_acceso_menu=1;
            $acceso_menu->eliminar_acceso_menu=1;
            $acceso_menu->ejecutar_acceso_menu=1;
            $acceso_menu->fecha_reg_acceso_menu=Carbon::now();
            $acceso_menu->save();

            $acceso_menu_padre = AccesoMenu::where('id_rol','=',$rol->id_rol)->where('id_menu','=',$menu['id_menu_padre'])->first();
            if(!$acceso_menu_padre){
                $acceso_padre = new AccesoMenu();
                $acceso_padre->id_rol=$rol->id_rol;
                $acceso_padre->id_menu=$menu['id_menu_padre'];
                $acceso_padre->estado_acceso_menu=1;
                $acceso_padre->fecha_reg_acceso_menu=Carbon::now();
                $acceso_padre->save();
            }
        }
        return['mensaje' => 'Guardado rol '.$nombre_rol.' con exito'];
    }
}
