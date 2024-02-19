<?php

namespace App\Http\Controllers\UCP;

use App\Http\Controllers\Controller;
use App\Models\CentroAtencion;
use App\Models\DetDocumentoAdquisicion;
use App\Models\LineaTrabajo;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class BienesServiciosController extends Controller
{


    function saveProductoAdquisicion(Request $request): array
    {



        return [];
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
        $centrosAtencion = CentroAtencion::all();

        // Devolver un arreglo asociativo con los resultados
        return [
            "detalleDocAdquisicion" => $detalleDocumentoAdquisicion,
            "lineaTrabajo"          => $lineaTrabajo,
            "unidadesMedida"        => $unidadesMedida,
            "centrosAtencion"       => $centrosAtencion,
        ];
    }
}
