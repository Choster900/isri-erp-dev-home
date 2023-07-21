<?php

//namespace App\Helpers;

use App\Models\User;
use App\Models\Rol;
use App\Models\Menu;

use Illuminate\Support\Collection;


if (!function_exists('getMenusByUsuarioRol')) {
    function getMenusByUsuarioRol($id_usuario, $id_rol)
    {
        $user = User::find($id_usuario);
        if ($user && $user->hasRole($id_usuario, $id_rol)) {
            $rol = Rol::find($id_rol);
            $rolxsistema = [];
            $rolxsistema['id_rol'] = $rol->id_rol;
            $rolxsistema['rol'] = $rol->nombre_rol;
            $rolxsistema['sistema'] = $rol->sistema->nombre_sistema;
            $rolxsistema['icono_sistema'] = $rol->sistema->icono_sistema;
            $menu_padre = [];

            foreach ($rol->menus as $menu) {
                if ($menu->pivot->estado_acceso_menu == 1) {
                    $menu_hijos = [];
                    $menu_submenu = [];
                    if ($menu->id_menu_padre == null && $menu->estado_menu == 1) {
                        $hijos = Menu::where("id_menu_padre", $menu->id_menu)->get();
                        foreach ($hijos as $hijo) {
                            if ($hijo->estado_menu == 1) {
                                foreach ($hijo->roles as $hijo_rol) {
                                    if ($hijo_rol->pivot->estado_acceso_menu == 1 && $hijo_rol->pivot->id_rol == $rol->id_rol) {
                                        $array_hijo['id_menu'] = $hijo->id_menu;
                                        $array_hijo['nombre_submenu'] = $hijo->nombre_menu;
                                        $array_hijo['nombre_ruta'] = $hijo->nombre_ruta_menu;
                                        $array_hijo['url'] = $hijo->url_menu;
                                        $array_hijo['insertar'] = $hijo_rol->pivot->insertar_acceso_menu;
                                        $array_hijo['actualizar'] = $hijo_rol->pivot->actualizar_acceso_menu;
                                        $array_hijo['eliminar'] = $hijo_rol->pivot->eliminar_acceso_menu;
                                        $array_hijo['ejecutar'] = $hijo_rol->pivot->ejecutar_acceso_menu;
                                        array_push($menu_hijos, $array_hijo);
                                        $array_hijo = [];
                                    } //End fourth if
                                } //End third foreach
                            } //End third if
                        } //End second foreach
                        $menu_submenu['menu'] = $menu->nombre_menu;
                        $menu_submenu['submenu'] = $menu_hijos;
                        array_push($menu_padre, $menu_submenu);
                    }
                } //End second if
            } //End first foreach
            $rolxsistema['urls'] = $menu_padre;
            return $rolxsistema;
        } else {
            return false;
        }
    }
}

if (!function_exists('userRolHasThisUrl')) {
    function userRolHasThisUrl(array $menus, String $url)
    {
        $hasUrl = false;
        //foreach ($menus as $value) {
            $menu = $menus["urls"];
            foreach ($menu as $value2) {
                $submenu = $value2["submenu"];
                foreach ($submenu as $value3) {
                    if ($value3['url'] === $url) {
                        $hasUrl = true;
                    }
                }
            }
        //}
        return $hasUrl;
    }
}

if (!function_exists('userHasThisUrl')) {
    function userHasThisUrl(array $menus, String $url)
    {
        $hasUrl = false;
        foreach ($menus as $value) {
            $menu = $value["urls"];
            foreach ($menu as $value2) {
                $submenu = $value2["submenu"];
                foreach ($submenu as $value3) {
                    if ($value3['url'] === $url) {
                        $hasUrl = true;
                    }
                }
            }
        }
        return $hasUrl;
    }
}

if (!function_exists('getMenusByUsuario')) {
    function getMenusByUsuario($id_usuario)
    {
        $user = User::find($id_usuario);
        if ($user) {
            $roles = [];
            foreach ($user->roles as $rol) {
                if ($user->hasRole($id_usuario, $rol->id_rol)) {
                    $rol = Rol::find($rol->id_rol);
                    $rolxsistema2 = [];
                    $rolxsistema2['id_rol'] = $rol->id_rol;
                    $rolxsistema2['rol'] = $rol->nombre_rol;
                    $rolxsistema2['sistema'] = $rol->sistema->nombre_sistema;
                    $rolxsistema2['icono_sistema'] = $rol->sistema->icono_sistema;
                    $menu_padre = [];

                    foreach ($rol->menus as $menu) {
                        if ($menu->pivot->estado_acceso_menu == 1) {
                            $menu_hijos = [];
                            $menu_submenu = [];
                            if ($menu->id_menu_padre == null && $menu->estado_menu == 1) {
                                $hijos = Menu::where("id_menu_padre", $menu->id_menu)->get();
                                foreach ($hijos as $hijo) {
                                    if ($hijo->estado_menu == 1) {
                                        foreach ($hijo->roles as $hijo_rol) {
                                            if ($hijo_rol->pivot->estado_acceso_menu == 1 && $hijo_rol->pivot->id_rol == $rol->id_rol) {
                                                $array_hijo['id_menu'] = $hijo->id_menu;
                                                $array_hijo['nombre_submenu'] = $hijo->nombre_menu;
                                                $array_hijo['nombre_ruta'] = $hijo->nombre_ruta_menu;
                                                $array_hijo['url'] = $hijo->url_menu;
                                                $array_hijo['insertar'] = $hijo_rol->pivot->insertar_acceso_menu;
                                                $array_hijo['actualizar'] = $hijo_rol->pivot->actualizar_acceso_menu;
                                                $array_hijo['eliminar'] = $hijo_rol->pivot->eliminar_acceso_menu;
                                                $array_hijo['ejecutar'] = $hijo_rol->pivot->ejecutar_acceso_menu;
                                                array_push($menu_hijos, $array_hijo);
                                                $array_hijo = [];
                                            } //End fourth if
                                        } //End third foreach
                                    } //End third if
                                } //End second foreach
                                $menu_submenu['menu'] = $menu->nombre_menu;
                                $menu_submenu['submenu'] = $menu_hijos;
                                array_push($menu_padre, $menu_submenu);
                            }
                        } //End second if
                    } //End first foreach
                    $rolxsistema2['urls'] = $menu_padre;

                    array_push($roles, $rolxsistema2);
                }
            }
            return  $roles;
        } else {
            return 'No existe el usuario';
        }
    }
}
