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
            /*  'dui_proveedor',
            'nrc_proveedor',
            'nit_proveedor',
            'id_tipo_contribuyente',
            'id_sujeto_retencion',
            'razon_social_proveedor',
            'estado_proveedor',
            'nombre_comercial_proveedor', */
        ];

        $v_length = $request->input('length');
        $v_column = $request->input('column'); //Index
        $v_dir = $request->input('dir');
        $data = $request->input('search');


        $v_query = DetDocumentoAdquisicion::with([
            "productos_adquisiciones" => function ($query) {
                return $query->where("estado_prod_adquisicion", 1);
            },
            "productos_adquisiciones.producto.unidad_medida",
            "productos_adquisiciones.linea_trabajo",
            "productos_adquisiciones.centro_atencion",
            "productos_adquisiciones.marca",
            "documento_adquisicion.proveedor"
        ])->has('productos_adquisiciones')->orderBy($v_columns[$v_column], $v_dir);

        if ($data) {
            /*   $v_query->where('id_proveedor', 'like', '%' . $data["id_proveedor"] . '%')
                ->whereRaw('IFNULL(dui_proveedor, IFNULL(nit_proveedor, "")) like ?', '%' . $data["dui_proveedor"] . '%')
                ->where('razon_social_proveedor', 'like', '%' . $data["razon_social_proveedor"] . '%')
                ->where('nombre_comercial_proveedor', 'like', '%' . $data["nombre_comercial_proveedor"] . '%')
                ->where('estado_proveedor', 'like', '%' . $data["estado_proveedor"] . '%'); */
        }

        $v_roles = $v_query->paginate($v_length)->onEachSide(1);
        return [
            'data' => $v_roles,
            'draw' => $request->input('draw'),
        ];
    }

    public function saveProductoAdquisicion(BienesServiciosRequest $request): object
    {
        try {
            DB::beginTransaction();
            $detalles = $request->productAdq;
            $idDetDocAdquisicion = $request->idDetDocAdquisicion;
            $usuario = $request->user()->nick_usuario;
            $ip = $request->ip();
            $fechaActual = now();

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
                            ];

                            // Usar DB::insert para insertar directamente y mejorar el rendimiento
                            DB::table('producto_adquisicion')->insert($nuevoDetalle);
                        }
                    }
                }
            }
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
            $usuario = $request->user()->nick_usuario;
            $ip = $request->ip();
            $fechaActual = now();

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
                            ]);
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
    function getArrayObjectoForMultiSelect(): array
    {
        // Obtener detalles de documentos de adquisición con información adicional
        $detalleDocumentoAdquisicion = DetDocumentoAdquisicion::with(["documento_adquisicion.proveedor"])
            ->where("estado_det_doc_adquisicion", 1)
            ->whereDoesntHave("productos_adquisiciones")
            ->get();


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
