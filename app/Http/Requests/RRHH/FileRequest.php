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
        return [
            "fileArchivoAnexo" => 'required|mimes:jpg,png,jpeg|max:2000' 
        ];
    }

    public function messages()
    {
        return [
            "image.required" => "Tiene que seleccionar una archivo",
            "image.mimes" => "La imagen no es del formato adecuado",
            "image.max" => "La imagen no debe execer los 2MB",
        ];
    }
}
