<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Support\Facades\DB;

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
}
