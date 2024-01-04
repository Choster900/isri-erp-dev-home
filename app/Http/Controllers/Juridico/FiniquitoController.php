<?php

namespace App\Http\Controllers\Juridico;

use App\Http\Controllers\Controller;
use App\Models\EjercicioFiscal;
use App\Models\Empleado;
use App\Models\FiniquitoLaboral;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FiniquitoController extends Controller
{
    public function getFiniquitos(Request $request)
    {
        $columns = ['id_empleado', 'nombre_empleado', 'fecha_firma', 'hora_firma', 'monto', 'estado_finiquito'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = FiniquitoLaboral::select('*')
            ->with([
                'empleado.persona',
            ]);

        $data = $query->paginate($length)->onEachSide(1);
        return ['data' => $data, 'draw' => $request->input('draw')];
    }

    public function getInfoModalFiniquitos(Request $request)
    {
        $year = Carbon::now()->year;
        $ejercicio = EjercicioFiscal::where('ejercicio_fiscal', $year)->first();
        if ($ejercicio) {
            $finiquitos = FiniquitoLaboral::where('id_ejercicio_fiscal', $ejercicio->id_ejercicio_fiscal)->get();
            if ($finiquitos->count() >= 1) {
                return response()->json([
                    'logical_error' => 'El finiquito ya fue generado para el presente año.',
                ], 422);
            } else {
                $empleados = Empleado::with([
                    'plazas_asignadas' => function ($query) {
                        $query->where('estado_plaza_asignada', 1)
                        ->orderBy('fecha_reg_plaza_asignada','ASC'); 
                    },
                    'plazas_asignadas.centro_atencion' 
                    //test
                    => function ($empleados) {
                        $empleados->where('id_centro_atencion', 2);
                    }
                ])->where('id_estado_empleado', 1);
                    // ->whereHas('primer_centro_atencion', function ($empleados) {
                    //     $empleados->where('id_centro_atencion', 2);
                    // });
                return response()->json([
                    'empleados' => $empleados->get()
                ]);
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

    public function searchPersonJrd(Request $request)
    {
        $search = $request->busqueda;
        if ($search != '') {
            $persons = DB::table('persona')
                ->selectRaw('
                    CONCAT_WS(" ", 
                    COALESCE(pnombre_persona, ""), 
                    COALESCE(snombre_persona, ""), 
                    COALESCE(tnombre_persona, ""), 
                    COALESCE(papellido_persona, ""), 
                    COALESCE(sapellido_persona, ""), 
                    COALESCE(tapellido_persona, "")
                ) AS label,
                id_persona as value')
                ->where(function ($query) use ($search) {
                    $query->whereRaw("MATCH(pnombre_persona, snombre_persona, tnombre_persona, papellido_persona, sapellido_persona, tapellido_persona) AGAINST(?)", $search);
                })
                ->where('estado_persona', 1)
                ->get();
        }
        /* The above code is returning a JSON response in PHP. It includes an array called 'employees'
        which contains the value of the variable  if the variable  is not empty. If
         is empty, the 'employees' array will be empty as well. */
        return response()->json(
            [
                'persons'          => $search != '' ? $persons : [],
            ]
        );
    }
}
