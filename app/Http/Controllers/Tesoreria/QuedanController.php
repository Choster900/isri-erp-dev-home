<?php

namespace App\Http\Controllers\Tesoreria;

use App\Http\Controllers\Controller;
use App\Models\EmpleadoTesoreria;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use App\Models\Quedan;
use App\Models\DetalleQuedan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Tesoreria\QuedanRequest;


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
        $v_column = $request->input('column');
        $v_dir = $request->input('dir');
        $data = $request->input('search');
        $v_query = Quedan::select('*')->with(["detalle_quedan", "proveedor.giro", "proveedor.sujeto_retencion", "tesorero", "requerimiento_pago", "acuerdo_compra", "liquidacion_quedan"])->orderBy($v_columns[$v_column], $v_dir);


        if ($request->input("allWithANumberRequest")) {
            $v_query->where("id_estado_quedan", ">=", 2);
        }


        if ($data) {
            if (isset($data['id_quedan'])) {
                $v_query->where('id_quedan', 'like', '%' . $data["id_quedan"] . '%');
            }
            $v_query->where('fecha_emision_quedan', 'like', '%' . $data["fecha_emision_quedan"] . '%');

            $searchText = $data["buscar_por_detalle_quedan"];
            $v_query->whereHas('detalle_quedan', function ($query) use ($searchText) {
                $query->where(function ($query) use ($searchText) {
                    $query->where('numero_factura_det_quedan', 'like', '%' . $searchText . '%')
                        ->orWhere('numero_acta_det_quedan', 'like', '%' . $searchText . '%')
                        ->orWhere('producto_factura_det_quedan', 'like', '%' . $searchText . '%')
                        ->orWhere('servicio_factura_det_quedan', 'like', '%' . $searchText . '%')
                        ->orWhere('descripcion_det_quedan', 'like', '%' . $searchText . '%');
                });
            });


            $v_query->whereHas('requerimiento_pago', function ($query) use ($data) {
                $query->where('numero_requerimiento_pago', 'like', '%' . $data["numero_requerimiento_pago"] . '%');
            });


            $v_query->whereHas('proveedor', function ($query) use ($data) {
                $query->where('razon_social_proveedor', 'like', '%' . $data["razon_social_proveedor"] . '%');
            });
            $v_query->where('monto_liquido_quedan', 'like', '%' . $data["monto_liquido_quedan"] . '%');

            if (isset($data['id_requerimiento_pago'])) {
                $v_query->where('id_requerimiento_pago', 'like', '%' . $data["id_requerimiento_pago"] . '%');
            }

            if (isset($data['id_estado_quedan'])) {
                $v_query->where('id_estado_quedan', 'like', '%' . $data["id_estado_quedan"] . '%');
            }
        }

        $v_roles = $v_query->paginate($v_length)->onEachSide(1);
        return [
            'data' => $v_roles,
            'draw' => $request->input('draw'),
        ];
    }

    public function addQuedan(QuedanRequest $request)
    {
        $detalle_quedan = $request->detalle_quedan;

        try {
            DB::beginTransaction();
            $quedan = Quedan::insertGetId([
                'id_estado_quedan'              => 1,
                'id_proy_financiado'            => $request->quedan["id_proy_financiado"],
                'id_prioridad_pago'             => $request->quedan["id_prioridad_pago"],
                'id_proveedor'                  => $request->quedan["id_proveedor"],
                'id_acuerdo_compra'             => $request->quedan["id_acuerdo_compra"],
                'numero_acuerdo_quedan'         => $request->quedan["numero_acuerdo_quedan"],
                'numero_compromiso_ppto_quedan' => $request->quedan["numero_compromiso_ppto_quedan"],
                'descripcion_quedan'            => $request->quedan["descripcion_quedan"],
                'monto_liquido_quedan'          => $request->quedan["monto_liquido_quedan"],
                'monto_iva_quedan'              => $request->quedan["monto_iva_quedan"],
                'monto_isr_quedan'              => $request->quedan["monto_isr_quedan"],
                'id_empleado_tesoreria'         => EmpleadoTesoreria::select("id_empleado_tesoreria")->where('estado_empleado_tesoreria', 1)->where("id_cargo_tesoreria", 1)->get()[0]->id_empleado_tesoreria,
                'fecha_emision_quedan'          => Carbon::now(),
                'estado_quedan'                 => 1,
                'fecha_reg_quedan'              => Carbon::now(),

            ]);


            foreach ( $detalle_quedan as $key => $value ) {

                if ($value[7]) {
                    $new_detalle = array(
                        'id_quedan'                   => $quedan,
                        'numero_factura_det_quedan'   => $value[2],
                        'id_dependencia'              => $value[3],
                        'numero_acta_det_quedan'      => $value[4],
                        'descripcion_det_quedan'      => $value[5],
                        'producto_factura_det_quedan' => $value[6]['producto'],
                        'servicio_factura_det_quedan' => $value[6]['servicio'],
                        'fecha_factura_det_quedan'    => Carbon::now(),
                        'fecha_reg_det_quedan'        => Carbon::now(),
                        'usuario_det_quedan'          => $request->user()->nick_usuario,
                        'ip_det_quedan'               => $request->ip(),

                    );
                    DetalleQuedan::create($new_detalle);
                }
            }

            $res = Quedan::select('*')->with([
                "detalle_quedan",
                "proveedor.giro",
                "proveedor.sujeto_retencion",
                "tesorero"
            ])->where("id_quedan", $quedan)->get();

            DB::commit();
            return $res;
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }
    public function updateDetalleQuedan(QuedanRequest $request)
    {
        $id_quedan = $request->id_quedan;
        $detalle_quedan = $request->detalle_quedan;


        try {
            DB::beginTransaction();

            Quedan::where("id_quedan", $id_quedan)->update([
                'id_proveedor'                  => $request->quedan["id_proveedor"],
                'id_acuerdo_compra'             => $request->quedan["id_acuerdo_compra"],
                'numero_acuerdo_quedan'         => $request->quedan["numero_acuerdo_quedan"],
                'numero_compromiso_ppto_quedan' => $request->quedan["numero_compromiso_ppto_quedan"],
                'descripcion_quedan'            => $request->quedan["descripcion_quedan"],
                'monto_liquido_quedan'          => $request->quedan["monto_liquido_quedan"],
                'monto_iva_quedan'              => $request->quedan["monto_iva_quedan"],
                'monto_isr_quedan'              => $request->quedan["monto_isr_quedan"],
                'id_proy_financiado'            => $request->quedan["id_proy_financiado"],
                'id_prioridad_pago'             => $request->quedan["id_prioridad_pago"],
            ]);

            foreach ( $detalle_quedan as $key => $value ) {

                if ($value[0] == '') { //validar que exista el detalle a modifica
                    $new_detalle = array(
                        'numero_factura_det_quedan'   => $value[2],
                        'id_dependencia'              => $value[3],
                        'numero_acta_det_quedan'      => $value[4],
                        'descripcion_det_quedan'      => $value[5],
                        'producto_factura_det_quedan' => $value[6]['producto'],
                        'servicio_factura_det_quedan' => $value[6]['servicio'],
                        'fecha_mod_det_quedan'        => Carbon::now(),
                        'usuario_det_quedan'          => $request->user()->nick_usuario,
                        'ip_det_quedan'               => $request->ip(),
                    );
                    DetalleQuedan::where("id_det_quedan", $value[1])->update($new_detalle);
                }
                if ($value[0] == 1 && $value[7] !== false) { //al momento de editar puede que agrege filas entonces se valida que la fila sea nueva 
                    $new_detalle = array(
                        'id_quedan'                   => $id_quedan,
                        'numero_factura_det_quedan'   => $value[2],
                        'id_dependencia'              => $value[3],
                        'numero_acta_det_quedan'      => $value[4],
                        'descripcion_det_quedan'      => $value[5],
                        'producto_factura_det_quedan' => $value[6]['producto'],
                        'servicio_factura_det_quedan' => $value[6]['servicio'],
                        'fecha_factura_det_quedan'    => Carbon::now(),
                        'fecha_reg_det_quedan'        => Carbon::now(),
                        'usuario_det_quedan'          => $request->user()->nick_usuario,
                        'ip_det_quedan'               => $request->ip(),
                    );
                    DetalleQuedan::create($new_detalle);
                }
                if ($value[7] === false && $value[0] == '') { //validar que la fila sea eliminada
                    $res = DetalleQuedan::find($value[1]);
                    $res->delete();
                }
            }

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }
    public function getListForSelect()
    {
        $v_Dependencias = DB::table('dependencia')
            ->select(
                'id_dependencia as value',
                'nombre_dependencia as label'
            )->whereNull('dep_id_dependencia')->get();

        $v_AcuerdoCompra = DB::table('acuerdo_compra')
            ->select(
                'id_acuerdo_compra as value',
                'nombre_acuerdo_compra as label'
            )->get();

        $v_Proveedor = DB::table('proveedor')
            ->select(
                'id_proveedor as value',
                'razon_social_proveedor as label'
            )->where("estado_proveedor", 1)->get();
        $v_Requerimiento = DB::table('requerimiento_pago')
            ->select(
                'id_requerimiento_pago as value',
                DB::raw("CONCAT(numero_requerimiento_pago,' - ',anio_requerimiento_pago) AS label")
            )->get(); //TODO: poner el estado del requerimiento pago donde exista y este abierto y no cerrado

        $v_Prioridad_pago = DB::table('prioridad_pago')
            ->select(
                'id_prioridad_pago as value',
                DB::raw("CONCAT(nivel_prioridad_pago,' - ',nombre_prioridad_pago) AS label")
            )->get();

        $v_Proyecto_finanziado = DB::table('proyecto_financiado')
            ->select(
                'id_proy_financiado as value',
                DB::raw("CONCAT(codigo_proy_financiado,' - ',nombre_proy_financiado) AS label")
            )->get();


        return [
            "dependencias"        => $v_Dependencias,
            "acuerdoCompras"      => $v_AcuerdoCompra,
            "proveedor"           => $v_Proveedor,
            "numeroRequerimiento" => $v_Requerimiento,
            "prioridadPago"       => $v_Prioridad_pago,
            "proyectoFinanciado"  => $v_Proyecto_finanziado,
        ];
    }
    public function getSuppliers() //Metodo utilizado al momento de seleccionar proveedor y hacer los calculos 
    { //se hacen esta peticion por que al no existir el quedan no existen registos previos de la base de datos y no podemos ver a que provedor pertenece
        return Proveedor::with(['sujeto_retencion', 'giro'])->where("estado_proveedor", 1)->get();
    }
}