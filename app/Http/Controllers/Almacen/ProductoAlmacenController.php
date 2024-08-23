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
    public function getProductosAlmacen(Request $request)
    {
        $columns = ['id_producto', 'nombre_completo_producto', 'codigo_producto', 'nombre_unidad_medida', 'nombre_sub_almacen', 'id_catalogo_perc', 'estado_producto'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = Producto::select('*')
            ->with([
                'unidad_medida',
                'sub_almacen'
            ]);

        //Sorting
        if ($column == 3) { //Order by nombre_unidad_medida
            $query->orderByRaw('
                (SELECT nombre_unidad_medida FROM unidad_medida WHERE unidad_medida.id_unidad_medida = producto.id_unidad_medida) ' . $dir);
        } else {
            if ($column == 4) { //Order by nombre_sub_almacen
                $query->orderByRaw('
                (SELECT nombre_sub_almacen FROM sub_almacen WHERE sub_almacen.id_sub_almacen = producto.id_sub_almacen) ' . $dir);
            } else {
                $query->orderBy($columns[$column], $dir);
            }
        }

        if ($search_value) {
            $query
                ->where('codigo_producto', 'like', '%' . $search_value['codigo_producto'] . '%')
                ->where('estado_producto', 'like', '%' . $search_value['estado_producto'] . '%')
                ->whereHas('unidad_medida', function ($query) use ($search_value) {
                    $query->where('nombre_unidad_medida', 'like', '%' . $search_value["nombre_unidad_medida"] . '%');
                });

            //Search by id
            if ($search_value['id_producto']) {
                $query->where('id_producto', $search_value['id_producto']);
            }
            //Search by description
            if ($search_value['nombre_completo_producto']) {
                $terms = explode(' ', $search_value['nombre_completo_producto']);
                foreach ($terms as $term) {
                    $query->where('nombre_completo_producto', 'like', '%' . $term . '%');
                }
            }
            //Search by subalmacen
            if ($search_value['nombre_sub_almacen']) {
                $query->whereHas('sub_almacen', function ($query) use ($search_value) {
                    $query->where('nombre_sub_almacen', 'like', '%' . $search_value["nombre_sub_almacen"] . '%');
                });
            }
            //Search by perc
            if ($search_value['id_catalogo_perc']) {
                $query->where('id_catalogo_perc', $search_value['id_catalogo_perc']);
            }
        }

        $data = $query->paginate($length)->onEachSide(1);
        return ['data' => $data, 'draw' => $request->input('draw')];
    }

    public function getInfoModalProdAlmacen(Request $request, $id)
    {
        $prod = Producto::with(['unidad_medida', 'catalogo_unspsc', 'proceso_compra'])->find($id);
        $catPerc = CatalogoPerc::selectRaw('id_catalogo_perc as value, concat(IFNULL(codigo_catalogo_perc,"")," - ",nombre_catalogo_perc) as label')
            ->where('estado_catalogo_perc', 1)->get();
        $subWarehouses = SubAlmacen::select('id_sub_almacen as value', 'nombre_sub_almacen as label')
            ->where('estado_sub_almacen', 1)->get();

        return response()->json([
            'prod'                          => $prod ?? [],
            'catPerc'                       => $catPerc,
            'subWarehouses'                 => $subWarehouses
        ]);
    }

    public function updateProductAlmacen(Request $request)
    {
        //Define the custom messages
        $customMessages = [
            'catPercId.required'                => 'Debe seleccionar PERC.',
            'subWarehouseId.required'           => 'Debe seleccionar sub almacen.',
            'perishable.not_in'                 => 'Debe seleccionar si es perecedero.',
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
