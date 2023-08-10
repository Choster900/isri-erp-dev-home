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
            'empleado.acuerdo_laboral',
            'empleado.acuerdo_laboral.tipo_acuerdo_laboral'
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
        
        

        /* return Persona::with([
            'empleado',
            'empleado.acuerdo_laboral',
            'empleado.acuerdo_laboral.tipo_acuerdo_laboral'
        ])->whereHas('empleado', function ($query) use ($request) {
            $query->where('codigo_empleado', 'like', '%' . $request["data"] . '%');
        })->orWhere(function ($query) use ($request) {
            $nombreCompleto = $request["data"];
            $query->whereRaw("CONCAT(IFNULL(pnombre_persona, ''), ' ', IFNULL(snombre_persona, ''), ' ', IFNULL(tnombre_persona, ''), ' ', IFNULL(papellido_persona, ''), ' ', IFNULL(sapellido_persona, ''), ' ', IFNULL(tapellido_persona, '')) LIKE ?", ["%" . $nombreCompleto . "%"]);
        })->get(); */
    }
}
