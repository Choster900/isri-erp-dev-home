<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Models\CatalogoCtaNicsp;
use App\Models\CatalogoPerc;
use App\Models\CuentaPresupuestal;
use App\Models\ProcesoCompra;
use App\Models\Producto;
use App\Models\SubAlmacen;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class ProductoAlmacenController extends Controller
{
    public function getInfoModalProdAlmacen(Request $request, $id)
    {
        $prod = Producto::with(['unidad_medida', 'catalogo_unspsc'])->find($id);

        $purchaseProcedures = ProcesoCompra::selectRaw('id_proceso_compra as value, concat(id_proceso_compra," - ",nombre_proceso_compra) as label')->get();
        $budgetAccounts = CuentaPresupuestal::selectRaw("id_ccta_presupuestal as value , concat(codigo_ccta_presupuestal, ' - ', nombre_ccta_presupuestal) as label")
            ->where('estado_ccta_presupuestal', 1)
            ->where('compra_ccta_presupuestal', 1)
            ->get();
        $catPerc = CatalogoPerc::selectRaw('id_catalogo_perc as value, concat(codigo_catalogo_perc," - ",nombre_catalogo_perc) as label')->get();
        $catNicsp = CatalogoCtaNicsp::selectRaw('id_ccta_nicsp as value, concat(codigo_ccta_nicsp," - ",nombre_ccta_nicsp) as label')
            ->whereRaw('LENGTH(codigo_ccta_nicsp) >= 7')
            ->get();
        $unitsMeasmt = UnidadMedida::select('id_unidad_medida as value', 'nombre_unidad_medida as label')
            ->where('estado_unidad_medida', 1)->get();
        $subWarehouses = SubAlmacen::select('id_sub_almacen as value', 'nombre_sub_almacen as label')
            ->where('estado_sub_almacen', 1)->get();

        return response()->json([
            'prod'                      => $prod ?? [],
            'purchaseProcedures'        => $purchaseProcedures,
            'budgetAccounts'            => $budgetAccounts,
            'unitsMeasmt'               => $unitsMeasmt,
            'catPerc'                   => $catPerc,
            'catNicsp'                  => $catNicsp,
            'subWarehouses'              => $subWarehouses
        ]);
    }
}
