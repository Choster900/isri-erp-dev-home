<?php

namespace App\Http\Controllers\UCP;

use App\Http\Controllers\Controller;
use App\Http\Requests\UCP\ProductoRequest;
use App\Models\CatalogoCtaNicsp;
use App\Models\CatalogoPerc;
use Illuminate\Http\Request;
use App\Models\CatalogoUnspsc;
use App\Models\CuentaPresupuestal;
use App\Models\ProcesoCompra;
use App\Models\Producto;
use App\Models\UnidadMedida;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    public function getProductos(Request $request)
    {
        $columns = ['id_producto', 'nombre_producto', 'descripcion_producto', 'id_ccta_presupuestal', 'id_unidad_medida', 'precio_producto', 'estado_producto'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = Producto::select('*')
            ->with([
                'unidad_medida',
            ])
            ->orderBy($columns[$column], $dir);

        if ($search_value) {
            $query->where('id_producto', 'like', '%' . $search_value['id_producto'] . '%')
                ->where('nombre_producto', 'like', '%' . $search_value['nombre_producto'] . '%')
                ->where('descripcion_producto', 'like', '%' . $search_value['descripcion_producto'] . '%')
                ->where('id_ccta_presupuestal', 'like', '%' . $search_value['id_ccta_presupuestal'] . '%')
                ->where('precio_producto', 'like', '%' . $search_value['precio_producto'] . '%')
                ->where('estado_producto', 'like', '%' . $search_value['estado_producto'] . '%')
                ->whereHas('unidad_medida', function ($query) use ($search_value) {
                    $query->where('nombre_unidad_medida', 'like', '%' . $search_value["unidad_medida"] . '%');
                });
        }

        $data = $query->paginate($length)->onEachSide(1);
        return ['data' => $data, 'draw' => $request->input('draw')];
    }

    public function getInfoModalProd(Request $request, $id)
    {
        $prod = Producto::with(['unidad_medida', 'catalogo_unspsc'])->find($id);

        $purchaseProcedures = ProcesoCompra::selectRaw('id_proceso_compra as value, concat(id_proceso_compra," - ",nombre_proceso_compra) as label')->get();
        $budgetAccounts = CuentaPresupuestal::selectRaw("id_ccta_presupuestal as value , concat(id_ccta_presupuestal, ' - ', nombre_ccta_presupuestal) as label")
            ->where('compra_ccta_presupuestal', 1)
            ->get();
        $catPerc = CatalogoPerc::selectRaw('id_catalogo_perc as value, concat(codigo_catalogo_perc," - ",nombre_catalogo_perc) as label')->get();
        $catNicsp = CatalogoCtaNicsp::selectRaw('id_ccta_nicsp as value, concat(codigo_ccta_nicsp," - ",nombre_ccta_nicsp) as label')
            ->whereRaw('LENGTH(codigo_ccta_nicsp) >= 7')
            ->get();
        $unitsMeasmt = UnidadMedida::select('id_unidad_medida as value', 'nombre_unidad_medida as label')
            ->where('estado_unidad_medida', 1)->get();

        return response()->json([
            'prod'                      => $prod ?? [],
            'purchaseProcedures'        => $purchaseProcedures,
            'budgetAccounts'            => $budgetAccounts,
            'unitsMeasmt'               => $unitsMeasmt,
            'catPerc'                   => $catPerc,
            'catNicsp'                  => $catNicsp

        ]);
    }

    public function searchUnspsc(Request $request)
    {
        $search = $request->busqueda;
        if ($search != '') {
            $catUnspsc = CatalogoUnspsc::selectRaw('id_catalogo_unspsc as value, concat(codigo_catalogo_unspsc," - ",nombre_catalogo_unspsc) as label')
                ->where('nombre_catalogo_unspsc', 'like', '%' . $search . '%')
                ->orWhere('codigo_catalogo_unspsc', 'like', '%' . $search . '%')
                ->get();
        }
        return response()->json(
            [
                'catUnspsc'          => $search != '' ? $catUnspsc : [],
            ]
        );
    }

    public function saveProduct(ProductoRequest $request)
    {
        DB::beginTransaction();
        try {
            $newProductCode = "";
            $lastProduct = Producto::where('id_ccta_presupuestal', $request->budgetAccountId)
                ->orderBy('fecha_reg_producto', 'desc')
                ->first();

            if ($lastProduct) {
                // Extract the correlative part of the product code
                $correlative = explode('-', $lastProduct->codigo_producto)[1];

                // Increment the correlative part by one
                $newCorrelative = intval($correlative) + 1;

                // Concatenate the category part of the product code with the incremented correlative part
                $newProductCode = $request->budgetAccountId . '-' . $newCorrelative;
            } else {
                $newProductCode = $request->budgetAccountId . '-1';
            }

            $product = new Producto([
                'id_proceso_compra'         => $request->purchaseProcedureId,
                'id_catalogo_unspsc'        => $request->unspscId,
                'id_ccta_presupuestal'      => $request->budgetAccountId,
                'id_unidad_medida'          => $request->mUnitId,
                'codigo_producto'           => $newProductCode,
                'nombre_producto'           => $request->name,
                'descripcion_producto'      => $request->description,
                'nombre_completo_producto'  => $request->name." ".$request->description,
                'precio_producto'           => substr($request->price, 1),
                'basico_producto'           => $request->gAndS == -1 ? null : $request->gAndS,
                'perecedero_producto'       => $request->perishable == -1 ? null : $request->perishable,
                'estado_producto'           => 1,
                'fecha_reg_producto'        => Carbon::now(),
                'usuario_producto'          => $request->user()->nick_usuario,
                'ip_producto'               => $request->ip(),
            ]);
            $product->save();

            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'message'          => 'Producto guardado con éxito.',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack(); // En caso de error, revierte las operaciones anteriores
            return response()->json([
                'logical_error' => 'Ha ocurrido un error con sus datos.',
                'error' => $th->getMessage(),
            ], 422);
        }
    }

    public function updateProduct(ProductoRequest $request)
    {
        $prod = Producto::find($request->id);
        if ($prod->estado_producto == 0) {
            return response()->json(['logical_error' => 'Error, el producto seleccionado ha sido deshabilitado.'], 422);
        } else {
            DB::beginTransaction();
            try {
                $prod->update([
                    'id_proceso_compra'         => $request->purchaseProcedureId,
                    'id_catalogo_unspsc'        => $request->unspscId,
                    'id_ccta_presupuestal'      => $request->budgetAccountId,
                    'id_unidad_medida'          => $request->mUnitId,
                    'nombre_producto'           => $request->name,
                    'descripcion_producto'      => $request->description,
                    'nombre_completo_producto'  => $request->name." ".$request->description,
                    'precio_producto'           => substr($request->price, 1),
                    'basico_producto'           => $request->gAndS == -1 ? null : $request->gAndS,
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

    public function changeStatusProduct(Request $request)
    {
        $prod = Producto::find($request->id);

        if ($request->status == $prod->estado_producto) {
            DB::beginTransaction();
            try {
                $prod->update([
                    'estado_producto'                        => $prod->estado_producto == 1 ? 0 : 1,
                    'fecha_mod_producto'                     => Carbon::now(),
                    'usuario_producto'                       => $request->user()->nick_usuario,
                    'ip_producto'                            => $request->ip(),
                ]);
                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'message'          => "Acción ejecutada con éxito.",
                ]);
            } catch (\Exception $e) {
                DB::rollBack(); // En caso de error, revierte las operaciones anteriores
                return response()->json([
                    'logical_error' => 'Ha ocurrido un error con sus datos.',
                    'error' => $e,
                ], 422);
            }
        } else {
            return response()->json(['logical_error' => 'Error, este producto ya ha sido ' . $prod->estado_producto == 1 ? 'activado.' : 'desactivado.',], 422);
        }
    }
}
