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
use Luecano\NumeroALetras\NumeroALetras;
use Illuminate\Pagination\LengthAwarePaginator;


class QuedanController extends Controller
{
    //
    public function getDataQuedan(Request $request)
    {
        // Definir las columnas seleccionadas para la consulta
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
        // Obtener los parámetros de la solicitud
        $v_length = $request->input('length');
        $v_column = $request->input('column');
        $v_dir = $request->input('dir');
        $data = $request->input('search');

        // Construir la consulta base con las relaciones
        $v_query = Quedan::select('*')->with([
            "detalle_quedan",
            "tesorero",
            "requerimiento_pago",
            "acuerdo_compra",
            "liquidacion_quedan",
            "proyecto_financiado",
            "serie_retencion_iva",
            "proveedor.giro",
            "proveedor.sujeto_retencion",
        ])->orderBy($v_columns[$v_column], $v_dir);

        // Aplicar filtro para requerimientos de número mayor o igual a 2
        if ($request->input("allWithANumberRequest")) {
            $v_query->where("id_estado_quedan", ">=", 2);
        }

        // Aplicar filtros de búsqueda
        if ($data) {
            if (isset($data['id_quedan'])) {
                $v_query->where('id_quedan', 'like', '%' . $data["id_quedan"] . '%');
            }
            $v_query->where('fecha_emision_quedan', 'like', '%' . $data["fecha_emision_quedan"] . '%');

            // Filtro de búsqueda en detalle_quedan
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

            if (isset($data['numero_requerimiento_pago'])) {
                $v_query->whereHas('requerimiento_pago', function ($query) use ($data) {
                    $query->where('numero_requerimiento_pago', 'like', '%' . $data["numero_requerimiento_pago"] . '%');
                });
            }
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
        // Ejecutar la consulta paginada
        $v_quedan = $v_query->paginate($v_length)->onEachSide(1);

        // Convertir el monto en letra usando la clase NumeroALetras
        $numbersToLetters = new NumeroALetras();
        $amountLetter = array_map(function ($v_quedan) use ($numbersToLetters) {
            return ['monto_iva_quedan_letter' => $numbersToLetters->toInvoice($v_quedan['monto_iva_quedan'], 2, 'DÓLARES')];
        }, $v_quedan->toArray()['data']);

        // Mapear los resultados para agregar el monto en letra
        $newQuery = $v_quedan->map(function ($item, $key) use ($amountLetter) {
            $item->monto_iva_quedan_letter = $amountLetter[$key]['monto_iva_quedan_letter'];
            return $item;
        });

        $paginator = new LengthAwarePaginator(
            $newQuery,
            $v_quedan->total(),
            $v_quedan->perPage(),
            $v_quedan->currentPage(),
            ['path' => url()->current()]
        );
        //retornado la data en un arreglo
        return [
            'data' => $paginator,
            'draw' => $request->input('draw'),
        ];
    }

    public function addQuedan(QuedanRequest $request)
    {
        $detalle_quedan = $request->detalle_quedan;

        try {
            DB::beginTransaction();
            // Insertar el registro de quedan y obtener el ID generado
            $quedan = Quedan::insertGetId([
                'id_estado_quedan'              => 1,
                'id_proy_financiado'            => $request->quedan["id_proy_financiado"],
                'id_prioridad_pago'             => $request->quedan["id_prioridad_pago"],
                'id_proveedor'                  => $request->quedan["id_proveedor"],
                'id_serie_retencion_iva'        => 1, //VALOR QUEDAMO POR EL MOMENTO
                'id_acuerdo_compra'             => $request->quedan["id_acuerdo_compra"],
                'numero_acuerdo_quedan'         => $request->quedan["numero_acuerdo_quedan"],
                'numero_compromiso_ppto_quedan' => $request->quedan["numero_compromiso_ppto_quedan"],
                'numero_retencion_iva_quedan'   => $request->quedan["numero_retencion_iva_quedan"],
                'descripcion_quedan'            => $request->quedan["descripcion_quedan"],
                'monto_liquido_quedan'          => $request->quedan["monto_liquido_quedan"],
                'monto_iva_quedan'              => $request->quedan["monto_iva_quedan"],
                'monto_isr_quedan'              => $request->quedan["monto_isr_quedan"],
                'monto_total_quedan'            => $request->quedan["monto_total_quedan"],
                'id_empleado_tesoreria'         => EmpleadoTesoreria::select("id_empleado_tesoreria")->where('estado_empleado_tesoreria', 1)->where("id_cargo_tesoreria", 1)->get()[0]->id_empleado_tesoreria,
                'fecha_emision_quedan'          => Carbon::now(),
                'estado_quedan'                 => 1,
                'fecha_reg_quedan'              => Carbon::now(),
                'usuario_quedan'                => $request->user()->nick_usuario,
                'ip_quedan'                     => $request->ip(),

            ]);

            // Insertar los registros de detalle_quedan
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
                        'fecha_factura_det_quedan'    => $value[8],
                        'fecha_reg_det_quedan'        => Carbon::now(),
                        'usuario_det_quedan'          => $request->user()->nick_usuario,
                        'ip_det_quedan'               => $request->ip(),

                    );
                    DetalleQuedan::create($new_detalle);
                }
            }
            // Obtener los datos del quedan con relaciones
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
            // Actualizar los campos principales del quedan
            Quedan::where("id_quedan", $id_quedan)->update([
                'id_proveedor'                  => $request->quedan["id_proveedor"],
                'id_acuerdo_compra'             => $request->quedan["id_acuerdo_compra"],
                'numero_acuerdo_quedan'         => $request->quedan["numero_acuerdo_quedan"],
                'numero_compromiso_ppto_quedan' => $request->quedan["numero_compromiso_ppto_quedan"],
                'numero_retencion_iva_quedan'   => $request->quedan["numero_retencion_iva_quedan"],
                'descripcion_quedan'            => $request->quedan["descripcion_quedan"],
                'monto_liquido_quedan'          => $request->quedan["monto_liquido_quedan"],
                'monto_iva_quedan'              => $request->quedan["monto_iva_quedan"],
                'monto_isr_quedan'              => $request->quedan["monto_isr_quedan"],
                'monto_total_quedan'            => $request->quedan["monto_total_quedan"],
                'id_proy_financiado'            => $request->quedan["id_proy_financiado"],
                'id_prioridad_pago'             => $request->quedan["id_prioridad_pago"],
                'usuario_quedan'                => $request->user()->nick_usuario,
                'fecha_mod_quedan'              => Carbon::now(),
            ]);

