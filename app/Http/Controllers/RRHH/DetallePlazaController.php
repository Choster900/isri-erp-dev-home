<?php

namespace App\Http\Controllers\RRHH;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DetallePlaza;

class DetallePlazaController extends Controller
{
    public function getDetJobPositions(Request $request)
    {
        $columns = [
            'id_det_plaza',
            'codigo_det_plaza',
            'nombre_plaza',
            'estado_plaza',
            'codigo_dependencia',
            'nombre_empleado'
        ];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = DetallePlaza::select('*')
            ->with('plaza_asignada_activa.empleado.persona')
            ->with('plaza')
            ->with('plaza_asignada_activa.dependencia');

        if ($search_value) {
            $query->where('id_empleado', 'like', '%' . $search_value['id_empleado'] . '%')
                ->where('codigo_empleado', 'like', '%' . $search_value['codigo_empleado'] . '%')
                ->where('estado_empleado', 'like', '%' . $search_value['estado_empleado'] . '%')
                ->whereHas('persona', function ($query) use ($search_value) {
                    $query->where('dui_persona', 'like', '%' . $search_value["dui_persona"] . '%');
                })
                ->whereHas('plazas_asignadas.dependencia', function ($query) use ($search_value) {
                    $query->where('codigo_dependencia', 'like', '%' . $search_value["dependencia"] . '%');
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

        $jodPositions = $query->paginate($length)->onEachSide(1);
        return ['data' => $jodPositions, 'draw' => $request->input('draw')];
    }
}
