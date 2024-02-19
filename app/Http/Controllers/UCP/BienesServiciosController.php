<?php

namespace App\Http\Controllers\UCP;

use App\Http\Controllers\Controller;
use App\Models\CentroAtencion;
use App\Models\DetDocumentoAdquisicion;
use App\Models\LineaTrabajo;
use App\Models\Marca;
use App\Models\ProductoAdquisicion;
use App\Models\UnidadMedida;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BienesServiciosController extends Controller
{


    public function saveProductoAdquisicion(Request $request): object
    {
        $detalles = $request->productAdq;
        $idDetDocAdquisicion = $request->idDetDocAdquisicion;
        $usuario = $request->user()->nick_usuario;
        $ip = $request->ip();
        $fechaActual = now();

        foreach ($detalles as $detalle) {
            foreach ($detalle["detalleDoc"] as $detalleProducto) {
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

        return response()->json($request); // O cualquier otra respuesta que desees enviar
    }

    /**
     * Obtiene arreglo de dinstintos objetos para el uso en multiselect.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Request
     */
    function getArrayObjectoForMultiSelect(): array
    {
        // Obtener detalles de documentos de adquisición con información adicional
        $detalleDocumentoAdquisicion = DetDocumentoAdquisicion::with(["documento_adquisicion.proveedor"])->get();

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
