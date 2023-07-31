<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class GlobalMethodsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function getMenusByUsuario($id_usuario)
    {
        $user = User::find($id_usuario);
        if ($user) {
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
                    // session(['menu' => $rolxsistema]);
                    // return Inertia::render('Index', [
                    //     'menu' => $rolxsistema
                    // ]);
                }
            }
            return  $rolxsistema2;
        } else {
            return 'No existe el usuario';
        }
    }

    public function register()
    {
        if (!function_exists('userHasThisUrl')) {
            function userHasThisUrl(array $menus, String $url)
            {
                $hasUrl = false;
                foreach ($menus as $value) {
                    foreach ($value['submenu'] as $value2) {
                        if ($value2['url'] === $url) {
                            $hasUrl = true;
                        }
                    }
                }
                return $hasUrl;
            }
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
