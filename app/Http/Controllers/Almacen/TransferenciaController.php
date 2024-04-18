<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Models\Requerimiento;
use Illuminate\Http\Request;

class TransferenciaController extends Controller
{
    public function getTransferencias(Request $request)
    {
        $columns = ['id_requerimiento', 'id_centro_atencion', 'motivo', 'id_proy_financiado', 'num_requerimiento', 'fecha_requerimiento', 'id_estado_req'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = Requerimiento::select('*')
            ->with([
                'centro_atencion',
                'proyecto_financiado',
                'estado_requerimiento',
                'motivo_ajuste',
                'detalles_requerimiento.producto.unidad_medida',
                'detalles_requerimiento.marca'
            ])
            ->where('id_tipo_req', 3);

        

        $data = $query->paginate($length)->onEachSide(1);
        return ['data' => $data, 'draw' => $request->input('draw')];
    }
}
