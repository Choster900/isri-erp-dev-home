<?php

namespace App\Http\Controllers\ActivoFijo;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivoFijo\BienEspecificoRequest;
use App\Models\BienEspecifico;
use App\Models\CuentaPresupuestal;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BienEspecificoController extends Controller
{
    public function getBienEspecifico(Request $request)
    {
        $columns = ['id_bien_especifico', 'nombre_bien_especifico', 'descripcion_bien_especifico', 'id_ccta_presupuestal', 'estado_bien_especifico'];

        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $search_value = $request->input('search');

        $query = BienEspecifico::select('id_bien_especifico', 'nombre_bien_especifico', 'descripcion_bien_especifico', 'id_ccta_presupuestal', 'estado_bien_especifico')
            ->orderBy($columns[$column], $dir);

        if ($search_value) {
            $query->where('id_bien_especifico', 'like', '%' . $search_value['id_bien_especifico'] . '%')
                ->where('nombre_bien_especifico', 'like', '%' . $search_value['nombre_bien_especifico'] . '%')
                ->where('descripcion_bien_especifico', 'like', '%' . $search_value['descripcion_bien_especifico'] . '%')
                ->where('id_ccta_presupuestal', 'like', '%' . $search_value['id_ccta_presupuestal'] . '%')
                ->where('estado_bien_especifico', 'like', '%' . $search_value['estado_bien_especifico'] . '%');
        }

        $bienes = $query->paginate($length)->onEachSide(1);
        return ['data' => $bienes, 'draw' => $request->input('draw')];
    }
    public function changeStateSpecificAsset(Request $request)
    {
        $asset = BienEspecifico::find($request->id_bien_especifico);
        if ($asset->estado_bien_especifico == 1) {
            if ($request->state_bien_especifico == 1) {
                $asset->update([
                    'estado_bien_especifico' => 0,
                    'fecha_bien_especifico' => Carbon::now(),
                    'usuario_bien_especifico' => $request->user()->nick_usuario,
                    'ip_bien_especifico' => $request->ip(),
                ]);
                return ['mensaje' => 'Bien especifico ' . $asset->nombre_bien_especifico . ' ha sido desactivado con exito'];
            } else {
                return ['mensaje' => 'El bien especifico seleccionado ya ha sido activado por otro usuario'];
            }
        } else {
            if ($asset->estado_bien_especifico == 0) {
                if ($request->state_bien_especifico == 0) {
                    $asset->update([
                        'estado_bien_especifico' => 1,
                        'fecha_bien_especifico' => Carbon::now(),
                        'usuario_bien_especifico' => $request->user()->nick_usuario,
                        'ip_bien_especifico' => $request->ip(),
                    ]);
                    return ['mensaje' => 'Bien especifico ' . $asset->nombre_bien_especifico . ' ha sido activado con exito'];
                } else {
                    return ['mensaje' => 'El bien especifico seleccionado ya ha sido desactivado por otro usuario'];
                }
            }
        }
    }
    public function getSelectSpecificAsset(Request $request)
    {
        $budget_accounts = CuentaPresupuestal::selectRaw("id_ccta_presupuestal as value , concat(id_ccta_presupuestal, ' - ', nombre_ccta_presupuestal) as label")
            ->where('activo_fijo_ccta_presupuestal', '=', 1)
            ->where('estado_ccta_presupuestal', '=', 1)
            ->orderBy('nombre_ccta_presupuestal')
            ->get();
        return ['budget_accounts' => $budget_accounts];
    }
    public function saveSpecificAsset(BienEspecificoRequest $request)
    {
        $new_specific_asset = new BienEspecifico([
            'id_ccta_presupuestal' => $request->budget_account_id,
            'nombre_bien_especifico' => $request->name,
            'descripcion_bien_especifico' => $request->description,
            'estado_bien_especifico' => 1,
            'fecha_reg_bien_especifico' => Carbon::now(),
            'usuario_bien_especifico' => $request->user()->nick_usuario,
            'ip_bien_especifico' => $request->ip(),
        ]);
        $new_specific_asset->save();
        return ['mensaje' => 'Bien especifico guardado con éxito.'];
    }
    public function updateSpecificAsset(BienEspecificoRequest $request)
    {
        $asset = BienEspecifico::find($request->id);
        if ($asset->estado_bien_especifico == 0) {
            return response()->json(['logical_error' => 'Error, el bien especifico seleccionado ha sido desactivado por otro usuario.'], 422);
        } else {
            $asset->update([
                'id_ccta_presupuestal' => $request->budget_account_id,
                'nombre_bien_especifico' => $request->name,
                'descripcion_bien_especifico' => $request->description,
                'estado_bien_especifico' => 1,
                'fecha_mod_bien_especifico' => Carbon::now(),
                'usuario_bien_especifico' => $request->user()->nick_usuario,
                'ip_bien_especifico' => $request->ip(),
            ]);
            return ['mensaje' => 'Marca actualizada con éxito'];
        }
    }
}
