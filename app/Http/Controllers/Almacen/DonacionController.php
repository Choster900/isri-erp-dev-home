<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\RecepcionPedido;
use Illuminate\Http\Request;

class DonacionController extends Controller
{
    public function getDonaciones(Request $request)
    {
        $columns = ['id_recepcion_pedido', 'acta_recepcion_pedido', 'id_proy_financiado', 'incumple_acuerdo_recepcion_pedido', 'monto_recepcion_pedido', 'fecha_recepcion_pedido', 'id_estado_recepcion_pedido'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = RecepcionPedido::select('*')
            ->with([
                'estado_recepcion'
            ])
            ->where('id_proy_financiado', 4)
            ->orderBy($columns[$column], $dir);

        $data = $query->paginate($length)->onEachSide(1);
        return ['data' => $data, 'draw' => $request->input('draw')];
    }

    public function getInfoModalDonation(Request $request)
    {
        $idRec = $request->id; //Reception id from the view, if it's 0 that means we are creating a new reception
        if ($idRec > 0) { //This means we are updating an existing reception
            $recep = RecepcionPedido::with([
                'detalle_recepcion.producto.unidad_medida',
                'estado_recepcion',
                'proveedor'
            ])->find($idRec);

            if (!$recep) {
                return response()->json([
                    'logical_error' => 'Error, no fue posible obtener la recepción consultada. Consulte con el administrador.',
                ], 422);
            }
            $suppliers = Proveedor::select('id_proveedor as value', 'razon_social_proveedor as label', 'nit_proveedor')
                ->where('estado_proveedor', 1)->orWhere('id_proveedor', $recep->id_proveedor)->get();
        } else { //Creating a new one
            $recep = [];
            $suppliers = Proveedor::select('id_proveedor as value', 'razon_social_proveedor as label', 'nit_proveedor')
                ->where('estado_proveedor', 1)->get();
        }

        return response()->json([
            'recep'                         => $recep,
            'suppliers'                     => $suppliers
        ]);
    }

    public function searchProduct(Request $request)
    {
        $search = $request->busqueda;
        $prodIdToIgnore = $request->prodIdToIgnore;
        if ($search != '') {
            $products = Producto::select('id_producto as value', 'nombre_producto as label')
                ->where('nombre_producto', 'like', '%' . $search . '%')
                ->where('estado_producto', 1)
                ->whereNotIn('id_producto', $prodIdToIgnore)
                ->get();
        }
        return response()->json(
            [
                'products'          => $search != '' ? $products : [],
            ]
        );
    }
}
