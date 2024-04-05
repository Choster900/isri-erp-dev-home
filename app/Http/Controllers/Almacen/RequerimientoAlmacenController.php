<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Almacen\RequerimientoRequest;
use App\Models\CentroAtencion;
use App\Models\CentroProduccion;
use App\Models\DetalleKardex;
use App\Models\DetallePlaza;
use App\Models\DetalleRequerimiento;
use App\Models\ExistenciaAlmacen;
use App\Models\Kardex;
use App\Models\LineaTrabajo;
use App\Models\Marca;
use App\Models\Permiso;
use App\Models\PermisoUsuario;
use App\Models\Producto;
use App\Models\ProyectoFinanciado;
use App\Models\Requerimiento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Else_;

class RequerimientoAlmacenController extends Controller
{

    public function getRequerimientoAlmacen(Request $request)
    {
        $v_columns = [
            'id_requerimiento',
            'id_centro_atencion',
            'id_proy_financiado',
            'id_estado_req',
            'num_requerimiento',
        ];

        $v_length = $request->input('length');
        $v_column = $request->input('column'); //Index
        $v_dir = $request->input('dir');
        $data = $request->input('search');


        $object = PermisoUsuario::where('id_usuario', $request->user()->id_usuario)->get();
        $coleccion = collect($object);

        $v_query = Requerimiento::with([
            "detalles_requerimiento.producto",
            "centro_atencion",
            "proyecto_financiado",
            "estado_requerimiento"

        ])->when($coleccion->contains('id_rol', 23) || $coleccion->contains('id_rol', 24), function ($query) {
            return $query->where('id_estado_req', 2)
                ->orWhere('id_estado_req', 3)
                ->orWhere('id_estado_req', 4);
        })
            ->orderBy($v_columns[$v_column], $v_dir);

        if ($data) {
            /*   $v_query->where('id_proveedor', 'like', '%' . $data["id_proveedor"] . '%')
                ->whereRaw('IFNULL(dui_proveedor, IFNULL(nit_proveedor, "")) like ?', '%' . $data["dui_proveedor"] . '%')
                ->where('razon_social_proveedor', 'like', '%' . $data["razon_social_proveedor"] . '%')
                ->where('nombre_comercial_proveedor', 'like', '%' . $data["nombre_comercial_proveedor"] . '%')
                ->where('estado_proveedor', 'like', '%' . $data["estado_proveedor"] . '%'); */
        }

        $data = $v_query->paginate($v_length)->onEachSide(1);
        return [
            'data'       => $data,
            'canSaveReq' => $coleccion->contains('id_rol', 23) || $coleccion->contains('id_rol', 24),
            'draw'       => $request->input('draw'),
        ];
    }

    function getObject(): array
    {

        // Obtener todas las líneas de trabajo
        $lineaTrabajo = LineaTrabajo::all();

        // Obtener todos los centros de atención
        $centrosAtencion = CentroAtencion::all();

        // Obtener todos los proyectos financiados
        $proyectosFinanciados = ProyectoFinanciado::all();

        $centroProduccion = CentroProduccion::all();

        $marcas = Marca::all();

        // Obtener todos los productos
        $productos = Producto::all();
        return [
            'lineaTrabajo'         => $lineaTrabajo,
            'marcas'               => $marcas,
            'centrosAtencion'      => $centrosAtencion,
            'proyectosFinanciados' => $proyectosFinanciados,
            'productos'            => $productos,
            'centroProduccion'     => $centroProduccion,
        ];
    }

    function getProductByNameOrCode(Request $request)
    {
        $stringBusqueda = $request->nombre;
        $data = Producto::where('nombre_producto', 'like', '%' . $stringBusqueda . '%')->get();

        return $data->map(function ($item) {
            return [
                'value'           => $item->id_producto,
                'label'           => $item->codigo_producto . ' - ' . $item->nombre_producto,
                'allDataPersonas' => $item,
            ];
        });
    }

