<?php

use App\Models\User;
use App\Models\Rol;
use App\Models\Menu;
use Inertia\Inertia;

//This method check if the user has access to the specific url
if (!function_exists('checkModuleAccessAndRedirect')) {
    function checkModuleAccessAndRedirect(Int $id_usuario, String $url, String $page)
    {
        $id_rol = session()->get('id_rol');

        if (!$id_usuario || !$id_rol) {
            return redirect('dashboard');
        }

        $menuRolUsuario = getMenusByUsuarioRol($id_usuario, $id_rol);
        if ($menuRolUsuario) {
            $allowed_url = $menuRolUsuario && userRolHasThisUrl($menuRolUsuario, $url);

            return $allowed_url
                ? Inertia::render($page, ['menu' => $menuRolUsuario])
                : redirect('dashboard');
        } else {
            redirect('dashboard');
        }
    }
}

//This method obtains the user menu for a given role.
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
                    if ($menu->id_menu_padre == null && $menu->estado_menu == 1) {
                        $hijos = Menu::where("id_menu_padre", $menu->id_menu)->get();
                        foreach ($hijos as $hijo) {
                            if ($hijo->estado_menu == 1) {
                                foreach ($hijo->roles as $hijo_rol) {
                                    if ($hijo_rol->pivot->estado_acceso_menu == 1 && $hijo_rol->pivot->id_rol == $rol->id_rol) {
                                        $menu_hijos[] = [
                                            'id_menu' => $hijo->id_menu,
                                            'nombre_submenu' => $hijo->nombre_menu,
                                            'nombre_ruta' => $hijo->nombre_ruta_menu,
                                            'url' => $hijo->url_menu,
                                            'insertar' => $hijo_rol->pivot->insertar_acceso_menu,
                                            'actualizar' => $hijo_rol->pivot->actualizar_acceso_menu,
                                            'eliminar' => $hijo_rol->pivot->eliminar_acceso_menu,
                                            'ejecutar' => $hijo_rol->pivot->ejecutar_acceso_menu,
                                        ];
                                    } //End fourth if
                                } //End third foreach
                            } //End third if
                        } //End second foreach
                        $menu_padre[] = [
                            'menu' => $menu->nombre_menu,
                            'submenu' => $menu_hijos,
                        ];
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

//This method defines whether a role has a specific url assigned to it.
if (!function_exists('userRolHasThisUrl')) {
    function userRolHasThisUrl(array $menus, String $url)
    {
        foreach ($menus['urls'] as $menu) {
            foreach ($menu['submenu'] as $submenu) {
                if ($submenu['url'] === $url) {
                    return true;
                }
            }
        }
        return false;
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

if (!function_exists('downwardRounding')) {
    function downwardRounding(float $amount)
    {
        // Convertimos a un número de 4 decimales
        $number = round($amount, 4);

        // Obtenemos la parte fraccionaria
        $fractionalPart = $number - floor($number);

        // Convertimos la parte fraccionaria a una cadena
        $fractionalStr = sprintf("%.4f", $fractionalPart);
        $fractionalStr = substr($fractionalStr, 2); // Obtenemos los decimales como cadena

        // Obtenemos los terceros y cuartos decimales
        $thirdAndFourthDecimals = intval(substr($fractionalStr, 2, 2));

        // Verificamos si los decimales tercero y cuarto son mayores a 50
        if ($thirdAndFourthDecimals > 50) {
            return number_format(ceil($number * 100) / 100, 2);
        } else {
            return number_format(floor($number * 100) / 100, 2);
        }
    }
}
