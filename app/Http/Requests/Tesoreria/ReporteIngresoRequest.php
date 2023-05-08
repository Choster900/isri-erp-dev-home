<?php

namespace App\Http\Requests\Tesoreria;

use Illuminate\Foundation\Http\FormRequest;

class ReporteIngresoRequest extends FormRequest
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
            'start_date' => ['required','before_or_equal:end_date'],
            'end_date' => ['required'],
        ];
    }

    public function messages(){
        return [
            'start_date.required' => 'Debe seleccionar fecha inicio.',
            'start_date.before_or_equal' => 'Debe ser menor o igual que la fecha final.',
            'end_date.required' => 'Debe seleccionar fecha fin.',
        ];
    }
}
