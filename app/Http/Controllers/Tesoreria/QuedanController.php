<?php

namespace App\Http\Controllers\Tesoreria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quedan;
use App\Models\DetalleQuedan;
use App\Models\Tesorero;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



class QuedanController extends Controller
{
    //
    public function getDataQuedan(Request $request)
    {
        $v_columns = [
            'id_quedan',
            'id_estado_quedan',
            'id_prioridad_pago',
            'id_requerimiento_pago',
            'id_tesorero',
            'id_proy_financiado',
            'id_serie_retencion_iva',
            'id_proveedor',
            'numero_quedan',
            'numero_retencion_iva_quedan',
            'numero_compromiso_ppto_quedan',
            'fecha_emision_quedan',
            'fecha_retencion_iva_quedan',
            'fecha_pago_quedan',
            'monto_liquido_quedan',
            'monto_iva_quedan',
            'monto_isr_quedan',
            'descripcion_quedan',
            'estado_quedan',
            'fecha_reg_quedan',
            'fecha_mod_quedan',
            'usuario_quedan',
            'ip_quedan',
        ];

        $v_length = $request->input('length');
        $v_column = $request->input('column'); //Index
        $v_dir = $request->input('dir');
        $data = $request->input('search');
        $v_query = Quedan::select('*')->with("detalle_quedan");

        if ($data) {
            $v_query->where('id_quedan', 'like', '%' . $data["id_quedan"] . '%');

        }

        $v_roles = $v_query->paginate($v_length)->onEachSide(1);
        return [
            'data' => $v_roles,
            'draw' => $request->input('draw'),
        ];
    }

    public function addQuedan(Request $request)
    {
        $quedan = Quedan::insertGetId([
            //creadon quedan
            'id_estado_quedan'     => 1,
            'id_tesorero'          => Tesorero::select("id_tesorero")->where('estado_tesorero', 1)->get()[0]->id_tesorero,
            'fecha_emision_quedan' => Carbon::now(),
            'estado_quedan'        => 1,
            'fecha_reg_quedan'     => Carbon::now(),
        ]);
        DetalleQuedan::create([
            //agregando por defecto un detalle al quedan agregado anteriormente
            'id_quedan'                => $quedan,
            'fecha_reg_det_quedan'     => Carbon::now(),
            'fecha_factura_det_quedan' => Carbon::now(),
        ]);
        return $quedan;
    }
    public function addDetalleQuedan(Request $request)
    {
        return DetalleQuedan::create([
            //agregando por defecto un detalle al quedan agregado anteriormente
            'id_quedan'                => $request->input('id_quedan'),
            'fecha_reg_det_quedan'     => Carbon::now(),
            'fecha_factura_det_quedan' => Carbon::now(),
        ]);
    }
    public function getQuedan(Request $request)
    {
        return Quedan::with("detalle_quedan")->find($request->input("id_quedan"));
    }

    public function updateDetalleQuedan(Request $request)
    {
        return DetalleQuedan
            ::where('id_det_quedan', $request->input('id_det_quedan'))
            ->where('id_quedan', $request->input('id_quedan'))->update([
                $request->input("campos") => $request->input("value"),
            ]);
    }
    public function getListForSelect()
    {
        $v_Dependencias = DB::table('dependencia')
            ->select(
                'id_dependencia as value',
                'nombre_dependencia as label'
            )->whereNotNull('dep_id_dependencia')->get();

        $v_AcuerdoCompra = DB::table('acuerdo_compra')
            ->select(
                'id_acuerdo_compra as value',
                'nombre_acuerdo_compra as label'
            )->get();

        return [
            "dependencias"   => $v_Dependencias,
            "acuerdoCompras" => $v_AcuerdoCompra
        ];
    }

}