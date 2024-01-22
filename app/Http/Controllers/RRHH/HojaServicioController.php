<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HojaServicioController extends Controller
{
    //
    function getEmployees(Request $request)
    {
        $query = Persona::with([
            'empleado',
            'fotos' => function ($query) {
                $query->where('estado_foto', 1);
            },
            'profesion',
            'estado_civil',
            'municipio.departamento.pais',
            'nivel_educativo.tipo_nivel_educativo',
            'empleado.acuerdo_laboral' => function ($query) {
                $query->where('estado_acuerdo_laboral', 1);
                $query->orderBy('fecha_reg_acuerdo_laboral', 'desc');
            },
            'empleado.acuerdo_laboral.tipo_acuerdo_laboral',
            'empleado.plazas_asignadas.detalle_plaza.plaza',
            'empleado.plazas_asignadas.dependencia',
            'empleado.evaluaciones_personal.detalle_evaluaciones_personal',
            'empleado.evaluaciones_personal.periodo_evaluacion',
            'empleado.evaluaciones_personal.tipo_evaluacion_personal',
            'empleado.evaluaciones_personal.plaza_evaluada.plaza_asignada.detalle_plaza.plaza',
        ]);
        /* ->whereHas('empleado', function ($query) use ($request) {
                $query->where('codigo_empleado', 'like', '%' . $request["data"] . '%');
            }); */
        if (!empty($request["data"])) {
            $query->orWhere(function ($query) use ($request) {
                $query->whereRaw("MATCH ( pnombre_persona,
                     snombre_persona,
                      tnombre_persona,
                      papellido_persona,
                       sapellido_persona,
                        tapellido_persona )
                         AGAINST ( '" . $request['data'] . "')");
            }); // Limitar a los últimos 5 registros
        }


        $query->whereHas('empleado');

        if (!$request["data"]) {
            $query->take(10); // Limitar a los últimos 5 registros
        }

        $result = $query->get();

        return $result;
    }

    function getMyOwnInformation(Request $request)
    {
        $query = Persona::with([
            'empleado',
            'fotos' => function ($query) {
                $query->where('estado_foto', 1);
            },
            'profesion',
            'estado_civil',
            'municipio.departamento.pais',
            'nivel_educativo.tipo_nivel_educativo',
            'empleado.acuerdo_laboral' => function ($query) {
                $query->where('estado_acuerdo_laboral', 1);
                $query->orderBy('fecha_reg_acuerdo_laboral', 'desc');
            },
            'empleado.acuerdo_laboral.tipo_acuerdo_laboral',
            'empleado.plazas_asignadas.detalle_plaza.plaza',
            'empleado.plazas_asignadas.dependencia',
            'empleado.evaluaciones_personal',
        ]);
        $id = $request->user()->id_usuario;
        $query->where('id_usuario', $id);

        $result = $query->get();

        return $result;
    }
}
