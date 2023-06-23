<?php

namespace App\Http\Requests\ActivoFijo;

use Illuminate\Foundation\Http\FormRequest;

class BienMuebleYVehiculoRequest extends FormRequest
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

    public function validationData()
    {
        $data = $this->all();

        $pagina1 = $this->input('page1');
        $pagina2 = $this->input('page2');

        if ($pagina1) {
            $data = $this->only(['specific_asset_id', 'condition_id', 'financing_source_id','supplier_id','acquisition_value']);
        } elseif ($pagina2) {
            $data = $this->only(['dependency_id', 'brand_id', 'model_id']);
        }

        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'specific_asset_id' => 'required',
            'condition_id' => 'required',
            'financing_source_id' => 'required',
            'supplier_id' => 'required',
            'acquisition_value' => 'required',
            'dependency_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'specific_asset_id.required' => 'Debe seleccionar bien.',
            'condition_id.unique' => 'Debe seleccionar condicion.',
            'financing_source_id.required' => 'Debe seleccionar fuente financiamiento.',
            'supplier_id.required' => 'Debe seleccionar proveedor.',
            'acquisition_value.required' => 'Debe escribir valor de adquisicion.',
            'dependency_id.required' => 'Debe seleccionar dependencia.',
        ];
    }
}
