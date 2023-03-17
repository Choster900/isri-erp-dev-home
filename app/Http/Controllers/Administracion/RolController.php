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
        $columns = ['id_rol', 'nombre_sistema', 'nombre_rol', 'fecha_reg_rol', 'estado_rol'];

        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $search_value = $request->input('search');

        $query = Rol::select('rol.id_rol as id_rol', 'sistema.nombre_sistema as nombre_sistema', 'rol.nombre_rol as nombre_rol', 'rol.fecha_reg_rol as fecha_reg_rol', 'rol.estado_rol as estado_rol')
            ->join('sistema', 'rol.id_sistema', '=', 'sistema.id_sistema')
            ->orderBy($columns[$column], $dir);

        if ($search_value) {
            $query->where(function ($query) use ($search_value) {
                $query->where('nombre_rol', 'like', '%' . $search_value . '%')
                    ->orWhere('id_rol', 'like', '%' . $search_value . '%')
                    ->orWhere('fecha_reg_rol', 'like', '%' . $search_value . '%')
                    ->orWhere('nombre_sistema', 'like', '%' . $search_value . '%');
            });
        }

        $roles = $query->paginate($length)->onEachSide(1);
        return ['data' => $roles, 'draw' => $request->input('draw')];
    }

    public function changeStateRolAll(Request $request)
    {
        $id_rol = $request->input('id_rol');
        $estado_anterior = $request->input('estado_rol');
        $msg = "";
        $rol = Rol::find($id_rol);
        $estado_anterior == 1 ? $rol->estado_rol = 0 : $rol->estado_rol = 1;
        $permisox = PermisoUsuario::where('id_rol', '=', $id_rol)->get();
        foreach ( $permisox as $permiso ) {
            $estado_anterior == 1 ? $permiso->estado_permiso_usuario = 0 : $permiso->estado_permiso_usuario = 1;
            $permiso->ip_permiso_usuario = $request->ip();
            $permiso->fecha_mod_permiso_usuario = Carbon::now();
            $permiso->update();
        }
        $estado_anterior == 1 ? $msg = "Desactivado" : $msg = "Activado";
        $rol->ip_rol = $request->ip();
        $rol->fecha_mod_rol = Carbon::now();
        $rol->update();
        return ['mensaje' => $msg . ' rol ' . $rol->nombre_rol . ' con exito'];
    }
    public function getMenusEditRol(Request $request)
    {
        $id = $request->input('id_rol');
        $rol = Rol::find($id);
        $sistema = Sistema::find($rol->id_sistema);

        $id_menus_asignados = [];
        $menus_asignados = $rol->menus->load('parentMenu')
            ->where('estado_menu', '=', 1)
            ->where('id_menu_padre', '<>', null)
            ->where('pivot.estado_acceso_menu', '=', 1);

        if ($menus_asignados != null) {
            //Arreglo de ids de menus hijos asignados
            foreach ( $menus_asignados as $menu ) {
                $id_menus_asignados[] = $menu->id_menu;
            }
            //Llenando select de menus padre
            foreach ( $sistema->menus->load('childrenMenus') as $menu ) {
                $var = false;
                foreach ( $menu->childrenMenus as $hijo ) {
                    if (!(in_array($hijo->id_menu, $id_menus_asignados)) && $menu->estado_menu == 1 && $hijo->estado_menu == 1) {
                        $var = true;
                    }
                }
                if ($var == true) {
                    $array_roles['value'] = $menu->id_menu;
                    $array_roles['label'] = $menu->nombre_menu;
                    $array_rol[] = $array_roles;
                }
            }
            if (!(isset($array_rol))) {
                $array_rol = [];
            }
        } else {
            foreach ( $sistema->menus as $menu ) {
                if ($menu->id_menu_padre == null) {
                    $array_roles['value'] = $menu->id_menu;
                    $array_roles['label'] = $menu->nombre_menu;
                    $array_rol[] = $array_roles;
                }
            }
        }
        return ['rolMenus' => $menus_asignados, 'menus' => $array_rol, 'id_menus_asignados' => $id_menus_asignados, 'nombre_rol' => $rol->nombre_rol];
    }
    //Mismo metodo para Crear Rol y Editar Rol, el array id_menus_rol es utilizado unicamente en el modal ModalRoles
    public function getChildrenMenus(Request $request)
    {
        $id_menu = $request->input('id_menu');
        $menus_asignados = $request->input('id_menus_rol');
        $menu_padre = Menu::find($id_menu);
        if (isset($menus_asignados)) {
            foreach ( $menu_padre->childrenMenus as $menu ) {
                if (!in_array($menu->id_menu, $menus_asignados)) {
                    $array_roles['value'] = $menu->id_menu;
                    $array_roles['label'] = $menu->nombre_menu;
                    $array_rol[] = $array_roles;
                }
            }
        } else {
            foreach ( $menu_padre->childrenMenus as $menu ) {
                $array_roles['value'] = $menu->id_menu;
                $array_roles['label'] = $menu->nombre_menu;
                $array_rol[] = $array_roles;
            }
        }
        return ['childrenMenus' => $array_rol];
    }

    public function saveMenu(Request $request)
    {
        $id_parent_menu = $request->input('id_menu');
        $id_children_menu = $request->input('id_childrenMenu');
        $id_rol = $request->input('id_rol');
        $acceso_menu_padre = AccesoMenu::where('id_rol', '=', $id_rol)->where('id_menu', '=', $id_parent_menu)->first();
        $acceso_menu_hijo = AccesoMenu::where('id_rol', '=', $id_rol)->where('id_menu', '=', $id_children_menu)->first();
        if ($acceso_menu_padre) {
            if ($acceso_menu_padre->estado_acceso_menu == 0) {
                $acceso_menu_padre->estado_acceso_menu = 1;
                $acceso_menu_padre->fecha_mod_acceso_menu = Carbon::now();
                $acceso_menu_padre->ip_acceso_menu = $request->ip();
                $acceso_menu_padre->update();
            }
        } else {
            $new_menu_parent = new AccesoMenu();
            $new_menu_parent->id_rol = $id_rol;
            $new_menu_parent->id_menu = $id_parent_menu;
            $new_menu_parent->estado_acceso_menu = 1;
            $new_menu_parent->ip_acceso_menu = $request->ip();
            $new_menu_parent->fecha_reg_acceso_menu = Carbon::now();
            $new_menu_parent->save();
        }
        if ($acceso_menu_hijo) {
            if ($acceso_menu_hijo->estado_acceso_menu == 0) {
                $acceso_menu_hijo->estado_acceso_menu = 1;
                $acceso_menu_hijo->fecha_mod_acceso_menu = Carbon::now();
                $acceso_menu_hijo->ip_acceso_menu = $request->ip();
                $acceso_menu_hijo->update();
            }
        } else {
            $new_menu_children = new AccesoMenu();
            $new_menu_children->id_rol = $id_rol;
            $new_menu_children->id_menu = $id_children_menu;
            $new_menu_children->estado_acceso_menu = 1;
            $new_menu_children->insertar_acceso_menu = 1;
            $new_menu_children->actualizar_acceso_menu = 1;
            $new_menu_children->eliminar_acceso_menu = 1;
            $new_menu_children->ejecutar_acceso_menu = 1;
            $acceso_menu_hijo->ip_acceso_menu = $request->ip();
            $new_menu_children->fecha_reg_acceso_menu = Carbon::now();
            $new_menu_children->save();
        }
        //Activate rol if it's necessary
        $rol = Rol::find($id_rol);
        if ($rol->estado_rol == 0) {
            $rol->estado_rol = 1;
            $update_table = true;
            foreach ( $rol->usuarios as $user ) {
                $user->pivot->ip_permiso_usuario = $request->ip();
                $user->pivot->fecha_mod_permiso_usuario = Carbon::now();
                $user->pivot->estado_permiso_usuario = 1;
                $user->pivot->update();
            }
        } else {
            $update_table = false;
        }
        $rol->fecha_mod_rol = Carbon::now();
        $rol->ip_rol = $request->ip();
        $rol->update();
        $menu = Menu::find($id_children_menu);
        return ['mensaje' => 'Guardado menu ' . $menu->nombre_menu . ' con exito', 'update' => $update_table];
    }
    public function desactiveMenu(Request $request)
    {
        $id_rol = $request->input('id_rol');
        $id_children_menu = $request->input('id_menu');
        $acceso_menu = AccesoMenu::where('id_menu', '=', $id_children_menu)->where('id_rol', '=', $id_rol)->first();
        $acceso_menu->estado_acceso_menu = 0;
        $acceso_menu->fecha_mod_acceso_menu = Carbon::now();
        $acceso_menu->ip_acceso_menu = $request->ip();
        $acceso_menu->update();
        $children_menu = Menu::find($id_children_menu);
        $parent_menu = $children_menu->parentMenu;
        $is_still_parent = false;
        foreach ( $parent_menu->childrenMenus as $child ) {
            $access_child = AccesoMenu::where('id_menu', '=', $child->id_menu)->where('id_rol', '=', $id_rol)->first();
            if ($access_child && $access_child->estado_acceso_menu == 1) {
                $is_still_parent = true;
            }
        }
        if (!$is_still_parent) {
            $access_parent = AccesoMenu::where('id_menu', '=', $parent_menu->id_menu)->where('id_rol', '=', $id_rol)->first();
            $access_parent->fecha_mod_acceso_menu = Carbon::now();
            $access_parent->ip_acceso_menu = $request->ip();
            $access_parent->estado_acceso_menu = 0;
            $access_parent->update();
        }

        $rol = Rol::find($id_rol);
        $menus_disponibles = false;
        $update_table = false;
        foreach ( $rol->menus as $menu ) {
            if ($menu->pivot->estado_acceso_menu == 1) {
                $menus_disponibles = true;
            }
            if (!$menus_disponibles) {
                $rol->estado_rol = 0;
                $rol->ip_rol = $request->ip();
                $rol->fecha_mod_rol = Carbon::now();
                $rol->update();
                //Desactivate rol if it's necessary
                $update_table = true;
            }
            $menu = Menu::find($id_children_menu);
            return ['mensaje' => 'Desactivado menu ' . $menu->nombre_menu . ' con exito', 'update' => $update_table];
        }
    }

    //Methods for create Role
    public function getSystemsAll(Request $request)
    {
        $sistemas = Sistema::get();
        $array_sistemas = [];
        foreach ( $sistemas as $sistema ) {
            $array_sis['value'] = $sistema->id_sistema;
            $array_sis['label'] = $sistema->nombre_sistema;
            $array_sistemas[] = $array_sis;
        }
        return ['sistemas' => $array_sistemas];
    }
    public function getParentsMenuAll(Request $request)
    {
        $id_sistema = $request->input('id_sistema');
        $sistema = Sistema::find($id_sistema);
        foreach ( $sistema->menus as $menu ) {
            if ($menu->id_menu_padre == null && $menu->estado_menu == 1) {
                $array_parent['value'] = $menu->id_menu;
                $array_parent['label'] = $menu->nombre_menu;
                $array_parents[] = $array_parent;
            }
        }
        return ['parentsMenu' => $array_parents];
    }
    public function createRol(Request $request)
    {
        $nombre_rol = $request->input('nombre_rol');
        $id_sistema = $request->input('id_sistema');
        $menus = $request->input('menus');
        $new_rol = new Rol();
        $new_rol->id_sistema = $id_sistema;
        $new_rol->nombre_rol = $nombre_rol;
        $new_rol->estado_rol = 1;
        $new_rol->fecha_reg_rol = Carbon::now();
        $new_rol->ip_rol = $request->ip();
        $new_rol->save();

        foreach ( $menus as $menu ) {
            $new_acceso_menu = new AccesoMenu();
            $new_acceso_menu->id_rol = $new_rol->id_rol;
            $new_acceso_menu->id_menu = $menu['id'];
            $new_acceso_menu->estado_acceso_menu = 1;
            $new_acceso_menu->insertar_acceso_menu = 1;
            $new_acceso_menu->actualizar_acceso_menu = 1;
            $new_acceso_menu->eliminar_acceso_menu = 1;
            $new_acceso_menu->ejecutar_acceso_menu = 1;
            $new_acceso_menu->ip_acceso_menu = $request->ip();
            $new_acceso_menu->fecha_reg_acceso_menu = Carbon::now();
            $new_acceso_menu->save();

            $acceso_menu_padre = AccesoMenu::where('id_rol', '=', $new_rol->id_rol)->where('id_menu', '=', $menu['id_menu_padre'])->first();
            if (!$acceso_menu_padre) {
                $new_acceso_padre = new AccesoMenu();
                $new_acceso_padre->id_rol = $new_rol->id_rol;
                $new_acceso_padre->id_menu = $menu['id_menu_padre'];
                $new_acceso_padre->estado_acceso_menu = 1;
                $new_acceso_padre->ip_acceso_menu = $request->ip();
                $new_acceso_padre->fecha_reg_acceso_menu = Carbon::now();
                $new_acceso_padre->save();
            }
        }

        return ['mensaje' => 'Guardado rol ' . $nombre_rol . ' con exito'];

    }

    public function updatePermits(Request $request)
    {
        $acceso_menu = AccesoMenu::find($request->id_acceso_menu);
        $acceso_menu->insertar_acceso_menu = $request->insertar;
        $acceso_menu->actualizar_acceso_menu = $request->actualizar;
        $acceso_menu->eliminar_acceso_menu = $request->eliminar;
        $acceso_menu->ejecutar_acceso_menu = $request->ejecutar;
        $acceso_menu->fecha_mod_acceso_menu = Carbon::now();
        $acceso_menu->ip_acceso_menu = $request->ip();
        $acceso_menu->usuario_acceso_menu = $request->user()->nick_usuario;
        $acceso_menu->update();
        return ['mensaje' => 'Permisos actualizados con exito.'];
    }

}