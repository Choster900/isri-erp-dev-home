<?php

namespace App\Http\Controllers\UCP;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaUcpController extends Controller
{
    public function getMarcasUcp(Request $request)
    {
        $columns = ['id_marca', 'nombre_marca', 'fecha_reg_marca', 'fecha_mod_marca', 'estado_marca'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = Marca::select('*')
            ->orderBy($columns[$column], $dir);

        $data = $query->paginate($length)->onEachSide(1);
        return ['data' => $data, 'draw' => $request->input('draw')];
    }
}
