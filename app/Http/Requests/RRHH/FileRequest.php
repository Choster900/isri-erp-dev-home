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
        $idArchivoAnexo = $this->input('idArchivoAnexo');

        return [
            "idTipoArchivoAnexo" => ['required'],
            "idPersona" => ['required'],
            "fileArchivoAnexo" => [empty($idArchivoAnexo) ? 'required' : '', !empty($fileArchivoAnexo) ? 'mimes:jpg,png,jpeg,pdf' : '',/* 'max:2000' */],
        ];
    }

    public function messages()
    {
        return [
            "fileArchivoAnexo.required" => "Por favor, seleccione un archivo.",
            "fileArchivoAnexo.mimes" => "El formato del archivo no es válido. Por favor, use formatos como JPG, PNG, JPEG o PDF.",
            //"fileArchivoAnexo.max" => "El tamaño del archivo no debe exceder los 2MB.",
            "idTipoArchivoAnexo.required" => "El tipo de anexo para este registro es requerido.",
            "idPersona.required" => "La persona para este registro es un dato requerido.",
        ];
    }
}
