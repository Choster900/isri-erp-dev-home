<?php

namespace App\Http\Controllers\ActivoFijo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marca;
use Carbon\Carbon;
use App\Http\Requests\ActivoFijo\MarcaRequest;

class MarcaController extends Controller
{
    public function getMarcas(Request $request)
    {
        $columns = ['id_marca', 'nombre_marca', 'fecha_reg_marca', 'estado_marca'];

        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $search_value = $request->input('search');

        $query = Marca::select('*')->orderBy($columns[$column], $dir);

        if ($search_value) {
            $query->where('id_marca', 'like', '%' . $search_value['id_marca'] . '%')
                ->where('nombre_marca', 'like', '%' . $search_value['nombre_marca'] . '%')
                ->where('fecha_reg_marca', 'like', '%' . $search_value['fecha_reg_marca'] . '%')
                ->where('estado_marca', 'like', '%' . $search_value['estado_marca'] . '%');
        }

        $marcas = $query->paginate($length)->onEachSide(1);
        return ['data' => $marcas, 'draw' => $request->input('draw')];
    }

    public function changeStateBrand(Request $request)
    {
        $marca = Marca::find($request->id_brand);
        if ($marca->estado_marca == 1) {
            if ($request->state_brand == 1) {
                $marca->update([
                    'estado_marca' => 0,
                    'fecha_mod_marca' => Carbon::now(),
                    'usuario_marca' => $request->user()->nick_usuario,
                    'ip_marca' => $request->ip(),
                ]);
                return ['mensaje' => 'Marca ' . $marca->nombre_marca . ' ha sido desactivada con exito'];
            } else {
                return ['mensaje' => 'La marca seleccionada ya ha sido activada por otro usuario'];
            }
        } else {
            if ($marca->estado_marca == 0) {
                if ($request->state_brand == 0) {
                    $marca->update([
                        'estado_marca' => 1,
                        'fecha_mod_marca' => Carbon::now(),
                        'usuario_marca' => $request->user()->nick_usuario,
                        'ip_marca' => $request->ip(),
                    ]);
                    return ['mensaje' => 'Marca ' . $marca->nombre_marca . ' ha sido activada con exito'];
                } else {
                    return ['mensaje' => 'La marca seleccionada ya ha sido desactivada por otro usuario'];
                }
            }
        }
    }

    public function saveBrand(MarcaRequest $request)
    {
        $new_marca = new Marca();
        $new_marca->nombre_marca = $request->name_brand;
        $new_marca->estado_marca = 1;
        $new_marca->fecha_reg_marca = Carbon::now();
        $new_marca->ip_marca = $request->ip();
        $new_marca->usuario_marca = $request->user()->nick_usuario;
        $new_marca->save();
        return ['mensaje' => 'Marca guardada con éxito'];
    }

    public function updateBrand(MarcaRequest $request)
    {
        $marca = Marca::find($request->id_brand);
        if ($marca->estado_marca == 0) {
            return response()->json(['logical_error' => 'Error, la marca seleccionada ha sido desactivada por otro usuario.'], 422);
        } else {
            $marca->nombre_marca = $request->name_brand;
            $marca->fecha_mod_marca = Carbon::now();
            $marca->ip_marca = $request->ip();
            $marca->usuario_marca = $request->user()->nick_usuario;
            $marca->update();
            return ['mensaje' => 'Marca actualizada con éxito'];
        }
    }
}
