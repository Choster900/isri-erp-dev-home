<?php

namespace App\Http\Controllers\Juridico;

use App\Http\Controllers\Controller;
use App\Models\EjercicioFiscal;
use App\Models\FiniquitoLaboral;
use Illuminate\Http\Request;

class FiniquitoController extends Controller
{
    public function getFiniquitos(Request $request)
    {
        $columns = ['id_ejercicio', 'ejercicio_fiscal', 'monto_ejercicio', 'estado_ejercicio'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = EjercicioFiscal::select('*')
            ->with([
                'estado_ejercicio_fiscal',
                'finiquitos_ejercicio_fiscal'
            ])
            ->where('id_estado_ef', '!=', 2)
            ->whereHas('finiquitos_ejercicio_fiscal');

        $data = $query->paginate($length)->onEachSide(1);
        return ['data' => $data, 'draw' => $request->input('draw')];
    }
}
