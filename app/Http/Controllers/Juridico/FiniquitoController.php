<?php

namespace App\Http\Controllers\Juridico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Juridico\AllFiniquitosRequest;
use App\Models\EjercicioFiscal;
use App\Models\Empleado;
use App\Models\FiniquitoLaboral;
use App\Models\Persona;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Luecano\NumeroALetras\NumeroALetras;

class FiniquitoController extends Controller
{
    public function getFiniquitos(Request $request)
    {
        $columns = ['dui_empleado', 'nombre_empleado', 'fecha_firma_finiquito_laboral', 'hora_firma_finiquito_laboral', 'monto_finiquito_laboral', 'firmado_finiquito_laboral'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = FiniquitoLaboral::select('*')
            ->with([
                'empleado.persona',
            ]);

        if ($column == -1) {
            $query->orderBy('id_finiquito_laboral', 'desc');
        } else {
            if ($column == 0) { //dui
                $query->orderByRaw('
                        (SELECT dui_persona FROM persona WHERE persona.id_persona = 
                            (SELECT id_persona FROM empleado WHERE empleado.id_empleado = finiquito_laboral.id_empleado) 
                        ) ' . $dir);
            } else {
                if ($column == 1) { //nombre
                    $query->orderByRaw('
                        (SELECT pnombre_persona FROM persona WHERE persona.id_persona = 
                            (SELECT id_persona FROM empleado WHERE empleado.id_empleado = finiquito_laboral.id_empleado) 
                        ) ' . $dir);
                } else {
                    $query->orderBy($columns[$column], $dir);
                }
            }
        }

        if ($search_value) {
            $query->where('fecha_firma_finiquito_laboral', 'like', '%' . $search_value['fecha_firma_finiquito_laboral'] . '%')
                ->where('monto_finiquito_laboral', 'like', '%' . $search_value['monto_finiquito_laboral'] . '%')
                ->where('hora_firma_finiquito_laboral', 'like', '%' . $search_value['hora_firma_finiquito_laboral'] . '%')
                ->where('firmado_finiquito_laboral', 'like', '%' . $search_value['firmado_finiquito_laboral'] . '%');
            if ($search_value['nombre_empleado']) {
                $query->whereHas(
                    'empleado.persona',
                    function ($query) use ($search_value) {
                        if ($search_value["nombre_empleado"] != '') {
                            $query->whereRaw("MATCH(pnombre_persona, snombre_persona, tnombre_persona, papellido_persona, sapellido_persona, tapellido_persona) AGAINST(?)", $search_value["nombre_empleado"]);
                        }
                    }
                );
            }
            if ($search_value['dui_empleado']) {
                $query->whereHas(
                    'empleado.persona',
                    function ($query) use ($search_value) {
                        if ($search_value["dui_empleado"] != '') {
                            $query->where('dui_persona', 'like', '%' . $search_value['dui_empleado'] . '%');
                        }
                    }
                );
            }
        }

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
                    ->where('id_estado_empleado', 1);
                //I'm testing just with one center
                // ->whereHas('plazas_asignadas.centro_atencion', function ($query) {
                //     $query->where('id_centro_atencion', 10);
                // });

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
                'persons'          => $persons,
            ]
        );
    }

    public function storeFiniquitos(AllFiniquitosRequest $request)
    {
        // Obtener los centros y sus horarios desde la vista
        $centros = $request->centers;

        // Arreglo para almacenar los horarios ocupados por cada fecha
        $horariosOcupados = [];
        $conflictoConHorario = []; // Arreglo para almacenar los IDs de centros con conflictos
        $conflictoConEspacio = [];

        foreach ($centros as $centro) {
            if ($centro['startTime']) {

                $intervalo = $centro['interval'];
                $totalEmpleados = count($centro['empleados']);

                $id = $centro['id'];
                $fecha = date('d/m/Y', strtotime($centro['date']));

                //Calculamos el tiempo disponible en minutos
                $hora1 = Carbon::createFromFormat('H:i:s', $centro['startTime']);
                $hora2 = Carbon::createFromFormat('H:i:s', $centro['endTime']);
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
                        if (($centro['startTime'] >= $horario['horaInicio'] && $centro['startTime'] <= $horario['horaFin']) ||
                            ($centro['endTime'] >= $horario['horaInicio'] && $centro['endTime'] <= $horario['horaFin'])
                        ) {
                            $conflicto = true;
                            break; // Hay conflicto, salir del bucle
                        }
                    }

                    if ($conflicto) {
                        // Agregar el ID del centro con conflicto al arreglo de conflictoConHorario
                        $conflictoConHorario[] = $id;
                    } else {
                        // No hay conflicto, agregar estos horarios a la lista de ocupados
                        $horariosOcupados[$fecha][] = [
                            'horaInicio' => $centro['startTime'],
                            'horaFin' => $centro['endTime'],
                        ];
                    }
                } else {
                    // Si la fecha no está registrada, agregar estos horarios a la lista de ocupados
                    $horariosOcupados[$fecha][] = [
                        'horaInicio' => $centro['startTime'],
                        'horaFin' => $centro['endTime'],
                    ];
                }
            }
        }

