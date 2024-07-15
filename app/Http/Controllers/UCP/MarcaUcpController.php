<?php

namespace App\Http\Controllers\UCP;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use App\Models\TipoMarca;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MarcaUcpController extends Controller
{
    public function getMarcasUcp(Request $request)
    {
        $columns = ['id_marca', 'nombre_marca', 'id_tipo_marca', 'fecha_reg_marca', 'fecha_mod_marca', 'estado_marca'];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = Marca::select('*')
            ->with([
                'tipo_marca',
            ])
            ->orderBy($columns[$column], $dir);

        if ($search_value) {
            $query->where('nombre_marca', 'like', '%' . $search_value['nombre_marca'] . '%')
                ->where('fecha_reg_marca', 'like', '%' . $search_value['fecha_reg_marca'] . '%')
                ->where('estado_marca', 'like', '%' . $search_value['estado_marca'] . '%');

            //Search by id
            if ($search_value['id_marca']) {
                $query->where('id_marca', $search_value['id_marca']);
            }
            //Search by id_tipo_marca
            if ($search_value['id_tipo_marca']) {
                $query->where('id_tipo_marca', $search_value['id_tipo_marca']);
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
        $brandTypes = TipoMarca::select('id_tipo_marca as value', 'nombre_tipo_marca as label')->get();

        return response()->json([
            'brand'                         => $brand ?? [],
            'brandTypes'                    => $brandTypes
        ]);
    }

    public function saveBrandUcp(Request $request)
    {
        //Define the custom messages
        $customMessages = [
            'id_tipo_marca.required'                    => 'Debe seleccionar tipo marca.',
            'nombre_marca.required'                     => 'Debe escribir el nombre de la marca.',
        ];
        // Validate the request data with custom error messages and custom rule
        $validatedData = Validator::make($request->all(), [
            'id_tipo_marca'                             => 'required',
            'nombre_marca'                              => 'required',
        ], $customMessages)->validate();

        DB::beginTransaction();
        try {
            $brand = new Marca([
                'id_tipo_marca'                 => $request->id_tipo_marca,
                'nombre_marca'                  => $request->nombre_marca,
                'estado_marca'                  => 1,
                'fecha_reg_marca'               => Carbon::now(),
                'usuario_marca'                 => $request->user()->nick_usuario,
                'ip_marca'                      => $request->ip(),
            ]);
            $brand->save();

            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'message'          => 'Marca guardada con éxito.',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack(); // En caso de error, revierte las operaciones anteriores
            return response()->json([
                'logical_error' => 'Ha ocurrido un error con sus datos.',
                'error' => $th->getMessage(),
            ], 422);
        }
    }

    public function updateBrandUcp(Request $request)
    {
        //Define the custom messages
        $customMessages = [
            'id_tipo_marca.required'                    => 'Debe seleccionar tipo marca.',
            'nombre_marca.required'                     => 'Debe escribir el nombre de la marca.',
        ];
        // Validate the request data with custom error messages and custom rule
        $validatedData = Validator::make($request->all(), [
            'id_tipo_marca'                             => 'required',
            'nombre_marca'                              => 'required',
        ], $customMessages)->validate();

        $brand = Marca::find($request->id_marca);
        if ($brand->estado_marca == 0) {
            return response()->json(['logical_error' => 'Error, la marca seleccionada ha sido deshabilitada.'], 422);
        } else {
            DB::beginTransaction();
            try {
                $brand->update([
                    'id_tipo_marca'                 => $request->id_tipo_marca,
                    'nombre_marca'                  => $request->nombre_marca,
                    'fecha_mod_marca'               => Carbon::now(),
                    'usuario_marca'                 => $request->user()->nick_usuario,
                    'ip_marca'                      => $request->ip(),
                ]);

                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'message'          => 'Marca actualizada con éxito.',
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
        $brand = Marca::find($request->id);
        if ($request->status == $brand->estado_marca) {
            DB::beginTransaction();
            try {
                $brand->update([
                    'estado_marca'                           => $brand->estado_marca == 1 ? 0 : 1,
                    'fecha_mod_marca'                        => Carbon::now(),
                    'usuario_marca'                          => $request->user()->nick_usuario,
                    'ip_marca'                               => $request->ip(),
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
            return response()->json(['logical_error' => 'Error, esta marca ya ha sido ' . $brand->estado_marca == 1 ? 'activada.' : 'desactivada.',], 422);
        }
    }
}
