<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Rol;

class RolController extends Controller
{
    public function getRoles(Request $request)
    {

        $columns = ['id_rol', 'nombre_sistema', 'nombre_rol','estado_rol','fecha_reg_rol'];

        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $searchValue = $request->input('search');

        $query=Rol::with('sistema')->orderBy($columns[$column], $dir);

        if ($searchValue) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('nombre_rol', 'like', '%' . $searchValue . '%')
                    ->orWhere('id_rol', 'like', '%' . $searchValue . '%')
                    ->orWhere('estado_rol', 'like', '%' . $searchValue . '%')
                    ->orWhere('fecha_reg_rol', 'like', '%' . $searchValue . '%')
                    ->orWhere('nombre_sistema', 'like', '%' . $searchValue . '%');
            });
        }

        $roles = $query->paginate($length)->onEachSide(1);
        return ['data' => $roles, 'draw' => $request->input('draw')];
    }
}
