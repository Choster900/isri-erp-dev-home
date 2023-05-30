<?php

namespace App\Http\Controllers\ActivoFijo;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Modelo;
use App\Models\Marca;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ActivoFijo\ModeloRequest;
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

    public function changeStateModel(Request $request)
    {
        $modelo = Modelo::find($request->id_model);
        if ($modelo->estado_modelo == 1) {
            if ($request->state_model == 1) {
                $modelo->update([
                    'estado_modelo' => 0,
                    'fecha_mod_modelo' => Carbon::now(),
                    'usuario_modelo' => $request->user()->nick_usuario,
                    'ip_modelo' => $request->ip(),
                ]);
                return ['mensaje' => 'Modelo ' . $modelo->nombre_modelo . ' ha sido desactivado con exito'];
            } else {
                return ['mensaje' => 'El modelo seleccionado ya ha sido activado por otro usuario'];
            }
        } else {
            if ($modelo->estado_modelo == 0) {
                if ($request->state_model == 0) {
                    $modelo->update([
                        'estado_modelo' => 1,
                        'fecha_mod_modelo' => Carbon::now(),
                        'usuario_modelo' => $request->user()->nick_usuario,
                        'ip_modelo' => $request->ip(),
                    ]);
                    return ['mensaje' => 'Modelo ' . $modelo->nombre_modelo . ' ha sido activado con exito'];
                } else {
                    return ['mensaje' => 'El modelo seleccionado ya ha sido desactivado por otro usuario'];
                }
            }
        }
        $estado_anterior = $request->input('state_model');
        $msg = "";
        $modelo = Modelo::find($request->id_model);
        $estado_anterior == 1 ? $modelo->estado_modelo = 0 : $modelo->estado_modelo = 1;
        $estado_anterior == 1 ? $msg = "Desactivado" : $msg = "Activado";
        $modelo->ip_modelo = $request->ip();
        $modelo->fecha_mod_modelo = Carbon::now();
        $modelo->usuario_modelo = $request->user()->nick_usuario;
        $modelo->update();
        return ['mensaje' => $msg . ' modelo ' . $modelo->nombre_modelo . ' con exito'];
    }

    public function getBrands(Request $request)
    {
        $brands = Marca::select('id_marca as value', 'nombre_marca as label')
            ->where('estado_marca', '=', 1)
            ->orderBy('nombre_marca')
            ->get();
        return ['brands' => $brands];
    }

    public function saveModel(ModeloRequest $request)
    {
        $new_model = new Modelo();
        $new_model->id_marca = $request->id_brand;
        $new_model->nombre_modelo = $request->name_model;
        $new_model->estado_modelo = 1;
        $new_model->fecha_reg_modelo = Carbon::now();
        $new_model->usuario_modelo = $request->user()->nick_usuario;
        $new_model->ip_modelo = $request->ip();
        $new_model->save();
        return ['mensaje' => 'Modelo ' . $request->name_model . ' guardado con éxito.'];
    }

    public function updateModel(ModeloRequest $request)
    {
        $model = Modelo::find($request->id_model);
        if ($model->estado_modelo == 0) {
            return response()->json(['logical_error' => 'Error, el modelo seleccionado ha sido desactivado por otro usuario.'], 422);
        } else {
            $model->id_marca = $request->id_brand;
            $model->nombre_modelo = $request->name_model;
            $model->fecha_mod_modelo = Carbon::now();
            $model->usuario_modelo = $request->user()->nick_usuario;
            $model->ip_modelo = $request->ip();
            $model->update();
            return ['mensaje' => 'Modelo ' . $request->name_model . ' actualizado con éxito.'];
        }
    }
}
