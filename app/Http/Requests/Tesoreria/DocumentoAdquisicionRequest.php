<?php

namespace App\Http\Requests\Tesoreria;

use App\Models\DetDocumentoAdquisicion;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DocumentoAdquisicionRequest extends FormRequest
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
        foreach ($this->input('items', []) as $key => $items) {
            $rules["number"] = [Rule::unique('documento_adquisicion','numero_doc_adquisicion')
            ->ignore($this->input('id'), 'id_doc_adquisicion')];
            $rules["items.{$key}.commitment_number"] = [
                function ($attribute, $value, $fail) use ($key, $items) {
                    if (!$items['deleted'] && !empty($value)) {
                        $year = date('Y'); // Obtener el año actual
                        if($items['id']!=''){
                            $db_commitment = DetDocumentoAdquisicion::where('id_det_doc_adquisicion', '!=', $items['id'])
                            ->where('estado_det_doc_adquisicion',1)
                            ->where('compromiso_ppto_det_doc_adquisicion', $items['commitment_number'])
                            ->whereRaw("YEAR(fecha_reg_det_doc_adquisicion) = ?", $year)
                            ->first();
                        }else{
                            $db_commitment = DetDocumentoAdquisicion::where('compromiso_ppto_det_doc_adquisicion', $items['commitment_number'])
                            ->where('estado_det_doc_adquisicion',1)
                            ->whereRaw("YEAR(fecha_reg_det_doc_adquisicion) = ?", $year)
                            ->first();
                        }
                        if ($db_commitment) {
                            $fail("Este compromiso ya fue registrado para el presente año.");
                        }
                    }
                }
            ];
        }
        return $rules;
    }
    public function messages()
    {
        $messages = [];   
        $messages["number.unique"] = "Este numero de documento ya fue registrado";
        return $messages;
    }
}
