<?php

namespace App\Http\Controllers\UCP;

use App\Http\Controllers\Controller;
use App\Http\Requests\UCP\BienesServiciosRequest;
use App\Models\CentroAtencion;
use App\Models\DetDocumentoAdquisicion;
use App\Models\DocumentoAdquisicion;
use App\Models\LineaTrabajo;
use App\Models\Marca;
use App\Models\ProductoAdquisicion;
use App\Models\Proveedor;
use App\Models\UnidadMedida;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BienesServiciosController extends Controller
{

    public function getProductoAdquisicionByDocumentoAdquisicion(Request $request)
    {
        $v_columns = [
            'id_doc_adquisicion',
            'id_prod_adquisicion',
            'id_producto',
            'id_det_doc_adquisicion',
            'id_marca',
            'id_lt',
            'id_centro_atencion',
            'cant_prod_adquisicion',
            'costo_prod_adquisicion',
            'descripcion_prod_adquisicion',
            'estado_prod_adquisicion',
            'fecha_reg_prod_adquisicion',
            'fecha_mod_prod_adquisicion',
            'usuario_prod_adquisicion',
            'ip_prod_adquisicion',

        ];

        $v_length = $request->input('length');
        $v_column = $request->input('column'); //Index
        $v_dir = $request->input('dir');
        $data = $request->input('search');
        $docType = $request->input('docType');


        $v_query = DetDocumentoAdquisicion::with([
            "estado_documento_adquisicion",
            "productos_adquisiciones" => function ($query) {
                return $query->where("estado_prod_adquisicion", 1);
            },
            "productos_adquisiciones.producto.unidad_medida",
            "productos_adquisiciones.linea_trabajo",
            "productos_adquisiciones.centro_atencion",
            "productos_adquisiciones.marca",
            "documento_adquisicion.proveedor",
            "documento_adquisicion.tipo_documento_adquisicion",
            "documento_adquisicion.tipo_gestion_compra",
        ])
            ->whereHas('documento_adquisicion', function ($query) use ($docType) {

                if ($docType  == "contrato") {
                    # code...
                    $query->where('id_tipo_doc_adquisicion', 1);
                } else if ($docType  == "orden de compra") {
                    # code...
                    $query->where('id_tipo_doc_adquisicion', 2);
                }
            })
            ->has('productos_adquisiciones')->orderBy($v_columns[$v_column], $v_dir);

        if ($data) {

            $v_query->whereHas('documento_adquisicion.proveedor', function ($query) use ($data) {
                $query->where('razon_social_proveedor', 'like', '%' . $data["razon_social_proveedor"] . '%');
            });
            $v_query->whereHas('documento_adquisicion', function ($query) use ($data) {
                $query->where('id_tipo_doc_adquisicion', 'like', '%' . $data["id_tipo_doc_adquisicion"] . '%');
            });
            $v_query->whereHas('documento_adquisicion', function ($query) use ($data) {
                $query->where('id_tipo_gestion_compra', 'like', '%' . $data["id_tipo_gestion_compra"] . '%');
            });

            $v_query->whereHas('documento_adquisicion', function ($query) use ($data) {
                $query->where('numero_doc_adquisicion', 'like', '%' . $data["id_tipo_gestion_compra"] . '%');
            });
            $v_query->whereHas('documento_adquisicion', function ($query) use ($data) {
                $query->where('numero_doc_adquisicion', 'like', '%' . $data["id_tipo_gestion_compra"] . '%');
            });


            $v_query->where('monto_det_doc_adquisicion', 'like', '%' . $data["monto_det_doc_adquisicion"] . '%');

            $v_query->whereHas('estado_documento_adquisicion', function ($query) use ($data) {
                $query->where('id_estado_doc_adquisicion', 'like', '%' . $data["id_estado_doc_adquisicion"] . '%');
            });
        }

        $data = $v_query->paginate($v_length)->onEachSide(1);
        return [
            'data' => $data,
            'draw' => $request->input('draw'),
        ];
    }

    public function saveProductoAdquisicion(BienesServiciosRequest $request): object
    {

        /*  return $request; */
        try {
            DB::beginTransaction();
            $detalles = $request->productAdq;
            $idDetDocAdquisicion = $request->idDetDocAdquisicion;
            $notificacionDetDocAdquisicion = $request->notificacionDetDocAdquisicion;
            $recepcionDetDocAdquisicion = $request->recepcionDetDocAdquisicion;
            $observacionDetDocAdquisicion = $request->observacionDetDocAdquisicion;
            $usuario = $request->user()->nick_usuario;
            $ip = $request->ip();
            $fechaActual = now();
            $totCostoProdAdquisicion = 0; // Variable para almacenar la el total

            foreach ($detalles as $detalle) {
                if ($detalle["estadoLt"] != 0) {
                    foreach ($detalle["detalleDoc"] as $detalleProducto) {
                        if ($detalleProducto["estadoProdAdquisicion"] == 1) {
                            $nuevoDetalle = [
                                'id_producto'                  => $detalleProducto["idProducto"],
                                'id_det_doc_adquisicion'       => $idDetDocAdquisicion,
                                'id_marca'                     => $detalleProducto["idMarca"],
                                'id_lt'                        => $detalle["idLt"],
                                'id_centro_atencion'           => $detalleProducto["idCentroAtencion"],
                                'cant_prod_adquisicion'        => $detalleProducto["cantProdAdquisicion"],
                                'costo_prod_adquisicion'       => $detalleProducto["costoProdAdquisicion"],
                                'descripcion_prod_adquisicion' => $detalleProducto["descripcionProdAdquisicion"],
                                'estado_prod_adquisicion'      => 1,
                                'fecha_reg_prod_adquisicion'   => $fechaActual,
                                'usuario_prod_adquisicion'     => $usuario,
                                'ip_prod_adquisicion'          => $ip,

                                'cant_ene_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["January"],
                                'cant_feb_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["February"],
                                'cant_mar_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["March"],
                                'cant_abr_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["April"],
                                'cant_may_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["May"],
                                'cant_jun_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["June"],
                                'cant_jul_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["July"],
                                'cant_ago_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["August"],
                                'cant_sept_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["September"],
                                'cant_oct_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["October"],
                                'cant_nov_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["November"],
                                'cant_dic_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["December"],
                            ];
                            // Sumando el total
                            $totCostoProdAdquisicion += $detalleProducto["valorTotalProduct"];

                            // Usar DB::insert para insertar directamente y mejorar el rendimiento
                            DB::table('producto_adquisicion')->insert($nuevoDetalle);
                        }
                    }
                }
            }
            DetDocumentoAdquisicion::where("id_det_doc_adquisicion", $idDetDocAdquisicion)->update([
                "monto_det_doc_adquisicion" => $totCostoProdAdquisicion,
                "observacion_det_doc_adquisicion" => $notificacionDetDocAdquisicion,
                "recepcion_det_doc_adquisicion" => $recepcionDetDocAdquisicion,
                "notificacion_det_doc_adquisicion" => $observacionDetDocAdquisicion,
            ]);

            DB::commit();
            return response()->json($request); // O cualquier otra respuesta que desees enviar
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }

    public function updateProductoAdquisicion(BienesServiciosRequest $request)
    {
        try {
            $detalles = $request->productAdq;
            $idDetDocAdquisicion = $request->idDetDocAdquisicion;
            $notificacionDetDocAdquisicion = $request->notificacionDetDocAdquisicion;
            $recepcionDetDocAdquisicion = $request->recepcionDetDocAdquisicion;
            $observacionDetDocAdquisicion = $request->observacionDetDocAdquisicion;
            $usuario = $request->user()->nick_usuario;
            $ip = $request->ip();
            $fechaActual = now();
            $totCostoProdAdquisicion = 0; // Variable para almacenar la el total

            foreach ($detalles as $detalle) {
                // Verificar si la linea de trabajo del producto adquisicion no ha sido eliminada en el front
                if ($detalle["estadoLt"] != 0) {
                    foreach ($detalle["detalleDoc"] as $detalleProducto) {
                        // Actualizar producto existente
                        if ($detalleProducto["estadoProdAdquisicion"] == 2) {
                            ProductoAdquisicion::where([
                                'id_prod_adquisicion' => $detalleProducto["idProdAdquisicion"],
                                'id_lt'               => $detalle["idLt"],
                            ])->update([
                                // Actualizar campos del producto
                                'id_producto'                  => $detalleProducto["idProducto"],
                                'id_det_doc_adquisicion'       => $idDetDocAdquisicion,
                                'id_marca'                     => $detalleProducto["idMarca"],
                                'id_lt'                        => $detalle["idLt"],
                                'id_centro_atencion'           => $detalleProducto["idCentroAtencion"],
                                'cant_prod_adquisicion'        => $detalleProducto["cantProdAdquisicion"],
                                'costo_prod_adquisicion'       => $detalleProducto["costoProdAdquisicion"],
                                'descripcion_prod_adquisicion' => $detalleProducto["descripcionProdAdquisicion"],
                                'estado_prod_adquisicion'      => 1,
                                'fecha_reg_prod_adquisicion'   => $fechaActual,
                                'usuario_prod_adquisicion'     => $usuario,
                                'ip_prod_adquisicion'          => $ip,
                                'cant_ene_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["January"],
                                'cant_feb_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["February"],
                                'cant_mar_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["March"],
                                'cant_abr_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["April"],
                                'cant_may_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["May"],
                                'cant_jun_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["June"],
                                'cant_jul_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["July"],
                                'cant_ago_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["August"],
                                'cant_sept_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["September"],
                                'cant_oct_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["October"],
                                'cant_nov_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["November"],
                                'cant_dic_prod_adquisicion' => $detalleProducto["amountsPerMonthList"]["December"],
                            ]);

                            // Sumando el total
                            $totCostoProdAdquisicion += $detalleProducto["valorTotalProduct"];
                        }
                        // Insertar nuevo producto
                        else if ($detalleProducto["estadoProdAdquisicion"] == 1) {
                            $nuevoDetalle = [
                                'id_producto'                  => $detalleProducto["idProducto"],
                                'id_det_doc_adquisicion'       => $idDetDocAdquisicion,
                                'id_marca'                     => $detalleProducto["idMarca"],
                                'id_lt'                        => $detalle["idLt"],
                                'id_centro_atencion'           => $detalleProducto["idCentroAtencion"],
                                'cant_prod_adquisicion'        => $detalleProducto["cantProdAdquisicion"],
                                'costo_prod_adquisicion'       => $detalleProducto["costoProdAdquisicion"],
                                'descripcion_prod_adquisicion' => $detalleProducto["descripcionProdAdquisicion"],
                                'estado_prod_adquisicion'      => 1,
                                'fecha_reg_prod_adquisicion'   => $fechaActual,
                                'usuario_prod_adquisicion'     => $usuario,
                                'ip_prod_adquisicion'          => $ip,
                            ];

                            // Sumando el total
                            $totCostoProdAdquisicion += $detalleProducto["valorTotalProduct"];
                            // Usar DB::insert para insertar directamente y mejorar el rendimiento
                            DB::table('producto_adquisicion')->insert($nuevoDetalle);
                        }
                        // Desactivar producto
                        else {
                            ProductoAdquisicion::where([
                                'id_prod_adquisicion' => $detalleProducto["idProdAdquisicion"],
                                'id_lt'               => $detalle["idLt"],
                            ])->update([
                                'estado_prod_adquisicion' => 0,
                            ]);
                        }
                    }
                }
                // Desactivar los productos adquisiciones por linea de trabajo
                else {
                    foreach ($detalle["detalleDoc"] as $detalleProducto) {
                        ProductoAdquisicion::where([
                            'id_prod_adquisicion' => $detalleProducto["idProdAdquisicion"],
                            'id_lt'               => $detalle["idLt"],
                        ])->update([
                            'estado_prod_adquisicion' => 0,
                        ]);
                    }
                }
            }
            DetDocumentoAdquisicion::where("id_det_doc_adquisicion", $idDetDocAdquisicion)->update([
                "monto_det_doc_adquisicion" => $totCostoProdAdquisicion,
                "observacion_det_doc_adquisicion" => $notificacionDetDocAdquisicion,
                "recepcion_det_doc_adquisicion" => $recepcionDetDocAdquisicion,
                "notificacion_det_doc_adquisicion" => $observacionDetDocAdquisicion,
            ]);

            return response()->json(["message" => "Actualización exitosa"]);
        } catch (\Exception $e) {
            // Manejar excepciones y retornar un mensaje de error
            return response()->json(["error" => "Error en la actualización: " . $e->getMessage()], 500);
        }
    }


    /**
     * Obtiene arreglo de dinstintos objetos para el uso en multiselect.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Request
     */
    function getArrayObjectoForMultiSelect(Request $request): array
    {
        // Obtener detalles de documentos de adquisición con información adicional

        $query = DetDocumentoAdquisicion::with([
            "documento_adquisicion.proveedor",
            "documento_adquisicion.proceso_compra"
        ])->where("estado_det_doc_adquisicion", 1)->whereDoesntHave("productos_adquisiciones");

        // Si el tipo de documento es "contrato", añadir una condición adicional a la consulta
        if ($request->tipoDoc == "contrato") {
            $query->whereHas('documento_adquisicion', function ($query) {
                $query->where('id_tipo_doc_adquisicion', 1);
            });
        }else if ($request->tipoDoc == "orden de compra") {
            $query->whereHas('documento_adquisicion', function ($query) {
                $query->where('id_tipo_doc_adquisicion', 2);
            });
        }

        $detalleDocumentoAdquisicion = $query->get();


        // Obtener todas las unidades de medida
        $unidadesMedida = UnidadMedida::all();

        // Obtener todas las líneas de trabajo
        $lineaTrabajo = LineaTrabajo::all();

        // Obtener todos los centros de atención
        $centrosAtencion = CentroAtencion::all();

        // Obtener marca
        $marca = Marca::all();

        // Devolver un arreglo asociativo con los resultados
        return [
            "detalleDocAdquisicion" => $detalleDocumentoAdquisicion,
            "lineaTrabajo"          => $lineaTrabajo,
            "unidadesMedida"        => $unidadesMedida,
            "centrosAtencion"       => $centrosAtencion,
            "marca"                 => $marca,
        ];
    }
}
