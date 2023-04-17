<?php

namespace App\Http\Controllers\Tesoreria;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ConceptoIngreso;
use App\Models\CuentaPresupuestal;
use App\Models\ReciboIngreso;
use Carbon\Carbon;

class ReciboIngresoController extends Controller
{
    public function getRecibosIngreso(Request $request)
    {
        $columns = ['numero_recibo_ingreso', 'id_ccta_presupuestal', 'cliente_recibo_ingreso', 'monto_recibo_ingreso', 'estado_recibo_ingreso', 'descripcion_recibo_ingreso'];

        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $search_value = $request->input('search');

        $query = ReciboIngreso::select('*')
            ->orderBy($columns[$column], $dir);
        if ($search_value) {
            $query->where('numero_recibo_ingreso', 'like', '%' . $search_value['numero_recibo_ingreso'] . '%')
                ->where('id_ccta_presupuestal', 'like', '%' . $search_value['id_ccta_presupuestal'] . '%')
                ->where('cliente_recibo_ingreso', 'like', '%' . $search_value['cliente_recibo_ingreso'] . '%')
                ->where('monto_recibo_ingreso', 'like', '%' . $search_value['monto_recibo_ingreso'] . '%')
                ->where('descripcion_recibo_ingreso', 'like', '%' . $search_value['descripcion_recibo_ingreso'] . '%')
                ->where('estado_recibo_ingreso', 'like', '%' . $search_value['estado_recibo_ingreso'] . '%');
        }
        $income_concept = $query->paginate($length)->onEachSide(1);
        return ['data' => $income_concept, 'draw' => $request->input('draw')];
    }

    public function changeStateIncomeReceipt(Request $request)
    {
        $estado_anterior = $request->input('income_receipt_state');
        $msg = "";
        $recibo_ingreso = ReciboIngreso::find($request->income_receipt_id);
        $estado_anterior == 1 ? $recibo_ingreso->estado_recibo_ingreso = 0 : $recibo_ingreso->estado_recibo_ingreso = 1;
        $estado_anterior == 1 ? $msg = "Desactivado" : $msg = "Activado";
        $recibo_ingreso->ip_recibo_ingreso = $request->ip();
        $recibo_ingreso->fecha_mod_recibo_ingreso = Carbon::now();
        $recibo_ingreso->usuario_recibo_ingreso = $request->user()->nick_usuario;
        $recibo_ingreso->update();
        return ['mensaje' => $msg . ' recibo numero ' . $recibo_ingreso->numero_recibo_ingreso . ' con exito'];
    }

    public function getTreasuryBudgetAccounts(Request $request)
    {
        $budget_accounts = CuentaPresupuestal::selectRaw("id_ccta_presupuestal as value , concat(id_ccta_presupuestal, ' - ', nombre_ccta_presupuestal) as label")
            ->where('tesoreria_ccta_presupuestal', '=', 1)
            ->where('estado_ccta_presupuestal', '=', 1)
            ->orderBy('nombre_ccta_presupuestal')
            ->get();
        return ['budget_accounts' => $budget_accounts];
    }

    public function getIncomeConcept(Request $request)
    {
        $income_concept = ConceptoIngreso::selectRaw("id_concepto_ingreso as value , concat(nombre_dependencia, ' - ', nombre_concepto_ingreso) as label")
            ->join('dependencia', function ($join) {
                $join->on('concepto_ingreso.id_dependencia', '=', 'dependencia.id_dependencia');
            })
            ->where('id_ccta_presupuestal', $request->budget_account_id);

        return ['income_concept' => $income_concept];
    }
}
