<?php

namespace App\Http\Controllers\ActivoFijo;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Modelo;
use Carbon\Carbon;

class ModeloController extends Controller
{
    public function getModelos(Request $request)
    {
        $columns = ['id_modelo', 'nombre_marca', 'nombre_modelo', 'fecha_reg_modelo', 'estado_modelo'];

        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $search_value = $request->input('search');

        $query = Modelo::select('*')
                    ->join('marca', function ($join) {
                        $join->on('modelo.id_marca', '=', 'marca.id_marca');
                    })
                    ->orderBy($columns[$column], $dir);

        if ($search_value) {
            $query->where('id_modelo', 'like', '%' . $search_value['id_modelo'] . '%')
                ->where('nombre_marca', 'like', '%' . $search_value['nombre_marca'] . '%')
                ->where('nombre_modelo', 'like', '%' . $search_value['nombre_modelo'] . '%')
                ->where('fecha_reg_modelo', 'like', '%' . $search_value['fecha_reg_modelo'] . '%')
                ->where('estado_modelo', 'like', '%' . $search_value['estado_modelo'] . '%');
        }

        $modelos = $query->paginate($length)->onEachSide(1);
        return ['data' => $modelos, 'draw' => $request->input('draw')];
    }

    public function changeStateModel(Request $request){
        $estado_anterior = $request->input('state_model');
        $msg="";
        $modelo = Modelo::find($request->id_model);
        $estado_anterior==1 ? $modelo->estado_modelo=0 : $modelo->estado_modelo=1;
        $estado_anterior==1 ? $msg="Desactivado" : $msg="Activado";
        $modelo->ip_modelo=$request->ip();
        $modelo->fecha_mod_modelo=Carbon::now();
        $modelo->usuario_modelo=$request->user()->nick_usuario;
        $modelo->update();
        return ['mensaje' => $msg.' modelo '.$modelo->nombre_modelo.' con exito'];
    }
}
