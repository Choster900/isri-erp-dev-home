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
            'empleado.acuerdo_laboral.tipo_acuerdo_laboral',
            'empleado.plazas_asignadas.detalle_plaza.plaza',
            'empleado.plazas_asignadas.dependencia',
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
            $query->take(7); // Limitar a los últimos 5 registros
        }

        $result = $query->get();

        return $result;
    }
}