    function addRequerimiento(RequerimientoRequest $request)
    {
        //     $requerimiento = [];
        $requerimiento = [
            'id_lt'                     => $request->idLt,
            'id_centro_atencion'        => $request->idCentroAtencion,
            'id_proy_financiado'        => $request->idProyFinanciado,
            'id_estado_req'             => 1,
            'id_tipo_mov_kardex'        => 2,
            'id_tipo_req'               => 1,
            'num_requerimiento'         => $request->numRequerimiento,
            'fecha_requerimiento'       => Carbon::now(),
            'observacion_requerimiento' => $request->observacionRequerimiento,
            'fecha_reg_requerimiento'   => Carbon::now(),
            'usuario_requerimiento'     => $request->user()->nick_usuario,
            'ip_requerimiento'          => $request->ip(),

        ];
        $requerimientoId = Requerimiento::insertGetId($requerimiento);

        foreach ($request->dataDetalleRequerimiento as $key => $value) {

            foreach ($value["productos"] as $key => $value2) {

                DetalleRequerimiento::insert(
                    [
                        'id_producto'                 => $value2["idProducto"],
                        'id_centro_produccion'        => $value["idCentroProduccion"],
                        'id_marca'                    => $value2["idMarca"],
                        'id_requerimiento'            => $requerimientoId,
                        'cant_det_requerimiento'      => $value2["cantDetRequerimiento"],
                        'costo_det_requerimiento'     => 0,
                        'fecha_reg_det_requerimiento' => Carbon::now(),
                        'usuario_det_requerimiento'   => $request->user()->nick_usuario,
                        'ip_det_requerimiento'        => $request->ip(),
                    ]
                );
            }
        }
    }

    function updateRequerimientoAlmacen(RequerimientoRequest $request)
    {


        //     $requerimiento = [];
        $requerimiento = [
            'id_requerimiento'          => $request->idRequerimiento,
            'id_lt'                     => $request->idLt,
            'id_centro_atencion'        => $request->idCentroAtencion,
            'id_proy_financiado'        => $request->idProyFinanciado,
            'id_estado_req'             => 1,
            'num_requerimiento'         => $request->numRequerimiento,
            'fecha_requerimiento'       => Carbon::now(),
            'observacion_requerimiento' => $request->observacionRequerimiento,
            'fecha_mod_requerimiento'   => Carbon::now(),
            'usuario_requerimiento'     => $request->user()->nick_usuario,
            'ip_requerimiento'          => $request->ip(),

        ];

        Requerimiento::where("id_requerimiento", $request->idRequerimiento)->update($requerimiento);


        foreach ($request->dataDetalleRequerimiento as $key => $value) {


            foreach ($value["productos"] as $key => $value2) {

                if ($value2["idDetRequerimiento"]) {
                    if ($value2["stateProducto"] == 1) {
                        DetalleRequerimiento::where("id_det_requerimiento", $value2["idDetRequerimiento"])->update(
                            [
                                'id_producto'                 => $value2["idProducto"],
                                'id_marca'                    => $value2["idMarca"],
                                'id_centro_produccion'        => $value["idCentroProduccion"],
                                'id_requerimiento'            => $request->idRequerimiento,
                                'cant_det_requerimiento'      => $value2["cantDetRequerimiento"],
                                'costo_det_requerimiento'     => 0,
                                'fecha_reg_det_requerimiento' => Carbon::now(),
                                'usuario_det_requerimiento'   => $request->user()->nick_usuario,
                                'ip_det_requerimiento'        => $request->ip(),
                            ]
                        );
                    } else {
                        // eliminar
                        DetalleRequerimiento::where("id_det_requerimiento", $value2["idDetRequerimiento"])->delete();
                    }
                } else {

                    if ($value2["stateProducto"] == 1) {

                        // Agregar
                        DetalleRequerimiento::insert(
                            [
                                'id_producto'                 => $value2["idProducto"],
                                'id_marca'                    => $value2["idMarca"],
                                'id_centro_produccion'        => $value["idCentroProduccion"],
                                'id_requerimiento'            => $request->idRequerimiento,
                                'cant_det_requerimiento'      => $value2["cantDetRequerimiento"],
                                'costo_det_requerimiento'     => 0,
                                'fecha_reg_det_requerimiento' => Carbon::now(),
                                'usuario_det_requerimiento'   => $request->user()->nick_usuario,
                                'ip_det_requerimiento'        => $request->ip(),
                            ]
                        );
                    }
                }
            }
        }


        return $request;
    }

