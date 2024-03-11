<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Models\RecepcionPedido;
use Illuminate\Http\Request;

class DonacionController extends Controller
{
    public function getDonaciones(Request $request)
    {
        $columns = ['id_recepcion_pedido', 'acta_recepcion_pedido', 'id_proy_financiado', 'incumple_acuerdo_recepcion_pedido', 'monto_recepcion_pedido', 'fecha_recepcion_pedido', 'id_estado_recepcion_pedido'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = RecepcionPedido::select('*')
            ->with([
                'estado_recepcion'
            ])
            ->where('id_proy_financiado',4)
            ->orderBy($columns[$column], $dir);

        $data = $query->paginate($length)->onEachSide(1);
        return ['data' => $data, 'draw' => $request->input('draw')];
    }
}
