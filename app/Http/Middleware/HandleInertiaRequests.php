<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use App\Models\Menu;
use App\Models\User;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        //We obtain the roles of the logged in user.
        if ($request->user()) {
            $r_object = (object) $request->user()->roles()->get();
            $id_usuario = $request->user()->id_usuario;
            $user = User::find($id_usuario);
            $roles = [];
            foreach ($r_object as $rol) {
                $rol_active_and_menus=false;
                foreach($rol->menus as $menu){
                    if ($user->hasRole($id_usuario,$rol->id_rol) && $rol->hasMenu($rol->id_rol,$menu->id_menu)) {
                        $rol_active_and_menus=true;
                    }
                }
                if($rol_active_and_menus){
                    $rolxsistema = [];
                        $rolxsistema['id_rol'] = $rol->id_rol;
                        $rolxsistema['rol'] = $rol->nombre_rol;
                        $rolxsistema['sistema'] = $rol->sistema->nombre_sistema;
                        $rolxsistema['icono_sistema'] = $rol->sistema->icono_sistema;

                        array_push($roles, $rolxsistema);
                }
            }
        }

        //We send arrays that will be accessible throughout the app.
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            "menus" => $request->user() ? $roles : [],
            'flash' => [
                'message' => fn () => $request->session()->get('message')
            ],
        ]);
    }
}
