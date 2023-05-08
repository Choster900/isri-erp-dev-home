<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Rol;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class IndexController extends Controller
{
    public function getMenus(Request $request, $id_rol)
    {
        //We obtain the submenus for a specific role.
        $id_usuario = $request->user()->id_usuario;
        $user = User::find($id_usuario);
        if ($user->hasRole($id_usuario, $id_rol)) {
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
                                        $array_hijo['nombre_ruta'] = $hijo->nombre_ruta;
                                        $array_hijo['url'] = $hijo->url_menu;
                                        $array_hijo['insertar'] = $hijo_rol->pivot->insertar_acceso_menu;
                                        $array_hijo['actualizar'] = $hijo_rol->pivot->actualizar_acceso_menu;
                                        $array_hijo['eliminar'] = $hijo_rol->pivot->eliminar_acceso_menu;
                                        $array_hijo['ejecutar'] = $hijo_rol->pivot->ejecutar_acceso_menu;
                                        array_push($menu_hijos, $array_hijo);
                                        $array_hijo = [];
                                    }//End fourth if
                                } //End third foreach
                            }//End third if
                        }//End second foreach
                        $menu_submenu['menu'] = $menu->nombre_menu;
                        $menu_submenu['submenu'] = $menu_hijos;
                        array_push($menu_padre, $menu_submenu);
                    }
                } //End second if
            } //End first foreach
          $rolxsistema['urls'] = $menu_padre;
            session(['menu' => $rolxsistema]);
            return Inertia::render('Index', [
                'menu' => $rolxsistema
            ]);
        } //End first if
        else {
            return redirect(route('dashboard'));
        }//End else
    }

    //Function that renders the page to change password.
    public function createCambiarContraseña(){
        return Inertia::render('Auth/SetPassword');
    }

    //Function to validate and change password.
    public function cambiarContraseña(Request $request){
        $validate = Validator::make($request->all(), [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ],[
            'current_password.required' => 'La contraseña actual es obligatoria',
            'current_password.current_password' => 'La contraseña no coincide con nuestros registros',
            'password.required' => 'Debe escribir su nueva contraseña',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        if($validate->fails()){
            return back()->withErrors($validate->errors())->withInput();
        }

        $user = User::find($request->user()->id_usuario);
        $user->password_usuario = Hash::make($request->password);
        $user->update();
        return redirect(route('dashboard'))->withMessage("Contraseña actualizada");
    }
}
