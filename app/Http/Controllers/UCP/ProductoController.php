<?php

namespace App\Http\Controllers\UCP;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function getProductos(Request $request)
    {
        $columns = ['dui_empleado', 'nombre_empleado', 'fecha_firma_finiquito_laboral', 'hora_firma_finiquito_laboral', 'monto_finiquito_laboral', 'firmado_finiquito_laboral'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = Producto::select('*')
            // ->with([
            //     'empleado.persona',
            // ])
            ;

        $data = $query->paginate($length)->onEachSide(1);
        return ['data' => $data, 'draw' => $request->input('draw')];
    }
}
