<?php

namespace App\Http\Controllers\Tesoreria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DocumentoAdquisicion;

class DocumentoAdquisicionController extends Controller
{
    public function getDocAdquisicion(Request $request)
    {
        $columns = ['nombre_tipo_doc_adquisicion', 'numero_doc_adquisicion', 'razon_social_proveedor', 'monto_doc_adquisicion', 'compromiso_ppto_det_doc_adquisicion', 'estado_doc_adquisicion'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = DocumentoAdquisicion::select('*')
            ->with('detalles')
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
        if ($column != 4) {
            $query->orderBy($columns[$column], $dir);
        } else {
            $query->orderByRaw('(SELECT MAX(compromiso_ppto_det_doc_adquisicion) FROM detalle_documento_adquisicion WHERE detalle_documento_adquisicion.id_doc_adquisicion = documento_adquisicion.id_doc_adquisicion) ' . $dir);
        }

        if ($search_value) {
            $query->where('nombre_tipo_doc_adquisicion', 'like', '%' . $search_value['nombre_tipo_doc_adquisicion'] . '%')
                ->where('numero_doc_adquisicion', 'like', '%' . $search_value['numero_doc_adquisicion'] . '%')
                ->where('razon_social_proveedor', 'like', '%' . $search_value['razon_social_proveedor'] . '%')
                ->where('monto_doc_adquisicion', 'like', '%' . $search_value['monto_doc_adquisicion'] . '%')
                ->whereHas('detalles', function ($query) use ($search_value) {
                    $query->where('compromiso_ppto_det_doc_adquisicion', 'like', '%' . $search_value["compromiso_ppto_det_doc_adquisicion"] . '%')
                        ->orWhere('monto_det_doc_adquisicion', 'like', '%' . $search_value["compromiso_ppto_det_doc_adquisicion"] . '%');
                })
                ->where('estado_doc_adquisicion', 'like', '%' . $search_value['estado_doc_adquisicion'] . '%');
        }
        $acquisition_docs = $query->paginate($length)->onEachSide(1);
        return ['data' => $acquisition_docs, 'draw' => $request->input('draw')];
    }
    public function changeStateAcqdoc(Request $request)
    {
        // $servicio = ConceptoIngreso::find($request->id_service);
        // if ($servicio->estado_concepto_ingreso == 1) {
        //     if ($request->state_service == 1) {
        //         $servicio->update([
        //             'estado_concepto_ingreso' => 0,
        //             'fecha_mod_concepto_ingreso' => Carbon::now(),
        //             'usuario_concepto_ingreso' => $request->user()->nick_usuario,
        //             'ip_concepto_ingreso' => $request->ip(),
        //         ]);
        //         return ['mensaje' => 'Concepto de ingreso ' . $servicio->nombre_concepto_ingreso . ' ha sido desactivado con exito'];
        //     } else {
        //         return ['mensaje' => 'El concepto de ingreso seleccionado ya ha sido activado por otro usuario'];
        //     }
        // } else {
        //     if ($servicio->estado_concepto_ingreso == 0) {
        //         if ($request->state_service == 0) {
        //             $servicio->update([
        //                 'estado_concepto_ingreso' => 1,
        //                 'fecha_mod_concepto_ingreso' => Carbon::now(),
        //                 'usuario_concepto_ingreso' => $request->user()->nick_usuario,
        //                 'ip_concepto_ingreso' => $request->ip(),
        //             ]);
        //             return ['mensaje' => 'Concepto de ingreso ' . $servicio->nombre_concepto_ingreso . ' ha sido activado con exito'];
        //         } else {
        //             return ['mensaje' => 'El concepto de ingreso seleccionado ya ha sido desactivado por otro usuario'];
        //         }
        //     }
        // }
    }
    public function getSelectsAcqDoc(Request $request)
    {
        // $budget_accounts = CuentaPresupuestal::selectRaw("id_ccta_presupuestal as value , concat(id_ccta_presupuestal, ' - ', nombre_ccta_presupuestal) as label")
        //     ->where('tesoreria_ccta_presupuestal', '=', 1)
        //     ->where('estado_ccta_presupuestal', '=', 1)
        //     ->orderBy('nombre_ccta_presupuestal')
        //     ->get();
        // $dependencies = Dependencia::selectRaw("id_dependencia as value , concat(codigo_dependencia, ' - ', nombre_dependencia) as label")
        //     ->where('id_tipo_dependencia', '=', 1)
        //     ->orderBy('nombre_dependencia')
        //     ->get();
        // $financing_sources = ProyectoFinanciado::select('id_proy_financiado as value', 'nombre_proy_financiado as label')
        //     ->where('estado_proy_financiado', '=', 1)
        //     ->orderBy('nombre_proy_financiado')
        //     ->get();

        // return ['budget_accounts' => $budget_accounts, 'dependencies' => $dependencies, 'financing_sources' => $financing_sources];
    }

}
