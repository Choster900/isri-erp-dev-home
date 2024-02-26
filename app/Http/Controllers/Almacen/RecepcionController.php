<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Almacen\RecepcionRequest;
use App\Models\CentroAtencion;
use App\Models\DetalleRecepcionPedido;
use App\Models\DetDocumentoAdquisicion;
use App\Models\DocumentoAdquisicion;
use App\Models\Producto;
use App\Models\ProductoAdquisicion;
use App\Models\RecepcionPedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class RecepcionController extends Controller
{
    public function getRecepciones(Request $request)
    {
        $columns = ['id_recepcion_pedido', 'documento', 'item', 'acta_recepcion_pedido', 'fecha_recepcion_pedido', 'id_estado_recepcion_pedido'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = RecepcionPedido::select('*')
            ->with([
                'detalle_recepcion',
                'det_doc_adquisicion.documento_adquisicion.tipo_documento_adquisicion',
                'estado_recepcion'
            ]);

        $data = $query->paginate($length)->onEachSide(1);
        return ['data' => $data, 'draw' => $request->input('draw')];
    }

    public function getInitialInfoDoc(Request $request)
    {
        //It returns all the documents that has pending products
        $pendingItems = DB::table(DB::raw('(
            SELECT
                dda.id_doc_adquisicion,
                dda.id_det_doc_adquisicion,
                da.numero_doc_adquisicion,
                dda.nombre_det_doc_adquisicion,
                (pa.cant_prod_adquisicion - IFNULL(ing.total_prod_recibido, 0)) AS diferencia
            FROM
                documento_adquisicion AS da
            INNER JOIN detalle_documento_adquisicion AS dda ON da.id_doc_adquisicion = dda.id_doc_adquisicion
            INNER JOIN producto_adquisicion AS pa ON dda.id_det_doc_adquisicion = pa.id_det_doc_adquisicion
            LEFT JOIN (
                SELECT
                    drp.id_prod_adquisicion,
                    SUM(drp.cant_det_recepcion_pedido) AS total_prod_recibido
                FROM
                    recepcion_pedido AS rp
                INNER JOIN detalle_recepcion_pedido AS drp ON rp.id_recepcion_pedido = drp.id_recepcion_pedido
                WHERE
                    drp.estado_det_recepcion_pedido = 1
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
                'pendiente.nombre_det_doc_adquisicion as label',
                'pendiente.id_det_doc_adquisicion as value'
            )
            ->where('pendiente.diferencia', '>', 0)
            ->groupBy('pendiente.id_doc_adquisicion');

        $idDocs = $pendingItems->pluck('pendiente.id_doc_adquisicion')->toArray();

        $docs = DB::table('documento_adquisicion')
            ->select('id_doc_adquisicion as value', 'numero_doc_adquisicion as label', 'id_tipo_doc_adquisicion')
            ->whereIn('id_doc_adquisicion', $idDocs)
            ->where('estado_doc_adquisicion', 1)
            ->get();

        return response()->json([
            'test'                          => $pendingItems->get(),
            'docs'                          => $docs
        ]);
    }

    public function getInfoModalRecep(Request $request)
    {
        $idRec = $request->id;
        if ($idRec > 0) {
            $recep = RecepcionPedido::with([
                'detalle_recepcion.producto.unidad_medida',
                'detalle_recepcion.producto_adquisicion',
                'det_doc_adquisicion.documento_adquisicion.tipo_documento_adquisicion',
                'estado_recepcion'
            ])->find($idRec);
            $item = DetDocumentoAdquisicion::with(['documento_adquisicion.proveedor', 'fuente_financiamiento'])->find($recep->det_doc_adquisicion->id_det_doc_adquisicion);
            if (!$recep || !$item) {
                return response()->json([
                    'logical_error' => 'Error, no fue posible obtener la recepción consultada. Consulte con el administrador.',
                ], 422);
            }
        } else {
            $recep = [];
            $item = DetDocumentoAdquisicion::with(['documento_adquisicion.proveedor', 'fuente_financiamiento'])->find($request->detId);
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

                return response()->json([
                    'recep'                         => $recep,
                    'products'                      => $procedure,
                    'itemInfo'                      => $item,
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
        $hasBackendError = false;
        $procedure = DB::select(
            'CALL PR_GET_PRODUCT_ACQUISITION(?)',
            array($request->detDocId)
        );

        // Convertir el array en un objeto
        $object = (object) $procedure;
        // Convertir el objeto en una colección
        $collection = new Collection($object);

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
                'id_estado_recepcion_pedido'            => 1,
                'factura_recepcion_pedido'              => $request->invoice,
                'fecha_recepcion_pedido'                => Carbon::now(),
                'acta_recepcion_pedido'                 => $codeActa,
                //'incumple_acuerdo_recepcion_pedido'     => $request->direction,
                //'incumplimiento_recepcion_pedido'       => $request->number,
                'observacion_recepcion_pedido'          => $request->observation,
                'fecha_reg_recepcion_pedido'            => Carbon::now(),
                'usuario_recepcion_pedido'              => $request->user()->nick_usuario,
                'ip_recepcion_pedido'                   => $request->ip(),
            ]);
            $rec->save();

            foreach ($request->prods as $prod) {
                $compare = $collection->firstWhere('value', $prod['prodId']);
                if ($compare->total_menos_acumulado == $prod['initial']) { //$prod['initial] is 'total_menos_acumulado' from the view
                    $prodAdq = ProductoAdquisicion::find($prod['prodId']);
                    $newDet = new DetalleRecepcionPedido([
                        'id_centro_atencion'                        => $rec->id_recepcion_pedido,
                        'id_producto'                               => $prodAdq->id_producto,
                        'id_recepcion_pedido'                       => $rec->id_recepcion_pedido,
                        'id_marca'                                  => $prodAdq->id_marca,
                        'id_lt'                                     => $prodAdq->id_lt,
                        'id_prod_adquisicion'                       => $prod['prodId'],
                        'cant_det_recepcion_pedido'                 => $prod['qty'],
                        'costo_det_recepcion_pedido'                => $prodAdq->costo_prod_adquisicion,
                        'fecha_vencimiento_det_recepcion_pedido'    => $prod['expiryDate'] ? date('Y/m/d', strtotime($prod['expiryDate'])) : null,
                        'estado_det_recepcion_pedido'               => 1,
                        'fecha_reg_det_recepcion_pedido'            => Carbon::now(),
                        'usuario_det_recepcion_pedido'              => $request->user()->nick_usuario,
                        'ip_det_recepcion_pedido'                   => $request->ip()
                    ]);
                    $newDet->save();
                } else {
                    $hasBackendError = true;
                }
            }

            if ($hasBackendError) {
                DB::rollBack(); // En caso de error, revierte las operaciones anteriores
                return response()->json([
                    'logical_error' => 'Error, la disponibilidad de recepcion de bienes ha cambiado, intente nuevamente.',
                    'refresh'       => true,
                    'prods'         => $procedure
                ], 422);
            } else {
                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'message'          => 'Guardado nueva recepcion con éxito.',
                ]);
            }
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
        $hasBackendError = false;
        $procedure = DB::select(
            'CALL PR_GET_PRODUCT_ACQUISITION_MINUS_CURRENT_RECEIPT(?, ?)',
            array($request->detDocId, $request->id)
        );

        // Convertir el array en un objeto
        $object = (object) $procedure;
        // Convertir el objeto en una colección
        $collection = new Collection($object);

        $rec = RecepcionPedido::find($request->id);
        if (!$rec || $rec->id_estado_recepcion_pedido == 3) {
            return response()->json(['logical_error' => 'Error, la recepcion que intentas modificar no existe o ha sido eliminadas.'], 422);
        } else {
            DB::beginTransaction();
            try {
                $rec->update([
                    'factura_recepcion_pedido'              => $request->invoice,
                    //'incumple_acuerdo_recepcion_pedido'     => $request->direction,
                    //'incumplimiento_recepcion_pedido'       => $request->number,
                    'observacion_recepcion_pedido'          => $request->observation,
                    'fecha_mod_recepcion_pedido'            => Carbon::now(),
                    'usuario_recepcion_pedido'              => $request->user()->nick_usuario,
                    'ip_recepcion_pedido'                   => $request->ip(),
                ]);

                foreach ($request->prods as $prod) {
                    $compare = $collection->firstWhere('value', $prod['prodId']);
                    if ($compare->total_menos_acumulado == $prod['initial']) { //$prod['initial] is 'total_menos_acumulado' from the view
                        if ($prod['detRecId'] != "" && $prod['deleted'] == false) {
                            $prodAdq = ProductoAdquisicion::find($prod['prodId']);
                            $det = DetalleRecepcionPedido::find($prod['detRecId']);
                            $det->update([
                                'id_centro_atencion'                        => $prodAdq->id_centro_atencion,
                                'id_producto'                               => $prodAdq->id_producto,
                                'id_marca'                                  => $prodAdq->id_marca,
                                'id_lt'                                     => $prodAdq->id_lt,
                                'id_recepcion_pedido'                       => $request->id,
                                'id_prod_adquisicion'                       => $prod['prodId'],
                                'cant_det_recepcion_pedido'                 => $prod['qty'],
                                'costo_det_recepcion_pedido'                => $prodAdq->costo_prod_adquisicion,
                                'fecha_vencimiento_det_recepcion_pedido'    => $prod['expiryDate'] ? date('Y/m/d', strtotime($prod['expiryDate'])) : null,
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
                            $existDetail = DetalleRecepcionPedido::where('id_recepcion_pedido', $request->id)
                                ->where('id_det_recepcion_pedido', $prod['detRecId'])
                                ->first();
                            if ($existDetail) {
                                $existDetail->update([
                                    'cant_det_recepcion_pedido'                 => $prod['qty'],
                                    'fecha_vencimiento_det_recepcion_pedido'    => $prod['expiryDate'],
                                    'fecha_mod_det_recepcion_pedido'            => Carbon::now(),
                                    'usuario_det_recepcion_pedido'              => $request->user()->nick_usuario,
                                    'ip_det_recepcion_pedido'                   => $request->ip()
                                ]);
                            } else {
                                $newDet = new DetalleRecepcionPedido([
                                    'id_centro_atencion'                        => $prodAdq->id_centro_atencion,
                                    'id_producto'                               => $prodAdq->id_producto,
                                    'id_marca'                                  => $prodAdq->id_marca,
                                    'id_lt'                                     => $prodAdq->id_lt,
                                    'id_prod_adquisicion'                       => $prod['prodId'],
                                    'cant_det_recepcion_pedido'                 => $prod['qty'],
                                    'estado_det_recepcion_pedido'               => 1,
                                    'costo_det_recepcion_pedido'                => $prodAdq->costo_prod_adquisicion,
                                    'fecha_vencimiento_det_recepcion_pedido'    => $prod['expiryDate'] ? date('Y/m/d', strtotime($prod['expiryDate'])) : null,
                                    'fecha_reg_det_recepcion_pedido'            => Carbon::now(),
                                    'usuario_det_recepcion_pedido'              => $request->user()->nick_usuario,
                                    'ip_det_recepcion_pedido'                   => $request->ip()
                                ]);
                                $newDet->save();
                            }
                        }
                    } else {
                        $hasBackendError = true;
                    }
                }

                if ($hasBackendError) {
                    DB::rollBack(); // En caso de error, revierte las operaciones anteriores
                    return response()->json([
                        'logical_error' => 'Error, la disponibilidad de recepcion de bienes ha cambiado, intente nuevamente.',
                        'refresh'       => true,
                        'prods'         => $procedure
                    ], 422);
                } else {
                    DB::commit(); // Confirma las operaciones en la base de datos
                    return response()->json([
                        'message'          => 'Actualizada recepcion pedido con exito.',
                    ]);
                }
            } catch (\Throwable $th) {
                DB::rollBack(); // En caso de error, revierte las operaciones anteriores
                return response()->json([
                    'logical_error' => 'Ha ocurrido un error con sus datos.',
                    'error' => $th->getMessage(),
                ], 422);
            }
        }
    }
}
