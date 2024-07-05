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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductoAlmacenController extends Controller
{
    public function getInfoModalProdAlmacen(Request $request, $id)
    {
        $prod = Producto::with(['unidad_medida', 'catalogo_unspsc', 'proceso_compra'])->find($id);

        $purchaseProcedures = ProcesoCompra::selectRaw('id_proceso_compra as value, concat(id_proceso_compra," - ",nombre_proceso_compra) as label')->get();
        $budgetAccounts = CuentaPresupuestal::selectRaw("id_ccta_presupuestal as value , concat(codigo_ccta_presupuestal, ' - ', nombre_ccta_presupuestal) as label")
            ->where('estado_ccta_presupuestal', 1)
            ->where('compra_ccta_presupuestal', 1)
            ->get();
        $catPerc = CatalogoPerc::selectRaw('id_catalogo_perc as value, concat(IFNULL(codigo_catalogo_perc,"")," - ",nombre_catalogo_perc) as label')->get();
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

    public function updateProductAlmacen(Request $request)
    {
        //Define the custom messages
        $customMessages = [
            'catPercId.required'                => 'Debe seleccionar PERC.',
            'subWarehouseId.required'           => 'Debe seleccionar sub almacen.',
            'perishable.not_in'              => 'Debe seleccionar si es perecedero.',
        ];
        // Validate the request data with custom error messages and custom rule
        $validatedData = Validator::make($request->all(), [
            'catPercId'                         => 'required',
            'subWarehouseId'                    => 'required',
            'perishable'                        => 'not_in:-1',
        ], $customMessages)->validate();

        $prod = Producto::find($request->id);
        if ($prod->estado_producto == 0) {
            return response()->json(['logical_error' => 'Error, el producto que intentas modificar ha sido deshabilitado.'], 422);
        } else {
            DB::beginTransaction();
            try {
                $prod->update([
                    'id_catalogo_perc'          => $request->catPercId,
                    'id_sub_almacen'            => $request->subWarehouseId,
                    'perecedero_producto'       => $request->perishable == -1 ? null : $request->perishable,
                    'fecha_mod_producto'        => Carbon::now(),
                    'usuario_producto'          => $request->user()->nick_usuario,
                    'ip_producto'               => $request->ip(),
                ]);

                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'message'          => 'Producto actualizado con éxito.',
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
}
