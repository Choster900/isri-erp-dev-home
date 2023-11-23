<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Http\Requests\RRHH\DependenciaRequest;
use App\Models\Dependencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            ->where('id_dependencia', '<>', 1);

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

    //Obtiene unicamente los centros
    public function getCentrosAtencion(Request $request)
    {
        $dependencies = Dependencia::with('jefatura')->where('dep_id_dependencia', null)->get();
        return response()->json([
            'dependencies' => $dependencies
        ]);
    }

    public function getInfoModalDependencias(Request $request, $id)
    {
        $dependency =  Dependencia::with('jefatura')->find($id);
        $dependencies = DB::table('dependencia')
            ->selectRaw("
                dependencia.id_dependencia as value,
                CASE
                    WHEN dependencia.dep_id_dependencia IS NOT NULL THEN CONCAT(dep.codigo_dependencia, ' - ', dependencia.nombre_dependencia,' (',dependencia.codigo_dependencia,')')
                    ELSE CONCAT(dependencia.codigo_dependencia, ' - ', dependencia.nombre_dependencia)
                END as label,
                dependencia.id_dependencia,
                dependencia.dep_id_dependencia as depPadre
            ")
            ->leftJoin('dependencia as dep', 'dependencia.dep_id_dependencia', '=', 'dep.id_dependencia')
            //->where('dependencia.id_dependencia', '!=', 11) //Todos excepto presidencia
            ->get();

        return response()->json([
            'dependency'      => $dependency ?? [],
            'dependencies'    => $dependencies ?? []
        ]);
    }

    public function searchEmployee(Request $request)
    {
        $search = $request->busqueda;
        if ($search != '') {
            $empleados = DB::table('empleado as e')
                ->selectRaw('
                    CONCAT_WS(" ", 
                    COALESCE(p.pnombre_persona, ""), 
                    COALESCE(p.snombre_persona, ""), 
                    COALESCE(p.tnombre_persona, ""), 
                    COALESCE(p.papellido_persona, ""), 
                    COALESCE(p.sapellido_persona, ""), 
                    COALESCE(p.tapellido_persona, "")
                ) AS label,
                e.id_empleado as value')
                ->join('persona as p', 'e.id_persona', '=', 'p.id_persona')
                ->where(function ($query) use ($search) {
                    $query->whereRaw("MATCH(p.pnombre_persona, p.snombre_persona, p.tnombre_persona, p.papellido_persona, p.sapellido_persona, p.tapellido_persona) AGAINST(?)", $search);
                })
                ->get();
        }
        return response()->json(
            [
                'employees'          => $search != '' ? $empleados : [],
            ]
        );
    }

    public function storeDependency(DependenciaRequest $request){
        return response()->json([
            'message' => 'exitooooo'
        ]);
    }
}
