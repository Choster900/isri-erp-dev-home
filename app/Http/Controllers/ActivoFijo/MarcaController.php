<?php

namespace App\Http\Controllers\ActivoFijo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marca;
use Carbon\Carbon;

class MarcaController extends Controller
{
    public function getMarcas(Request $request)
    {
            $columns = ['id_marca', 'nombre_marca', 'fecha_reg_marca','estado_marca'];

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

    public function changeStateBrand(Request $request){
        $estado_anterior = $request->input('state_brand');
        $msg="";
        $marca = Marca::find($request->id_brand);
        $estado_anterior==1 ? $marca->estado_marca=0 : $marca->estado_marca=1;

        $estado_anterior==1 ? $msg="Desactivado" : $msg="Activado";
        $marca->ip_marca=$request->ip();
        $marca->fecha_mod_marca=Carbon::now();
        $marca->usuario_marca=$request->user()->nick_usuario;
        $marca->update();
        
        return ['mensaje' => $msg.' marca '.$marca->nombre_marca.' con exito'];
    }

    public function saveBrand(Request $request){
        $marca=Marca::where('nombre_marca','=',$request->brand)->first();
        if($marca){
            $mensaje= 'El nombre de marca "'.$request->brand.'" ya existe, intente nuevamente';
            return response()->json($mensaje,422);
        }else{
            $new_marca = new Marca();
            $new_marca->nombre_marca=$request->brand;
            $new_marca->estado_marca=1;
            $new_marca->fecha_reg_marca=Carbon::now();
            $new_marca->ip_marca=$request->ip();
            $new_marca->usuario_marca=$request->user()->nick_usuario;
            $new_marca->save();
            return ['mensaje' => 'Marca guardada con éxito'];
        }
    }

    public function updateBrand(Request $request){
        $old_marca=Marca::where('nombre_marca','=',$request->name_brand)->first();
        if($old_marca){
            $mensaje= 'El nombre de marca "'.$request->name_brand.'" ya existe, intente nuevamente';
            return response()->json($mensaje,422);
        }else{
            $marca = Marca::find($request->id_brand);
            $marca->nombre_marca=$request->name_brand;
            $marca->fecha_mod_marca=Carbon::now();
            $marca->ip_marca=$request->ip();
            $marca->usuario_marca=$request->user()->nick_usuario;
            $marca->update();
            return ['mensaje' => 'Marca actualizada con éxito'];
        }
    }
}
