<?php

namespace App\Http\Controllers\UCP;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use App\Models\TipoMarca;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarcaUcpController extends Controller
{
    public function getMarcasUcp(Request $request)
    {
        $columns = ['id_marca', 'nombre_marca', 'fecha_reg_marca', 'fecha_mod_marca', 'estado_marca'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = Marca::select('*')
            ->orderBy($columns[$column], $dir);

        if ($search_value) {
            $query->where('nombre_marca', 'like', '%' . $search_value['nombre_marca'] . '%')
                ->where('fecha_reg_marca', 'like', '%' . $search_value['fecha_reg_marca'] . '%')
                ->where('estado_marca', 'like', '%' . $search_value['estado_marca'] . '%');

            //Search by id
            if ($search_value['id_marca']) {
                $query->where('id_marca', $search_value['id_marca']);
            }
            //Search by fecha_mod_marca
            if ($search_value['fecha_mod_marca'] == 'N/A' || $search_value['fecha_mod_marca'] == 'n/a') {
                $query->where('fecha_mod_marca', null);
            } else {
                $query->whereRaw('IFNULL(fecha_mod_marca, "") like ?', '%' . $search_value['fecha_mod_marca'] . '%');
            }
        }

        $data = $query->paginate($length)->onEachSide(1);
        return ['data' => $data, 'draw' => $request->input('draw')];
    }

    public function getInfoModalBrandUcp(Request $request, $id)
    {
        $brand = Marca::find($id);
        $brandTypes = TipoMarca::select('id_tipo_marca as value','nombre_tipo_marca as label')->get();

        return response()->json([
            'brand'                         => $brand ?? [],
            'brandTypes'                    => $brandTypes
        ]);
    }

    public function saveProduct(Request $request)
    {
        DB::beginTransaction();
        try {
            $product = new Marca([
                'id_tipo_marca'                 => $request->purchaseProcedureId,
                'id_tipo_marca'                 => $request->unspscId,
                'estado_producto'               => 1,
                'fecha_reg_producto'            => Carbon::now(),
                'usuario_producto'              => $request->user()->nick_usuario,
                'ip_producto'                   => $request->ip(),
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
}
