<?php

namespace App\Http\Controllers\Juridico;

use App\Http\Controllers\Controller;
use App\Models\EjercicioFiscal;
use App\Models\Empleado;
use App\Models\FiniquitoLaboral;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            ])
            ->orderBy('id_finiquito_laboral', 'DESC');

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
                            ->orderBy('fecha_reg_plaza_asignada', 'ASC');
                    },
                    'plazas_asignadas.centro_atencion'
                ])
                    ->where('id_estado_empleado', 1)
                    //I'm testing just with one center
                    ->whereHas('plazas_asignadas.centro_atencion', function ($query) {
                        $query->where('id_centro_atencion', 10);
                    });
                //->where('id_empleado', '>', 935);

                return response()->json([
                    'empleados' => $empleados->get()
                ]);
            }
        } else {
            return response()->json([
                'logical_error' => 'No existe el ejercicio fiscal para este año, consulte con informática.',
            ], 422);
        }
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

    public function storeFiniquitos(Request $request)
    {
        // Obtener los centros y sus horarios desde la vista
        $centros = $request->centers;

        // Arreglo para almacenar los horarios ocupados por cada fecha
        $horariosOcupados = [];
        $centrosConConflictos = []; // Arreglo para almacenar los IDs de centros con conflictos
        $conflictoConEspacio = [];

        foreach ($centros as $centro) {
            if ($centro['startTime']) {

                $intervalo = $centro['interval'];
                $totalEmpleados = count($centro['empleados']);

                $id = $centro['id'];
                $fecha = date('d/m/Y', strtotime($centro['date']));

                // Construir la cadena de tiempo para la hora de inicio
                $horaInicio = sprintf('%02d', $centro['startTime']['hours']) . ':' . sprintf('%02d', $centro['startTime']['minutes']) . ':' . sprintf('%02d', $centro['startTime']['seconds']);

                // Construir la cadena de tiempo para la hora de fin
                $horaFin = sprintf('%02d', $centro['endTime']['hours']) . ':' . sprintf('%02d', $centro['endTime']['minutes']) . ':' . sprintf('%02d', $centro['endTime']['seconds']);

                //Calculamos el tiempo disponible en minutos
                $hora1 = Carbon::createFromFormat('H:i:s', $horaInicio);
                $hora2 = Carbon::createFromFormat('H:i:s', $horaFin);
                $minutosDisp = $hora2->diffInMinutes($hora1);

                if (($intervalo * $totalEmpleados) > $minutosDisp) { //Verificamos si hay tiempo suficiente
                    $conflictoConEspacio[] = $id;
                }

                // Verificar si la fecha ya está registrada como ocupada
                if (array_key_exists($fecha, $horariosOcupados)) {
                    // Si la fecha ya está registrada, verificar si hay conflicto de horarios
                    $conflicto = false;
                    foreach ($horariosOcupados[$fecha] as $horario) {
                        // Verificar si la hora de inicio o fin caen dentro del rango ocupado
                        if (($horaInicio >= $horario['horaInicio'] && $horaInicio <= $horario['horaFin']) ||
                            ($horaFin >= $horario['horaInicio'] && $horaFin <= $horario['horaFin'])
                        ) {
                            $conflicto = true;
                            break; // Hay conflicto, salir del bucle
                        }
                    }

                    if ($conflicto) {
                        // Agregar el ID del centro con conflicto al arreglo de centrosConConflictos
                        $centrosConConflictos[] = $id;
                    } else {
                        // No hay conflicto, agregar estos horarios a la lista de ocupados
                        $horariosOcupados[$fecha][] = [
                            'horaInicio' => $horaInicio,
                            'horaFin' => $horaFin,
                        ];
                    }
                } else {
                    // Si la fecha no está registrada, agregar estos horarios a la lista de ocupados
                    $horariosOcupados[$fecha][] = [
                        'horaInicio' => $horaInicio,
                        'horaFin' => $horaFin,
                    ];
                }
            }
        }

        if (count($centrosConConflictos) > 0) {
            return response()->json([
                'logical_error' => 'Existe conflicto en los horarios seleccionados.',
                'centrosConConflicto' => $centrosConConflictos
            ], 422);
        } else if (count($conflictoConEspacio) > 0) {
            return response()->json([
                'logical_error' => 'El rango de tiempo seleccionado no es suficiente para cubrir todos los empleados, cambie sus parametros de intervalo, hora inicio y hora fin e intente nuevamente.',
                'conflictoEspacio' => $conflictoConEspacio
            ], 422);
        } else {
            $year = Carbon::now()->year;
            $ejercicio = EjercicioFiscal::where('ejercicio_fiscal', $year)->first();
            DB::beginTransaction();
            try {
                foreach ($centros as $center) {
                    $fecha = date('Y/m/d', strtotime($center['date']));
                    $intervalo = $center['interval'];
                    $horaFirma = sprintf('%02d', $center['startTime']['hours']) . ':' . sprintf('%02d', $centro['startTime']['minutes']) . ':' . sprintf('%02d', $centro['startTime']['seconds']);
                    $horaFormat = Carbon::createFromFormat('H:i:s', $horaFirma);

                    foreach ($center['empleados'] as $indice => $empleado) {
                        if ($indice == 0) {
                            $horaFirmaEmpleado = $horaFormat;
                        } else {
                            $horaFirmaEmpleado = $horaFormat->copy()->addMinutes($indice * $intervalo);
                        }
                        $finiquito = new FiniquitoLaboral([
                            'id_empleado'                           => $empleado['id_empleado'],
                            'id_persona'                            => $request->personId,
                            'id_ejercicio_fiscal'                   => $ejercicio->id_ejercicio_fiscal,
                            'monto_finiquito_laboral'               => $request->amount,
                            'fecha_firma_finiquito_laboral'         => $fecha,
                            'hora_firma_finiquito_laboral'          => $horaFirmaEmpleado->format('H:i:s'),
                            'firmado_finiquito_laboral'             => 0,
                            'fecha_reg_finiquito_laboral'           => Carbon::now(),
                            'usuario_finiquito_laboral'             => $request->user()->nick_usuario,
                            'ip_finiquito_laboral'                  => $request->ip(),
                        ]);
                        $finiquito->save();
                    }
                }

                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'message'          => "Finiquito generado con éxito.",
                ]);
            } catch (\Throwable $th) {
                DB::rollBack(); // En caso de error, revierte las operaciones anteriores
                return response()->json([
                    'logical_error' => 'Ha ocurrido un error con sus datos.',
                    'error' => $th->getMessage(),
                ], 422);
            }
        }
    }
}