            foreach ( $detalle_quedan as $key => $value ) {

                if ($value[0] == '') {
                    // Actualizar un detalle de quedan existente
                    $new_detalle = array(
                        'numero_factura_det_quedan'   => $value[2],
                        'id_dependencia'              => $value[3],
                        'numero_acta_det_quedan'      => $value[4],
                        'descripcion_det_quedan'      => $value[5],
                        'producto_factura_det_quedan' => $value[6]['producto'],
                        'servicio_factura_det_quedan' => $value[6]['servicio'],
                        'fecha_factura_det_quedan'    => $value[8],
                        'fecha_mod_det_quedan'        => Carbon::now(),
                        'usuario_det_quedan'          => $request->user()->nick_usuario,
                        'ip_det_quedan'               => $request->ip(),
                    );
                    DetalleQuedan::where("id_det_quedan", $value[1])->update($new_detalle);
                }
                if ($value[0] == 1 && $value[7] !== false) {
                    //Al momento de editar puede que agrege filas entonces se valida que la fila sea nueva 
                    // Agregar un nuevo detalle_quedan
                    $new_detalle = array(
                        'id_quedan'                   => $id_quedan,
                        'numero_factura_det_quedan'   => $value[2],
                        'id_dependencia'              => $value[3],
                        'numero_acta_det_quedan'      => $value[4],
                        'descripcion_det_quedan'      => $value[5],
                        'producto_factura_det_quedan' => $value[6]['producto'],
                        'servicio_factura_det_quedan' => $value[6]['servicio'],
                        'fecha_factura_det_quedan'    => $value[8],
                        'fecha_reg_det_quedan'        => Carbon::now(),
                        'usuario_det_quedan'          => $request->user()->nick_usuario,
                        'ip_det_quedan'               => $request->ip(),
                    );
                    DetalleQuedan::create($new_detalle);
                }
                if ($value[7] === false && $value[0] == '') {
                    //validar que la fila sea eliminada
                    // Eliminar un detalle_quedan
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
                DB::raw("CONCAT(' - ',codigo_dependencia) AS label")
            )->whereNull('dep_id_dependencia')->get();

        $v_AcuerdoCompra = DB::table('acuerdo_compra')
            ->select(
                'id_acuerdo_compra as value',
                'nombre_acuerdo_compra as label'
            )->get();

        $v_Proveedor = DB::table('proveedor')
            ->select(
                'id_proveedor as value',
                'razon_social_proveedor as label',
                'giro.codigo_giro',
                'giro.nombre_giro',
                'sujeto_retencion.iva_sujeto_retencion',
                'sujeto_retencion.isrl_sujeto_retencion',
            )->join('giro', function ($join) {
                $join->on('giro.id_giro', '=', 'proveedor.id_giro');
            })->join('sujeto_retencion', function ($join) {
            $join->on('sujeto_retencion.id_sujeto_retencion', '=', 'proveedor.id_sujeto_retencion');
        })->where("estado_proveedor", 1)->get();

        $v_Requerimiento = DB::table('requerimiento_pago')
            ->select(
                'id_requerimiento_pago as value',
                DB::raw("CONCAT(numero_requerimiento_pago,' - ',anio_requerimiento_pago) AS label"),
                'id_estado_req_pago'
            )->where("estado_requerimiento_pago", 1)->get();

        $v_Prioridad_pago = DB::table('prioridad_pago')
            ->select(
                'id_prioridad_pago as value',
                DB::raw("CONCAT(nivel_prioridad_pago,' - ',nombre_prioridad_pago) AS label")
            )->get();

        $v_Proyecto_finanziado = DB::table('fuente_financiamiento')
            ->select(
                'id_fuente_fmto as value',
                DB::raw("CONCAT(codigo_fuente_fmto,' - ',nombre_fuente_fmto) AS label")
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
    public function updateFechaRetencionIva(Request $request) //Metodo utilizado al momento de seleccionar proveedor y hacer los calculos 
    { //se hacen esta peticion por que al no existir el quedan no existen registos previos de la base de datos y no podemos ver a que provedor pertenece
        return Quedan::where("id_quedan", $request->id_quedan)->update(["fecha_retencion_iva_quedan" => Carbon::now()]);
    }
    public function getAmountBySupplierPerMonth(Request $request)
    {
        // Obtener el mes actual
        $mesActual = Carbon::now()->format('m');
        $resultado = [];

        // Obtener proveedores con quedans del mes actual
        $proveedores = Proveedor::with([
            'quedan' => function ($query) use ($mesActual) {
                $query->whereMonth('fecha_emision_quedan', $mesActual);
            }
        ])->get()->filter(function ($proveedor) {
            // Filtrar proveedores con quedans no vacíos
            return $proveedor->quedan->isNotEmpty();
        });
        foreach ( $proveedores as $proveedor ) {
            // Mapear quedans con detalles para el proveedor actual
            $quedan_con_detalle = $proveedor->quedan->map(function ($quedan) {
                return [
                    'id_quedan'          => $quedan->id_quedan,
                    'monto_total_quedan' => $quedan->monto_total_quedan,
                ];
            });
            // Calcular el total mensual para el proveedor actual
            $total_mensual = $proveedor->quedan->sum('monto_total_quedan');
            // Crear un arreglo con los datos del proveedor actual
            $proveedor_arr = [
                'id_proveedor'  => $proveedor->id_proveedor,
                'razon_social'  => $proveedor->razon_social_proveedor,
                'quedan'        => $quedan_con_detalle,
                'total_mensual' => $total_mensual,
            ];
            // Agregar el arreglo del proveedor al resultado
            array_push($resultado, $proveedor_arr);
        }
        return $resultado;
    }

}