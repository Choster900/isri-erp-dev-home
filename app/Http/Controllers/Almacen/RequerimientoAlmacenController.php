<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Models\CentroAtencion;
use App\Models\CentroProduccion;
use App\Models\DetalleRequerimiento;
use App\Models\LineaTrabajo;
use App\Models\Producto;
use App\Models\ProyectoFinanciado;
use App\Models\Requerimiento;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RequerimientoAlmacenController extends Controller
{

    public function getRequerimientoAlmacen(Request $request)
    {
        $v_columns = [
            'id_requerimiento',
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


        $v_query = Requerimiento::with([
            "detalles_requerimiento.producto",
        ])->orderBy($v_columns[$v_column], $v_dir);

        if ($data) {
            /*   $v_query->where('id_proveedor', 'like', '%' . $data["id_proveedor"] . '%')
                ->whereRaw('IFNULL(dui_proveedor, IFNULL(nit_proveedor, "")) like ?', '%' . $data["dui_proveedor"] . '%')
                ->where('razon_social_proveedor', 'like', '%' . $data["razon_social_proveedor"] . '%')
                ->where('nombre_comercial_proveedor', 'like', '%' . $data["nombre_comercial_proveedor"] . '%')
                ->where('estado_proveedor', 'like', '%' . $data["estado_proveedor"] . '%'); */
        }

        $data = $v_query->paginate($v_length)->onEachSide(1);
        return [
            'data' => $data,
            'draw' => $request->input('draw'),
        ];
    }
    //Big L - "Work, Pt. II" (feat. Gang Starr)

    function getObject(): array
    {

        // Obtener todas las líneas de trabajo
        $lineaTrabajo = LineaTrabajo::all();

        // Obtener todos los centros de atención
        $centrosAtencion = CentroAtencion::all();

        // Obtener todos los proyectos financiados
        $proyectosFinanciados = ProyectoFinanciado::all();

        $centroProduccion = CentroProduccion::all();

        // Obtener todos los productos
        $productos = Producto::all();
        return [
            'lineaTrabajo'         => $lineaTrabajo,
            'centrosAtencion'      => $centrosAtencion,
            'proyectosFinanciados' => $proyectosFinanciados,
            'productos'            => $productos,
            'centroProduccion'     => $centroProduccion,
        ];
    }

    function addRequerimiento(Request $request)
    {


        //     $requerimiento = [];
        $requerimiento = [
            'id_lt' => $request->idLt,
            'id_centro_atencion' =>  $request->idCentroAtencion,
            'id_proy_financiado' => $request->idProyFinanciado,
            'id_estado_req' => 1,
            'num_requerimiento' => $request->numRequerimiento,
            'cant_personal_requerimiento' => $request->cantPersonalRequerimiento,
            'fecha_requerimiento' => Carbon::now(),
            'observacion_requerimiento' => $request->observacionRequerimiento,
            'fecha_reg_requerimiento' => Carbon::now(),
            'usuario_requerimiento' =>  $request->user()->nick_usuario,
            'ip_requerimiento' => $request->ip(),

        ];
        $requerimientoId = Requerimiento::insertGetId($requerimiento);

        foreach ($request->dataDetalleRequerimiento as $key => $value) {

            foreach ($value["productos"] as $key => $value2) {

                DetalleRequerimiento::insert(
                    [
                        'id_producto' =>  $value2["idProducto"],
                        'id_centro_produccion' => $value["idCentroProduccion"],
                        'id_requerimiento' => $requerimientoId,
                        'cant_det_requerimiento' => $value2["cantDetRequerimiento"],
                        'costo_det_requerimiento' => 0,
                        'fecha_reg_det_requerimiento' => Carbon::now(),
                        'usuario_det_requerimiento' => $request->user()->nick_usuario,
                        'ip_det_requerimiento' =>  $request->ip(),
                    ]
                );
            }
        }
    }
}
