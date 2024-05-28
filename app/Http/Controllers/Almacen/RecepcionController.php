<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Almacen\RecepcionRequest;
use App\Models\DetalleKardex;
use App\Models\DetalleRecepcionPedido;
use App\Models\DetDocumentoAdquisicion;
use App\Models\Kardex;
use App\Models\Marca;
use App\Models\ProductoAdquisicion;
use App\Models\RecepcionPedido;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Luecano\NumeroALetras\NumeroALetras;

class RecepcionController extends Controller
{
    public function getRecepciones(Request $request)
    {
        $columns = ['id_recepcion_pedido', 'acta_recepcion_pedido', 'tipo_documento', 'numero_documento', 'monto_recepcion_pedido', 'fecha_recepcion_pedido', 'id_estado_recepcion_pedido'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $rol = Rol::find(session()->get('id_rol')); //rol from the view

        $user = $request->user(); //Logged user
        $id_empleado = $user->persona->empleado->id_empleado;

        $query = RecepcionPedido::select('*')
            ->with([
                'detalle_recepcion',
                'det_doc_adquisicion.documento_adquisicion.proceso_compra.empleado',
                'det_doc_adquisicion.documento_adquisicion.tipo_documento_adquisicion',
                'estado_recepcion'
            ])
            ->where('id_proy_financiado', "!=", 4);

        if ($rol->nombre_rol != 'Administrador ALM') { //Filter if the user is not an admin
            $query->whereHas('det_doc_adquisicion.documento_adquisicion.proceso_compra.empleado', function ($query) use ($id_empleado) {
                $query->where('id_empleado', $id_empleado);
            });
        }

        //Sorting
        if ($column == 2) { //Order by document type
            $query->orderByRaw('
                (SELECT id_tipo_doc_adquisicion FROM documento_adquisicion WHERE documento_adquisicion.id_doc_adquisicion = 
                (SELECT id_doc_adquisicion FROM detalle_documento_adquisicion WHERE detalle_documento_adquisicion.id_det_doc_adquisicion = recepcion_pedido.id_det_doc_adquisicion) 
            ) ' . $dir);
        } else {
            if ($column == 3) { //Order by document number
                $query->orderByRaw('
                (SELECT numero_doc_adquisicion FROM documento_adquisicion WHERE documento_adquisicion.id_doc_adquisicion = 
                (SELECT id_doc_adquisicion FROM detalle_documento_adquisicion WHERE detalle_documento_adquisicion.id_det_doc_adquisicion = recepcion_pedido.id_det_doc_adquisicion) 
            ) ' . $dir);
            } else {
                $query->orderBy($columns[$column], $dir);
            }
        }

        //Searching parameters
        if ($search_value) {
            $query->where('id_recepcion_pedido', 'like', '%' . $search_value['id_recepcion_pedido'] . '%') //Search by reception id
                ->where('acta_recepcion_pedido', 'like', '%' . $search_value['acta_recepcion_pedido'] . '%') //Search by Acta
                ->where('id_estado_recepcion_pedido', 'like', '%' . $search_value['id_estado_recepcion_pedido'] . '%') //Search by reception status
                ->where('fecha_recepcion_pedido', 'like', '%' . $search_value['fecha_recepcion_pedido'] . '%') //Search by reception date
                ->where('monto_recepcion_pedido', 'like', '%' . $search_value['monto_recepcion_pedido'] . '%'); //Search by reception amount
            //Search by document type
            if ($search_value['tipo_documento']) {
                $query->whereHas(
                    'det_doc_adquisicion.documento_adquisicion',
                    function ($query) use ($search_value) {
                        if ($search_value["tipo_documento"] != '') {
                            $query->where('id_tipo_doc_adquisicion', 'like', '%' . $search_value['tipo_documento'] . '%');
                        }
                    }
                );
            }
            //Search by document number
            if ($search_value['numero_documento']) {
                $query->whereHas(
                    'det_doc_adquisicion.documento_adquisicion',
                    function ($query) use ($search_value) {
                        if ($search_value["numero_documento"] != '') {
                            $query->where('numero_doc_adquisicion', 'like', '%' . $search_value['numero_documento'] . '%');
                        }
                    }
                );
            }
        }

        $data = $query->paginate($length)->onEachSide(1);
        return ['data' => $data, 'draw' => $request->input('draw')];
    }

    public function getInitialInfoDoc(Request $request)
    {
        $user = $request->user(); //Logged user
        $id_empleado = $user->persona->empleado->id_empleado;

        $rol = Rol::find(session()->get('id_rol')); //rol from the view

        //It returns all the documents that has pending products
        $pendingItems = DB::table(DB::raw('(
            SELECT
                dda.id_doc_adquisicion,
                dda.id_det_doc_adquisicion,
                da.numero_doc_adquisicion,
                dda.nombre_det_doc_adquisicion,
                pc.id_empleado,
                pc.id_proceso_compra,
                (pa.cant_prod_adquisicion - IFNULL(ing.total_prod_recibido, 0)) AS diferencia,
                ROUND((pa.cant_prod_adquisicion * pa.costo_prod_adquisicion) - IFNULL(ing.total_monto_recibido, 0), 2) AS diferencia_monto
            FROM
                documento_adquisicion AS da
            INNER JOIN detalle_documento_adquisicion AS dda ON da.id_doc_adquisicion = dda.id_doc_adquisicion
            INNER JOIN proceso_compra AS pc ON pc.id_proceso_compra = da.id_proceso_compra
            INNER JOIN producto_adquisicion AS pa ON dda.id_det_doc_adquisicion = pa.id_det_doc_adquisicion
            LEFT JOIN (
                SELECT
                    drp.id_prod_adquisicion,
                    SUM(drp.cant_det_recepcion_pedido) AS total_prod_recibido,
                    SUM(drp.cant_det_recepcion_pedido * drp.costo_det_recepcion_pedido) AS total_monto_recibido
                FROM
                    recepcion_pedido AS rp
                INNER JOIN detalle_recepcion_pedido AS drp ON rp.id_recepcion_pedido = drp.id_recepcion_pedido
                WHERE
                    drp.estado_det_recepcion_pedido = 1
                    AND rp.id_estado_recepcion_pedido != 3
                GROUP BY
                    drp.id_prod_adquisicion
            ) AS ing ON pa.id_prod_adquisicion = ing.id_prod_adquisicion
            WHERE
                dda.estado_det_doc_adquisicion = 1
                AND pa.estado_prod_adquisicion = 1
                AND dda.id_estado_doc_adquisicion = 2
        ) AS pendiente'))
            ->select(
                'pendiente.id_doc_adquisicion',
                'pendiente.numero_doc_adquisicion',
                DB::raw('pendiente.nombre_det_doc_adquisicion as label'),
                DB::raw('pendiente.id_det_doc_adquisicion as value')
            )
            ->whereRaw('(pendiente.diferencia_monto > 0 AND pendiente.id_proceso_compra = 5) OR (pendiente.id_proceso_compra != 5 AND pendiente.diferencia > 0)')
            ->groupBy('pendiente.id_doc_adquisicion');

        //We evaluate if the user is not admin to apply filter
        if ($rol->nombre_rol != 'Administrador ALM') {
            $pendingItems->where('id_empleado', $id_empleado);
        }

        //This returns the missing documents id
        $idDocs = $pendingItems->pluck('pendiente.id_doc_adquisicion')->toArray();
        //We only get the pending acquisition documents
        $docs = DB::table('documento_adquisicion')
            ->select('id_doc_adquisicion as value', 'numero_doc_adquisicion as label', 'id_tipo_doc_adquisicion')
            ->whereIn('id_doc_adquisicion', $idDocs)
            ->where('estado_doc_adquisicion', 1)
            ->get();
        //Return the response to the view
        return response()->json([
            'test'                          => $pendingItems->get(),
            'docs'                          => $docs
        ]);
    }

    public function checkAvailableMonths(Request $request)
    {
        $idDetDoc = $request->input('detDocId');

        $mesesConFaltantes = DB::select(
            '
            WITH Interno AS (
                SELECT
                    drp.id_prod_adquisicion,
                    rp.id_mes_recepcion,
                    SUM(drp.cant_det_recepcion_pedido) AS contador,
                    SUM(drp.cant_det_recepcion_pedido * drp.costo_det_recepcion_pedido) AS contador_monto
                FROM
                    recepcion_pedido rp
                INNER JOIN detalle_recepcion_pedido drp 
                    ON rp.id_recepcion_pedido = drp.id_recepcion_pedido
                WHERE
                    rp.id_det_doc_adquisicion = ?
                    AND rp.id_estado_recepcion_pedido != 3
                    AND drp.estado_det_recepcion_pedido = 1
                GROUP BY
                    drp.id_prod_adquisicion, rp.id_mes_recepcion
            ),
            MesesConFaltantes AS (
                SELECT DISTINCT
                    mr.id_mes_recepcion
                FROM producto_adquisicion pa
                JOIN mes_recepcion mr ON mr.id_mes_recepcion BETWEEN 1 AND 12
                LEFT JOIN Interno i 
                    ON pa.id_prod_adquisicion = i.id_prod_adquisicion 
                    AND mr.id_mes_recepcion = i.id_mes_recepcion
                WHERE
                    pa.id_det_doc_adquisicion = ?
                    AND CASE mr.id_mes_recepcion
                        WHEN 1 THEN pa.cant_ene_prod_adquisicion * pa.costo_prod_adquisicion
                        WHEN 2 THEN pa.cant_feb_prod_adquisicion * pa.costo_prod_adquisicion
                        WHEN 3 THEN pa.cant_mar_prod_adquisicion * pa.costo_prod_adquisicion
                        WHEN 4 THEN pa.cant_abr_prod_adquisicion * pa.costo_prod_adquisicion
                        WHEN 5 THEN pa.cant_may_prod_adquisicion * pa.costo_prod_adquisicion
                        WHEN 6 THEN pa.cant_jun_prod_adquisicion * pa.costo_prod_adquisicion
                        WHEN 7 THEN pa.cant_jul_prod_adquisicion * pa.costo_prod_adquisicion
                        WHEN 8 THEN pa.cant_ago_prod_adquisicion * pa.costo_prod_adquisicion
                        WHEN 9 THEN pa.cant_sept_prod_adquisicion * pa.costo_prod_adquisicion
                        WHEN 10 THEN pa.cant_oct_prod_adquisicion * pa.costo_prod_adquisicion
                        WHEN 11 THEN pa.cant_nov_prod_adquisicion * pa.costo_prod_adquisicion
                        WHEN 12 THEN pa.cant_dic_prod_adquisicion * pa.costo_prod_adquisicion
                    END > IFNULL(i.contador_monto, 0)
            )
            SELECT id_mes_recepcion FROM MesesConFaltantes;
        ',
            [$idDetDoc, $idDetDoc]
        );

        // Extraer los IDs de los meses
        $mesIds = array_map(function ($row) {
            return $row->id_mes_recepcion;
        }, $mesesConFaltantes);

        if (empty($mesIds)) {
            return response()->json(['logical_error' => 'No se encontraron meses disponibles.'], 500);
        }

        // Consultar la tabla mes_recepcion con los IDs obtenidos
        $mesesSeleccionables = DB::table('mes_recepcion')
            ->select('id_mes_recepcion as value', 'nombre_mes_recepcion as label')
            ->whereIn('id_mes_recepcion', $mesIds)
            ->get();

        return response()->json([
            'monthsAvail'      => $mesesSeleccionables
        ]);
    }

    public function getInfoModalRecep(Request $request)
    {
        $idRec = $request->id; //Reception id from the view, if it's 0 that means we are creating a new reception
        if ($idRec > 0) { //This means we are updating an existing reception
            $recep = RecepcionPedido::with([
                'detalle_recepcion.producto.unidad_medida',
                'detalle_recepcion.producto_adquisicion.linea_trabajo',
                'detalle_recepcion.producto_adquisicion.centro_atencion',
                'detalle_recepcion.marca',
                'det_doc_adquisicion.documento_adquisicion.tipo_documento_adquisicion',
                'estado_recepcion',
                'mes_recepcion'
            ])->find($idRec);
            $item = DetDocumentoAdquisicion::with(['documento_adquisicion.proveedor', 'fuente_financiamiento', 'documento_adquisicion.tipo_documento_adquisicion', 'documento_adquisicion.proceso_compra'])->find($recep->det_doc_adquisicion->id_det_doc_adquisicion);
            if (!$recep || !$item) {
                return response()->json([
                    'logical_error' => 'Error, no fue posible obtener la recepción consultada. Consulte con el administrador.',
                ], 422);
            }
        } else {
            $recep = [];
            $item = DetDocumentoAdquisicion::with(['documento_adquisicion.proveedor', 'fuente_financiamiento', 'documento_adquisicion.tipo_documento_adquisicion', 'documento_adquisicion.proceso_compra'])->find($request->detId);
        }

        if ($item->estado_det_doc_adquisicion == 0 && $recep != []) {
            $data = [
                'id_estado_recepcion_pedido'            => 3,
                'fecha_mod_permiso'                     => Carbon::now(),
                'usuario_permiso'                       => $request->user()->nick_usuario,
                'ip_permiso'                            => $request->ip(),
            ];
            $recep->update($data);
            return response()->json([
                'logical_error' => 'Error, el item del documento asociado ha sido eliminado.',
            ], 422);
        } else {
            if ($item->productos_adquisiciones()->where('estado_prod_adquisicion', 1)->exists()) {
                if ($idRec > 0) {
                    $procedure = DB::select(
                        'CALL PR_GET_PRODUCT_ACQUISITION_MINUS_CURRENT_RECEIPT(?, ?)',
                        array($item->id_det_doc_adquisicion, $recep->id_recepcion_pedido)
                    );
                } else {
                    $procedure = DB::select(
                        'CALL PR_GET_PRODUCT_ACQUISITION(?)',
                        array($item->id_det_doc_adquisicion)
                    );
                }
                $brands = Marca::select('id_marca as value', 'nombre_marca as label')->get();

                return response()->json([
                    'recep'                         => $recep,
                    'products'                      => $procedure,
                    'itemInfo'                      => $item,
                    'brands'                         => $brands
                ]);
            } else {
                return response()->json([
                    'logical_error' => 'Error, el item seleccionado no posee ningun producto asociado.',
                ], 422);
            }
        }
    }

    public function storeReception(RecepcionRequest $request)
    {
        $procedure = DB::select(
            'CALL PR_GET_PRODUCT_ACQUISITION(?)',
            array($request->detDocId)
        );

        // Obtener el valor de total_menos_acumulado de todos los elementos de $procedure
        $totalMenosAcumuladoProcedure = array_column($procedure, $request->isGas ? 'total_menos_acumulado_monto' : 'total_menos_acumulado');

        // Obtener el valor de total_menos_acumulado de todos los elementos de $request->procedure
        $totalMenosAcumuladoRequest = array_column($request->procedure, $request->isGas ? 'total_menos_acumulado_monto' : 'total_menos_acumulado');

        // Comparar los valores
        if ($totalMenosAcumuladoProcedure !== $totalMenosAcumuladoRequest) {
            return response()->json([
                'logical_error' => 'Error, la disponibilidad de recepcion de bienes ha cambiado, intente nuevamente.',
                'refresh'       => true,
                'prods'         => $procedure
            ], 422);
        }

        $detDoc = DetDocumentoAdquisicion::with('documento_adquisicion.proveedor')->find($request->detDocId);

        DB::beginTransaction();
        try {
            $codeActa = "";
            $year = Carbon::now()->year;
            $lastActa = RecepcionPedido::whereYear('fecha_reg_recepcion_pedido', $year)
                ->orderBy('fecha_reg_recepcion_pedido', 'desc')
                ->first();
            if (!$lastActa) {
                $codeActa = '1/' . $year;
            } else {
                $correlative = intval(explode('/', $lastActa->acta_recepcion_pedido)[0]) + 1;
                $codeActa = $correlative . '/' . $year;
            }

            $rec = new RecepcionPedido([
                'id_det_doc_adquisicion'                => $request->detDocId,
                'id_proy_financiado'                    => $request->financingSourceId,
                'monto_recepcion_pedido'                => $request->total,
                'id_proveedor'                          => $detDoc->documento_adquisicion->proveedor->id_proveedor,
                'id_estado_recepcion_pedido'            => 1,
                'factura_recepcion_pedido'              => $request->invoice,
                'fecha_recepcion_pedido'                => Carbon::now(),
                'acta_recepcion_pedido'                 => $codeActa,
                'observacion_recepcion_pedido'          => $request->observation,
                'fecha_reg_recepcion_pedido'            => Carbon::now(),
                'usuario_recepcion_pedido'              => $request->user()->nick_usuario,
                'ip_recepcion_pedido'                   => $request->ip(),
            ]);
            $rec->save();

            foreach ($request->prods as $lts) {
                foreach ($lts['productos'] as $prod) {
                    $fecha = $prod['expiryDate'] != '' ? date('Y/m/d', strtotime($prod['expiryDate'])) : null;

                    $prodAdq = ProductoAdquisicion::find($prod['prodId']);

                    $existDetail = DetalleRecepcionPedido::where('id_recepcion_pedido', $rec->id_recepcion_pedido)
                        ->where('id_prod_adquisicion', $prodAdq->id_prod_adquisicion)
                        ->where('id_marca', $prod['brandId'])
                        ->where('fecha_vcto_det_recepcion_pedido', $fecha)
                        ->where('costo_det_recepcion_pedido', number_format($prod['cost'], 2))
                        ->first();

                    if ($existDetail) {
                        $existDetail->update([
                            'cant_det_recepcion_pedido'                 => $existDetail->cant_det_recepcion_pedido + $prod['qty'],
                            'estado_det_recepcion_pedido'               => 1,
                            'fecha_mod_det_recepcion_pedido'            => Carbon::now(),
                            'usuario_det_recepcion_pedido'              => $request->user()->nick_usuario,
                            'ip_det_recepcion_pedido'                   => $request->ip()
                        ]);
                    } else {
                        $newDet = new DetalleRecepcionPedido([
                            'id_centro_atencion'                        => $prodAdq->id_centro_atencion,
                            'id_producto'                               => $prodAdq->id_producto,
                            'id_recepcion_pedido'                       => $rec->id_recepcion_pedido,
                            'id_marca'                                  => $prod['brandId'],
                            'id_lt'                                     => $prodAdq->id_lt,
                            'id_prod_adquisicion'                       => $prod['prodId'],
                            'cant_det_recepcion_pedido'                 => $prod['qty'],
                            'costo_det_recepcion_pedido'                => $request->isGas ? $prod['cost'] : $prodAdq->costo_prod_adquisicion,
                            'fecha_vcto_det_recepcion_pedido'           => $fecha,
                            'estado_det_recepcion_pedido'               => 1,
                            'fecha_reg_det_recepcion_pedido'            => Carbon::now(),
                            'usuario_det_recepcion_pedido'              => $request->user()->nick_usuario,
                            'ip_det_recepcion_pedido'                   => $request->ip()
                        ]);
                        $newDet->save();
                    }
                }
            }

            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'message'           => 'Guardado nueva recepcion con éxito.',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack(); // En caso de error, revierte las operaciones anteriores
            return response()->json([
                'logical_error' => 'Ha ocurrido un error con sus datos.',
                'error' => $th->getMessage(),
            ], 422);
        }
    }

    public function updateReception(RecepcionRequest $request)
    {
        $procedure = DB::select(
            'CALL PR_GET_PRODUCT_ACQUISITION(?)',
            array($request->detDocId)
        );

        $procedure = DB::select(
            'CALL PR_GET_PRODUCT_ACQUISITION_MINUS_CURRENT_RECEIPT(?, ?)',
            array($request->detDocId, $request->id)
        );

        // Obtener el valor de total_menos_acumulado de todos los elementos de $procedure
        $totalMenosAcumuladoProcedure = array_column($procedure, $request->isGas ? 'total_menos_acumulado_monto' : 'total_menos_acumulado');

        // Obtener el valor de total_menos_acumulado de todos los elementos de $request->procedure
        $totalMenosAcumuladoRequest = array_column($request->procedure, $request->isGas ? 'total_menos_acumulado_monto' : 'total_menos_acumulado');

        // Comparar los valores
        if ($totalMenosAcumuladoProcedure !== $totalMenosAcumuladoRequest) {
            return response()->json([
                'logical_error' => 'Error, la disponibilidad de recepcion de bienes ha cambiado, intente nuevamente xxx.',
                'refresh'       => true,
                'prods'            => $procedure,
            ], 422);
        }

        $rec = RecepcionPedido::find($request->id);
        if (!$rec || $rec->id_estado_recepcion_pedido == 3) {
            return response()->json(['logical_error' => 'Error, la recepcion que intentas modificar no existe o ha sido eliminadas.'], 422);
        } else {
            DB::beginTransaction();
            try {
                $rec->update([
                    'monto_recepcion_pedido'                => $request->total,
                    'observacion_recepcion_pedido'          => $request->observation,
                    'fecha_mod_recepcion_pedido'            => Carbon::now(),
                    'usuario_recepcion_pedido'              => $request->user()->nick_usuario,
                    'ip_recepcion_pedido'                   => $request->ip(),
                ]);

                foreach ($request->prods as $lts) {
                    foreach ($lts['productos'] as $prod) {
                        $prodAdq = ProductoAdquisicion::find($prod['prodId']);

                        $fecha = $prod['expiryDate'] != '' ? date('Y/m/d', strtotime($prod['expiryDate'])) : null;

                        if ($prod['detRecId'] != "" && $prod['deleted'] == false) {
                            $det = DetalleRecepcionPedido::find($prod['detRecId']);
                            $det->update([
                                'id_centro_atencion'                        => $prodAdq->id_centro_atencion,
                                'id_producto'                               => $prodAdq->id_producto,
                                'id_marca'                                  => $prod['brandId'],
                                'id_lt'                                     => $prodAdq->id_lt,
                                'id_recepcion_pedido'                       => $request->id,
                                'id_prod_adquisicion'                       => $prod['prodId'],
                                'cant_det_recepcion_pedido'                 => $prod['qty'],
                                'costo_det_recepcion_pedido'                => $request->isGas ? $prod['cost'] : $prodAdq->costo_prod_adquisicion,
                                'fecha_vcto_det_recepcion_pedido'           => $fecha,
                                'fecha_mod_det_recepcion_pedido'            => Carbon::now(),
                                'usuario_det_recepcion_pedido'              => $request->user()->nick_usuario,
                                'ip_det_recepcion_pedido'                   => $request->ip()
                            ]);
                        }

                        if ($prod['detRecId'] != "" && $prod['deleted'] == true) {
                            $det = DetalleRecepcionPedido::find($prod['detRecId']);
                            $det->update([
                                'estado_det_recepcion_pedido' => 0,
                                'fecha_mod_det_recepcion_pedido' => Carbon::now(),
                                'usuario_det_recepcion_pedido' => $request->user()->nick_usuario,
                                'ip_det_recepcion_pedido' => $request->ip()
                            ]);
                        }

                        if ($prod['detRecId'] == "" && $prod['deleted'] == false) {

                            $existDetail = DetalleRecepcionPedido::where('id_recepcion_pedido', $rec->id_recepcion_pedido)
                                ->where('id_prod_adquisicion', $prodAdq->id_prod_adquisicion)
                                ->where('id_marca', $prod['brandId'])
                                ->where('fecha_vcto_det_recepcion_pedido', $fecha)
                                ->where('costo_det_recepcion_pedido', number_format($prod['cost'], 6))
                                ->first();

                            if ($existDetail) {
                                $cant = $existDetail->estado_det_recepcion_pedido == 0 ? $prod['qty'] : $existDetail->cant_det_recepcion_pedido + $prod['qty'];

                                $existDetail->update([
                                    'cant_det_recepcion_pedido'                 => $cant,
                                    //'fecha_vencimiento_det_recepcion_pedido'    => $prod['expiryDate'],
                                    'estado_det_recepcion_pedido'               => 1,
                                    'fecha_mod_det_recepcion_pedido'            => Carbon::now(),
                                    'usuario_det_recepcion_pedido'              => $request->user()->nick_usuario,
                                    'ip_det_recepcion_pedido'                   => $request->ip()
                                ]);
                            } else {
                                $newDet = new DetalleRecepcionPedido([
                                    'id_centro_atencion'                        => $prodAdq->id_centro_atencion,
                                    'id_producto'                               => $prodAdq->id_producto,
                                    'id_marca'                                  => $prod['brandId'],
                                    'id_lt'                                     => $prodAdq->id_lt,
                                    'id_recepcion_pedido'                       => $request->id,
                                    'id_prod_adquisicion'                       => $prod['prodId'],
                                    'cant_det_recepcion_pedido'                 => $prod['qty'],
                                    'estado_det_recepcion_pedido'               => 1,
                                    'costo_det_recepcion_pedido'                => $request->isGas ? $prod['cost'] : $prodAdq->costo_prod_adquisicion,
                                    'fecha_vcto_det_recepcion_pedido'           => $fecha,
                                    'fecha_reg_det_recepcion_pedido'            => Carbon::now(),
                                    'usuario_det_recepcion_pedido'              => $request->user()->nick_usuario,
                                    'ip_det_recepcion_pedido'                   => $request->ip()
                                ]);
                                $newDet->save();
                            }
                        }
                    }
                }
                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'message'          => 'Actualizada recepcion pedido con exito.',
                ]);
            } catch (\Throwable $th) {
                DB::rollBack(); // En caso de error, revierte las operaciones anteriores
                return response()->json([
                    'logical_error' => 'Ha ocurrido un error con sus datos.',
                    'error' => $th->getMessage(),
                ], 422);
            }
        }
    }

    public function changeStatusReception(Request $request)
    {
        $reception = RecepcionPedido::find($request->id);
        if ($reception->id_estado_recepcion_pedido == 1 && $request->status == 1) {
            DB::beginTransaction();
            try {
                $reception->update([
                    'id_estado_recepcion_pedido'            => 3,
                    'fecha_mod_recepcion_pedido'            => Carbon::now(),
                    'usuario_recepcion_pedido'              => $request->user()->nick_usuario,
                    'ip_recepcion_pedido'                   => $request->ip(),
                ]);
                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'message'          => "Recepción eliminada con éxito.",
                ]);
            } catch (\Exception $e) {
                DB::rollBack(); // En caso de error, revierte las operaciones anteriores
                return response()->json([
                    'logical_error' => 'Ha ocurrido un error con sus datos.',
                    'error' => $e,
                ], 422);
            }
        } else {
            return response()->json(['logical_error' => 'Error, otro usuario ha cambiado el estado de esta recepción.',], 422);
        }
    }

