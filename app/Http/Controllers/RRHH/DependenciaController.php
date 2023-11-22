<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Dependencia;
use Illuminate\Http\Request;

class DependenciaController extends Controller
{
    public function getDependencias(Request $request)
    {
        $columns = ['id_dependencia', 'nombre_dependencia', 'codigo_dependencia', 'jefatura', 'estado_dependencia'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = Dependencia::select('*')
            ->with([
                'jefatura'
            ])
            ->where('jerarquia_organizacion_dependencia','<>',null);

        if ($column == 3) {
            $query->orderByRaw('(SELECT pnombre_persona FROM persona WHERE persona.id_persona = dependencia.id_persona) ' . $dir);
        } else {
            $query->orderBy($columns[$column], $dir);
        }

        if ($search_value) {
            $query->where('id_dependencia', 'like', '%' . $search_value['id_dependencia'] . '%')
                ->where('nombre_dependencia', 'like', '%' . $search_value["nombre_dependencia"] . '%')
                ->where('codigo_dependencia', 'like', '%' . $search_value["codigo_dependencia"] . '%')
                ->where('estado_dependencia', 'like', '%' . $search_value["estado_dependencia"] . '%')
                ->where(function ($query) use ($search_value) {
                    if ($search_value["jefatura"]) {
                        if ($search_value['jefatura'] == 'N/Asign.' || $search_value['jefatura'] == 'N/A') {
                            $query->where('id_persona', null);
                        } else {
                            $query->whereHas('jefatura', function ($query) use ($search_value) {
                                $query->whereRaw("MATCH(pnombre_persona, snombre_persona, tnombre_persona, papellido_persona, sapellido_persona, tapellido_persona) AGAINST(?)", $search_value["jefatura"]);
                            });
                        }
                    }
                });
        }

        $dependencies = $query->paginate($length)->onEachSide(1);
        return ['data' => $dependencies, 'draw' => $request->input('draw')];
    }
}
