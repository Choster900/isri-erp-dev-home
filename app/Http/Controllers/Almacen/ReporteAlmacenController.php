<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\Tesoreria\ReporteFacturaRequest;
use App\Http\Requests\Tesoreria\ReporteIngresoDiarioRequest;
use App\Http\Requests\Tesoreria\ReporteIngresoRequest;
use App\Http\Requests\Tesoreria\ReporteQuedanRequest;
use App\Http\Requests\Tesoreria\RetencionISRRequest;
use App\Models\CentroAtencion;
use App\Models\ConceptoIngreso;
use App\Models\CuentaPresupuestal;
use App\Models\Dependencia;
use App\Models\EstadoQuedan;
use App\Models\Proveedor;
use App\Models\ProyectoFinanciado;
use App\Models\ReciboIngreso;
use App\Models\RequerimientoPago;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ReporteAlmacenController extends Controller
{

    public function getReporteFinanciero(Request $request)
    {
        try {
            $rules = [
                "reportInfo.startDate"         => "required",
                "reportInfo.endDate"           => "required",
                "reportInfo.financingSourceId" => "required",
            ];
            $customMessages = [
                'reportInfo.startDate.required'         => 'La fecha de inicio es obligatoria.',
                'reportInfo.endDate.required'           => 'La fecha de fin es obligatoria.',
                'reportInfo.financingSourceId.required' => 'La fuente de financiamiento es obligatorio.',
            ];
            $validator = Validator::make($request->all(), $rules, $customMessages);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                $message = 'The given data was invalid.';
                return response()->json(['message' => $message, 'errors' => $errors], 422);
            }
            $startDate = $request->input('reportInfo.startDate') != '' ? date('Y-m-d', strtotime($request->input('reportInfo.startDate'))) : null;
            $endDate = $request->input('reportInfo.endDate') != '' ? date('Y-m-d', strtotime($request->input('reportInfo.endDate'))) : null;
            $financingSourceId = $request->input('reportInfo.financingSourceId');
            $numeroCuenta = $request->input('reportInfo.numeroCuenta');

            $result = DB::select('CALL PR_RPT_FINANCIERO (?, ?, ?, ?)', array($financingSourceId, $numeroCuenta, $startDate, $endDate));
            return $result;
        } catch (\Exception $e) {
            // Manejar la excepción
            return response()->json(['message' => 'Ha ocurrido un error al procesar la solicitud.', 'error' => $e->getMessage()], 422);
        }
    }


    public function createExcelReport(Request $request)
    {

        $fechaDesde = $request->paramsToRequest["startDate"] != '' ? date('Y-m-d', strtotime($request->paramsToRequest["startDate"])) : null;
        $fechaHasta = $request->paramsToRequest["endDate"] != '' ? date('Y-m-d', strtotime($request->paramsToRequest["endDate"])) : null;

        $proyectoFinanciadoId = $request->paramsToRequest["financingSourceId"];
        $numeroCuenta = $request->paramsToRequest["numeroCuenta"];


        // Crear una instancia de Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'SISTEMA DE ALMACEN PARA EL CONTROL DE BIENES EN EXISTENCIA - ISRI');

        $sheet->mergeCells('C2:J2');
        $sheet->setCellValue('C2', 'REPORTE MENSUAL FINANCIERO DEL ALMACEN DE BIENES EN EXISTENCIA');

        $sheet->mergeCells('D3:H3');
        $sheet->setCellValue('D3', 'ALMACEN GENERAL');

        $sheet->mergeCells('K4:L4');
        $sheet->setCellValue('K4', 'DEL ' . date_format(date_create($fechaDesde), 'd, F, Y'));

        $sheet->mergeCells('A5:B5');
        $sheet->setCellValue('A5', 'FONDO GENERAL');

        $sheet->mergeCells('K5:L5');
        $sheet->setCellValue('K5', 'AL ' . date_format(date_create($fechaHasta), 'd, F, Y'));




        $sheet->getStyle('A1')->getFont()->setSize(8);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        $sheet->getStyle('C2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('C2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        $sheet->getStyle('D3')->getFont()->setSize(11);
        $sheet->getStyle('D3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('K4')->getFont()->setBold(true)->setSize(10); // DESDE
        $sheet->getStyle('K4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $sheet->getStyle('K5')->getFont()->setBold(true)->setSize(10); // HASTA
        $sheet->getStyle('K5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        $sheet->getStyle('K4')->getFont()->setBold(true)->setSize(10); // Fondo general
        $sheet->getStyle('K4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);


        // Encabezados desde A6 hasta L6
        $encabezados = [
            'ESPECIFICO',
            'DESCRIPCION',
            'SALDO ANTERIOR',
            'CUENTA CONTABLE',
            'COMPRAS',
            'TRANSFERENCIA INGRESO',
            'AJUSTE DE INGRESO',
            'CUENTA CONTABLE',
            'CONSUMO',
            'TRANSFERENCIA DE EGRESO',
            'AJUSTE SALIDA',
            'NUEVO SALDO'
        ];

        $sheet->fromArray([$encabezados], null, 'A6');

        // Establecer el formato de fuente para los encabezados
        $sheet->getStyle('A6:L6')->getFont()->setBold(true)->setSize(9);
        $sheet->getStyle('A6:M6')->getFont()->setName('HP Simplified');
        $sheet->getStyle('A6:L6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        $sheet->getRowDimension(6)->setRowHeight(29);
        $sheet->getColumnDimension('F')->setWidth(13);
        $sheet->getColumnDimension('J')->setWidth(13);
        $sheet->getColumnDimension('B')->setWidth(35);

        // Ajustar el texto en todas las celdas de la fila 6
        foreach (range('A', 'L') as $column) {
            $sheet->getStyle($column . '6')->getAlignment()->setWrapText(true);
        }

        foreach (range('A', 'L') as $column) {
            $sheet->getStyle($column . '6')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle($column . '6')->getBorders()->getTop()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
            $sheet->getStyle($column . '6')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle($column . '6')->getBorders()->getBottom()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
        }

        // Ejecutar el procedimiento almacenado y obtener los resultados
        $result = DB::select('CALL PR_RPT_FINANCIERO (?, ?, ?, ?)', array($proyectoFinanciadoId, $numeroCuenta, $fechaDesde, $fechaHasta));

        // Inicializar el índice de fila para escribir los datos
        $fila = 7;

        // Definir el orden de las columnas
        $columnas = [
            "id",
            "codigo_cta_presupuesto_rpt_finaciero",
            "nombre_cta_presupuesto_rpt_finaciero",
            "saldo_inicial_rpt_finaciero",
            "codigo_cta_gasto_rpt_finaciero",
            "compra_rpt_finaciero",
            "transf_ingreso_rpt_finaciero",
            "ajuste_ingreso_rpt_finaciero",
            "codigo_cta_inversion_rpt_finaciero",
            "consumo_rpt_finaciero",
            "transf_egreso_rpt_finaciero",
            "ajuste_egreso_rpt_finaciero",
            "saldo_actual_rpt_finaciero"
        ];


        // Iterar sobre los resultados y escribir en la hoja de cálculo
        foreach ($result as $filaResultado) {
            // Convertir el objeto stdClass en un array
            $filaArray = (array) $filaResultado;

            // Inicializar el índice de columna
            $columna = 0;

            // Iterar sobre las columnas definidas y escribir los valores en la hoja de cálculo
            foreach ($columnas as $nombreColumna) {
                // Obtener el valor de la columna actual
                $valor = isset($filaArray[$nombreColumna]) ? $filaArray[$nombreColumna] : '';

                // Obtener la letra de la columna a partir del índice de columna
                $letraColumna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columna);

                $sheet->getStyle('C' . $fila)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');
                $sheet->getStyle('E' . $fila)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');
                $sheet->getStyle('F' . $fila)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');
                $sheet->getStyle('G' . $fila)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');
                $sheet->getStyle('I' . $fila)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');
                $sheet->getStyle('J' . $fila)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');
                $sheet->getStyle('K' . $fila)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');
                $sheet->getStyle('L' . $fila)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');

                $sheet->getStyle('A' . $fila . ':L' . $fila)->getFont()->setName('Calibri')->setSize(9);


                // Obtener el número de fila
                $numeroFila = $fila; // Sumar 8 para compensar la fila de encabezados

                // Escribir el valor en la celda correspondiente
                $sheet->setCellValue($letraColumna . $numeroFila, $valor);

                // Incrementar el índice de columna
                $columna++;
            }

            // Incrementar el número de fila
            $fila++;
        }

        // Obtener el número de filas en la hoja de cálculo
        $ultimaFila = $fila - 1;

        // Aplicar formato en negrita a toda la última fila
        $sheet->getStyle('A' . $ultimaFila . ':L' . $ultimaFila)->getFont()->setBold(true);


        // Guardar el archivo como XLSX
        $writer = new Xlsx($spreadsheet);




        // Establecer el nombre del archivo
        $current_date = Carbon::now()->format('d_m_Y');
        $filename = 'texto_excel_' . $current_date . '.xlsx';

        // Establecer las cabeceras para descargar el archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Guardar el archivo en la salida PHP
        $writer->save('php://output');
    }

    public function getExcelDocumentConsumo(Request $request)
    {


        $startDate = $request->input('fechaDesde') != '' ? date('Y-m-d', strtotime($request->input('fechaDesde'))) : null;
        $endDate = $request->input('fechaHasta') != '' ? date('Y-m-d', strtotime($request->input('fechaHasta'))) : null;

        // Llamar al procedimiento almacenado con los parámetros proporcionados

        // Obtener el tipo de transacción del request
        $tipoTransaccion = $request->idTipoTransaccion;

        // Definir un array asociativo para mapear los valores de idtipomov e idtiporeq
        $transaccionMap = [
            1 => ['idtipomov' => 2, 'idtiporeq' => 1],
            2 => ['idtipomov' => 1, 'idtiporeq' => 2],
            3 => ['idtipomov' => 2, 'idtiporeq' => 2],
            4 => ['idtipomov' => 2, 'idtiporeq' => 3],
            5 => ['idtipomov' => 1, 'idtiporeq' => 3],
        ];

        // Obtener los valores de idtipomov e idtiporeq según el tipo de transacción
        $transaccionValues = $transaccionMap[$tipoTransaccion] ?? ['idtipomov' => 2, 'idtiporeq' => 1];


        $params = [
            'tipovista'     => $request->tipoReporte,
            'idtipomov'     => $transaccionValues['idtipomov'],
            'idtiporeq'     => $transaccionValues['idtiporeq'],
            'idproy'        => $request->idProyectoFinanciamiento,
            'idcentro'      => $request->idCentroAtencion == 0 ? null : $request->idCentroAtencion,
            'idcuenta'      => $request->idCuenta,
            'fecha_inicial' => $startDate,
            'fecha_final'   => $endDate,
        ];


        // Crear una instancia de Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();



        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'SISTEMA DE ALMACEN PARA EL CONTROL DE BIENES EN EXISTENCIA - ISRI');
        $sheet->getStyle('A1')->getFont()->setSize(8);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);


        $sheet->mergeCells('A2:C2');
        $sheet->setCellValue('A2', 'CONSUMO POR PROGRAMA');
        $sheet->getStyle('A2')->getFont()->setSize(20);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        if ($params['tipovista'] === 'C') {
            $sheet->mergeCells('E3:F3');
            $sheet->setCellValue('E3', 'DEL ' . date_format(date_create($startDate), 'd, F, Y'));
            $sheet->getStyle('E3')->getFont()->setSize(9);
            $sheet->getStyle('E3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        } else {

            $sheet->mergeCells('H3:I3');
            $sheet->setCellValue('H3', 'DEL ' . date_format(date_create($startDate), 'd, F, Y'));
            $sheet->getStyle('H3')->getFont()->setSize(9);
            $sheet->getStyle('H3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        }

        $sheet->mergeCells('A4:B4');
        $sheet->setCellValue('A4', 'ALMACEN GENERAL');
        $sheet->getStyle('A4')->getFont()->setSize(9);
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);


        if ($params['tipovista'] === 'C') {
            $sheet->mergeCells('E4:F4');
            $sheet->setCellValue('E4', 'DEL ' . date_format(date_create($endDate), 'd, F, Y'));
            $sheet->getStyle('E4')->getFont()->setSize(9);
            $sheet->getStyle('E4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        } else {
            $sheet->mergeCells('H4:I4');
            $sheet->setCellValue('H4', 'DEL ' . date_format(date_create($endDate), 'd, F, Y'));
            $sheet->getStyle('H4')->getFont()->setSize(9);
            $sheet->getStyle('H4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        }

        $sheet->setCellValue('A5', 'PRODUCTOS');
        $sheet->getStyle('A5')->getFont()->setSize(9);


        if ($params['tipovista'] === 'C') {
            $sheet->mergeCells('E5:F5');
            $sheet->setCellValue('E5', 'FONDO GENERAL');
            $sheet->getStyle('E5')->getFont()->setSize(9);
        } else {
            $sheet->mergeCells('H5:I5');
            $sheet->setCellValue('H5', 'FONDO GENERAL');
            $sheet->getStyle('H5')->getFont()->setSize(9);
        }



        // Encabezados desde A6 hasta L6

        if ($params['tipovista'] === 'C') {
            $encabezados = [
                'COD',
                'NOMBRE',
                'MARCA',
                'UNIDAD',
                'CANTIDAD',
                'MONTOS'
            ];
        } else {
            $encabezados = [
                'COD',
                'NOMBRE',
                'MARCA',
                'UNIDAD',
                'NUMERO',
                'FECHA',
                'CANTIDAD',
                'COSTO',
                'MONTOS'
            ];
        }


        $sheet->fromArray([$encabezados], null, 'A6');

        // Establecer estilo para encabezados
        $styleHeader = [
            'font' => ['bold' => true, 'size' => 9],
        ];

        foreach (range('A', 'I') as $column) {
            $sheet->getStyle($column . '6')->applyFromArray($styleHeader);
        }

        // Ejecutar el procedimiento almacenado y obtener los resultados
        $result = DB::select("CALL PR_RPT_CONSUMO(:tipovista, :idtipomov, :idtiporeq, :idproy, :idcentro, :idcuenta, :fecha_inicial, :fecha_final)", $params);

        $sheet->getColumnDimension('B')->setWidth(35);


        // Pintar los datos en el documento
        $row = 7; // Comenzar desde la fila 2 para dejar espacio para los encabezados


        if ($params['tipovista'] === 'C') {
            foreach (range('A', 'F') as $column) {

                $sheet->getStyle($column . '6')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('A6')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('F6')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle($column . '6')->getBorders()->getTop()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
                $sheet->getStyle($column . '6')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle($column . '6')->getBorders()->getBottom()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
            }
        } else {
            foreach (range('A', 'I') as $column) {

                $sheet->getStyle($column . '6')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('A6')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('I6')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle($column . '6')->getBorders()->getTop()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
                $sheet->getStyle($column . '6')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle($column . '6')->getBorders()->getBottom()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
            }
        }


        // Ajustar el texto en todas las celdas de la fila 6
        foreach (range('A', 'I') as $column) {

            $sheet->getStyle($column . '6')->getAlignment()->setWrapText(true);
        }

        foreach ($result as $data) {
            /* $sheet->setCellValue('A' . $row, $data->COD); */

            if ($data->id_tipo_reg_rpt_consumo == 0) {

                if ($params['tipovista'] === 'C') {

                    $sheet->mergeCells('A' . $row . ':F' . $row);
                    $sheet->setCellValue('A' . $row, $data->nombre_prod_rpt_consumo);
                    $sheet->getStyle('A' . $row)->getFont()->setBold(true);
                    $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setName('Calibri')->setSize(11);
                } else {
                    $sheet->mergeCells('A' . $row . ':I' . $row);
                    $sheet->setCellValue('A' . $row, $data->nombre_prod_rpt_consumo);
                    $sheet->getStyle('A' . $row)->getFont()->setBold(true);
                    $sheet->getStyle('A' . $row . ':I' . $row)->getFont()->setName('Calibri')->setSize(11);
                }
            }

            if ($data->id_tipo_reg_rpt_consumo == 1) {

                $sheet->setCellValue('A' . $row, $data->id_ccta_presupuesto_rpt_consumo);
                $sheet->mergeCells('B' . $row . ':I' . $row);
                $sheet->setCellValue('B' . $row, $data->nombre_prod_rpt_consumo);
                $sheet->getStyle('A' . $row . ':I' . $row)->getFont()->setName('Calibri')->setSize(10);
            }

            if ($data->id_tipo_reg_rpt_consumo == 2) {

                if ($params['tipovista'] === 'C') {
                    $sheet->setCellValue('A' . $row, $data->codigo_uplt_rpt_consumo);
                    $sheet->setCellValue('B' . $row, $data->nombre_prod_rpt_consumo);
                    $sheet->setCellValue('C' . $row, $data->marca_rpt_consumo);
                    $sheet->setCellValue('D' . $row, $data->nombre_umedida_rpt_consumo);
                    $sheet->setCellValue('E' . $row, $data->cant_rpt_consumo);
                    $sheet->setCellValue('F' . $row, $data->monto_rpt_consumo);
                    $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');
                } else {
                    $sheet->setCellValue('A' . $row, $data->codigo_uplt_rpt_consumo);
                    $sheet->setCellValue('B' . $row, $data->nombre_prod_rpt_consumo);
                    $sheet->setCellValue('C' . $row, $data->marca_rpt_consumo);
                    $sheet->setCellValue('D' . $row, $data->nombre_umedida_rpt_consumo);
                    $sheet->setCellValue('E' . $row, $data->numero_mov_rpt_consumo);
                    $sheet->setCellValue('F' . $row, $data->fecha);
                    $sheet->setCellValue('G' . $row, $data->cant_rpt_consumo);
                    $sheet->setCellValue('H' . $row, $data->costo_rpt_consumo);
                    $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');

                    $sheet->setCellValue('I' . $row, $data->monto_rpt_consumo);
                    $sheet->getStyle('I' . $row)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');
                }

                $sheet->getStyle('A' . $row . ':I' . $row)->getFont()->setName('Calibri')->setSize(9);
            }

            if ($data->id_tipo_reg_rpt_consumo == 3) {

                if ($params['tipovista'] === 'C') {
                    $sheet->mergeCells('A' . $row . ':E' . $row);
                    $sheet->setCellValue('A' . $row, $data->nombre_prod_rpt_consumo);
                    $sheet->setCellValue('F' . $row, $data->monto_rpt_consumo);
                    $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setName('Calibri')->setSize(9);
                    $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');
                } else {
                    $sheet->mergeCells('A' . $row . ':H' . $row);
                    $sheet->setCellValue('A' . $row, $data->nombre_prod_rpt_consumo);
                    $sheet->setCellValue('I' . $row, $data->monto_rpt_consumo);
                    $sheet->getStyle('A' . $row . ':I' . $row)->getFont()->setName('Calibri')->setSize(9);
                    $sheet->getStyle('I' . $row)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');
                }
            }

            $row++;
        }
        // Guardar el archivo como XLSX
        $writer = new Xlsx($spreadsheet);

        // Establecer el nombre del archivo
        $current_date = Carbon::now()->format('d_m_Y');
        $filename = 'texto_excel_' . $current_date . '.xlsx';

        // Establecer las cabeceras para descargar el archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Guardar el archivo en la salida PHP
        $writer->save('php://output');
    }

    public function getReporteConsumo(Request $request)
    {
        $rules = [
            "fechaDesde"               => "required",
            "fechaHasta"               => "required",
            "idCentroAtencion"         => "required",
            "idProyectoFinanciamiento" => "required",
            "idTipoTransaccion"        => "required",
            "tipoReporte"              => "required",

        ];
        $customMessages = [
            "fechaDesde.required"               => "La Fecha inicio es obligatorioa.",
            "fechaHasta.required"               => "La Fecha fin es obligatoria.",
            "idCentroAtencion.required"         => "El centro de atención es obligatorio.",
            "idProyectoFinanciamiento.required" => "La fuente de F. es obligatorio.",
            "idTipoTransaccion.required"        => "El tipo de transacción es obligatorio.",
            "tipoReporte.required"              => "El tipo de reporte es obligatorio.",
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $message = 'The given data was invalid.';
            return response()->json(['message' => $message, 'errors' => $errors], 422);
        }

        $startDate = $request->input('fechaDesde') != '' ? date('Y-m-d', strtotime($request->input('fechaDesde'))) : null;
        $endDate = $request->input('fechaHasta') != '' ? date('Y-m-d', strtotime($request->input('fechaHasta'))) : null;

        // Llamar al procedimiento almacenado con los parámetros proporcionados

        // Obtener el tipo de transacción del request
        $tipoTransaccion = $request->idTipoTransaccion;

        // Definir un array asociativo para mapear los valores de idtipomov e idtiporeq
        $transaccionMap = [
            1 => ['idtipomov' => 2, 'idtiporeq' => 1],
            2 => ['idtipomov' => 1, 'idtiporeq' => 2],
            3 => ['idtipomov' => 2, 'idtiporeq' => 2],
            4 => ['idtipomov' => 2, 'idtiporeq' => 3],
            5 => ['idtipomov' => 1, 'idtiporeq' => 3],
        ];

        // Obtener los valores de idtipomov e idtiporeq según el tipo de transacción
        $transaccionValues = $transaccionMap[$tipoTransaccion] ?? ['idtipomov' => 2, 'idtiporeq' => 1];


        $params = [
            'tipovista'     => $request->tipoReporte,
            'idtipomov'     => $transaccionValues['idtipomov'],
            'idtiporeq'     => $transaccionValues['idtiporeq'],
            'idproy'        => $request->idProyectoFinanciamiento,
            'idcentro'      => $request->idCentroAtencion == 0 ? null : $request->idCentroAtencion,
            'idcuenta'      => $request->idCuenta,
            'fecha_inicial' => $startDate,
            'fecha_final'   => $endDate,
        ];

        return DB::select("CALL PR_RPT_CONSUMO(:tipovista, :idtipomov, :idtiporeq, :idproy, :idcentro, :idcuenta, :fecha_inicial, :fecha_final)", $params);

        #return DB::select("CALL PR_RPT_CONSUMO('D',2,1,1,NULL,NULL,'2024-01-01','2024-04-12')");

        #return $endDate;
        #return $params;
    }

    function getReporteDonacion(Request $request)
    {

        $rules = [
            "varFilteredInForm.idProy"       => "required",
            "varFilteredInForm.fechaFinal"   => "required",
            "varFilteredInForm.fechaInicial" => "required",
            "varFilteredInForm.idCentro"     => "required",
            "varFilteredInForm.porcentaje"   => "required",


        ];
        $customMessages = [
            "varFilteredInForm.idProy.required"       => "El proyecto F. es obligatorio.",
            "varFilteredInForm.fechaFinal.required"   => "La fecha final es obligatoria.",
            "varFilteredInForm.fechaInicial.required" => "La fecha inicial es obligatoria.",
            "varFilteredInForm.idCentro.required"     => "El centro es obligatorio.",
            "varFilteredInForm.porcentaje.required"   => "El porcentaje es obligatorio.",
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $message = 'The given data was invalid.';
            return response()->json(['message' => $message, 'errors' => $errors], 422);
        }



        $fechaInicial = $request->varFilteredInForm["fechaInicial"] != '' ? date('Y-m-d', strtotime($request->varFilteredInForm["fechaInicial"])) : null;
        $fechaFinal = $request->varFilteredInForm["fechaFinal"] != '' ? date('Y-m-d', strtotime($request->varFilteredInForm["fechaFinal"])) : null;



        /* $request->paramsToRequest["startDate"] */

        $params = [
            'idproy'        => $request->varFilteredInForm["idProy"],
            'idcentro'      => $request->varFilteredInForm["idCentro"] == 0 ? null : $request->varFilteredInForm["idCentro"],
            'porcentaje'    => $request->varFilteredInForm["porcentaje"],
            'fecha_inicial' => $fechaInicial,
            'fecha_final'   => $fechaFinal,
        ];

        return DB::select("CALL PR_RPT_POCA_ROTACION(:idproy, :idcentro, :porcentaje, :fecha_inicial, :fecha_final)", $params);
    }

    public function getExcelReporteRotacion(Request $request)
    {

        $fechaInicial = $request->varFilteredInForm["fechaInicial"] != '' ? date('Y-m-d', strtotime($request->varFilteredInForm["fechaInicial"])) : null;
        $fechaFinal = $request->varFilteredInForm["fechaFinal"] != '' ? date('Y-m-d', strtotime($request->varFilteredInForm["fechaFinal"])) : null;


        $params = [
            'idproy'        => $request->varFilteredInForm["idProy"],
            'idcentro'      => $request->varFilteredInForm["idCentro"] == 0 ? null : $request->varFilteredInForm["idCentro"],
            'porcentaje'    => $request->varFilteredInForm["porcentaje"],
            'fecha_inicial' => $fechaInicial,
            'fecha_final'   => $fechaFinal,
        ];

        $result = DB::select("CALL PR_RPT_POCA_ROTACION(:idproy, :idcentro, :porcentaje, :fecha_inicial, :fecha_final)", $params);


        // Crear una instancia de Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'SISTEMA DE ALMACEN PARA EL CONTROL DE BIENES EN EXISTENCIA - ISRI');
        $sheet->getStyle('A1')->getFont()->setSize(8);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);


        $sheet->mergeCells('C2:F2');
        $sheet->setCellValue('C2', 'INFORME ANUAL BIENES DE POCA ROTACION');
        $sheet->getStyle('C2')->getFont()->setBold(true)->setSize(18);
        $sheet->getStyle('F2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);


        $sheet->mergeCells('H3:I3');
        $sheet->setCellValue('H3', 'DEL ' . date_format(date_create($fechaInicial), 'd, F, Y'));
        #$sheet->setCellValue('H3', 'DEL 12 , noviembre, 2023');
        $sheet->getStyle('H3')->getFont()->setSize(9);
        $sheet->getStyle('H3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        $sheet->mergeCells('H4:I4');
        $sheet->setCellValue('H4', 'DEL ' . date_format(date_create($fechaFinal), 'd, F, Y'));
        #$sheet->setCellValue('H4', 'AL 16. ABRIL, 2024');
        $sheet->getStyle('H4')->getFont()->setSize(9);
        $sheet->getStyle('H4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);


        $encabezados = [
            'DEPENDENCIA',
            'COD',
            'CONCEPTO',
            'EXISTENCIA AL INICIO DEL PERIODO',
            'ROTACION IDEAL (0.80 EXIST.INI.)',
            'SALIDA DEL PERIODO',
            'DIFERENCIA',
            'EXISTENCIA AL FINAL DEL PERIODO',
            'COSTO UNITA. APROXIMA',
            'MONTO APROXIMA'
        ];


        $sheet->fromArray([$encabezados], null, 'A6');
        $sheet->getRowDimension(6)->setRowHeight(37);
        $sheet->getColumnDimension('C')->setWidth(45);

        // Establecer estilo para encabezados
        $styleHeader = [
            'font' => ['bold' => true, 'size' => 9],
        ];
        $sheet->getStyle('A6:J6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach (range('A', 'J') as $column) {
            $sheet->getStyle($column . '6')->applyFromArray($styleHeader);
            $sheet->getStyle($column . '6')->getAlignment()->setWrapText(true);
        }




        $row = 7; // Comenzar desde la fila 2 para dejar espacio para los encabezados

        foreach ($result as $data) {

            $sheet->setCellValue('A' . $row, $data->sigla_centro_rpt_rotacion);
            $sheet->setCellValue('B' . $row, $data->id_prod_rpt_rotacion);
            $sheet->setCellValue('C' . $row, $data->nombre_prod_rpt_rotacion);

            if ($data->id_tipo_reg_rpt_rotacion == 1) {
                $sheet->getStyle('C' . $row)->getFont()->setBold(true);
            }

            $sheet->setCellValue('D' . $row, $data->existencia_inicial_rtp_rotacion);
            $sheet->setCellValue('E' . $row, $data->rotacion_ideal_rtp_rotacion);
            $sheet->setCellValue('F' . $row, $data->rotacion_real_rtp_rotacion);
            $sheet->setCellValue('G' . $row, $data->diferencia_rtp_rotacion);
            $sheet->setCellValue('H' . $row, $data->existencia_final_rtp_rotacion);
            $sheet->setCellValue('I' . $row, $data->costo_aprox_rtp_rotacion);
            $sheet->setCellValue('J' . $row, $data->saldo_aprox_rtp_rotacion);
            $sheet->getStyle('J' . $row)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');

            // Establecer la alineación centrada para todas las celdas excepto en la columna C
            foreach (range('A', 'J') as $column) {
                if ($column != 'C') {
                    $sheet->getStyle($column . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }
            }

            $sheet->getStyle('A' . $row . ':J' . $row)->getFont()->setName('Calibri')->setSize(9);



            $row++;
        }




        // Guardar el archivo como XLSX
        $writer = new Xlsx($spreadsheet);

        // Establecer el nombre del archivo
        $current_date = Carbon::now()->format('d_m_Y');
        $filename = 'texto_excel_' . $current_date . '.xlsx';

        // Establecer las cabeceras para descargar el archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Guardar el archivo en la salida PHP
        $writer->save('php://output');
    }
}