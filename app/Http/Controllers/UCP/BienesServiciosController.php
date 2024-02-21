<?php

namespace App\Http\Controllers\UCP;

use App\Http\Controllers\Controller;
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
        $detalleDocumentoAdquisicion = DetDocumentoAdquisicion::with(["documento_adquisicion.proveedor"])
            ->where("estado_det_doc_adquisicion", 1)
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
