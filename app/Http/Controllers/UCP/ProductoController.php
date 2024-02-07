<?php

namespace App\Http\Controllers\UCP;

use App\Http\Controllers\Controller;
use App\Models\CatalogoUnspsc;
use App\Models\CuentaPresupuestal;
use App\Models\ProcesoCompra;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function getProductos(Request $request)
    {
        $columns = ['dui_empleado', 'nombre_empleado', 'fecha_firma_finiquito_laboral', 'hora_firma_finiquito_laboral', 'monto_finiquito_laboral', 'firmado_finiquito_laboral'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = Producto::select('*')
            ->with([
                'unidad_medida',
            ]);

        $data = $query->paginate($length)->onEachSide(1);
        return ['data' => $data, 'draw' => $request->input('draw')];
    }

    public function getInfoModalProd(Request $request, $id)
    {
        $prod = Producto::with(['unidad_medida', 'catalogo_unspsc'])->find($id);

        $purchaseProcedures = ProcesoCompra::select('id_proceso_compra as value', 'nombre_proceso_compra as label')->get();
        $budgetAccounts = CuentaPresupuestal::select('id_ccta_presupuestal as value', 'nombre_ccta_presupuestal as label')
        ->where('compra_ccta_presupuestal',1)
        ->get();

        if ($prod) {
            return response()->json([
                'prod'                      => $prod ?? [],
                'purchaseProcedures'        => $purchaseProcedures,
                'budgetAccounts'            => $budgetAccounts
            ]);
        } else {
            return response()->json([
                'logical_error' => 'No existe el producto que estas intentando modificar.',
            ], 422);
        }
    }

    public function searchUnspsc(Request $request)
    {
        $search = $request->busqueda;
        if ($search != '') {
            $catUnspsc = CatalogoUnspsc::select('id_catalogo_unspsc as value', 'nombre_catalogo_unspsc as label')
                ->where('nombre_catalogo_unspsc', 'like', '%' . $search . '%')->get();
        }
        return response()->json(
            [
                'catUnspsc'          => $search != '' ? $catUnspsc : [],
            ]
        );
    }
}
