<?php

namespace App\Http\Controllers\Tesoreria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tesoreria\ReporteFacturaRequest;
use App\Http\Requests\Tesoreria\ReporteQuedanRequest;
use App\Http\Requests\Tesoreria\RetencionISRRequest;
use App\Models\EstadoQuedan;
use App\Models\Proveedor;
use App\Models\ProyectoFinanciado;
use App\Models\RequerimientoPago;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Illuminate\Support\Facades\DB;

class ReporteTesoreriaController extends Controller
{
    public function getSelectsReport(Request $request){
        $financing_sources = ProyectoFinanciado::select('id_proy_financiado as value', 'nombre_proy_financiado as label')
            ->where('estado_proy_financiado', '=', 1)
            ->orderBy('nombre_proy_financiado')
            ->get();
        $states_quedan = EstadoQuedan::select('id_estado_quedan as value', 'nombre_estado_quedan as label')
            ->get();
        return ['financing_sources' => $financing_sources, 'states_quedan' => $states_quedan];
    }
    public function createQuedanReport(ReporteQuedanRequest $request){
        if ($request->financing_source_id != '' || $request->financing_source_id != null) {
            $query_financing_source = 'AND `id_proy_financiado` = ' . $request->financing_source_id;
        } else {
            $query_financing_source = '';
        }
        if ($request->state_quedan_id != '' || $request->state_quedan_id != null) {
            $query_quedan_state = ' AND `id_estado_quedan` = ' . $request->state_quedan_id;
        } else {
            $query_quedan_state = '';
        }
        $array = DB::select(
            '
            SELECT 
            `p`.`razon_social_proveedor`, 
            `rp`.`numero_requerimiento_pago`, 
            DATE_FORMAT(`rp`.`fecha_requerimiento_pago`,"%d/%m/%Y") as fecha_requerimiento_pago, 
            `monto_liquido_quedan`,
            IFNULL((SELECT SUM(`lq`.`monto_liquidacion_quedan`) FROM `liquidacion_quedan` lq WHERE `lq`.`id_quedan` = `quedan`.`id_quedan` GROUP BY `lq`.`id_quedan` ), 0) AS monto_pagado, 
            DATE_FORMAT(`fecha_emision_quedan`,"%d/%m/%Y") as fecha_emision_quedan, 
            `pp`.`nivel_prioridad_pago`,
            `pp`.`nombre_prioridad_pago` 
            FROM 
            `quedan` 
            INNER JOIN `proveedor` AS p on `quedan`.`id_proveedor` = `p`.`id_proveedor` 
            LEFT OUTER JOIN `requerimiento_pago` AS rp ON `quedan`.`id_requerimiento_pago` = `rp`.`id_requerimiento_pago`
            INNER JOIN `prioridad_pago` AS pp on `quedan`.`id_prioridad_pago` = `pp`.`id_prioridad_pago`
            WHERE `fecha_emision_quedan` BETWEEN ? AND ?
            ' . $query_financing_source . $query_quedan_state,
            [$request->start_date, $request->end_date]
        );

        if (empty($array)) {
            return response()->json(['error' => 'No se encontraron registros'], 404);
        }

        $array = json_decode(json_encode($array), true);

        array_unshift($array, array('SUMINISTRANTE', 'N° REQUERIMIENTO', 'FECHA REQUERIMIENTO', 'MONTO LIQUIDO', 'MONTO PAGADO', 'FECHA QUEDAN', 'PRIORIDAD', 'DESCRIPCION'));

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getStyle('3')->getFont()->setBold(true);
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        foreach ($columns as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $sheet->fromArray($array, NULL, 'A3');

        // Combina las celdas 
        $sheet->mergeCells('A2:H2');
        $sheet->mergeCells('A1:H1');

        $fecha_fin = Carbon::createFromFormat('Y-m-d', $request->end_date);
        $fecha_inicio = Carbon::createFromFormat('Y-m-d', $request->start_date);
        Carbon::setLocale('es');
        $fecha_f_fin = $fecha_fin->isoFormat('D [DE] MMMM [DE] YYYY');
        $fecha_f_inicio = $fecha_inicio->isoFormat('D [DE] MMMM');
        $join_date = $fecha_f_inicio . ' AL ' . $fecha_f_fin;
        $fechaFormateada = strtoupper($join_date);

        //Definiendo nombre de fuente de financiamiento para desplegar en el informe
        $fuente = ProyectoFinanciado::find($request->financing_source_id);
        if ($fuente) {
            $fuente_financiamiento = $fuente->nombre_proy_financiado;
        } else {
            $fuente_financiamiento = 'TODOS LOS FONDOS';
        }
        //Definimos el estado del quedan
        $estado = EstadoQuedan::find($request->state_quedan_id);
        if ($estado) {
            $quedan_state = $estado->nombre_estado_quedan;
        } else {
            $quedan_state = 'TODOS LOS ESTADOS';
        }

        //Calculo de la suma de monto liquido
        $total_liquido = 0;
        $total_pagado = 0;
        $lastRow = $sheet->getHighestDataRow(); // obtiene el número de la última fila con datos
        for ($i = 3; $i <= $lastRow; $i++) { // comienza en la fila 3 (primer dato) y recorre hasta la última fila con datos
            $value_liquido = $sheet->getCell('D' . $i)->getValue(); // obtiene el valor de la celda D de la fila actual
            if (is_numeric($value_liquido)) { // comprueba que el valor sea numérico
                $total_liquido += $value_liquido; // suma el valor a la variable $total
            }
            $value_pagado = $sheet->getCell('E' . $i)->getValue(); // obtiene el valor de la celda D de la fila actual
            if (is_numeric($value_pagado)) { // comprueba que el valor sea numérico
                $total_pagado += $value_pagado; // suma el valor a la variable $total
            }
        }
        $sheet->setCellValue('D' . ($lastRow + 1), $total_liquido);
        $sheet->setCellValue('E' . ($lastRow + 1), $total_pagado);
        $sheet->setCellValue('C' . ($lastRow + 1), 'TOTAL');
        //Definiendo en negrita las celdas para el total y los montos sumados
        $sheet->getStyle('C' . ($lastRow + 1))->getFont()->setBold(true);
        $sheet->getStyle('D' . ($lastRow + 1))->getFont()->setBold(true);
        $sheet->getStyle('E' . ($lastRow + 1))->getFont()->setBold(true);

        // Definimos encabezado
        $sheet->setCellValue('A2', 'REPORTE DE QUEDAN DEL ' . $fechaFormateada . ' - ' . $quedan_state . ' - ' . $fuente_financiamiento);
        $sheet->setCellValue('A1', 'INSTITUTO SALVADOREÑO DE REHABILITACION INTEGRAL - ISRI');
        $sheet->getStyle('A1:A2')->getFont()->setSize(14);

        // Aplica el formato de centrado y negrita al texto
        $style = $sheet->getStyle('A1:A2');
        $style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $style->getFont()->setBold(true);

        $writer = new Xlsx($spreadsheet);

        $filename = 'RPT_REPORTE_QUEDAN.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
    public function getSelectsInvoiceReporting(Request $request){
        $financing_sources = ProyectoFinanciado::select('id_proy_financiado as value', 'nombre_proy_financiado as label')
            ->where('estado_proy_financiado', '=', 1)
            ->orderBy('nombre_proy_financiado')
            ->get();
        $suppliers = Proveedor::select('id_proveedor as value', 'razon_social_proveedor as label')
            ->where('estado_proveedor', '=', 1)
            ->orderBy('razon_social_proveedor')
            ->get();
        $requirement_collection = RequerimientoPago::selectRaw("id_requerimiento_pago as value, concat(numero_requerimiento_pago, ' - ', anio_requerimiento_pago) as label")
            ->where('estado_requerimiento_pago', '=', 1)
            ->orderBy('id_requerimiento_pago', 'DESC')
            ->get();
        $requirements = $requirement_collection->toArray();
        array_unshift($requirements, ['value' => -1, 'label' => 'Sin requerimiento']);
        return ['suppliers' => $suppliers, 'requirements' => $requirements, 'financing_sources' => $financing_sources];
    }
    public function createInvoiceReport(ReporteFacturaRequest $request){
        if ($request->supplier_id != "" && $request->supplier_id != null) {
            $query_supplier = 'AND q.id_proveedor = ' . $request->supplier_id;
        } else {
            $query_supplier = '';
        }
        if ($request->requirement_id != "" && $request->requirement_id != null) {
            if ($request->requirement_id == -1) {
                $query_requirement = ' AND q.id_requerimiento_pago IS NULL OR q.id_requerimiento_pago = ""';
            } else {
                $query_requirement = ' AND q.id_requerimiento_pago = ' . $request->requirement_id;
            }
        } else {
            $query_requirement = '';
        }
        if ($request->financing_source_id != "" && $request->financing_source_id != null) {
            $query_financing_source = ' AND q.id_proy_financiado = ' . $request->financing_source_id;
        } else {
            $query_financing_source = '';
        }
        $array = DB::select(
            '
            SELECT 
			IFNULL(p.nit_proveedor,p.dui_proveedor) AS documento,
			p.razon_social_proveedor,
			dp.codigo_dependencia,
			q.numero_compromiso_ppto_quedan,
			rp.numero_requerimiento_pago,
            DATE_FORMAT(dq.fecha_factura_det_quedan,"%d/%m/%Y") as fecha_factura_det_quedan,
			(IFNULL(dq.producto_factura_det_quedan,0)+IFNULL(dq.servicio_factura_det_quedan,0)) AS total_factura,
			-- (IFNULL(q.monto_liquido_quedan,0)+IFNULL(q.monto_iva_quedan,0)+IFNULL(q.monto_isr_quedan,0)) AS total_quedan,
			ROUND(IF((IFNULL(q.monto_liquido_quedan,0)+IFNULL(q.monto_iva_quedan,0)+IFNULL(q.monto_isr_quedan,0))>=113,
			(((IFNULL(dq.producto_factura_det_quedan,0)+IFNULL(dq.servicio_factura_det_quedan,0))/1.13)*sr.iva_sujeto_retencion),0),2) as retencion_iva,
			ROUND(IFNULL((dq.servicio_factura_det_quedan*sr.isrl_sujeto_retencion),0),2) AS rentencion_isr,
			((IFNULL(dq.producto_factura_det_quedan,0)+IFNULL(dq.servicio_factura_det_quedan,0))- ROUND(IF((IFNULL(q.monto_liquido_quedan,0)+IFNULL(q.monto_iva_quedan,0)+IFNULL(q.monto_isr_quedan,0))>=113,
			(((IFNULL(dq.producto_factura_det_quedan,0)+IFNULL(dq.servicio_factura_det_quedan,0))/1.13)*sr.iva_sujeto_retencion),0),2) - ROUND(IFNULL((dq.servicio_factura_det_quedan*sr.isrl_sujeto_retencion),0),2)) as liquido,
			pp.nombre_prioridad_pago,
            pp.nivel_prioridad_pago
            FROM 
            quedan AS q
            INNER JOIN detalle_quedan AS dq on q.id_quedan = dq.id_quedan
            INNER JOIN dependencia AS dp on dq.id_dependencia = dp.id_dependencia 
            INNER JOIN proveedor AS p on q.id_proveedor = p.id_proveedor
            INNER JOIN sujeto_retencion AS sr on p.id_sujeto_retencion = sr.id_sujeto_retencion  
            LEFT OUTER JOIN requerimiento_pago AS rp on q.id_requerimiento_pago = rp.id_requerimiento_pago 
            LEFT OUTER JOIN prioridad_pago AS pp on q.id_prioridad_pago = pp.id_prioridad_pago

            WHERE dq.fecha_factura_det_quedan BETWEEN ? AND ?
            ' . $query_supplier . $query_requirement . $query_financing_source,
            [$request->start_date, $request->end_date]
        );
        // return ['data' => $array];

        if (empty($array)) {
            return response()->json(['error' => 'No se encontraron registros'], 404);
        }

        $array = json_decode(json_encode($array), true);
        array_unshift($array, array('NIT/DUI', 'SUMINISTRANTE', 'CENTRO', 'COMPROMISO', 'REQ', 'FECHA', 'MONTO', 'RENTA', '1% IVA', 'LIQUIDO', 'CONCEPTO', 'PRIORIDAD'));
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $fecha_fin = Carbon::createFromFormat('Y-m-d', $request->end_date);
        $fecha_inicio = Carbon::createFromFormat('Y-m-d', $request->start_date);
        Carbon::setLocale('es');
        $fecha_f_fin = $fecha_fin->isoFormat('D [DE] MMMM [DE] YYYY');
        $fecha_f_inicio = $fecha_inicio->isoFormat('D [DE] MMMM');
        $join_date = $fecha_f_inicio . ' AL ' . $fecha_f_fin;
        $fechaFormateada = strtoupper($join_date);

        //Definiendo nombre de fuente de financiamiento para desplegar en el informe
        $fuente = ProyectoFinanciado::find($request->financing_source_id);
        if ($fuente) {
            $fuente_financiamiento = $fuente->nombre_proy_financiado;
        } else {
            $fuente_financiamiento = 'TODOS LOS FONDOS';
        }
        // Combina las celdas 
        $sheet->mergeCells('A2:L2');
        $sheet->mergeCells('A1:L1');
        // Definimos encabezado
        $sheet->setCellValue('A2', 'REPORTE DE FACTURAS DEL ' . $fechaFormateada . ' - ' . $fuente_financiamiento);
        $sheet->setCellValue('A1', 'INSTITUTO SALVADOREÑO DE REHABILITACION INTEGRAL - ISRI');
        $sheet->getStyle('A1:A2')->getFont()->setSize(14);

        // Aplica el formato de centrado y negrita al texto
        $style = $sheet->getStyle('A1:A2');
        $style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $style->getFont()->setBold(true);

        $sheet->getStyle('3')->getFont()->setBold(true);
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];
        foreach ($columns as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $sheet->fromArray($array, NULL, 'A3');

        $writer = new Xlsx($spreadsheet);

        $filename = 'RPT_REPORTE_FACTURAS.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function createQuedanReportPDF(ReporteQuedanRequest $request){
        if ($request->financing_source_id != '' || $request->financing_source_id != null) {
            $query_financing_source = 'AND `id_proy_financiado` = ' . $request->financing_source_id;
        } else {
            $query_financing_source = '';
        }
        if ($request->state_quedan_id != '' || $request->state_quedan_id != null) {
            $query_quedan_state = ' AND `id_estado_quedan` = ' . $request->state_quedan_id;
        } else {
            $query_quedan_state = '';
        }
        $array = DB::select(
            '
            SELECT 
            `p`.`razon_social_proveedor`, 
            `rp`.`numero_requerimiento_pago`, 
            DATE_FORMAT(`rp`.`fecha_requerimiento_pago`,"%d/%m/%Y") as fecha_requerimiento_pago, 
            `monto_liquido_quedan`,
            IFNULL((SELECT SUM(`lq`.`monto_liquidacion_quedan`) FROM `liquidacion_quedan` lq WHERE `lq`.`id_quedan` = `quedan`.`id_quedan` GROUP BY `lq`.`id_quedan` ), 0) AS monto_pagado, 
            DATE_FORMAT(`fecha_emision_quedan`,"%d/%m/%Y") as fecha_emision_quedan, 
            `pp`.`nivel_prioridad_pago`,
            `pp`.`nombre_prioridad_pago` 
            FROM 
            `quedan` 
            INNER JOIN `proveedor` AS p on `quedan`.`id_proveedor` = `p`.`id_proveedor` 
            LEFT OUTER JOIN `requerimiento_pago` AS rp ON `quedan`.`id_requerimiento_pago` = `rp`.`id_requerimiento_pago`
            INNER JOIN `prioridad_pago` AS pp on `quedan`.`id_prioridad_pago` = `pp`.`id_prioridad_pago`
            WHERE `fecha_emision_quedan` BETWEEN ? AND ?
            ' . $query_financing_source . $query_quedan_state,
            [$request->start_date, $request->end_date]
        );

        if (empty($array)) {
            return response()->json(['error' => 'No se encontraron registros'], 404);
        }
        return ['mensaje' => 'Hola desde el back'];
    }
    public function getSelectsWithholdingTaxReport(Request $request){
        $financing_sources = ProyectoFinanciado::select('id_proy_financiado as value', 'nombre_proy_financiado as label')
            ->where('estado_proy_financiado', '=', 1)
            ->orderBy('nombre_proy_financiado')
            ->get();
        return ['financing_sources' => $financing_sources];
    }
    public function createIncomeTaxReport(RetencionISRRequest $request){
        $array = DB::select(
            '
            SELECT 	sr.codigo_mh_sujeto_retencion, 
					p.codigo_mh_pais, 
					UPPER(pv.razon_social_proveedor) AS razon_social_proveedor, 
					IFNULL(REPLACE(pv.nit_proveedor, "-", ""),"") as nit,
					IFNULL(REPLACE(pv.dui_proveedor, "-", ""),"") as dui,
					"11" AS codigo_ingreso,
					q.monto_isr_quedan + q.monto_iva_quedan + q.monto_liquido_quedan AS monto_devengado,
					"00.00" AS bonificaciones,
                    -- q.monto_isr_quedan AS monto_retenido,
                    q.monto_isr_quedan,
					"00.00" AS aguinaldo_exento,
					"00.00" AS aguinaldo_gravado,
					"00.00" AS afp,
					"00.00" AS isss,
					"00.00" AS inpep,
					"00.00" AS ipsfa,
					"00.00" AS cefafa,
					"00.00" AS bienestar_magisterial,
					"00.00" AS isss_ivm,
					CONCAT(LPAD(rp.mes_requerimiento_pago, 2, "0"), rp.anio_requerimiento_pago) AS periodo
					
			FROM quedan q
			INNER JOIN proyecto_financiado pf ON q.id_proy_financiado = pf.id_proy_financiado
			INNER JOIN requerimiento_pago rp ON q.id_requerimiento_pago = rp.id_requerimiento_pago
			INNER JOIN proveedor pv ON q.id_proveedor = pv.id_proveedor
			INNER JOIN sujeto_retencion sr ON pv.id_sujeto_retencion = sr.id_sujeto_retencion
			INNER JOIN municipio m ON pv.id_municipio = m.id_municipio
			INNER JOIN departamento d ON m.id_departamento = d.id_departamento
			INNER JOIN pais p ON d.id_pais = p.id_pais
			
			WHERE q.fecha_emision_quedan BETWEEN ? AND ?
            AND q.id_proy_financiado = ?',
            [$request->start_date, $request->end_date, $request->financing_source_id]
        );
        if (empty($array)) {
            return response()->json(['error' => 'No se encontraron registros'], 404);
        }

        $array = json_decode(json_encode($array), true);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $formatDecimal = '0.00';

        $sheet->getStyle('G')->getNumberFormat()->setFormatCode($formatDecimal);
        $sheet->getStyle('I')->getNumberFormat()->setFormatCode($formatDecimal);

        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);

        $sheet->fromArray($array, NULL, 'A1');

        $writer = new Xlsx($spreadsheet);

        $current_date = Carbon::now()->format('d-m-Y');
        $filename = 'RPT_RETENCION_ISR' . $current_date . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
    public function createWithholdingIVAReport(RetencionISRRequest $request){
        $array = DB::select(
            '
            SELECT
                REPLACE(p.nit_proveedor, "-", "") as nit,
                DATE_FORMAT(q.fecha_emision_quedan,"%d/%m/%Y") AS fecha,
                "07" AS tipo_doc,
                sr.resolucion_serie_retencion_iva,
                sr.serie_retencion_iva,
                q.numero_retencion_iva_quedan,
                (IFNULL(q.monto_liquido_quedan,0)+IFNULL(q.monto_iva_quedan,0)+IFNULL(q.monto_isr_quedan,0)) AS monto_sujeto,
                q.monto_iva_quedan,
                REPLACE(p.dui_proveedor, "-", "") as dui,
                "10" AS anexo 
            FROM
                quedan q
            INNER JOIN proveedor p ON q.id_proveedor = p.id_proveedor
            LEFT JOIN serie_retencion_iva sr on q.id_serie_retencion_iva = sr.id_serie_retencion_iva
			
			WHERE q.fecha_emision_quedan BETWEEN ? AND ?
            AND q.id_proy_financiado = ?',
            [$request->start_date, $request->end_date, $request->financing_source_id]
        );
        if (empty($array)) {
            return response()->json(['error' => 'No se encontraron registros'], 404);
        }

        $array = json_decode(json_encode($array), true);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
        foreach ($columns as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $sheet->fromArray($array, NULL, 'A1');

        $writer = new Xlsx($spreadsheet);

        $current_date = Carbon::now()->format('d-m-Y');
        $filename = 'RPT_RETENCION_ISR' . $current_date . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
