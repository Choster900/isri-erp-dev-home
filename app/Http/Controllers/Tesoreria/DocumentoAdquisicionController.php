<?php

namespace App\Http\Controllers\Tesoreria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tesoreria\DocumentoAdquisicionRequest;
use App\Models\AdministradorAdquisicion;
use App\Models\DetDocumentoAdquisicion;
use App\Models\DocumentoAdquisicion;
use App\Models\Empleado;
use App\Models\ProcesoCompra;
use App\Models\Proveedor;
use App\Models\ProyectoFinanciado;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DocumentoAdquisicionController extends Controller
{
    public function getDocAdquisicion(Request $request)
    {
        $columns = ['id_doc_adquisicion', 'nombre_tipo_doc_adquisicion', 'numero_doc_adquisicion', 'razon_social_proveedor', 'monto_doc_adquisicion', 'compromiso_ppto_det_doc_adquisicion', 'estado_doc_adquisicion'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = DocumentoAdquisicion::select('*')
            ->with('detalles.fuente_financiamiento', 'detalles.quedan')
            ->join('tipo_gestion_compra', function ($join) {
                $join->on('documento_adquisicion.id_tipo_gestion_compra', '=', 'tipo_gestion_compra.id_tipo_gestion_compra');
            })
            ->join('tipo_documento_adquisicion', function ($join) {
                $join->on('documento_adquisicion.id_tipo_doc_adquisicion', '=', 'tipo_documento_adquisicion.id_tipo_doc_adquisicion');
            })
            ->join('proveedor', function ($join) {
                $join->on('documento_adquisicion.id_proveedor', '=', 'proveedor.id_proveedor');
            });
        //Sorting query
        if ($column != 5) {
            $query->orderBy($columns[$column], $dir);
        } else {
            $query->orderByRaw('(SELECT MAX(compromiso_ppto_det_doc_adquisicion) FROM detalle_documento_adquisicion WHERE detalle_documento_adquisicion.id_doc_adquisicion = documento_adquisicion.id_doc_adquisicion) ' . $dir);
        }

        if ($search_value) {
            $query->where('id_doc_adquisicion', 'like', '%' . $search_value['id_doc_adquisicion'] . '%')
                ->where('nombre_tipo_doc_adquisicion', 'like', '%' . $search_value['nombre_tipo_doc_adquisicion'] . '%')
                ->where('numero_doc_adquisicion', 'like', '%' . $search_value['numero_doc_adquisicion'] . '%')
                ->where('razon_social_proveedor', 'like', '%' . $search_value['razon_social_proveedor'] . '%')
                ->where('monto_doc_adquisicion', 'like', '%' . $search_value['monto_doc_adquisicion'] . '%')
                ->whereHas('detalles.fuente_financiamiento', function ($query) use ($search_value) {
                    $query->where('compromiso_ppto_det_doc_adquisicion', 'like', '%' . $search_value["compromiso_ppto_det_doc_adquisicion"] . '%')
                        ->orWhere('monto_det_doc_adquisicion', 'like', '%' . $search_value["compromiso_ppto_det_doc_adquisicion"] . '%')
                        ->orWhere('codigo_proy_financiado', 'like', '%' . $search_value["compromiso_ppto_det_doc_adquisicion"] . '%');
                })
                ->where('estado_doc_adquisicion', 'like', '%' . $search_value['estado_doc_adquisicion'] . '%');
        }
        $acquisition_docs = $query->paginate($length)->onEachSide(1);
        return ['data' => $acquisition_docs, 'draw' => $request->input('draw')];
    }
    public function changeStateAcqdoc(Request $request)
    {
        $acq_doc = DocumentoAdquisicion::find($request->id);
        $has_assigned = $acq_doc->detalles()->whereHas('quedan')->exists();
        if ($has_assigned) {
            return response()->json(['logical_error' => 'Error, el documento seleccionado no puede ser desactivado porque ya tiene quedan asignados.'], 422);
        } else {
            if ($acq_doc->estado_doc_adquisicion == 1) {
                if ($request->status == 1) {
                    $acq_doc->update([
                        'estado_doc_adquisicion'    => 0,
                        'fecha_mod_doc_adquisicion' => Carbon::now(),
                        'usuario_doc_adquisicion'   => $request->user()->nick_usuario,
                        'ip_doc_adquisicion'        => $request->ip(),
                    ]);
                    return ['message' => 'Documento de aquisicion numero ' . $acq_doc->numero_doc_adquisicion . ' ha sido desactivado con exito'];
                } else {
                    return ['message' => 'El documento de adquisicion seleccionado ya ha sido activado por otro usuario'];
                }
            } else {
                if ($acq_doc->estado_doc_adquisicion == 0) {
                    if ($request->status == 0) {
                        $acq_doc->update([
                            'estado_doc_adquisicion'    => 1,
                            'fecha_mod_doc_adquisicion' => Carbon::now(),
                            'usuario_doc_adquisicion'   => $request->user()->nick_usuario,
                            'ip_doc_adquisicion'        => $request->ip(),
                        ]);
                        return ['message' => 'Documento de adquisicion numero ' . $acq_doc->numero_doc_adquisicion . ' ha sido activado con exito'];
                    } else {
                        return ['message' => 'El documento de adquisicion seleccionado ya ha sido desactivado por otro usuario'];
                    }
                }
            }
        }
    }
    //New method for composition API
    public function getInfoModalDocAdquisicion(Request $request, $id)
    {
        $acqDoc = DocumentoAdquisicion::with([
            'detalles.quedan',
            'proveedor',
            'tipo_gestion_compra',
            'tipo_documento_adquisicion',
            'detalles.fuente_financiamiento',
            'administradores'
        ])->find($id);

        $doc_types = DB::table('tipo_documento_adquisicion')
            ->select('id_tipo_doc_adquisicion as value', 'nombre_tipo_doc_adquisicion as label')
            ->where('estado_tipo_doc_adquisicion', 1)
            ->orderBy('nombre_tipo_doc_adquisicion')
            ->get();
        $management_types = DB::table('tipo_gestion_compra')
            ->select('id_tipo_gestion_compra as value', 'nombre_tipo_gestion_compra as label')
            ->where('estado_tipo_gestion_compra', '=', 1)
            ->orderBy('nombre_tipo_gestion_compra')
            ->get();
        $financing_sources = ProyectoFinanciado::select('id_proy_financiado as value', 'nombre_proy_financiado as label', 'codigo_proy_financiado')
            ->where('estado_proy_financiado', '=', 1)
            ->whereIn('id_proy_financiado', [1, 3]) //FG y RP
            ->orderBy('nombre_proy_financiado')
            ->get();
        $suppliers = Proveedor::select('id_proveedor as value', 'razon_social_proveedor as label')
            ->where('estado_proveedor', '=', 1)
            ->orderBy('razon_social_proveedor')
            ->get();
        $procesoCompra = ProcesoCompra::select('id_proceso_compra as value', 'nombre_proceso_compra as label', 'id_tipo_proceso_compra as idTipoProcesoCompra')

            ->orderBy('nombre_proceso_compra')
            ->get();
        $allEmployees = Empleado::with('persona')
            ->where('id_estado_empleado', 1)->get();
        $employees = $allEmployees->map(function ($e) {
            return [
                'value' => $e->id_empleado,
                'label' => $e->persona->nombre_apellido
            ];
        });

        return response()->json([
            'doc_types'         => $doc_types,
            'management_types'  => $management_types,
            'financing_sources' => $financing_sources,
            'procesoCompra'     => $procesoCompra,
            'suppliers'         => $suppliers,
            'acqDoc'            => $acqDoc ?? [],
            'employees'         => $employees
        ]);
    }
    public function saveAcqDoc(DocumentoAdquisicionRequest $request)
    {
        DB::beginTransaction();
        try {
            $new_acq_doc = new DocumentoAdquisicion([
                'id_tipo_gestion_compra'              => $request->management_type_id,
                'id_tipo_doc_adquisicion'             => $request->type_id,
                'id_proveedor'                        => $request->supplier_id,
                'id_proceso_compra'                   => $request->procesoCompraId,
                'monto_doc_adquisicion'               => $request->total,
                'numero_doc_adquisicion'              => $request->number,
                'numero_gestion_doc_adquisicion'      => $request->management_number,
                'numero_adjudicacion_doc_adquisicion' => $request->award_number,
                'fecha_adjudicacion_doc_adquisicion'  => $request->award_date,
                'estado_doc_adquisicion'              => 1,
                'fecha_reg_doc_adquisicion'           => Carbon::now(),
                'usuario_doc_adquisicion'             => $request->user()->nick_usuario,
                'ip_doc_adquisicion'                  => $request->ip(),
            ]);
            $new_acq_doc->save();

            foreach ( $request->employees as $emp ) {
                $newAdm = new AdministradorAdquisicion([
                    'id_doc_adquisicion'          => $new_acq_doc->id_doc_adquisicion,
                    'id_empleado'                 => $emp,
                    'estado_admon_adquisicion'    => 1,
                    'fecha_reg_admon_adquisicion' => Carbon::now(),
                    'usuario_admon_adquisicion'   => $request->user()->nick_usuario,
                    'ip_admon_adquisicion'        => $request->ip(),
                ]);
                $newAdm->save();
            }

            foreach ( $request->items as $detail ) {
                $new_item = new DetDocumentoAdquisicion([
                    'id_doc_adquisicion'                        => $new_acq_doc->id_doc_adquisicion,
                    'id_estado_doc_adquisicion'                 => 1,
                    'id_proy_financiado'                        => $detail['financing_source_id'],
                    'nombre_det_doc_adquisicion'                => $detail['name'],
                    'monto_det_doc_adquisicion'                 => isset($detail['amount']) ? $detail['amount'] : 0,
                    'compromiso_ppto_det_doc_adquisicion'       => $detail['commitment_number'],
                    'admon_det_doc_adquisicion'                 => $detail['contract_manager'],
                    'fecha_adjudicacion_det_doc_adquisicion'    => $request->award_date, //Award date for every contract item
                    'tipo_costo_det_doc_adquisicion'            => 0,
                    'estado_det_doc_adquisicion'                => 1,
                    'fecha_reg_det_doc_adquisicion'             => Carbon::now(),
                    'usuario_det_doc_adquisicion'               => $request->user()->nick_usuario,
                    'ip_det_doc_adquisicion'                    => $request->ip(),
                    'visible_ucp_det_doc_adquisicion'           => $request->comesFrom == 'ucp' ? 1 : 0 // Si se agrega desde tesoreria lo ponemos como 0 y si se agrega desde ucp 1
                ]);
                $new_item->save();
            }

            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'message' => 'Guardado documento numero ' . $new_acq_doc->numero_doc_adquisicion . ' con éxito',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack(); // En caso de error, revierte las operaciones anteriores
            return response()->json([
                'logical_error' => 'Ha ocurrido un error con sus datos.',
                'error'         => $th->getMessage(),
            ], 422);
        }
    }
    public function updateAcqDoc(DocumentoAdquisicionRequest $request)
    {
        $acq_doc = DocumentoAdquisicion::find($request->id);
        if ($acq_doc->estado_doc_adquisicion == 0) {
            return response()->json(['logical_error' => 'Error, el documento seleccionado ha sido desactivado por otro usuario.'], 422);
        } else {
            DB::beginTransaction();
            try {
                $acq_doc->update([
                    'id_tipo_gestion_compra'              => $request->management_type_id,
                    'id_proceso_compra'                   => $request->procesoCompraId,
                    'id_tipo_doc_adquisicion'             => $request->type_id,
                    'id_proveedor'                        => $request->supplier_id,
                    'monto_doc_adquisicion'               => $request->total,
                    'numero_doc_adquisicion'              => $request->number,
                    'numero_gestion_doc_adquisicion'      => $request->management_number,
                    'numero_adjudicacion_doc_adquisicion' => $request->award_number,
                    'fecha_adjudicacion_doc_adquisicion'  => $request->award_date,
                    'fecha_mod_doc_adquisicion'           => Carbon::now(),
                    'usuario_doc_adquisicion'             => $request->user()->nick_usuario,
                    'ip_doc_adquisicion'                  => $request->ip(),
                ]);

                //CRUD doc managers
                $newEmpIds = $request->employees;
                $currentEmpIds = AdministradorAdquisicion::where('id_doc_adquisicion', $acq_doc->id_doc_adquisicion)
                    ->where('estado_admon_adquisicion', 1)
                    ->pluck('id_empleado')
                    ->toArray();
                $deletedEmpIds = array_diff($currentEmpIds, $newEmpIds);
                //Deleting doc managers
                AdministradorAdquisicion::where('id_doc_adquisicion', $acq_doc->id_doc_adquisicion)
                    ->whereIn('id_empleado', $deletedEmpIds)
                    ->update([
                        'estado_admon_adquisicion'    => 0,
                        'fecha_mod_admon_adquisicion' => Carbon::now(),
                        'usuario_admon_adquisicion'   => $request->user()->nick_usuario,
                        'ip_admon_adquisicion'        => $request->ip(),
                    ]);
                //Activate an inactive doc manager or create a new one
                foreach ( $newEmpIds as $empId ) {
                    if (!in_array($empId, $currentEmpIds)) {
                        $existAdm = AdministradorAdquisicion::where('id_doc_adquisicion', $acq_doc->id_doc_adquisicion)
                            ->where('id_empleado', $empId)->first();
                        if ($existAdm) {
                            $existAdm->update([
                                'estado_admon_adquisicion'    => 1,
                                'fecha_mod_admon_adquisicion' => Carbon::now(),
                                'usuario_admon_adquisicion'   => $request->user()->nick_usuario,
                                'ip_admon_adquisicion'        => $request->ip(),
                            ]);
                        } else {
                            $newAdm = new AdministradorAdquisicion([
                                'id_empleado'                 => $empId,
                                'id_doc_adquisicion'          => $acq_doc->id_doc_adquisicion,
                                'estado_admon_adquisicion'    => 1,
                                'fecha_reg_admon_adquisicion' => Carbon::now(),
                                'usuario_admon_adquisicion'   => $request->user()->nick_usuario,
                                'ip_admon_adquisicion'        => $request->ip(),
                            ]);
                            $newAdm->save();
                        }
                    }
                }

                foreach ( $request->items as $detail ) {
                    if ($detail['id'] != "" && $detail['deleted'] == false) {
                        //Update item
                        $item = DetDocumentoAdquisicion::find($detail['id']);
                        $item->update([
                            'id_proy_financiado'                        => $detail['financing_source_id'],
                            'nombre_det_doc_adquisicion'                => $detail['name'],
                            'monto_det_doc_adquisicion'                 => is_numeric($detail['amount']) ? $detail['amount'] : 0,
                            'compromiso_ppto_det_doc_adquisicion'       => $detail['commitment_number'],
                            'admon_det_doc_adquisicion'                 => $detail['contract_manager'],
                            'fecha_adjudicacion_det_doc_adquisicion'    => $request->award_date, //Award date for every contract item
                            'fecha_mod_det_doc_adquisicion'             => Carbon::now(),
                            'usuario_det_doc_adquisicion'               => $request->user()->nick_usuario,
                            'ip_det_doc_adquisicion'                    => $request->ip()
                        ]);
                    }

                    if ($detail['id'] != "" && $detail['deleted'] == true) {
                        $item = DetDocumentoAdquisicion::find($detail['id']);
                        $item->update([
                            'estado_det_doc_adquisicion'    => 0,
                            'fecha_mod_det_doc_adquisicion' => Carbon::now(),
                            'usuario_det_doc_adquisicion'   => $request->user()->nick_usuario,
                            'ip_det_doc_adquisicion'        => $request->ip()
                        ]);
                    }

                    if ($detail['id'] == "" && $detail['deleted'] == false) {
                        $exist_item = DetDocumentoAdquisicion::where('id_doc_adquisicion', $request->id)
                            ->where('compromiso_ppto_det_doc_adquisicion', $detail['commitment_number'])
                            ->first();
                        if ($exist_item) {
                            $exist_item->update([
                                'id_proy_financiado'                        => $detail['financing_source_id'],
                                'nombre_det_doc_adquisicion'                => $detail['name'],
                                'monto_det_doc_adquisicion'                 => is_numeric($detail['amount']) ? $detail['amount'] : 0,
                                'compromiso_ppto_det_doc_adquisicion'       => $detail['commitment_number'],
                                'admon_det_doc_adquisicion'                 => $detail['contract_manager'],
                                'fecha_adjudicacion_det_doc_adquisicion'    => $request->award_date, //Award date for every contract item
                                'estado_det_doc_adquisicion'                => 1,
                                'fecha_mod_det_doc_adquisicion'             => Carbon::now(),
                                'usuario_det_doc_adquisicion'               => $request->user()->nick_usuario,
                                'ip_det_doc_adquisicion'                    => $request->ip()
                            ]);
                        } else {
                            $new_item = new DetDocumentoAdquisicion([
                                'id_doc_adquisicion'                        => $request->id,
                                'id_estado_doc_adquisicion'                 => 1,
                                'id_proy_financiado'                        => $detail['financing_source_id'],
                                'nombre_det_doc_adquisicion'                => $detail['name'],
                                'monto_det_doc_adquisicion'                 => is_numeric($detail['amount']) ? $detail['amount'] : 0,
                                'compromiso_ppto_det_doc_adquisicion'       => $detail['commitment_number'],
                                'fecha_adjudicacion_det_doc_adquisicion'    => $request->award_date, //Award date for every contract item
                                'admon_det_doc_adquisicion'                 => $detail['contract_manager'],
                                'estado_det_doc_adquisicion'                => 1,
                                'fecha_reg_det_doc_adquisicion'             => Carbon::now(),
                                'usuario_det_doc_adquisicion'               => $request->user()->nick_usuario,
                                'ip_det_doc_adquisicion'                    => $request->ip(),
                                'visible_ucp_det_doc_adquisicion'           => $request->comesFrom == 'ucp' ? 1 : 0 // Si se agrega desde tesoreria lo ponemos como 0 y si se agrega desde ucp 1
                            ]);
                            $new_item->save();
                        }
                    }
                }

                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'message' => 'Actualizado documento numero ' . $acq_doc->numero_doc_adquisicion . ' con éxito.',
                ]);
            } catch (\Throwable $th) {
                DB::rollBack(); // En caso de error, revierte las operaciones anteriores
                return response()->json([
                    'logical_error' => 'Ha ocurrido un error con sus datos.',
                    'error'         => $th->getMessage(),
                ], 422);
            }
        }
    }
}
