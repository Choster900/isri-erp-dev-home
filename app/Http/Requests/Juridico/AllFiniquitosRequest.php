<?php

namespace App\Http\Requests\Juridico;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class AllFiniquitosRequest extends FormRequest
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
        $this->reformatTimes();

        return [
            'amount' => 'required|numeric|min:1',
            'personId' => 'required',

            'centers.*.date' => 'required',
            'centers.*.startTime' => 'required',
            'centers.*.endTime' => 'required|after:centers.*.startTime',
            'centers.*.interval' => 'required|integer|min:1',

        ];
    }

    public function messages()
    {
        return [
            'amount.min' => 'El monto debe ser mayor a cero.',
            'amount.required' => 'El monto del finiquito es obligatorio.',
            'personId.required' => 'Debe seleccionar el notario a cargo.',

            'centers.*.date.required' => 'La fecha es obligatoria.',
            'centers.*.startTime.required' => 'La hora de inicio es obligatoria.',
            'centers.*.endTime.required' => 'La hora de fin es obligatoria.',
            'centers.*.endTime.after' => 'La hora fin debe ser mayor a la hora de inicio.',
            'centers.*.interval.required' => 'El intervalo es obligatorio.',
            'centers.*.interval.min' => 'El intervalo debe ser al menos 1.',
        ];
    }

    protected function reformatTimes()
    {
        $centers = $this->input('centers', []);

        foreach ($centers as &$center) {
            $center['startTime'] = $this->formatTime($center['startTime']);
            $center['endTime'] = $this->formatTime($center['endTime']);
            // Si también necesitas reformatear 'endTime', haz lo mismo aquí
        }

        $this->merge(['centers' => $centers]);
    }

    protected function formatTime($time)
    {
        $hours = $time['hours'] ?? "";
        $minutes = $time['minutes'] ?? "";
        $seconds = $time['seconds'] ?? "";

        $formattedTime = "";

        if ($hours != "" && $minutes != "" && $seconds != "") {
            // Aquí realizas el formateo del objeto de tiempo
            $formattedTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return $formattedTime;
    }
}
