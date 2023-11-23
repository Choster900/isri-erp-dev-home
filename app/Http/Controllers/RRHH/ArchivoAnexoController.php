<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Http\Requests\RRHH\FileRequest;
use App\Models\ArchivoAnexo;
use App\Models\Persona;
use App\Models\TipoArchivoAnexo;
use App\Models\TipoMine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArchivoAnexoController extends Controller
{
    //Controller for Anexos 

    function getEmployeeExpediente(Request $request)
    {
        $columns = [
            'id_persona',
            'id_empleado',
            'id_aspirante',
            'id_genero',
            'id_usuario',
            'id_municipio',
            'usuario_persona',
            'ip_persona',
            'id_estado_civil',
            'dui_persona',
            'peso_persona',
            'estatura_persona',
            'telefono_persona',
            'email_persona',
            'id_nivel_educativo',
            'id_profesion',
            'pnombre_persona',
            'snombre_persona',
            'tnombre_persona',
            'papellido_persona',
            'observacion_persona',
            'estado_persona',
            'sapellido_persona',
            'tapellido_persona',
            'fecha_reg_persona',
            'fecha_mod_persona',
            'fecha_nac_persona',
            'nombre_madre_persona',
            'nombre_padre_persona',
            'nombre_conyuge_persona',
        ];

        $length = $request->input('length');
        $column = $request->input('column');
        $dir = $request->input('dir');
        $data = $request->input('search');


        // Construir la consulta base con las relaciones
        $query = Persona::select('*')->with([
            "archivo_anexo" => function ($query) {
                $query->where('estado_archivo_anexo', 1);
            },
            "archivo_anexo.tipo_archivo_anexo",
            "empleado",
            "profesion",
            "residencias",
            "estado_civil",
            "nivel_educativo",
            "familiar.parentesco",
            "municipio.departamento.pais",
            "empleado.plazas_asignadas.detalle_plaza.plaza",
        ])->whereHas("archivo_anexo")
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

    /**
     * Creates a new ArchivoAnexo from the uploaded file.
     *
     * @param  FileRequest $request
     * @return ArchivoAnexo|null
     */
    function createArchivoAnexo(FileRequest $request)
    {
        try {
            // Begin a database transaction
            DB::beginTransaction();
            $imageUrl = null;
            $name = '';

            if ($request->hasFile("fileArchivoAnexo")) {
                // Retrieve the uploaded file
                $file = $request->file("fileArchivoAnexo");
                // Get the original file name
                $name = $file->getClientOriginalName();
                // Store the file in the 'anexos' directory within the 'public' disk
                $file->storeAs('anexos', $name, 'public');
                // Generate a URL for the stored file
                $imageUrl = Storage::url('anexos/' . $name);
            }
            //$idTipoMime = TipoMine::where('extension_tipo_mime', $request->idTipoMine)->value('id_tipo_mime');

            // Create a new ArchivoAnexo record in the database
            $response = ArchivoAnexo::create([
                'estado_archivo_anexo'      => 1,
                'nombre_archivo_anexo'      => $name,
                'url_archivo_anexo'         => $imageUrl,
                'id_persona'                => $request->idPersona,
                'ip_archivo_anexo'          => $request->ip(),
                'id_tipo_mime'              => $request->idTipoMine,
                'fecha_reg_archivo_anexo'   => Carbon::now(),
                'peso_archivo_anexo'        => $request->sizeArchivoAnexo,
                'id_tipo_archivo_anexo'     => $request->idTipoArchivoAnexo,
                'usuario_archivo_anexo'     => $request->user()->nick_usuario,
                'descripcion_archivo_anexo' => $request->descripcionArchivoAnexo,
            ]);
            // Commit the transaction
            DB::commit();

            // Return the created ArchivoAnexo
            return $response;
        } catch (\Throwable $th) {
            // An error occurred, rollback the transaction
            DB::rollback();

            // Return an error response
            return response()->json($th->getMessage(), 500);
        }
    }


    function modifiedArchivoAnexo(FileRequest $request)
    {
        try {
            // Begin a database transaction
            DB::beginTransaction();

            // Check if a file is present in the request
            if ($request->hasFile("fileArchivoAnexo")) {
                // Retrieve the uploaded file
                $file = $request->file("fileArchivoAnexo");

                // Get the original file name
                $name = $file->getClientOriginalName();

                // Store the file in the 'anexos' directory within the 'public' disk
                $path = $file->storeAs('anexos', $name, 'public');

                // Generate a URL for the stored file
                $imageUrl = Storage::url('anexos/' . $name);

                $sizeFile = $request->sizeArchivoAnexo;
                $tipoMine = $request->idTipoMine;
            } else {
                // No new file uploaded, retain existing file information
                $existingArchivoAnexo = ArchivoAnexo::find($request->idArchivoAnexo);
                $name = $existingArchivoAnexo->nombre_archivo_anexo;
                $sizeFile = $existingArchivoAnexo->peso_archivo_anexo;
                $tipoMine = $existingArchivoAnexo->id_tipo_mime;
                $imageUrl = $existingArchivoAnexo->url_archivo_anexo;
            }

            // Create a new ArchivoAnexo record in the database
            $response = ArchivoAnexo::where("id_archivo_anexo", $request->idArchivoAnexo)->update([
                'estado_archivo_anexo'      => 1,
                'id_tipo_mime'              => $tipoMine,
                'nombre_archivo_anexo'      => $name,
                'url_archivo_anexo'         => $imageUrl,
                'peso_archivo_anexo'        => $sizeFile,
                'id_persona'                => $request->idPersona,
                'ip_archivo_anexo'          => $request->ip(),
                'fecha_mod_archivo_anexo'   => Carbon::now(),
                'id_tipo_archivo_anexo'     => $request->idTipoArchivoAnexo,
                'usuario_archivo_anexo'     => $request->user()->nick_usuario,
                'descripcion_archivo_anexo' => $request->descripcionArchivoAnexo,
            ]);

            // Commit the transaction
            DB::commit();

            // Return the updated ArchivoAnexo
            return $response;
        } catch (\Throwable $th) {
            // An error occurred, rollback the transaction
            DB::rollback();

            // Return an error response
            return response()->json($th->getMessage(), 500);
        }
    }

    public function update(Request $request) //Metodo utilizado al momento de seleccionar proveedor y hacer los calculos 
    { //se hacen esta peticion por que al no existir el quedan no existen registos previos de la base de datos y no podemos ver a que provedor pertenece
        return ArchivoAnexo::where("id_quedan", $request->idArchivoAnexo)->update(["estado_archivo_anexo" => 0, "fecha_mod_archivo_anexo" => Carbon::now()]);
    }
}
