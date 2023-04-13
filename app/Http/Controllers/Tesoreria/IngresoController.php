<?php

namespace App\Http\Controllers\Tesoreria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ConceptoIngreso;
use App\Models\CuentaPresupuestal;
use App\Models\Dependencia;
use App\Models\ProyectoFinanciado;
use Carbon\Carbon;
use App\Http\Requests\IncomeConceptRequest;

class IngresoController extends Controller
{
    public function getConceptoIngresos(Request $request)
    {
        $columns = ['id_concepto_ingreso', 'nombre_dependencia', 'nombre_concepto_ingreso', 'id_ccta_presupuestal', 'estado_concepto_ingreso'];

        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $search_value = $request->input('search');

        $query = ConceptoIngreso::select('*')
                    ->leftJoin('dependencia', function ($join) {
                        $join->on('concepto_ingreso.id_dependencia', '=', 'dependencia.id_dependencia');
                    });

        if ($search_value) {
            $query->where([
                ['id_concepto_ingreso','like','%'.$search_value['id_concepto_ingreso'].'%'],
                ['nombre_concepto_ingreso','like','%'.$search_value['nombre_concepto_ingreso'].'%'],
                ['id_ccta_presupuestal','like','%'.$search_value['id_ccta_presupuestal'].'%'],
                ['estado_concepto_ingreso','like','%'.$search_value['estado_concepto_ingreso'].'%'],
                [function ($query) use ($search_value){
                    $query->whereRaw('IFNULL(nombre_dependencia, "") like ?','%' . $search_value['nombre_dependencia'] . '%')
                        ->orWhereRaw('IFNULL(codigo_dependencia, "") like ?','%' . $search_value['nombre_dependencia'] . '%');
                }],
            ]);
            // $query->where('id_concepto_ingreso', 'like', '%' . $search_value['id_concepto_ingreso'] . '%')
            //         ->whereRaw('IFNULL(nombre_dependencia, "") like ?','%' . $search_value['nombre_dependencia'] . '%')
            //         ->where('nombre_concepto_ingreso', 'like', '%' . $search_value['nombre_concepto_ingreso'] . '%')
            //         ->where('id_ccta_presupuestal', 'like', '%' . $search_value['id_ccta_presupuestal'] . '%')
            //         ->where('estado_concepto_ingreso', 'like', '%' . $search_value['estado_concepto_ingreso'] . '%');
        }

        $income_concept = $query->paginate($length)->onEachSide(1);
        return ['data' => $income_concept, 'draw' => $request->input('draw')];
    }

    public function changeStateIncomeConcept(Request $request){
        $estado_anterior = $request->input('state_service');
        $msg="";
        $servicio = ConceptoIngreso::find($request->id_service);
        $estado_anterior==1 ? $servicio->estado_concepto_ingreso=0 : $servicio->estado_concepto_ingreso=1;
        $estado_anterior==1 ? $msg="Desactivado" : $msg="Activado";
        $servicio->ip_concepto_ingreso=$request->ip();
        $servicio->fecha_mod_concepto_ingreso=Carbon::now();
        $servicio->usuario_concepto_ingreso=$request->user()->nick_usuario;
        $servicio->update();
        return ['mensaje' => $msg.' concepto de ingeso '.$servicio->nombre_concepto_ingreso.' con exito'];
    }
    public function getSelectsIncomeConcept(Request $request){
        $budget_accounts = CuentaPresupuestal::selectRaw("id_ccta_presupuestal as value , concat(id_ccta_presupuestal, ' - ', nombre_ccta_presupuestal) as label")
                ->where('tesoreria_ccta_presupuestal','=',1)
                ->where('estado_ccta_presupuestal','=',1)
                ->orderBy('nombre_ccta_presupuestal')
                ->get();
        $dependencies = Dependencia::selectRaw("id_dependencia as value , concat(codigo_dependencia, ' - ', nombre_dependencia) as label")
                ->where('id_tipo_dependencia','=',1)
                ->orderBy('nombre_dependencia')
                ->get();
        $financing_sources = ProyectoFinanciado::select('id_proy_financiado as value','nombre_proy_financiado as label')
                ->where('estado_proy_financiado','=',1)
                ->orderBy('nombre_proy_financiado')
                ->get();
        
        return ['budget_accounts' => $budget_accounts, 'dependencies' => $dependencies, 'financing_sources' => $financing_sources];
    }

    public function saveIncomeConcept(IncomeConceptRequest $request){
        $new_income_concept = new ConceptoIngreso();
        $new_income_concept->id_dependencia = $request->dependency_id;
        $new_income_concept->id_ccta_presupuestal = $request->budget_account_id;
        $new_income_concept->id_proy_financiado = $request->financing_source_id;
        $new_income_concept->nombre_concepto_ingreso = $request->name;
        $new_income_concept->detalle_concepto_ingreso = $request->detail;
        $new_income_concept->estado_concepto_ingreso = 1;
        $new_income_concept->fecha_reg_concepto_ingreso = Carbon::now();
        $new_income_concept->usuario_concepto_ingreso = $request->user()->nick_usuario;
        $new_income_concept->ip_concepto_ingreso = $request->ip();
        $new_income_concept->save();
        return ['mensaje' => 'Concepto de ingreso '.$request->name.' guardado con éxito.'];
    }
    public function updateIncomeConcept(IncomeConceptRequest $request){
        $income_concept = ConceptoIngreso::find($request->income_concept_id);
        $income_concept->id_dependencia = $request->dependency_id;
        $income_concept->id_ccta_presupuestal = $request->budget_account_id;
        $income_concept->id_proy_financiado = $request->financing_source_id;
        $income_concept->nombre_concepto_ingreso = $request->name;
        $income_concept->detalle_concepto_ingreso = $request->detail;
        $income_concept->fecha_mod_concepto_ingreso = Carbon::now();
        $income_concept->usuario_concepto_ingreso = $request->user()->nick_usuario;
        $income_concept->ip_concepto_ingreso = $request->ip();
        $income_concept->update();
        return ['mensaje' => 'Concepto de ingreso '.$request->name.' actualizado con éxito.'];
    }
}
