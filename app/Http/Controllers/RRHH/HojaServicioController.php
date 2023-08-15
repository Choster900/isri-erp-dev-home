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
        ])
            ->whereHas('empleado', function ($query) use ($request) {
                $query->where('codigo_empleado', 'like', '%' . $request["data"] . '%');
            })
            ->orWhere(function ($query) use ($request) {
                $query->where('pnombre_persona', 'like', '%' . $request["data"] . '%')
                    ->orWhere('snombre_persona', 'like', '%' . $request["data"] . '%')
                    ->orWhere('tnombre_persona', 'like', '%' . $request["data"] . '%')
                    ->orWhere('papellido_persona', 'like', '%' . $request["data"] . '%')
                    ->orWhere('sapellido_persona', 'like', '%' . $request["data"] . '%')
                    ->orWhere('tapellido_persona', 'like', '%' . $request["data"] . '%');
            })
            ->whereHas('empleado');
        
        if (!$request["data"]) {
            $query->take(7); // Limitar a los últimos 5 registros
        }
        
        $result = $query->get();
        
        return $result;
    }
}
