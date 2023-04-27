<?php

namespace App\Http\Controllers\Tesoreria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Quedan;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReporteTesoreriaController extends Controller
{
    public function testExcel(){
        $array = Quedan::select('*')->with([
            "detalle_quedan", "proveedor.giro", 
            "proveedor.sujeto_retencion", "tesorero", 
            "requerimiento_pago","acuerdo_compra"
            ])
                ->get()
                ->toArray();
        return ['array' => $array];

        
        array_unshift($user, array('SUMINISTRANTE', 'N° REQUERIMIENTO','FECHA REQUERIMIENTO','MONTO LIQUIDO','FECHA QUEDAN','PRIORIDAD','DESCRIPCION'));
        
        $data = [
            ['Name', 'Email'],
            ['John Doe', 'johndoe@example.com'],
            ['Jane Smith', 'janesmith@example.com'],
            ['Melvin', 'melvin@example.com'],
            // ...
        ];
    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        $sheet->fromArray($user, NULL, 'A3');
    
        $writer = new Xlsx($spreadsheet);
    
        $filename = 'data.xlsx';
    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
    
        $writer->save('php://output');
    }
}
