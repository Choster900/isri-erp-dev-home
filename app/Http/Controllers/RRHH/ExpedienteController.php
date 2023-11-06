<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\TipoArchivoAnexo;
use Illuminate\Http\Request;

class ExpedienteController extends Controller
{
    //Controller for Anexos 

    function getEmployeeExpediente(Request $request)
    {
        $columns = [
            'id_persona', 'id_empleado',
            'id_nivel_educativo', 'id_profesion',
            'id_usuario', 'id_municipio',
            'id_aspirante', 'id_genero',
            'id_estado_civil', 'dui_persona',
            'pnombre_persona', 'snombre_persona',
            'tnombre_persona', 'papellido_persona',
            'sapellido_persona', 'tapellido_persona',
            'fecha_nac_persona', 'nombre_madre_persona',
            'nombre_padre_persona', 'nombre_conyuge_persona',
            'telefono_persona', 'email_persona',
            'peso_persona', 'estatura_persona',
            'observacion_persona', 'estado_persona',
            'fecha_reg_persona', 'fecha_mod_persona',
            'usuario_persona', 'ip_persona',
        ];

        $length = $request->input('length');
        $column = $request->input('column');
        $dir = $request->input('dir');
        $data = $request->input('search');

        // Construir la consulta base con las relaciones
        $query = Persona::select('*')->with(["archivos_anexos"])->whereHas("archivos_anexos")
        ->where("estado_persona", 1)->orderBy($columns[$column], $dir);

        if ($data) {
            $query->where('id_persona', 'like', '%' . $data["id_persona"] . '%');

            /*  $searchNombres = $data["collecNombre"];
            $query->where(function ($query) use ($searchNombres) {
                $query->where('pnombre_persona', 'like', '%' . $searchNombres . '%')
                    ->orWhere('snombre_persona', 'like', '%' . $searchNombres . '%')
                    ->orWhere('tapellido_persona', 'like', '%' . $searchNombres . '%');
            });

            $searchApellidos = $data["collecApellido"];
            $query->where(function ($query) use ($searchApellidos) {
                $query->where('papellido_persona', 'like', '%' . $searchApellidos . '%')
                    ->orWhere('sapellido_persona', 'like', '%' . $searchApellidos . '%')
                    ->orWhere('tapellido_persona', 'like', '%' . $searchApellidos . '%');
            });

            $query->whereHas('familiar', function ($query) use ($data) {
                $query->where('nombre_familiar', 'like', '%' . $data["nombre_familiar"] . '%');
            });

            $query->whereHas('familiar', function ($query) use ($data) {
                $query->where('id_parentesco', 'like', '%' . $data["id_parentesco"] . '%');
            });

            $query->whereHas('familiar', function ($query) use ($data) {
                $query->where('porcentaje_familiar', 'like', '%' . $data["porcentaje_familiar"] . '%');
            }); */
        }

        $beneficiarios = $query->paginate($length)->onEachSide(1);

        return [
            'data' => $beneficiarios,
            'draw' => $request->input('draw'),
        ];
    }
}
