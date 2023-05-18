<?php

namespace App\Http\Controllers\Tesoreria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tesoreria\IncomeReceiptRequest;
use App\Models\ConceptoIngreso;
use App\Models\CuentaPresupuestal;
use App\Models\DetalleReciboIngreso;
use App\Models\ProyectoFinanciado;
use App\Models\ReciboIngreso;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Luecano\NumeroALetras\NumeroALetras;

class ReciboIngresoController extends Controller
{
    public function getRecibosIngreso(Request $request)
    {
        $columns = ['numero_recibo_ingreso', 'fecha_recibo_ingreso', 'cliente_recibo_ingreso', 'descripcion_recibo_ingreso', 'id_ccta_presupuestal', 'monto_recibo_ingreso', 'estado_recibo_ingreso'];

        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $search_value = $request->input('search');

        $query = ReciboIngreso::select('*')
            ->with('detalles')
            ->with('detalles.concepto_ingreso.dependencia')
            ->with('cuenta_presupuestal')
            ->with('proyecto_financiado')
            ->with('empleado_tesoreria')
            ->orderBy($columns[$column], $dir);
        if ($search_value) {
            $query->where('numero_recibo_ingreso', 'like', '%' . $search_value['numero_recibo_ingreso'] . '%')
                ->where('fecha_recibo_ingreso', 'like', '%' . $search_value['fecha_recibo_ingreso'] . '%')
                ->where('id_ccta_presupuestal', 'like', '%' . $search_value['id_ccta_presupuestal'] . '%')
                ->where('cliente_recibo_ingreso', 'like', '%' . $search_value['cliente_recibo_ingreso'] . '%')
                ->where('monto_recibo_ingreso', 'like', '%' . $search_value['monto_recibo_ingreso'] . '%')
                ->where('descripcion_recibo_ingreso', 'like', '%' . $search_value['descripcion_recibo_ingreso'] . '%')
                ->where('estado_recibo_ingreso', 'like', '%' . $search_value['estado_recibo_ingreso'] . '%');
        }
        $income_receipt = $query->paginate($length)->onEachSide(1);

        $numeroLetras = new NumeroALetras();
        $monto_letras = array_map(function ($income_receipt) use ($numeroLetras) {
            return ['monto_letras' => $numeroLetras->toInvoice($income_receipt['monto_recibo_ingreso'], 2, 'DÓLARES')];
        }, $income_receipt->toArray()['data']);
        $query_con_monto_letras = $income_receipt->map(function ($item, $key) use ($monto_letras) {
            $item->monto_letras = $monto_letras[$key]['monto_letras'];
            return $item;
        });
        $paginator = new LengthAwarePaginator(
            $query_con_monto_letras,
            $income_receipt->total(),
            $income_receipt->perPage(),
            $income_receipt->currentPage(),
            ['path' => url()->current()]
        );

        return ['data' => $paginator, 'draw' => $request->input('draw')];
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

    public function getModalReceiptSelects(Request $request)
    {
        $budget_accounts = CuentaPresupuestal::selectRaw("id_ccta_presupuestal as value , concat(id_ccta_presupuestal, ' - ', nombre_ccta_presupuestal) as label")
            ->where('tesoreria_ccta_presupuestal', '=', 1)
            ->where('estado_ccta_presupuestal', '=', 1)
            ->whereHas('conceptos_ingreso', function ($query) use ($request) {
                $query->where('estado_concepto_ingreso', 1);
            })
            ->orderBy('nombre_ccta_presupuestal')
            ->get();
        $treasury_clerk = DB::table('empleado_tesoreria')
            ->select('id_empleado_tesoreria as value', 'nombre_empleado_tesoreria as label')
            ->get();

        return ['budget_accounts' => $budget_accounts,'treasury_clerk' => $treasury_clerk];
    }

    public function getSelectFinancingSource(Request $request)
    {
        $financing_sources = ProyectoFinanciado::selectRaw('id_proy_financiado as value, nombre_proy_financiado as label')
            ->whereHas('conceptos_ingreso', function ($query) use ($request) {
                $query->where('id_ccta_presupuestal', $request->budget_account_id)
                      ->where('estado_concepto_ingreso', 1);
            })
            ->get();
        return ['financing_sources' => $financing_sources];
    }

    public function getSelectIncomeConcept(Request $request)
    {
        $income_concept_select = ConceptoIngreso::selectRaw("concepto_ingreso.id_concepto_ingreso as value , concat(coalesce(concat(dependencia.codigo_dependencia,' - '), ''), concepto_ingreso.nombre_concepto_ingreso) as label")
            ->leftJoin('dependencia', function ($join) {
                $join->on('concepto_ingreso.id_dependencia', '=', 'dependencia.id_dependencia');
            })
            ->where('concepto_ingreso.estado_concepto_ingreso', '=', 1)
            ->where('concepto_ingreso.id_proy_financiado', '=', $request->financing_source_id)
            ->where('concepto_ingreso.id_ccta_presupuestal', '=', $request->budget_account_id)
            ->get();
        return ['income_concept_select' => $income_concept_select];
    }

    public function saveIncomeReceipt(IncomeReceiptRequest $request)
    {
        $current_year = Carbon::now()->year;
        $last_receipt = ReciboIngreso::where('numero_recibo_ingreso', 'like', '%' . $current_year . '%')->orderBy('id_recibo_ingreso', 'desc')->first();
        if ($last_receipt) {
            $last_number = $last_receipt->numero_recibo_ingreso;
            $correlative = intval(substr($last_number, 0, strpos($last_number, '-'))) + 1;
        } else {
            $correlative = 1;
        }
        $receipt_number = $correlative . '-' . $current_year;

        $new_income_receipt = new ReciboIngreso([
            'id_ccta_presupuestal' => $request->budget_account_id,
            'id_proy_financiado' => $request->financing_source_id,
            'id_empleado_tesoreria' => $request->treasury_clerk_id,
            'cliente_recibo_ingreso' => $request->client,
            'descripcion_recibo_ingreso' => $request->description,
            'doc_identidad_recibo_ingreso' => $request->document,
            'direccion_cliente_recibo_ingreso' => $request->direction,
            'numero_recibo_ingreso' => $receipt_number,
            'fecha_recibo_ingreso' => Carbon::now(),
            'monto_recibo_ingreso' => $request->total,
            'estado_recibo_ingreso' => 1,
            'fecha_reg_recibo_ingreso' => Carbon::now(),
            'usuario_recibo_ingreso' => $request->user()->nick_usuario,
            'ip_recibo_ingreso' => $request->ip(),
        ]);
        $new_income_receipt->save();

        foreach ($request->income_detail as $detail) {
            $new_income_detail = new DetalleReciboIngreso([
                'id_recibo_ingreso' => $new_income_receipt->id_recibo_ingreso,
                'id_concepto_ingreso' => $detail['income_concept_id'],
                'monto_det_recibo_ingreso' => $detail['amount'],
                'estado_det_recibo_ingreso' => 1,
                'fecha_reg_det_recibo_ingreso' => Carbon::now(),
                'usuario_det_recibo_ingreso' => $request->user()->nick_usuario,
                'ip_det_recibo_ingreso' => $request->ip()
            ]);
            $new_income_detail->save();
        }
        return ['mensaje' => 'Guardado recibo numero ' . $new_income_receipt->numero_recibo_ingreso . ' con éxito'];
    }

    public function updateIncomeReceipt(IncomeReceiptRequest $request)
    {
        $income_receipt = ReciboIngreso::find($request->income_receipt_id);
        $income_receipt->update([
            'cliente_recibo_ingreso' => $request->client,
            'id_empleado_tesoreria' => $request->treasury_clerk_id,
            'descripcion_recibo_ingreso' => $request->description,
            'doc_identidad_recibo_ingreso' => $request->document,
            'direccion_cliente_recibo_ingreso' => $request->direction,
            'monto_recibo_ingreso' => $request->total,
            'fecha_mod_recibo_ingreso' => Carbon::now(),
            'usuario_recibo_ingreso' => $request->user()->nick_usuario,
            'ip_recibo_ingreso' => $request->ip(),
        ]);

        foreach ($request->income_detail as $detail) {
            if ($detail['detail_id'] != "" && $detail['deleted'] == false) {
                //Update detail
                $income_detail = DetalleReciboIngreso::find($detail['detail_id']);
                $income_detail->update([
                    'id_concepto_ingreso' => $detail['income_concept_id'],
                    'estado_det_recibo_ingreso' => 1,
                    'monto_det_recibo_ingreso' => $detail['amount'],
                    'fecha_mod_det_recibo_ingreso' => Carbon::now(),
                    'usuario_det_recibo_ingreso' => $request->user()->nick_usuario,
                    'ip_det_recibo_ingreso' => $request->ip()
                ]);
            }

            if ($detail['detail_id'] != "" && $detail['deleted'] == true) {
                $income_detail = DetalleReciboIngreso::find($detail['detail_id']);
                $income_detail->update([
                    'estado_det_recibo_ingreso' => 0,
                    'fecha_mod_det_recibo_ingreso' => Carbon::now(),
                    'usuario_det_recibo_ingreso' => $request->user()->nick_usuario,
                    'ip_det_recibo_ingreso' => $request->ip()
                ]);
            }

            if ($detail['detail_id'] == "" && $detail['deleted'] == false) {
                $exist_detail = DetalleReciboIngreso::where('id_recibo_ingreso', $request->income_receipt_id)
                    ->where('id_concepto_ingreso', $detail['income_concept_id'])
                    ->first();
                if ($exist_detail) {
                    $exist_detail->update([
                        'monto_det_recibo_ingreso' => $detail['amount'],
                        'fecha_mod_det_recibo_ingreso' => Carbon::now(),
                        'usuario_det_recibo_ingreso' => $request->user()->nick_usuario,
                        'ip_det_recibo_ingreso' => $request->ip(),
                        'estado_det_recibo_ingreso' => 1,
                    ]);
                } else {
                    $new_income_detail = new DetalleReciboIngreso([
                        'id_recibo_ingreso' => $request->income_receipt_id,
                        'id_concepto_ingreso' => $detail['income_concept_id'],
                        'monto_det_recibo_ingreso' => $detail['amount'],
                        'estado_det_recibo_ingreso' => 1,
                        'fecha_reg_det_recibo_ingreso' => Carbon::now(),
                        'usuario_det_recibo_ingreso' => $request->user()->nick_usuario,
                        'ip_det_recibo_ingreso' => $request->ip()
                    ]);
                    $new_income_detail->save();
                }
            }
        }
        return ['mensaje' => 'Actualizado recibo numero ' . $income_receipt->numero_recibo_ingreso . ' con éxito.'];
    }
}
