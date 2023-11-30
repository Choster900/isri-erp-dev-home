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
            'id_genero',
            'id_persona',
            'id_usuario',
            'ip_persona',
            'id_empleado',
            'dui_persona',
            'id_aspirante',
            'id_municipio',
            'peso_persona',
            'id_profesion',
            'email_persona',
            'estado_persona',
            'usuario_persona',
            'id_estado_civil',
            'pnombre_persona',
            'snombre_persona',
            'tnombre_persona',
            'estatura_persona',
            'telefono_persona',
            'papellido_persona',
            'sapellido_persona',
            'tapellido_persona',
            'fecha_reg_persona',
            'fecha_mod_persona',
            'fecha_nac_persona',
            'id_nivel_educativo',
            'observacion_persona',
            'nombre_madre_persona',
            'nombre_padre_persona',
            'nombre_conyuge_persona',
        ];

        $dir = $request->input('dir');
        $data = $request->input('search');
        $length = $request->input('length');
        $column = $request->input('column');


        // Construir la consulta base con las relaciones
        $query = Persona::select('*')->with([

            "archivo_anexo.tipo_archivo_anexo",
            "empleado",
            "profesion",
            "residencias",
            "estado_civil",
            "nivel_educativo",
            "familiar.parentesco",
            "municipio.departamento.pais",
            "archivo_anexo" => function ($query) {
                $query->where('estado_archivo_anexo', 1);
            },
            "empleado.plazas_asignadas.detalle_plaza.plaza",
        ])->whereHas("archivo_anexo")
            ->where("estado_persona", 1)->orderBy($columns[$column], $dir);

        if ($data) {

            if (isset($data['id_persona'])) {
                $query->where('id_persona', 'like', '%' . $data["id_persona"] . '%');
            }
            if (isset($data['dui_persona'])) {
                $query->where('dui_persona', 'like', '%' . $data["dui_persona"] . '%');
            }

            $searchNombres = $data["collecNombre"];
            if (isset($searchNombres)) {
                $query->whereRaw("MATCH ( pnombre_persona, snombre_persona, tnombre_persona ) AGAINST ( '" . $searchNombres . "')");
            }

            $searchApellidos = $data["collecApellido"];
            if (isset($searchApellidos)) {
                $query->whereRaw("MATCH ( papellido_persona, sapellido_persona, tapellido_persona ) AGAINST ( '" . $searchApellidos . "')");
            }

            if (isset($data['nombre_profesion'])) {
                $prefesion = $data["nombre_profesion"];
                $query->whereHas('profesion', function ($query) use ($prefesion) {
                    $query->where('nombre_profesion', 'like', '%' . $prefesion . '%');
                });
            }
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
                'nombre_archivo_anexo'      => $name,
                'id_tipo_mime'              => $tipoMine,
                'url_archivo_anexo'         => $imageUrl,
                'peso_archivo_anexo'        => $sizeFile,
                'fecha_mod_archivo_anexo'   => Carbon::now(),
                'ip_archivo_anexo'          => $request->ip(),
                'id_persona'                => $request->idPersona,
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