        if (count($conflictoConHorario) > 0) {
            return response()->json([
                'logical_error' => 'Existe conflicto en los horarios seleccionados.',
                'conflictoConHorario' => $conflictoConHorario
            ], 422);
        } else if (count($conflictoConEspacio) > 0) {
            return response()->json([
                'logical_error' => 'El rango de tiempo seleccionado no es suficiente para cubrir todos los empleados, cambie sus parametros de intervalo, hora inicio y hora fin e intente nuevamente.',
                'conflictoConEspacio' => $conflictoConEspacio
            ], 422);
        } else {
            $year = Carbon::now()->year;
            $ejercicio = EjercicioFiscal::where('ejercicio_fiscal', $year)->first();
            DB::beginTransaction();
            try {
                foreach ($centros as $center) {
                    $fecha = date('Y/m/d', strtotime($center['date']));
                    $intervalo = $center['interval'];
                    $horaFormat = Carbon::createFromFormat('H:i:s', $center['startTime']);

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

    public function getInforModalFiniquitoEmp(Request $request, $id)
    {
        $finiquitoEmp = FiniquitoLaboral::with('empleado.persona')->find($id);
        if ($finiquitoEmp) {
            return response()->json([
                'finiquitoEmp'                   => $finiquitoEmp ?? []
            ]);
        } else {
            return response()->json([
                'logical_error' => 'No existe el finiquito que estas intentando modificar.',
            ], 422);
        }
    }

    public function updateFiniquito(Request $request)
    {
        $customMessages = [
            'signatureTime.required'    => 'La hora de firma es requerida.',
            'signatureDate.required'    => 'La fecha firma es requerida.',
            'amount.required'           => 'El monto de finiquito es requerido.',
            'amount.min'                => 'El monto debe ser mayor a cero.',
        ];

        // Validate the request data with custom error messages and custom rule
        $validatedData = Validator::make($request->all(), [
            'signatureTime' => 'required',
            'signatureDate' => 'required',
            'amount'        => 'required|numeric|min:1'
        ], $customMessages)->validate();

        $fecha = date('Y/m/d', strtotime($request->signatureDate));
        $time = $request->signatureTime;
        $format = sprintf('%02d:%02d:%02d', $time['hours'], $time['minutes'], $time['seconds']);
        $timeFormat = Carbon::createFromFormat('H:i:s', $format);

        $existFiniquito = FiniquitoLaboral::where([
            ['id_finiquito_laboral', '!=', $request->id],
            ['fecha_firma_finiquito_laboral', '=', $fecha],
            ['hora_firma_finiquito_laboral', '=', $timeFormat],
        ])->exists();

        if ($existFiniquito) {
            return response()->json([
                'logical_error' => 'La combinacion fecha y hora ya ha sido designada para el finiquito de otro empleado, cambia los valores e intenta nuevamente.',
            ], 422);
        } else {
            DB::beginTransaction();
            try {
                $finiquitoEmp = FiniquitoLaboral::find($request->id);

                $finiquitoEmp->update([
                    'monto_finiquito_laboral'            => $request->amount,
                    'fecha_firma_finiquito_laboral'      => $fecha,
                    'hora_firma_finiquito_laboral'       => $timeFormat,
                    'fecha_mod_finiquito_laboral'        => Carbon::now(),
                    'usuario_finiquito_laboral'          => $request->user()->nick_usuario,
                    'ip_finiquito_laboral'               => $request->ip(),
                ]);

                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'message'          => "Finiquito actualizado con éxito.",
                ]);
            } catch (\Throwable $th) {
                DB::rollBack(); // En caso de error, revierte las operaciones anteriores
                return response()->json([
                    'logical_error' => 'Ha ocurrido un error con sus datos.',
                    'error' => $th->getMessage(),
                ], 422);
            }
            $finiquitoEmp = FiniquitoLaboral::find($request->id);
        }
    }

    public function getInfoForModalShowFiniquito(Request $request, $id)
    {
        $finiquitoEmp = FiniquitoLaboral::with([
            'empleado.persona',
            'empleado.persona.profesion',
            'empleado.persona.residencias' => function ($query) {
                $query->with('municipio.departamento')
                    ->where('estado_residencia', 1)
                    ->orderBy('fecha_reg_residencia', 'desc');
            },
            'persona.profesion',
            'persona.residencias' => function ($query) {
                $query->with('municipio.departamento')
                    ->where('estado_residencia', 1)
                    ->orderBy('fecha_reg_residencia', 'desc');
            }
        ])->find($id);

        $formatter = new NumeroALetras();
        $formatterDateA = new NumeroALetras();
        $formatterDateA->apocope = true;

        //Format hire date
        $hireDate = Carbon::parse($finiquitoEmp->empleado->fecha_contratacion_empleado)->format('Y-m-d');
        $hireMonth = Carbon::createFromFormat('Y-m-d', $hireDate)->format('m');
        $hireMonthText = strtoupper(Carbon::createFromFormat('m', $hireMonth)->locale('es_ES')->monthName);
        $hireDayText = $formatter->toWords(Carbon::createFromFormat('Y-m-d', $hireDate)->format('d'));
        $hireYearText = $formatter->toWords(Carbon::createFromFormat('Y-m-d', $hireDate)->format('Y'));

        //Format signature date apocope
        $signatureDateA = Carbon::parse($finiquitoEmp->fecha_firma_finiquito_laboral)->format('Y-m-d');
        $signatureMonthA = Carbon::createFromFormat('Y-m-d', $signatureDateA)->format('m');
        $signatureMonthTextA = strtoupper(Carbon::createFromFormat('m', $signatureMonthA)->locale('es_ES')->monthName);
        $signatureDayTextA = $formatterDateA->toWords(Carbon::createFromFormat('Y-m-d', $signatureDateA)->format('d'));
        $signatureYearTextA = $formatter->toWords(Carbon::createFromFormat('Y-m-d', $signatureDateA)->format('Y'));

        //Format settlement period
        $settlementDate = Carbon::parse($finiquitoEmp->fecha_reg_finiquito_laboral)->format('Y-m-d');
        $settlementYear = mb_strtolower($formatter->toWords(Carbon::createFromFormat('Y-m-d', $settlementDate)->format('Y')), 'UTF-8');

        //Format hour
        $time = Carbon::parse($finiquitoEmp->hora_firma_finiquito_laboral);
        $hoursText = $formatter->toWords($time->format('h'));
        $minutesText = $formatter->toWords($time->format('i'));

        //Format signature date
        $signatureDate = Carbon::parse($finiquitoEmp->fecha_firma_finiquito_laboral)->format('Y-m-d');
        $signatureMonth = Carbon::createFromFormat('Y-m-d', $signatureDate)->format('m');
        $signatureMonthText = strtoupper(Carbon::createFromFormat('m', $signatureMonth)->locale('es_ES')->monthName);
        $signatureDayText = $formatterDateA->toWords(Carbon::createFromFormat('Y-m-d', $signatureDate)->format('d'));
        $signatureYearText = $formatter->toWords(Carbon::createFromFormat('Y-m-d', $signatureDate)->format('Y'));


        if ($finiquitoEmp) {
            return response()->json([
                'finiquitoEmp'          => $finiquitoEmp ?? [],
                'hireDate'              => $hireDayText . " de " . $hireMonthText . " de " . $hireYearText,
                'signatureDateA'        => $signatureDayTextA . " días del mes de " . $signatureMonthTextA . " de " . $signatureYearTextA,
                'period'                => "del uno de enero al treinta y uno de diciembre del año " . $settlementYear,
                'signatureTime'         => $hoursText . " horas con " . $minutesText . " minutos",
                'signatureDate'         => $signatureDayText . " de " . $signatureMonthText . " de " . $signatureYearText,
                'amountText'            => $formatter->toInvoice($finiquitoEmp->monto_finiquito_laboral, 2, 'DÓLARES DE LOS ESTADOS UNIDOS DE AMERICA'),
                'year'                  => $formatter->toWords(Carbon::createFromFormat('Y-m-d', $signatureDate)->format('Y'))
            ]);
        } else {
            return response()->json([
                'logical_error' => 'No existe el finiquito que estas intentando modificar.',
            ], 422);
        }
    }

    public function printSettlement(Request $request, $id)
    {
        $finiquitoEmp = FiniquitoLaboral::find($id);

        DB::beginTransaction();
        try {
            $finiquitoEmp = FiniquitoLaboral::find($request->id);

            if ($finiquitoEmp->firmado_finiquito_laboral == 0) {
                $finiquitoEmp->update([
                    'firmado_finiquito_laboral'             => 1,
                    'fecha_mod_finiquito_laboral'           => Carbon::now(),
                    'usuario_finiquito_laboral'             => $request->user()->nick_usuario,
                    'ip_finiquito_laboral'                  => $request->ip(),
                ]);
            }

            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'message'          => "Finiquito actualizado con éxito.",
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
