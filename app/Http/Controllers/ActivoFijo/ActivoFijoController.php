<?php

namespace App\Http\Controllers\ActivoFijo;

use App\Http\Controllers\Controller;
use App\Models\ActivoFijo;
use Illuminate\Http\Request;

class ActivoFijoController extends Controller
{
    public function getActivos(Request $request)
    {
        $columns = ['id_af', 'nombre_bien_especifico', 'nombre_marca', 'serie_af', 'valor_adquisicion_af', 'estado_af'];

        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $search_value = $request->input('search');

        $query = ActivoFijo::select('*')
            ->with('modelo.marca')
            ->with('bien_especifico')
            ->whereIn('id_tipo_af', $request->asset_types)
            ->orderBy($columns[$column], $dir);        
        // $query = ActivoFijo::select('activo_fijo.id_af', 'activo_fijo.fecha_adquisicion_af', 'activo_fijo.serie_af', 'activo_fijo.estado_af', 'marca.nombre_marca', 'bien_especifico.nombre_bien_especifico')
        //     ->join('modelo', 'activo_fijo.id_modelo', '=', 'modelo.id_modelo')
        //     ->join('marca', 'modelo.id_marca', '=', 'marca.id_marca')
        //     ->join('bien_especifico', 'activo_fijo.id_bien_especifico', '=', 'bien_especifico.id_bien_especifico')
        //     ->whereIn('activo_fijo.id_tipo_af', $request->asset_types)
        //     ->orderBy($columns[$column], $dir);
        if ($search_value) {
            $query->where('id_af', 'like', '%' . $search_value['id_af'] . '%')
                ->where('valor_adquisicion_af', 'like', '%' . $search_value['valor_adquisicion_af'] . '%')
                ->whereRaw('IFNULL(serie_af, "") like ?', '%' . $search_value["serie_af"] . '%')
                ->where('estado_af', 'like', '%' . $search_value['estado_af'] . '%')
                ->whereHas('modelo.marca', function ($query) use ($search_value) {
                    $query->where('nombre_marca', 'like', '%' . $search_value["nombre_marca"] . '%');
                })
                ->whereHas('bien_especifico', function ($query) use ($search_value) {
                    $query->where('nombre_bien_especifico', 'like', '%' . $search_value["nombre_bien_especifico"] . '%');
                })
                ->whereIn('id_tipo_af', $request->asset_types);
        }

        $bienes = $query->paginate($length)->onEachSide(1);
        return ['data' => $bienes, 'draw' => $request->input('draw')];
    }
}
