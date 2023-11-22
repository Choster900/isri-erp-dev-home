<?php

namespace App\Http\Requests\RRHH;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $fileArchivoAnexo = $this->input('fileArchivoAnexo');

        return [
            "fileArchivoAnexo" => ['required', empty($fileArchivoAnexo) ? 'mimes:jpg,png,jpeg,pdf' : '', 'max:2000'],
            "idTipoArchivoAnexo" => ['required'/* ,'mimes:jpg,png,jpeg,pdf','max:2000' */],
            "idPersona" => ['required'/* ,'mimes:jpg,png,jpeg,pdf','max:2000' */]
        ];
    }

    public function messages()
    {
        return [
            "fileArchivoAnexo.required" => "Tiene que seleccionar una archivo",
            "fileArchivoAnexo.mimes" => "La imagen no es del formato adecuado",
            "fileArchivoAnexo.max" => "La imagen no debe execer los 2MB",
            "idTipoArchivoAnexo.required" => "El tipo de anexo para esta registro es requerido",
            "idPersona.required" => "La persona para este registro es un dato requerido",
        ];
    }
}
