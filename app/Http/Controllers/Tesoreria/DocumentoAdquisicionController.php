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
}