    /**
     * Actualiza el estado de un requerimiento y realiza acciones adicionales según el estado.
     *
     * @param  Request $request - La solicitud HTTP que contiene los datos necesarios para actualizar el requerimiento.
     * @return array            - Un arreglo vacío como respuesta.
     */
    function updateStateRequerimiento(Request $request): array
    {
        // Datos a actualizar en el requerimiento
        $objectUpdated = [
            "id_estado_req"           => $request->idEstado,
            'fecha_mod_requerimiento' => Carbon::now(),
        ];

        // Si el estado es "Despachado" (idEstado == 3)
        if ($request->idEstado == 3) {
            // Obtener los detalles del requerimiento
            $detReq = DetalleRequerimiento::where("id_requerimiento", $request->idRequerimiento)->get();
            $existencias = [];

            // Iterar sobre los detalles del requerimiento
            foreach ($detReq as $key => $value) {
                // Buscar la existencia en el almacén
                $existenciaAlmacen = ExistenciaAlmacen::where([
                    "id_producto"        => $value["id_producto"],
                    "id_proy_financiado" => $request->idProyectoFinanciado
                ])->first();

                // Si se encuentra la existencia, agregarla al arreglo de existencias
                if ($existenciaAlmacen !== null) {
                    $existencias[] = $existenciaAlmacen;
                }

                // Actualizar el costo del detalle del requerimiento con el costo de la existencia en el almacén
                DetalleRequerimiento::where("id_det_requerimiento", $value["id_det_requerimiento"])->update([
                    "costo_det_requerimiento" => $existenciaAlmacen["costo_existencia_almacen"]
                ]);
            }

            // Crear un registro en el kardex
            $requerimiento = Requerimiento::find($request->idRequerimiento);
            $kardexArray = [
                'id_proy_financiado' => $requerimiento->id_proy_financiado,
                'id_tipo_req'        => 1,
                'id_tipo_mov_kardex' => 2,
                'id_requerimiento'   => $request->idRequerimiento,
                'fecha_kardex'       => Carbon::now(),
                'observacion_kardex' => 'Despacho del requerimiento: ' . $requerimiento->num_requerimiento,
                'fecha_reg_kardex'   => Carbon::now(),
                'usuario_kardex'     => $request->user()->nick_usuario,
                'ip_kardex'          => $request->ip(),
            ];
            $cardexId = Kardex::insertGetId($kardexArray);

            // Registrar detalles en el kardex
            $detalleRequerimiento = DetalleRequerimiento::where("id_requerimiento", $request->idRequerimiento)->get();
            foreach ($detalleRequerimiento as $key => $value) {
                DetalleKardex::insert([
                    'id_kardex'                    => $cardexId,
                    'id_producto'                  => $value['id_producto'],
                    'id_lt'                        => $requerimiento->id_lt,
                    'id_centro_atencion'           => $requerimiento->id_centro_atencion,
                    'id_marca'                     => $value['id_marca'],
                    'cant_det_kardex'              => $value['cant_det_requerimiento'],
                    'costo_det_kardex'             => $value['costo_det_requerimiento'],
                    'fecha_reg_det_kardex'         => Carbon::now(),
                    'usuario_det_kardex'           => $request->user()->nick_usuario,
                    'ip_det_kardex'                => $request->ip(),
                ]);
            }

        // Si el estado es "Anulado" (idEstado == 4)
        } else if ($request->idEstado == 4) {
            // Actualizar el número de requerimiento a "(N/A)"
            $objectUpdated["num_requerimiento"] = '(N/A)';
        }

        // Actualizar el requerimiento con los datos actualizados
        Requerimiento::where("id_requerimiento", $request->idRequerimiento)->update($objectUpdated);

        // Devolver un arreglo vacío como respuesta
        return [];
    }

}
