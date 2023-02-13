<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class AccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $url = $request->path();
        $url2= "/".$url;
        $id_usuario = $request->user()->id_usuario;
        $user = User::find($id_usuario);
        $user_menu = session()->get('menu');

        $allowed_url = false;
        $r_object = (object) $request->user()->roles()->get();
        foreach ($r_object as $rol) {
            foreach ($rol->menus as $menu) {
                if ($user->hasRole($id_usuario,$rol->id_rol) && $menu->pivot->estado_acceso_menu == 1 && $menu->url_menu == $url2 && $user_menu) {
                    $allowed_url = true;
                }
            }
        }

        if ($allowed_url)
            return $next($request);

        return redirect('dashboard');
    }
}
