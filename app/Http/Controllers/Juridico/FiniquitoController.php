<?php

namespace App\Http\Controllers\Juridico;

use App\Http\Controllers\Controller;
use App\Models\EjercicioFiscal;
use App\Models\Empleado;
use App\Models\FiniquitoLaboral;
use Carbon\Carbon;
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

    public function getInfoModalFiniquitos(Request $request, $id)
    {
        $year = Carbon::now()->year;
        $ejercicio = EjercicioFiscal::where('ejercicio_fiscal', '2024')->first();
        if ($ejercicio) {
            if ($id != 0) {
                $query = EjercicioFiscal::with([
                    'estado_ejercicio_fiscal',
                    'finiquitos_ejercicio_fiscal.empleado.plazas_asignadas' => function ($query) {
                        $query->orderBy('fecha_plaza_asignada', 'asc'); // Ordenar etapas por 'id_etapa_permiso' de manera ascendente
                    },
                    'finiquitos_ejercicio_fiscal.empleado.plazas_asignadas.centro_atencion'
                ])->find($id);

                return response()->json([
                    'ejercicio' => $query
                ]);
            } else {
                $finiquitos = FiniquitoLaboral::where('id_ejercicio_fiscal', $ejercicio->id_ejercicio_fiscal)->get();
                if ($finiquitos->count() >= 1) {
                    return response()->json([
                        'logical_error' => 'El finiquito ya fue generado para el presente año.',
                    ], 422);
                } else {
                    return response()->json([
                        'logical_error' => 'Ahora si.',
                    ], 422);
                }
            }
        } else {
            return response()->json([
                'logical_error' => 'No existe el ejercicio fiscal para este año, consulte con informatica.',
            ], 422);
        }

        //Consulta para obtener los empleados con mas de una plaza
        // $query = Empleado::with([
        //     'plazas_asignadas' => function ($query){
        //         $query->orderBy('fecha_plaza_asignada','asc');
        //     }
        // ])->whereHas('finiquitos_empleado', function ($query) use ($id) {
        //     $query->where('id_ejercicio_fiscal', $id);
        // })->has('plazas_asignadas', '>', 1)->get();
    }
}
