<?php

namespace App\Http\Controllers\RRHH;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RRHH\DetallePlazaRequest;
use App\Models\ActividadInstitucional;
use App\Models\DetallePlaza;
use App\Models\LineaTrabajo;
use App\Models\Plaza;
use App\Models\ProyectoFinanciado;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DetallePlazaController extends Controller
{
    public function getDetJobPositions(Request $request)
    {
        $columns = [
            'codigo_det_plaza',
            'nombre_plaza',
            'id_estado_plaza',
            'codigo_dependencia',
            'nombre_empleado',
            'estado_det_plaza'
        ];

        $length = $request->length;
        $column = $request->column; //Index
        $dir = $request->dir;
        $search_value = $request->search;

        $query = DetallePlaza::select('*')
            ->with([
                'plaza_asignada_activa.empleado.persona',
                'plaza',
                'plaza_asignada_activa.dependencia',
                'actividad_institucional.linea_trabajo'
            ]);

        if ($column == -1) {
            $query->orderBy('id_det_plaza', 'desc');
        } else {
            if ($column == 1) {
                $query->orderByRaw('(SELECT nombre_plaza FROM plaza WHERE plaza.id_plaza = detalle_plaza.id_plaza) ' . $dir);
            } else {
                if ($column == 3) {
                    $query->orderByRaw('(SELECT codigo_dependencia FROM dependencia WHERE dependencia.id_dependencia = (SELECT id_dependencia FROM plaza_asignada WHERE plaza_asignada.id_det_plaza = detalle_plaza.id_det_plaza) ) ' . $dir);
                } else {
                    if ($column == 4) {
                        $query->orderByRaw('
                        (SELECT pnombre_persona FROM persona WHERE persona.id_persona = 
                            (SELECT id_persona FROM empleado WHERE empleado.id_empleado = 
                                (SELECT id_empleado FROM plaza_asignada WHERE plaza_asignada.id_det_plaza = detalle_plaza.id_det_plaza)
                                    ) ) ' . $dir);
                    } else {
                        $query->orderBy($columns[$column], $dir);
                    }
                }
            }
        }

        if ($search_value) {
            $query->where('codigo_det_plaza', 'like', '%' . $search_value['codigo_det_plaza'] . '%')
                ->where('estado_det_plaza', 'like', '%' . $search_value['estado_det_plaza'] . '%')
                ->where('id_estado_plaza', 'like', '%' . $search_value["id_estado_plaza"] . '%')
                ->where(function ($query) use ($search_value) {
                    $query->whereHas('plaza', function ($query) use ($search_value) {
                        $query->where('nombre_plaza', 'like', '%' . $search_value["nombre_plaza"] . '%');
                    });
                    if ($search_value["codigo_dependencia"]) {
                        $query->whereHas('plazas_asignadas.dependencia', function ($query) use ($search_value) {
                            $query->where('codigo_dependencia', 'like', '%' . $search_value["codigo_dependencia"] . '%');
                        });
                    }
                    if ($search_value["nombre_empleado"]) {
                        $query->whereHas(
                            'plaza_asignada_activa.empleado.persona',
                            function ($query) use ($search_value) {
                                $query->where('pnombre_persona', 'like', '%' . $search_value["nombre_empleado"] . '%')
                                    ->orWhere('snombre_persona', 'like', '%' . $search_value["nombre_empleado"] . '%')
                                    ->orWhere('tnombre_persona', 'like', '%' . $search_value["nombre_empleado"] . '%')
                                    ->orWhere('papellido_persona', 'like', '%' . $search_value["nombre_empleado"] . '%')
                                    ->orWhere('sapellido_persona', 'like', '%' . $search_value["nombre_empleado"] . '%')
                                    ->orWhere('tapellido_persona', 'like', '%' . $search_value["nombre_empleado"] . '%');
                            }
                        );
                    }
                });
        }

        $jodPositions = $query->paginate($length)->onEachSide(1);
        return ['data' => $jodPositions, 'draw' => $request->input('draw')];
    }

    public function getSelectsJobPositionDet(Request $request)
    {
        $uplt = LineaTrabajo::selectRaw("id_lt as value, concat(codigo_up_lt,' - ',nombre_lt) as label")
            ->where('plaza_lt', 1)
            ->get();
        $financingSources = ProyectoFinanciado::select('id_proy_financiado as value', 'nombre_proy_financiado as label')
            ->where('estado_proy_financiado', 1)
            ->get();
        $jobPositionStatus = DB::table('estado_plaza')
            ->select('id_estado_plaza as value', 'nombre_estado_plaza as label')
            ->get();
        $jodPositions = Plaza::select('id_plaza as value', 'nombre_plaza as label')
            ->get();
        $contractTypes = DB::table('tipo_contrato')
            ->selectRaw("id_tipo_contrato as value, concat(codigo_tipo_contrato,' - ',nombre_tipo_contrato) as label")
            ->get();
        $activities = ActividadInstitucional::selectRaw("id_actividad_institucional as value, 
        concat(codigo_actividad_institucional,' - ',nombre_actividad_institucional) as label,id_lt")
            ->where('estado_actividad_institucional', 1)
            ->get();


        return [
            "uplt"                  => $uplt,
            "financingSources"      => $financingSources,
            "jobPositionStatus"     => $jobPositionStatus,
            "jobPositions"          => $jodPositions,
            "contractTypes"         => $contractTypes,
            "activities"            => $activities
        ];
    }

    public function storeJobPositionDet(DetallePlazaRequest $request)
    {
        // Find the latest job_position_det record related to the given id_job_position
        $latestJobPositionDet = DetallePlaza::where('id_plaza', $request->id_plaza)
            ->latest('codigo_det_plaza')
            ->first();
        // Initialize the correlative code as 1 if no previous job_position_det is found
        $correlative = 1;
        // If a previous job_position_det exists, extract and increment the correlative part
        if ($latestJobPositionDet) {
            $latestCorrelative = (int)substr($latestJobPositionDet->codigo_det_plaza, -4);
            $correlative = $latestCorrelative + 1;
        }
        // Format the correlative code with leading zeros (4 digits)
        $formattedCorrelative = str_pad($correlative, 4, '0', STR_PAD_LEFT);
        // Combine the id_job_position and formatted correlative to get the final code
        $JobPositionDetCode = $request->id_plaza . '-' . $formattedCorrelative;

        $jobPositionDet = new DetallePlaza([
            'id_proy_financiado'            => $request->id_proy_financiado,
            'id_tipo_contrato'              => $request->id_tipo_contrato,
            'id_actividad_institucional'    => $request->id_actividad_institucional,
            'id_plaza'                      => $request->id_plaza,
            'id_estado_plaza'               => $request->id_estado_plaza,
            'codigo_det_plaza'              => $JobPositionDetCode,
            'estado_det_plaza'              => 1,
            'fecha_reg_det_plaza'           => Carbon::now(),
            'usuario_det_plaza'             => $request->user()->nick_usuario,
            'ip_det_plaza'                  => $request->ip(),
        ]);
        $jobPositionDet->save();

        return response()->json(['mensaje' => 'Plaza guardada con exito']);
    }

    public function updateJobPositionDet(DetallePlazaRequest $request)
    {
        $jobPositionDet = DetallePlaza::findOrFail($request->id_det_plaza);
        if ($jobPositionDet->estado_det_plaza == 0) {
            return response()->json(['logical_error' => 'Error, la plaza seleccionada ha sida desactivada por otro usuario.'], 422);
        } else {
            $data = [
                'id_proy_financiado' => $request->id_proy_financiado,
                'id_tipo_contrato' => $request->id_tipo_contrato,
                'id_actividad_institucional' => $request->id_actividad_institucional,
                'id_plaza' => $request->id_plaza,
                'fecha_mod_det_plaza' => Carbon::now(),
                'usuario_det_plaza' => $request->user()->nick_usuario,
                'ip_det_plaza' => $request->ip(),
            ];
            $jobPositionDet->update($data);

            return response()->json(['mensaje' => 'Plaza actualizada con éxito']);
        }
    }

    public function changeStatusJobPositionDet(Request $request)
    {
        $servicio = DetallePlaza::find($request->id);
        if ($servicio->estado_det_plaza == 1) {
            if ($request->status == 1) {
                $servicio->update([
                    'estado_det_plaza' => 0,
                    'fecha_mod_det_plaza' => Carbon::now(),
                    'usuario_det_plaza' => $request->user()->nick_usuario,
                    'ip_det_plaza' => $request->ip(),
                ]);
                return ['mensaje' => 'Plaza codigo ' . $servicio->codigo_det_plaza . ' ha sido desactivada con exito'];
            } else {
                return ['mensaje' => 'La plaza seleccionada ya ha sido activada por otro usuario'];
            }
        } else {
            if ($servicio->estado_det_plaza == 0) {
                if ($request->status == 0) {
                    $servicio->update([
                        'estado_det_plaza' => 1,
                        'fecha_mod_det_plaza' => Carbon::now(),
                        'usuario_det_plaza' => $request->user()->nick_usuario,
                        'ip_det_plaza' => $request->ip(),
                    ]);
                    return ['mensaje' => 'Plaza codigo ' . $servicio->codigo_det_plaza . ' ha sido activada con exito'];
                } else {
                    return ['mensaje' => 'La plaza seleccionada ya ha sido desactivada por otro usuario'];
                }
            }
        }
    }
}
