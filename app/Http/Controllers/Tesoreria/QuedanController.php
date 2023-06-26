<?php

namespace App\Http\Controllers\Tesoreria;

use App\Http\Controllers\Controller;
use App\Models\DocumentoAdquisicion;
use App\Models\EmpleadoTesoreria;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use App\Models\Quedan;
use App\Models\DetalleQuedan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Tesoreria\QuedanRequest;
use App\Models\RequerimientoPago;
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
            'id_empleado_tesoreria',
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
            "liquidacion_quedan",
            "proyecto_financiado",
            "tipo_documento_adquisicion.documento_adquisicion",
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
                $requerimiento = $data["numero_requerimiento_pago"];
                $v_query->whereHas('requerimiento_pago', function ($query) use ($requerimiento) {
                    $query->where('numero_requerimiento_pago', 'like', '%' . $requerimiento . '%');
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

            $quedanData = [
                'id_estado_quedan'            => 1,
                'id_proy_financiado'          => $request->quedan["id_proy_financiado"],
                'id_prioridad_pago'           => $request->quedan["id_prioridad_pago"],
                'id_proveedor'                => $request->quedan["id_proveedor"],
                'id_serie_retencion_iva'      => 1,
                //VALOR QUEDAMO POR EL MOMENTO
                'id_det_doc_adquisicion'      => $request->quedan["id_det_doc_adquisicion"],
                'id_tipo_doc_adquisicion'     => $request->quedan["id_tipo_doc_adquisicion"],
                'numero_retencion_iva_quedan' => $request->quedan["numero_retencion_iva_quedan"],
                'descripcion_quedan'          => $request->quedan["descripcion_quedan"],
                'monto_liquido_quedan'        => $request->quedan["monto_liquido_quedan"],
                'monto_iva_quedan'            => $request->quedan["monto_iva_quedan"],
                'monto_isr_quedan'            => $request->quedan["monto_isr_quedan"],
                'monto_total_quedan'          => $request->quedan["monto_total_quedan"],
                'id_empleado_tesoreria'       => EmpleadoTesoreria::select("id_empleado_tesoreria")
                    ->where('estado_empleado_tesoreria', 1)
                    ->where("id_cargo_tesoreria", 1)
                    ->value('id_empleado_tesoreria'),
                'fecha_emision_quedan'        => Carbon::now(),
                'estado_quedan'               => 1,
                'fecha_reg_quedan'            => Carbon::now(),
                'usuario_quedan'              => $request->user()->nick_usuario,
                'ip_quedan'                   => $request->ip(),
            ];

            // Insertar el registro de quedan y obtener el ID generado
            $quedan = Quedan::insertGetId($quedanData);

            // Insertar los registros de detalle_quedan
            $detalleData = [];
            foreach ( $detalle_quedan as $key => $value ) {
                if ($value["isDelete"]) {
                    $new_detalle = [
                        'id_quedan'                          => $quedan,
                        'numero_factura_det_quedan'          => $value["numero_factura_det_quedan"],
                        'id_dependencia'                     => $value["id_dependencia"],
                        'numero_acta_det_quedan'             => $value["numero_acta_det_quedan"],
                        'producto_factura_det_quedan'        => $value["monto"]['producto_factura_det_quedan'],
                        'servicio_factura_det_quedan'        => $value["monto"]['servicio_factura_det_quedan'],
                        'justificacion_det_quedan'           => $value["justificacion_det_quedan"],
                        'ajuste_producto_factura_det_quedan' => $value["reajuste"] ? $value["reajuste_monto"]['ajuste_producto_factura_det_quedan'] : 0,
                        'ajuste_servicio_factura_det_quedan' => $value["reajuste"] ? $value["reajuste_monto"]['ajuste_servicio_factura_det_quedan'] : 0,
                        'iva_factura_det_quedan'             => $value["retenciones"]['iva'],
                        'isr_factura_det_quedan'             => $value["retenciones"]['renta'],
                        'fecha_factura_det_quedan'           => $value["fecha_factura_det_quedan"],
                        'fecha_reg_det_quedan'               => Carbon::now(),
                        'usuario_det_quedan'                 => $request->user()->nick_usuario,
                        'ip_det_quedan'                      => $request->ip(),
                    ];
                    $detalleData[] = $new_detalle;
                }
            }
            DetalleQuedan::insert($detalleData);

            // Obtener los datos del quedan con relaciones
            $res = Quedan::with([
                "detalle_quedan",
                "proveedor.giro",
                "proveedor.sujeto_retencion",
                "tesorero"
            ])->find($quedan);

            DB::commit();
            return $res;
        } catch (\Throwable $th) {
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
                'id_proveedor'                => $request->quedan["id_proveedor"],
                'id_det_doc_adquisicion'      => $request->quedan["id_det_doc_adquisicion"],
                'id_tipo_doc_adquisicion'     => $request->quedan["id_tipo_doc_adquisicion"],
                'numero_retencion_iva_quedan' => $request->quedan["numero_retencion_iva_quedan"],
                'descripcion_quedan'          => $request->quedan["descripcion_quedan"],
                'monto_liquido_quedan'        => $request->quedan["monto_liquido_quedan"],
                'monto_iva_quedan'            => $request->quedan["monto_iva_quedan"],
                'monto_isr_quedan'            => $request->quedan["monto_isr_quedan"],
                'monto_total_quedan'          => $request->quedan["monto_total_quedan"],
                'id_proy_financiado'          => $request->quedan["id_proy_financiado"],
                'id_prioridad_pago'           => $request->quedan["id_prioridad_pago"],
                'usuario_quedan'              => $request->user()->nick_usuario,
                'fecha_mod_quedan'            => Carbon::now(),
            ]);

            foreach ( $detalle_quedan as $key => $value ) {

                if ($value["numberRow"] == '') {
                    // Actualizar un detalle de quedan existente
                    $new_detalle = array(
                        'numero_factura_det_quedan'          => $value["numero_factura_det_quedan"],
                        'id_dependencia'                     => $value["id_dependencia"],
                        'numero_acta_det_quedan'             => $value["numero_acta_det_quedan"],
                        'producto_factura_det_quedan'        => $value["monto"]['producto_factura_det_quedan'],
                        'servicio_factura_det_quedan'        => $value["monto"]['servicio_factura_det_quedan'],
                        'justificacion_det_quedan'           => $value["justificacion_det_quedan"],
                        'ajuste_producto_factura_det_quedan' => $value["reajuste"] ? $value["reajuste_monto"]['ajuste_producto_factura_det_quedan'] : 0,
                        'ajuste_servicio_factura_det_quedan' => $value["reajuste"] ? $value["reajuste_monto"]['ajuste_servicio_factura_det_quedan'] : 0,
                        'iva_factura_det_quedan'             => $value["retenciones"]['iva'],
                        'isr_factura_det_quedan'             => $value["retenciones"]['renta'],
                        'fecha_factura_det_quedan'           => $value["fecha_factura_det_quedan"],
                        'fecha_mod_det_quedan'               => Carbon::now(),
                        'usuario_det_quedan'                 => $request->user()->nick_usuario,
                        'ip_det_quedan'                      => $request->ip(),
                    );
                    DetalleQuedan::where("id_det_quedan", $value["id_det_quedan"])->update($new_detalle);
                }
                if ($value["numberRow"] == 1 && $value["isDelete"] !== false) {
                    //Al momento de editar puede que agrege filas entonces se valida que la fila sea nueva 
                    // Agregar un nuevo detalle_quedan
                    $new_detalle = array(
                        'id_quedan'                          => $id_quedan,
                        'numero_factura_det_quedan'          => $value["numero_factura_det_quedan"],
                        'id_dependencia'                     => $value["id_dependencia"],
                        'numero_acta_det_quedan'             => $value["numero_acta_det_quedan"],
                        'producto_factura_det_quedan'        => $value["monto"]['producto_factura_det_quedan'],
                        'servicio_factura_det_quedan'        => $value["monto"]['servicio_factura_det_quedan'],
                        'justificacion_det_quedan'           => $value["justificacion_det_quedan"],
                        'ajuste_producto_factura_det_quedan' => $value["reajuste"] ? $value["reajuste_monto"]['ajuste_producto_factura_det_quedan'] : 0,
                        'ajuste_servicio_factura_det_quedan' => $value["reajuste"] ? $value["reajuste_monto"]['ajuste_servicio_factura_det_quedan'] : 0,
                        'iva_factura_det_quedan'             => $value["retenciones"]['iva'],
                        'isr_factura_det_quedan'             => $value["retenciones"]['renta'],
                        'fecha_factura_det_quedan'           => $value["fecha_factura_det_quedan"],
                        'fecha_reg_det_quedan'               => Carbon::now(),
                        'usuario_det_quedan'                 => $request->user()->nick_usuario,
                        'ip_det_quedan'                      => $request->ip(),
                    );
                    DetalleQuedan::create($new_detalle);
                }
                if ($value["isDelete"] === false && $value["numberRow"] == '') {
                    //validar que la fila sea eliminada
                    // Eliminar un detalle_quedan
                    DetalleQuedan::destroy($value["id_det_quedan"]);
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

        $tipoAdquisicion = DB::table('tipo_documento_adquisicion')
            ->select(
                'id_tipo_doc_adquisicion as value',
                'nombre_tipo_doc_adquisicion as label',
                'estado_tipo_doc_adquisicion'
            )->get();

        $v_Proveedor = DB::table('proveedor')
            ->select('id_proveedor as value', 'razon_social_proveedor as label', 'dui_proveedor', 'giro.codigo_giro', 'giro.nombre_giro', 'sujeto_retencion.iva_sujeto_retencion', 'sujeto_retencion.isrl_sujeto_retencion')
            ->leftJoin('giro', 'giro.id_giro', '=', 'proveedor.id_giro')
            ->join('sujeto_retencion', 'sujeto_retencion.id_sujeto_retencion', '=', 'proveedor.id_sujeto_retencion')
            ->where('estado_proveedor', 1)
            ->get();

        $v_Requerimiento = RequerimientoPago::select(
            'requerimiento_pago.id_requerimiento_pago as value',
            DB::raw("CONCAT(requerimiento_pago.numero_requerimiento_pago,' - ',requerimiento_pago.anio_requerimiento_pago) AS label"),
            'requerimiento_pago.id_estado_req_pago',
            'requerimiento_pago.numero_requerimiento_pago',
            'requerimiento_pago.monto_requerimiento_pago',
            'requerimiento_pago.descripcion_requerimiento_pago',
            DB::raw('(SELECT SUM(monto_liquido_quedan) FROM quedan WHERE quedan.id_requerimiento_pago = requerimiento_pago.id_requerimiento_pago) AS suma_campo'),
            DB::raw('(requerimiento_pago.monto_requerimiento_pago - COALESCE((SELECT SUM(monto_liquido_quedan) FROM quedan WHERE quedan.id_requerimiento_pago = requerimiento_pago.id_requerimiento_pago), 0)) AS restante_requerimiento'),
        )->where('requerimiento_pago.estado_requerimiento_pago', 1)->get();


        $v_Prioridad_pago = DB::table('prioridad_pago')
            ->select(
                'id_prioridad_pago as value',
                DB::raw("CONCAT(nivel_prioridad_pago,' - ',nombre_prioridad_pago) AS label")
            )->get();

        $v_Proyecto_finanziado = DB::table('proyecto_financiado')
            ->select(
                'id_proy_financiado as value',
                'nombre_proy_financiado AS label',
            )->get();

            $documentosAdquisicion = DB::table('documento_adquisicion')
            ->select(
                'detalle_documento_adquisicion.id_det_doc_adquisicion as value',
                DB::raw("UPPER(CONCAT(documento_adquisicion.numero_doc_adquisicion, ' - COMPROMISO ', detalle_documento_adquisicion.compromiso_ppto_det_doc_adquisicion, ' - ',proyecto_financiado.codigo_proy_financiado)) AS label"),
                'proveedor.id_proveedor',
                'proyecto_financiado.id_proy_financiado',
                'documento_adquisicion.numero_doc_adquisicion',
                'documento_adquisicion.monto_doc_adquisicion',
                'detalle_documento_adquisicion.compromiso_ppto_det_doc_adquisicion',
                'tipo_documento_adquisicion.id_tipo_doc_adquisicion',
                'tipo_documento_adquisicion.nombre_tipo_doc_adquisicion',
                DB::raw('IFNULL(SUM(quedan.monto_liquido_quedan),0) as total_amount')
            )
            ->join('detalle_documento_adquisicion', 'documento_adquisicion.id_doc_adquisicion', '=', 'detalle_documento_adquisicion.id_doc_adquisicion')
            ->join('proyecto_financiado', 'detalle_documento_adquisicion.id_proy_financiado', '=', 'proyecto_financiado.id_proy_financiado')
            ->join('proveedor', 'proveedor.id_proveedor', '=', 'documento_adquisicion.id_proveedor')
            ->join('tipo_documento_adquisicion', 'tipo_documento_adquisicion.id_tipo_doc_adquisicion', '=', 'documento_adquisicion.id_tipo_doc_adquisicion')
            ->leftJoin('quedan', 'detalle_documento_adquisicion.id_det_doc_adquisicion', '=', 'quedan.id_det_doc_adquisicion')
            ->groupBy(
                'detalle_documento_adquisicion.id_det_doc_adquisicion',
                'documento_adquisicion.numero_doc_adquisicion',
                'documento_adquisicion.monto_doc_adquisicion',
                'detalle_documento_adquisicion.compromiso_ppto_det_doc_adquisicion',
                'tipo_documento_adquisicion.id_tipo_doc_adquisicion',
                'tipo_documento_adquisicion.nombre_tipo_doc_adquisicion',
                'proveedor.id_proveedor',
                'proyecto_financiado.id_proy_financiado',
                'proyecto_financiado.codigo_proy_financiado'
            )
            ->get();



        return [
            "dependencias"         => $v_Dependencias,
            "tipoAdquisicion"      => $tipoAdquisicion,
            "proveedor"            => $v_Proveedor,
            "numeroRequerimiento"  => $v_Requerimiento,
            "prioridadPago"        => $v_Prioridad_pago,
            "proyectoFinanciado"   => $v_Proyecto_finanziado,
            "documentoAdquisicion" => $documentosAdquisicion,
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