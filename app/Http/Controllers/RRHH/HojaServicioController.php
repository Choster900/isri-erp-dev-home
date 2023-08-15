<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\Request;

class HojaServicioController extends Controller
{
    //
    function getEmployees(Request $request)
    {
        return Persona::with([
            'empleado',
            'fotos',
            'profesion',
            'estado_civil',
            'municipio.departamento.pais',
            'nivel_educativo.tipo_nivel_educativo',
            'empleado.acuerdo_laboral.tipo_acuerdo_laboral',
            'empleado.plazas_asignadas.detalle_plaza.plaza',
        ])->whereHas('empleado', function ($query) use ($request) {
            $query->where('codigo_empleado', 'like', '%' . $request["data"] . '%');
        })->orWhere(function ($query) use ($request) {
            $query->where('pnombre_persona', 'like', '%' . $request["data"] . '%')
                  ->orWhere('snombre_persona', 'like', '%' . $request["data"] . '%')
                  ->orWhere('tnombre_persona', 'like', '%' . $request["data"] . '%')
                  ->orWhere('papellido_persona', 'like', '%' . $request["data"] . '%')
                  ->orWhere('sapellido_persona', 'like', '%' . $request["data"] . '%')
                  ->orWhere('tapellido_persona', 'like', '%' . $request["data"] . '%');
        })->whereHas('empleado')->get();
    }
}
