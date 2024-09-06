<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Session\TokenMismatchException;

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
        try {
            $url = $request->path();
            $url2 = "/" . $url;

            if (!$request->user()) {
                return redirect('dashboard');
            }

            $id_usuario = $request->user()->id_usuario;
            $user = User::find($id_usuario);

            $hasUrl = false;
            $allowed_url = false;
            $id_rol = session()->get('id_rol');

            if ($user) {
                $menuRolUsuario = getMenusByUsuarioRol($id_usuario, $id_rol);
                if ($menuRolUsuario) {
                    $hasUrl = userRolHasThisUrl($menuRolUsuario, $url2);
                    $allowed_url = $hasUrl;
                }
            }

            if ($allowed_url || $request->ajax()) {
                return $next($request);
            } else {
                return redirect('dashboard');
            }
        } catch (TokenMismatchException $e) {
            // Redirigir al dashboard si la sesión ha expirado
            return redirect('dashboard')->withErrors(['message' => 'Página expirada.']);
        }
    }
}
