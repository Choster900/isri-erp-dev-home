<?php

namespace App\Http\Controllers\RRHH;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CentroAtencion;
use App\Models\Dependencia;
use App\Models\Empleado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Facades\Excel;

class ReporteRRHHController extends Controller
{
    public function getInfoForReports(Request $request)
    {
        $dependencies = Dependencia::selectRaw("id_dependencia as value, concat(nombre_dependencia,' (',codigo_dependencia,')') as label, id_centro_atencion, codigo_dependencia")
            ->where('estado_dependencia', 1)->get();
        $mainCenters = CentroAtencion::selectRaw("id_centro_atencion as value, concat(nombre_centro_atencion,' (',codigo_centro_atencion,')') as label, codigo_centro_atencion")
            ->where('estado_centro_atencion', 1)->get();
        // Agregar el elemento al inicio de la colección
        $mainCenters->prepend(['value' => 0, 'label' => 'TODOS LOS CENTROS']);

        $states = DB::table('estado_empleado')->selectRaw('id_estado_empleado as value, nombre_estado_empleado as label')->get();
        $typesOfContract = DB::table('tipo_contrato')->selectRaw('id_tipo_contrato as value, nombre_tipo_contrato as label')->get();

        return response()->json([
            'dependencies'          => $dependencies,
            'states'                => $states,
            'typesOfContract'       => $typesOfContract,
            'mainCenters'           => $mainCenters
        ]);
    }
    public function getReportEmployeesRRHH(Request $request)
    {
        $customMessages = [
            'parentId.required' => 'Debe seleccionar el centro de atención.',
            'status.required' => 'Debe seleccionar el estado.',
        ];
        $validatedData = Validator::make($request->all(), [
            'parentId' => 'required',
            'status'   => 'required',
        ], $customMessages)->validate();

        $dependenciasIds = [];
        if ($request->depId) {
            $dependencia = Dependencia::find($request->depId);
            $dependenciasIds = $dependencia->all_dependencias_inferiores()->pluck('id_dependencia')->prepend($request->depId);
        }
        $query = Empleado::with(
            [
                'plazas_asignadas.dependencia.centro_atencion',
                'plazas_asignadas.detalle_plaza.plaza',
                'plazas_asignadas.detalle_plaza.tipo_contrato',
                'persona.genero',
                'persona.profesion',
                'persona.nivel_educativo',
                'persona.estado_civil',
                'tipo_pension',
                'persona.residencias.municipio.departamento',
                'persona.municipio',
                'persona.residencias' => function ($query) {
                    $query->where('estado_residencia', 1);
                },
                'periodos_laboral' => function ($query) {
                    $query->where('estado_periodo_laboral', 1); //Solo un periodo laboral debe estar activo
                },
                'plazas_asignadas' => function ($query) use ($request, $dependenciasIds) {
                    if ($request->status) {
                        if ($request->parentId != 0) {
                            $query->where('estado_plaza_asignada', $request->status == 1 ? 1 : 0);
                        }
                    }
                    //Filtramos por fechas
                    if ($request->startDate) {
                        $startDate = Carbon::parse($request->startDate)->endOfDay();
                        if ($request->status == 2) { //Inactivo
                            $query->whereDate('fecha_renuncia_plaza_asignada', '<=', $startDate);
                        } else {
                            if ($request->status == 1) { //Activo
                                $query->whereDate('fecha_plaza_asignada', '<=', $startDate);
                            } else {
                                $query->whereDate('fecha_renuncia_plaza_asignada', '<=', $startDate)
                                    ->orWhereDate('fecha_plaza_asignada', '<=', $startDate);
                            }
                        }
                    }
                    //Filtramos si existe tipo contratacion desde la vista
                    if ($request->typeOfContract) {
                        $query->whereHas('detalle_plaza', function ($query) use ($request) {
                            $query->where('id_tipo_contrato', $request->typeOfContract);
                        });
                    }
                    //Filtramos por centro o por dependencia
                    if ($request->parentId != 0) { //Verificamos si la opcion es 'Todos los centros'
                        $query->whereHas('dependencia', function ($query) use ($request, $dependenciasIds) {
                            if ($request->depId) {
                                $query->whereIn('id_dependencia', $dependenciasIds);
                            } else {
                                $query->where('id_centro_atencion', $request->parentId);
                            }
                        });
                    }
                }
            ]
        );

        if ($request->status) {
            $query->where('id_estado_empleado', $request->status);
        }

        $query->whereHas('plazas_asignadas', function ($query) use ($request, $dependenciasIds) {
            if ($request->status) {
                if ($request->parentId != 0) {
                    $query->where('estado_plaza_asignada', $request->status == 1 ? 1 : 0);
                }
            }
            //Filtramos por fechas
            if ($request->startDate) {
                $startDate = Carbon::parse($request->startDate)->endOfDay();
                if ($request->status == 2) { //Inactivo
                    $query->whereDate('fecha_renuncia_plaza_asignada', '<=', $startDate);
                } else {
                    if ($request->status == 1) { //Activo
                        $query->whereDate('fecha_plaza_asignada', '<=', $startDate);
                    } else {
                        $query->whereDate('fecha_renuncia_plaza_asignada', '<=', $startDate)
                            ->orWhereDate('fecha_plaza_asignada', '<=', $startDate);
                    }
                }
            }
            //Filtramos si existe tipo contratacion desde la vista
            if ($request->typeOfContract) {
                $query->whereHas('detalle_plaza', function ($query) use ($request) {
                    $query->where('id_tipo_contrato', $request->typeOfContract);
                });
            }
            //Filtramos por centro o por dependencia
            if ($request->parentId != 0) { //Verificamos si la opcion es 'Todos los centros'
                $query->whereHas('dependencia', function ($query) use ($request, $dependenciasIds) {
                    if ($request->depId) {
                        $query->whereIn('id_dependencia', $dependenciasIds);
                    } else {
                        $query->where('id_centro_atencion', $request->parentId);
                    }
                });
            }
        });

        return response()->json([
            'query'          => $query->get(),
        ]);
    }
    public function createExcelEmployees(Request $request)
    {
        $query = $request->queryResult; // Suponiendo que $queryResult es tu array con relaciones
        $total = count($query);

        $retirementY = 0;
        $retirementN = 0;
        $selectedData = [];
        foreach ($query as $empleado) {
            $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText(); // Crear un objeto RichText

            $empleado['pensionado_empleado'] == 1 ? $retirementY++ : $retirementN++;

            $plazasCount = count($empleado['plazas_asignadas']);
            foreach ($empleado['plazas_asignadas'] as $index => $plaza) {

                $endDate = !empty($plaza['fecha_renuncia_plaza_asignada']) ? Carbon::createFromFormat('Y-m-d', $plaza['fecha_renuncia_plaza_asignada'])->format('d/m/Y') : "";
                $startDate = Carbon::createFromFormat('Y-m-d', $plaza['fecha_plaza_asignada'])->format('d/m/Y');

                $fechaRenuncia = !empty($plaza['fecha_renuncia_plaza_asignada']) ? ' - ' . $endDate : '';
                $estadoEmpleado = $empleado['id_estado_empleado'] == 1 ? '' : (empty($plaza['fecha_renuncia_plaza_asignada']) ? ' - sin registro' : '');

                $period = ' (' . $startDate . $fechaRenuncia . $estadoEmpleado . ')';

                $sirh = $plaza['detalle_plaza']['id_puesto_sirhi_det_plaza'] ?
                    'SIRH = ' . $plaza['detalle_plaza']['id_puesto_sirhi_det_plaza'].' -- '
                    : '';

                $textRun = $richText->createTextRun(
                    $plaza['dependencia']['centro_atencion']['codigo_centro_atencion'] . ' -- ' .
                        $sirh . $plaza['detalle_plaza']['plaza']['nombre_plaza']
                        . ' -- ' . $plaza['detalle_plaza']['tipo_contrato']['nombre_tipo_contrato']
                        . ' -- ' . $period
                );

                // Agregar salto de línea si no es el último elemento
                if ($index !== $plazasCount - 1) {
                    $richText->createText("\n");
                }
            }
            $direccion = '';
            $depTo = '';
            $municipio = '';
            foreach ($empleado['persona']['residencias'] as $index => $dir) {
                if ($index == 0) {
                    $direccion = $dir['direccion_residencia'];
                    $depTo = $dir['municipio']['departamento']['nombre_departamento'];
                    $municipio = $dir['municipio']['nombre_municipio'];
                }
            }

            $fechaNacimiento = Carbon::createFromFormat('Y-m-d', $empleado['persona']['fecha_nac_persona']);
            $edad = $fechaNacimiento->diffInYears(Carbon::now());

            $selectedItem = [
                'codigo_empleado'   => $empleado['codigo_empleado'],
                'nombre'            => $empleado['persona']['nombre_completo'],
                'puesto'            => $richText,
                'dui'               => $empleado['persona']['dui_persona'],
                'departamento'      => $depTo,
                'municipio'         => $municipio,
                'direccion'         => $direccion,
                'genero'            => $empleado['persona']['genero']['nombre_genero'],
                'fecha_nac'         => Carbon::createFromFormat('Y-m-d', $empleado['persona']['fecha_nac_persona'])->format('d/m/Y'),
                'edad'              => $edad,
                'mun_nac'           => $empleado['persona']['municipio']['nombre_municipio'],
                'profesion'         => $empleado['persona']['profesion']['nombre_profesion'],
                'nivel'             => $empleado['persona']['nivel_educativo']['nombre_nivel_educativo'],
                'civil'             => $empleado['persona']['estado_civil']['nombre_estado_civil'],
                'pensionado'        => $empleado['pensionado_empleado'] == 1 ? 'SI' :  'NO',
                'e_personal'        => $empleado['persona']['email_persona'] ?? '',
                'e_laboral'         => $empleado['email_institucional_empleado'] ?? '',
                'telefono'          => $empleado['persona']['telefono_persona'] ?? "",
                'afiliado'          => $empleado['tipo_pension'] ? $empleado['tipo_pension']['codigo_tipo_pension'] : "",
                'nup'               => $empleado['nup_empleado'] ?? '',
                'isss'              => $empleado['isss_empleado'] ?? '',
            ];

            $selectedData[] = $selectedItem;
        }
        //Fields to show
        array_unshift($selectedData, array(
            'CODIGO', 'NOMBRE', 'PUESTO', 'DUI', 'DEPARTAMENTO RESIDENCIA', 'MUNICIPIO RESIDENCIA', 'DIRECCION',
            'GENERO', 'FECHA NACIMIENTO', 'EDAD', 'MUNICIPIO NACIMIENTO', 'PROFESION',
            'NIVEL EDUCATIVO', 'ESTADO CIVIL', 'PENSIONADO', 'CORREO PERSONAL', 'CORREO INSTITUCIONAL',
            'TELEFONO', 'AFILIADO A', 'NUP', 'ISSS'
        ));

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Merge cells
        $sheet->mergeCells('A1:U1');
        $sheet->mergeCells('A2:U2');
        $sheet->mergeCells('A3:U3');
        $sheet->mergeCells('A4:U4');
        // Set up the header
        $sheet->setCellValue('A1', 'INSTITUTO SALVADOREÑO DE REHABILITACION INTEGRAL - ISRI');
        $sheet->setCellValue('A2', $request->title);
        $sheet->setCellValue('A3', $request->location);
        $sheet->setCellValue('A4', $request->date);
        $sheet->getStyle('A1:A4')->getFont()->setSize(14);

        // Aplica el formato de centrado y negrita al texto
        $style = $sheet->getStyle('A1:A4');
        $style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $style->getFont()->setBold(true);

        $sheet->getStyle('5')->getFont()->setBold(true);

        // $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U'];
        // foreach ($columns as $column) {
        //     $sheet->getColumnDimension($column)->setAutoSize(true);
        // }

        $sheet->fromArray($selectedData, NULL, 'A5');

        // Recorrer todas las celdas y establecer el formato como texto
        // foreach ($sheet->getRowIterator() as $row) {
        //     foreach ($row->getCellIterator() as $cell) {
        //         $cell->setValueExplicit($cell->getValue(), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        //     }
        // }

        $lastRow = $sheet->getHighestDataRow(); // obtiene el número de la última fila con datos
        $sheet->setCellValue('B' . ($lastRow + 1), 'TOTAL Empleados: ' . $total);
        $sheet->setCellValue('C' . ($lastRow + 1), 'Pensionado : SI = ' . $retirementY . ' NO = ' . $retirementN);

        $sheet->getStyle('B' . ($lastRow + 1))->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('C' . ($lastRow + 1))->getFont()->setBold(true)->setSize(13);

        // Centrar texto en las celdas con los totales
        $sheet->getStyle('B' . ($lastRow + 1) . ':C' . ($lastRow + 1))
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $writer = new Xlsx($spreadsheet);

        $current_date = Carbon::now()->format('d_m_Y');
        $filename = 'RPT_EMPLEADOS_' . $current_date . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