    public function getInfoModalSendKardex(Request $request, $id)
    {
        $recInfo = RecepcionPedido::with(
            [
                'det_doc_adquisicion.documento_adquisicion.administradores' => function ($query) {
                    $query->where('estado_admon_adquisicion', 1);
                },
                'det_doc_adquisicion.documento_adquisicion.administradores.empleado.persona'
            ]
        )->find($id);

        $empOptions = $recInfo->det_doc_adquisicion->documento_adquisicion->administradores->map(function ($e) {
            return [
                'value'           => $e->empleado->id_empleado,
                'label'           => $e->empleado->persona->nombre_apellido
            ];
        });

        return response()->json([
            'recInfo'                           => $recInfo,
            'empOptions'                        => $empOptions
        ]);
    }

    public function sendGoodsReception(Request $request)
    {
        //Define the custom messages
        $customMessages = [
            'conctManagerId.required' => 'Debe seleccionar el administrador de documento.',
            'suppRep.required' => 'Debe escribir el nombre del representante del proveedor.',
            'nonCompliant.required' => 'Debe seleccionar si existe incumplimiento.',
            'nonCompliant.not_in' => 'Debe seleccionar si existe incumplimiento.',
            'observation.required_if' => 'Debe agregar la descripción por incumplimiento.'
        ];

        // Validate the request data with custom error messages and custom rule
        $validatedData = Validator::make($request->all(), [
            'conctManagerId' => 'required',
            'suppRep' => 'required',
            'nonCompliant' => 'required|not_in:-1',
            'observation' => 'required_if:nonCompliant,1',
        ], $customMessages)->validate();

        //Find the current products reception
        $reception = RecepcionPedido::with([
            'det_doc_adquisicion.fuente_financiamiento',
            'det_doc_adquisicion.documento_adquisicion.proceso_compra',
            'detalle_recepcion' => function ($query) {
                $query->where('estado_det_recepcion_pedido', 1);
            },
            'detalle_recepcion.producto_adquisicion'
        ])->find($request->id);
        //Find the user who stores the products reception
        $user = User::with('persona.empleado')->find($request->user()->id_usuario);

        if ($reception->id_estado_recepcion_pedido == 1) { //We must evaluate if the reception has the status 'CREADO'
            DB::beginTransaction(); //Start the transaction
            try {
                //We update the reception
                $reception->update([
                    'id_estado_recepcion_pedido'            => 2,
                    'incumple_acuerdo_recepcion_pedido'     => $request->nonCompliant,
                    'incumplimiento_recepcion_pedido'       => $request->observation,
                    'id_empleado'                           => $request->conctManagerId,
                    'representante_prov_recepcion_pedido'   => $request->suppRep,
                    'emp_id_empleado'                       => $user->persona->empleado->id_empleado,
                    'fecha_reg_recepcion_pedido'            => Carbon::now(),
                    'fecha_recepcion_pedido'                => Carbon::now(),
                    'usuario_recepcion_pedido'              => $request->user()->nick_usuario,
                    'ip_recepcion_pedido'                   => $request->ip(),
                ]);
                //Create a new Kardex object
                $kardex = new Kardex([
                    'id_recepcion_pedido'                   => $reception->id_recepcion_pedido,
                    'id_proy_financiado'                    => $reception->id_proy_financiado,
                    'id_tipo_mov_kardex'                    => 1,
                    'fecha_kardex'                          => Carbon::now(),
                    'fecha_reg_kardex'                      => Carbon::now(),
                    'usuario_kardex'                        => $request->user()->nick_usuario,
                    'ip_kardex'                             => $request->ip(),
                ]);
                $kardex->save();
                //Foreach 'detalle_reception' we create a 'detalle_kardex' instance
                foreach ($reception->detalle_recepcion as $det) {
                    $detKardex = new DetalleKardex([
                        'id_kardex'                         => $kardex->id_kardex,
                        'id_producto'                       => $det->producto_adquisicion->id_producto,
                        'id_lt'                             => $det->producto_adquisicion->id_lt,
                        'id_centro_atencion'                => $det->producto_adquisicion->id_centro_atencion,
                        'id_marca'                          => $det->id_marca,
                        'fecha_vencimiento_det_kardex'      => $det->fecha_vcto_det_recepcion_pedido,
                        'cant_det_kardex'                   => $det->cant_det_recepcion_pedido,
                        'costo_det_kardex'                  => $det->costo_det_recepcion_pedido,
                        'fecha_reg_det_kardex'              => Carbon::now(),
                        'usuario_det_kardex'                => $request->user()->nick_usuario,
                        'ip_det_kardex'                     => $request->ip(),
                    ]);
                    $detKardex->save();

                    //We update the stock
                    $resultados = DB::select(" SELECT FN_UPDATE_EXISTENCIA_ALMACEN(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        $reception->id_proy_financiado,
                        $det->id_producto,
                        $det->id_centro_atencion,
                        $det->id_lt ?? null,
                        $det->id_marca ?? null,
                        $det->cant_det_recepcion_pedido,
                        $det->costo_det_recepcion_pedido,
                        $det->fecha_vcto_det_recepcion_pedido,
                        Carbon::now(),
                        $request->user()->nick_usuario,
                        $request->ip()
                    ]);
                }

                if ($reception->det_doc_adquisicion->documento_adquisicion->proceso_compra->nombre_proceso_compra == 'GAS LICUADO DE PETROLEO') {
                    $pendientes = $this->getPendingItems(true, $reception);
                } else {
                    $pendientes = $this->getPendingItems(false, $reception);
                }

                if ($pendientes->isEmpty()) {
                    $item = DetDocumentoAdquisicion::find($reception->id_det_doc_adquisicion);
                    $item->update([
                        'id_estado_doc_adquisicion'         => 3,
                        'fecha_mod_det_doc_adquisicion'     => Carbon::now(),
                        'usuario_det_doc_adquisicion'       => $request->user()->nick_usuario,
                        'ip_det_doc_adquisicion'            => $request->ip()
                    ]);
                }

                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'message'          => "Recepción enviada al Kardex con éxito.",
                ]);
            } catch (\Exception $e) {
                DB::rollBack(); // En caso de error, revierte las operaciones anteriores
                return response()->json([
                    'logical_error' => 'Ha ocurrido un error con sus datos.',
                    'error' => $e->getMessage(),
                ], 422);
            }
        } else {
            return response()->json(['logical_error' => 'Error, otro usuario ha cambiado el estado de esta recepción.',], 422);
        }
    }

    public function printReception(Request $request, $id)
    {
        $recToPrint = RecepcionPedido::with([
            'detalle_recepcion' => function ($query) {
                $query->where('estado_det_recepcion_pedido', 1);
            },
            'det_doc_adquisicion.documento_adquisicion.tipo_documento_adquisicion',
            'det_doc_adquisicion.documento_adquisicion.proceso_compra',
            'det_doc_adquisicion.documento_adquisicion.proveedor',
            'det_doc_adquisicion.fuente_financiamiento',
            'detalle_recepcion.producto.unidad_medida',
            'detalle_recepcion.producto.catalogo_cta_presupuestal',
            'detalle_recepcion.centro_atencion',
            'detalle_recepcion.linea_trabajo',
            'detalle_recepcion.marca',
            'detalle_recepcion.producto_adquisicion',
            'administrador_contrato.persona',
            'guarda_almacen.persona'
        ])->find($id);

        if ($recToPrint->id_estado_recepcion_pedido == 2) {
            $numeroLetras = new NumeroALetras();
            $monto_letras = $numeroLetras->toInvoice($recToPrint['monto_recepcion_pedido'], 2, 'DÓLARES');

            $recToPrint->monto_letras = $monto_letras;

            // Agrupar los detalles de recepción por línea de trabajo
            $detallesAgrupados = $recToPrint->detalle_recepcion->groupBy('linea_trabajo');

            // Convertir la colección agrupada a un formato más legible
            $detallesFormateados = [];
            //Group by id_ccta_presupuestal
            $detallesFormateados2 = $recToPrint->detalle_recepcion->groupBy('producto.id_ccta_presupuestal');;
            foreach ($detallesAgrupados as $lineaTrabajo => $detalles) {
                $total = 0;
                foreach ($detalles as $detalle) {
                    $total += $detalle->cant_det_recepcion_pedido * $detalle->costo_det_recepcion_pedido;
                }
                // Agregar los campos comunes
                $detallesFormateados[] = [
                    'codigo_up_lt' => $detalles->first()->linea_trabajo->codigo_up_lt,
                    'nombre_up_lt' => $detalles->first()->linea_trabajo->nombre_lt,
                    'id_lt' => $detalles->first()->id_lt,
                    'total' => number_format($total, 2), // Formatear total
                    'productos' => $detalles->toArray(),
                ];
            }

            //Codigo para agrupar por linea y especifico
            $detallesFormateadosByEsp = [];
            //Group by id_ccta_presupuestal
            $detallesAgrupadosByEsp = $recToPrint->detalle_recepcion->groupBy(['id_lt', 'producto.id_ccta_presupuestal']);
            // Convertir la colección agrupada a un formato más legible
            foreach ($detallesAgrupadosByEsp as $lineaTrabajo => $detalles) {
                foreach ($detalles as $especifico => $det) {
                    // Obtener el primer detalle para obtener los campos comunes como 'codigo_up_lt' e 'id_lt'
                    $primerDetalle = $det->first();

                    // Calcular la suma de las cantidades multiplicadas por el costo de los detalles
                    $total = $det->sum(function ($detalle) {
                        return $detalle->cant_det_recepcion_pedido * $detalle->costo_det_recepcion_pedido;
                    });

                    // Agregar los campos comunes
                    $detallesFormateadosByEsp[] = [
                        'codigo_up_lt' => $primerDetalle->linea_trabajo->codigo_up_lt,
                        'nombre_up_lt' => $primerDetalle->linea_trabajo->nombre_lt,
                        'id_ccta_presupuestal' => $primerDetalle->producto->id_ccta_presupuestal,
                        'nombre_ccta_presupuestal' => $primerDetalle->producto->catalogo_cta_presupuestal->nombre_ccta_presupuestal,
                        'id_lt' => $primerDetalle->id_lt,
                        'total' => number_format($total, 2), //Formatear total
                        //'productos' => $det->toArray(),
                    ];
                }
            }

            // Asignar los detalles formateados a $recToPrint
            $recToPrint->detalles_agrupados = $detallesFormateados;
            $recToPrint->groupByEsp = collect($detallesFormateadosByEsp)->groupBy('codigo_up_lt')->toArray();

            return response()->json([
                'recToPrint'        => $recToPrint,
            ]);
        } else {
            return response()->json(['logical_error' => 'Error, la recepcion ha cambiado de estado.'], 422);
        }
    }


    function getPendingItems($byMonto, $reception)
    {
        $query = DB::table('producto_adquisicion AS dc')
            ->select('dc.id_prod_adquisicion', 'dc.id_producto', 'dc.cant_prod_adquisicion', 'dc.costo_prod_adquisicion')
            ->leftJoinSub(
                DB::table('detalle_recepcion_pedido AS drp')
                    ->selectRaw('drp.id_prod_adquisicion, SUM(drp.cant_det_recepcion_pedido' . ($byMonto ? ' * drp.costo_det_recepcion_pedido' : '') . ') AS recibido')
                    ->join('recepcion_pedido AS rp', 'drp.id_recepcion_pedido', '=', 'rp.id_recepcion_pedido')
                    ->where('rp.id_estado_recepcion_pedido', 2)
                    ->where('drp.estado_det_recepcion_pedido', 1)
                    ->groupBy('drp.id_prod_adquisicion'),
                'dr',
                'dc.id_prod_adquisicion',
                '=',
                'dr.id_prod_adquisicion'
            )
            ->where('dc.id_det_doc_adquisicion', $reception->id_det_doc_adquisicion)
            ->where('dc.estado_prod_adquisicion', 1)
            ->groupBy('dc.id_prod_adquisicion', 'dc.id_producto', 'dc.cant_prod_adquisicion', 'dc.costo_prod_adquisicion');

        if ($byMonto) {
            $query->addSelect(DB::raw('COALESCE(SUM(dr.recibido), 0) AS monto_recibido'))
                ->addSelect(DB::raw('ROUND(((dc.cant_prod_adquisicion * dc.costo_prod_adquisicion) - COALESCE(SUM(dr.recibido), 0)), 2) AS monto_pendiente'))
                ->havingRaw('monto_pendiente > 0');
        } else {
            $query->addSelect(DB::raw('COALESCE(SUM(dr.recibido), 0) AS cantidad_recibida'))
                ->addSelect(DB::raw('(dc.cant_prod_adquisicion - COALESCE(SUM(dr.recibido), 0)) AS cantidad_pendiente'))
                ->havingRaw('cantidad_pendiente > 0');
        }

        return $query->get();
    }
}
