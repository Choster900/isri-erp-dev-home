<?php

namespace App\Http\Controllers\RRHH;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Empleado;

class EmpleadoController extends Controller
{
    public function getEmployees(Request $request)
    {
        $columns = ['id_empleado', 'codigo_empleado', 'nombre_persona', 'dui_persona', 'email_persona', 'estado_empleado'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = Empleado::select('*')
            ->with('persona')
            ->orderBy($columns[$column], $dir);

        if ($search_value) {
            $query->where('id_empleado', 'like', '%' . $search_value['id_empleado'] . '%')
                ->where('codigo_empleado', 'like', '%' . $search_value['codigo_empleado'] . '%')
                ->where('estado_empleado', 'like', '%' . $search_value['estado_empleado'] . '%')
                ->whereHas('persona', function ($query) use ($search_value) {
                    $query->where('dui_persona', 'like', '%' . $search_value["dui_persona"] . '%')
                        ->where('email_persona', 'like', '%' . $search_value["email_persona"] . '%');
                })
                ->whereHas(
                    'persona',
                    function ($query) use ($search_value) {
                        $query->where('pnombre_persona', 'like', '%' . $search_value["nombre_persona"] . '%')
                            ->orWhere('snombre_persona', 'like', '%' . $search_value["nombre_persona"] . '%')
                            ->orWhere('tnombre_persona', 'like', '%' . $search_value["nombre_persona"] . '%')
                            ->orWhere('papellido_persona', 'like', '%' . $search_value["nombre_persona"] . '%')
                            ->orWhere('sapellido_persona', 'like', '%' . $search_value["nombre_persona"] . '%')
                            ->orWhere('tapellido_persona', 'like', '%' . $search_value["nombre_persona"] . '%');
                    }
                );
        }
        $employees = $query->paginate($length)->onEachSide(1);
        return ['data' => $employees, 'draw' => $request->input('draw')];
    }
}
