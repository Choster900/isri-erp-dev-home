<?php

namespace App\Http\Controllers\ActivoFijo;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivoFijo\BienMuebleYVehiculoRequest;
use App\Models\ActivoFijo;
use App\Models\BienEspecifico;
use App\Models\Dependencia;
use App\Models\EstadoActivoFijo;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Proveedor;
use App\Models\ProyectoFinanciado;
use Illuminate\Http\Request;

class ActivoFijoController extends Controller
{
    public function getActivos(Request $request)
    {
        $columns = ['id_af', 'nombre_bien_especifico', 'nombre_marca', 'serie_af', 'valor_adquisicion_af', 'estado_af'];

        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $search_value = $request->input('search');

        $query = ActivoFijo::select('*')
            ->leftJoin('modelo', 'activo_fijo.id_modelo', '=', 'modelo.id_modelo')
            ->leftJoin('marca', 'modelo.id_marca', '=', 'marca.id_marca')
            ->join('bien_especifico', 'activo_fijo.id_bien_especifico', '=', 'bien_especifico.id_bien_especifico')
            ->join('catalogo_cta_presupuestal', 'bien_especifico.id_ccta_presupuestal', '=', 'catalogo_cta_presupuestal.id_ccta_presupuestal')
            ->join('tipo_activo_fijo', 'catalogo_cta_presupuestal.id_tipo_af', '=', 'tipo_activo_fijo.id_tipo_af')
            ->whereIn('catalogo_cta_presupuestal.id_tipo_af', $request->asset_types)
            ->orderBy($columns[$column], $dir);

        if ($search_value) {
            $query->where('activo_fijo.id_af', 'like', '%' . $search_value['id_af'] . '%')
                ->where('activo_fijo.valor_adquisicion_af', 'like', '%' . $search_value['valor_adquisicion_af'] . '%')
                ->whereRaw('IFNULL(activo_fijo.serie_af, "") like ?', '%' . $search_value["serie_af"] . '%')
                ->where('activo_fijo.estado_af', 'like', '%' . $search_value['estado_af'] . '%')
                ->where('marca.nombre_marca', 'like', '%' . $search_value['nombre_marca'] . '%')
                ->where('bien_especifico.nombre_bien_especifico', 'like', '%' . $search_value['nombre_bien_especifico'] . '%');
            //->whereIn('id_tipo_af', $request->asset_types);
        }

        $bienes = $query->paginate($length)->onEachSide(1);
        return ['data' => $bienes, 'draw' => $request->input('draw')];
    }
    public function getSelectMVAsset(Request $request)
    {
        $bien_especifico = BienEspecifico::select('bien_especifico.id_bien_especifico as value', 'bien_especifico.nombre_bien_especifico as label', 'bien_especifico.id_ccta_presupuestal','tipo_activo_fijo.vida_util_tipo_af')
            ->join('catalogo_cta_presupuestal', 'bien_especifico.id_ccta_presupuestal', '=', 'catalogo_cta_presupuestal.id_ccta_presupuestal')
            ->join('tipo_activo_fijo', 'catalogo_cta_presupuestal.id_tipo_af', '=', 'tipo_activo_fijo.id_tipo_af')
            ->where('estado_bien_especifico', '=', 1)
            ->orderBy('nombre_bien_especifico')
            ->get();
        $estado_af = EstadoActivoFijo::select('id_estado_af as value', 'nombre_estado_af as label')
            ->whereIn('id_estado_af', [1, 2])
            ->where('estado_estado_af', '=', 1)
            ->orderBy('nombre_estado_af')
            ->get();
        $financing_sources = ProyectoFinanciado::select('id_proy_financiado as value', 'nombre_proy_financiado as label')
            ->where('estado_proy_financiado', '=', 1)
            ->orderBy('nombre_proy_financiado')
            ->get();
        $suppliers = Proveedor::select('id_proveedor as value', 'razon_social_proveedor as label')
            ->where('estado_proveedor', '=', 1)
            ->orderBy('razon_social_proveedor')
            ->get();
        $dependencies = Dependencia::selectRaw("id_dependencia as value , concat(codigo_dependencia, ' - ', nombre_dependencia) as label")
            ->where('id_tipo_dependencia', '=', 1)
            ->orderBy('nombre_dependencia')
            ->get();
        $brands = Marca::select('id_marca as value', 'nombre_marca as label')
            ->where('estado_marca', '=', 1)
            ->has('modelos')
            ->orderBy('nombre_marca')
            ->get();
        return [
            'specific_assets' => $bien_especifico, 'conditions' => $estado_af,
            'financing_sources' => $financing_sources, 'suppliers' => $suppliers,
            'dependencies' => $dependencies, 'brands' => $brands,
        ];
    }
    public function getModels(Request $request)
    {
        $models = Modelo::select('id_modelo as value', 'nombre_modelo as label')
            ->where('estado_modelo', '=', 1)
            ->where('id_marca', '=', $request->brand_id)
            ->orderBy('nombre_modelo')
            ->get();
        return ['models' => $models];
    }

    public function saveMVAsset(BienMuebleYVehiculoRequest $request)
    {
        return ['mensaje' => 'hola'];
    }
}
