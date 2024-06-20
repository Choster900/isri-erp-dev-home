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
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class ReciboIngresoController extends Controller
{
    public function getRecibosIngreso(Request $request)
    {
        $columns = ['numero_recibo_ingreso', 'fecha_recibo_ingreso', 'cliente_recibo_ingreso', 'descripcion_recibo_ingreso', 'codigo_ccta_presupuestal', 'monto_recibo_ingreso', 'estado_recibo_ingreso'];

        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $search_value = $request->input('search');

        $query = ReciboIngreso::select('*')
            ->with([
                'detalles',
                'detalles.concepto_ingreso.dependencia',
                'cuenta_presupuestal',
                'proyecto_financiado',
                'empleado_tesoreria'
            ]);

        if ($column == -1) {
            $query->orderBy('id_recibo_ingreso', 'desc');
        } else {
            if($column == 4) {
                $query->orderByRaw('(SELECT codigo_ccta_presupuestal FROM catalogo_cta_presupuestal WHERE catalogo_cta_presupuestal.id_ccta_presupuestal = recibo_ingreso.id_ccta_presupuestal) ' . $dir);
            }else{
                $query->orderBy($columns[$column], $dir);
            }
        }


        if ($search_value) {
            $query->where('numero_recibo_ingreso', 'like', '%' . $search_value['numero_recibo_ingreso'] . '%')
                ->where('fecha_recibo_ingreso', 'like', '%' . $search_value['fecha_recibo_ingreso'] . '%')
                ->where('cliente_recibo_ingreso', 'like', '%' . $search_value['cliente_recibo_ingreso'] . '%')
                ->where('monto_recibo_ingreso', 'like', '%' . $search_value['monto_recibo_ingreso'] . '%')
                ->where('descripcion_recibo_ingreso', 'like', '%' . $search_value['descripcion_recibo_ingreso'] . '%')
                ->where('estado_recibo_ingreso', 'like', '%' . $search_value['estado_recibo_ingreso'] . '%');

            //Search by document type
            if ($search_value['codigo_ccta_presupuestal']) {
                $query->whereHas(
                    'cuenta_presupuestal',
                    function ($query) use ($search_value) {
                        if ($search_value["codigo_ccta_presupuestal"] != '') {
                            $query->where('codigo_ccta_presupuestal', 'like', '%' . $search_value['codigo_ccta_presupuestal'] . '%');
                        }
                    }
                );
            }
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
        $recibo_ingreso = ReciboIngreso::find($request->id);
        if ($recibo_ingreso->estado_recibo_ingreso == 1) {
            if ($request->status == 1) {
                $recibo_ingreso->update([
                    'estado_recibo_ingreso' => 0,
                    'fecha_mod_recibo_ingreso' => Carbon::now(),
                    'usuario_recibo_ingreso' => $request->user()->nick_usuario,
                    'ip_recibo_ingreso' => $request->ip(),
                ]);
                return ['message' => 'Recibo de ingreso ' . $recibo_ingreso->numero_recibo_ingreso . ' ha sido desactivado con exito'];
            } else {
                return ['message' => 'El recibo de ingreso seleccionado ya ha sido activado por otro usuario'];
            }
        } else {
            if ($recibo_ingreso->estado_concepto_ingreso == 0) {
                if ($request->status == 0) {
                    $recibo_ingreso->update([
                        'estado_recibo_ingreso' => 1,
                        'fecha_mod_recibo_ingreso' => Carbon::now(),
                        'usuario_recibo_ingreso' => $request->user()->nick_usuario,
                        'ip_recibo_ingreso' => $request->ip(),
                    ]);
                    return ['message' => 'Recibo de ingreso ' . $recibo_ingreso->numero_recibo_ingreso . ' ha sido activado con exito'];
                } else {
                    return ['message' => 'El recibo de ingreso seleccionado ya ha sido desactivado por otro usuario'];
                }
            }
        }
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
        $income_concept_select = ConceptoIngreso::selectRaw("concepto_ingreso.id_concepto_ingreso as value , concat(coalesce(concat(centro_atencion.codigo_centro_atencion,' - '), ''), concepto_ingreso.nombre_concepto_ingreso) as label, concepto_ingreso.estado_concepto_ingreso as estado")
            ->leftJoin('centro_atencion', function ($join) {
                $join->on('concepto_ingreso.id_centro_atencion', '=', 'centro_atencion.id_centro_atencion');
            })
            ->where('concepto_ingreso.estado_concepto_ingreso', '=', 1)
            ->where('concepto_ingreso.id_proy_financiado', '=', $request->financing_source_id)
            ->where('concepto_ingreso.id_ccta_presupuestal', '=', $request->budget_account_id)
            ->get();
        return ['income_concept_select' => $income_concept_select];
    }

    public function saveIncomeReceipt(IncomeReceiptRequest $request)
    {
        DB::beginTransaction();
        try {
            $new_income_receipt = new ReciboIngreso([
                'id_ccta_presupuestal' => $request->budget_account_id,
                'id_proy_financiado' => $request->financing_source_id,
                'id_empleado_tesoreria' => $request->treasury_clerk_id,
                'cliente_recibo_ingreso' => $request->client,
                'descripcion_recibo_ingreso' => $request->description,
                'doc_identidad_recibo_ingreso' => $request->document,
                'direccion_cliente_recibo_ingreso' => $request->direction,
                'numero_recibo_ingreso' => $request->number,
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

            DB::commit(); // Confirma las operaciones en la base de datos
            return response()->json([
                'message'          => 'Guardado recibo numero ' . $new_income_receipt->numero_recibo_ingreso . ' con éxito',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack(); // En caso de error, revierte las operaciones anteriores
            return response()->json([
                'logical_error' => 'Ha ocurrido un error con sus datos.',
                'error' => $th->getMessage(),
            ], 422);
        }
    }

    public function updateIncomeReceipt(IncomeReceiptRequest $request)
    {
        $income_receipt = ReciboIngreso::find($request->income_receipt_id);
        if ($income_receipt->estado_recibo_ingreso == 0) {
            return response()->json(['logical_error' => 'Error, el recibo de ingreso seleccionado ha sido desactivado por otro usuario.'], 422);
        } else {
            DB::beginTransaction();
            try {
                $income_receipt->update([
                    'id_ccta_presupuestal' => $request->budget_account_id,
                    'id_proy_financiado' => $request->financing_source_id,
                    'cliente_recibo_ingreso' => $request->client,
                    'id_empleado_tesoreria' => $request->treasury_clerk_id,
                    'descripcion_recibo_ingreso' => $request->description,
                    'doc_identidad_recibo_ingreso' => $request->document,
                    'direccion_cliente_recibo_ingreso' => $request->direction,
                    'numero_recibo_ingreso' => $request->number,
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

                DB::commit(); // Confirma las operaciones en la base de datos
                return response()->json([
                    'message'          => 'Actualizado recibo numero ' . $income_receipt->numero_recibo_ingreso . ' con éxito.',
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

    //New function for Composition API
    public function getInfoModalReciboIngreso(Request $request, $id)
    {
        $receipt = ReciboIngreso::with([
            'detalles.concepto_ingreso.centro_atencion',
            'cuenta_presupuestal',
            'proyecto_financiado',
            'empleado_tesoreria'
        ])->find($id);

        $budget_accounts = CuentaPresupuestal::selectRaw("id_ccta_presupuestal as value , concat(codigo_ccta_presupuestal, ' - ', nombre_ccta_presupuestal) as label")
            ->where('tesoreria_ccta_presupuestal', '=', 1)
            ->where('estado_ccta_presupuestal', '=', 1)
            ->whereHas('conceptos_ingreso', function ($query) use ($receipt) {
                if ($receipt) {
                    // Si es modo edición, incluir conceptos de ingreso inactivos
                    $query->where('estado_concepto_ingreso', 1)
                        ->orWhere('id_ccta_presupuestal', $receipt->id_ccta_presupuestal);
                } else {
                    // Si es modo creación, solo incluir conceptos de ingreso activos
                    $query->where('estado_concepto_ingreso', 1);
                }
            })
            ->orderBy('nombre_ccta_presupuestal')
            ->get();

        $treasury_clerk = DB::table('empleado_tesoreria')
            ->select('id_empleado_tesoreria as value', 'nombre_empleado_tesoreria as label')
            ->get();

        $financing_sources = [];
        $income_concept_select = [];

        if ($receipt) {
            $financing_sources = ProyectoFinanciado::selectRaw('id_proy_financiado as value, nombre_proy_financiado as label')
                ->whereHas('conceptos_ingreso', function ($query) use ($receipt) {
                    $query->where('id_ccta_presupuestal', $receipt->id_ccta_presupuestal)
                        ->where('estado_concepto_ingreso', 1);
                })
                ->get();

            $income_concept_select = DB::table('concepto_ingreso as ci')
                ->leftJoin('centro_atencion as ca', 'ci.id_centro_atencion', '=', 'ca.id_centro_atencion')
                ->select(
                    'ci.id_concepto_ingreso as value',
                    DB::raw("CONCAT(COALESCE(CONCAT(ca.codigo_centro_atencion, ' - '), ''), ci.nombre_concepto_ingreso) AS label"),
                    'ci.estado_concepto_ingreso as estado'
                )
                ->where(function ($query) use ($receipt) {
                    $query->where('ci.id_proy_financiado', $receipt->id_proy_financiado)
                        ->where('ci.id_ccta_presupuestal', $receipt->id_ccta_presupuestal)
                        ->where('ci.estado_concepto_ingreso', 1);
                })
                ->orWhere(function ($query) use ($receipt) {
                    $query->whereIn('ci.id_concepto_ingreso', function ($subquery) use ($receipt) {
                        $subquery->select('id_concepto_ingreso')
                            ->from('detalle_recibo_ingreso')
                            ->where('id_recibo_ingreso', $receipt->id_recibo_ingreso);
                    });
                })
                ->get();
        }

        return response()->json([
            'receipt'                   => $receipt ?? [],
            'financing_sources'         => $financing_sources,
            'income_concept_select'     => $income_concept_select,
            'budget_accounts'           => $budget_accounts,
            'treasury_clerk'            => $treasury_clerk
        ]);
    }

    public function getInfoModalReciboFormat(Request $request, $id)
    {
        $receipt = ReciboIngreso::with([
            'detalles.concepto_ingreso.centro_atencion',
            'cuenta_presupuestal',
            'proyecto_financiado',
            'empleado_tesoreria'
        ])->find($id);

        if (!$receipt) {
            return response()->json(['error' => 'Recibo no encontrado'], 404);
        }

        $numeroLetras = new NumeroALetras();
        $monto_letras = $numeroLetras->toInvoice($receipt['monto_recibo_ingreso'], 2, 'DÓLARES');

        $receipt->monto_letras = $monto_letras;

        return response()->json([
            'receipt'                   => $receipt ?? [],
        ]);
    }
}
