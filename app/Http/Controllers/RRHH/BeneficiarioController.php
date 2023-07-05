<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Familiar;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BeneficiarioController extends Controller
{
    //
    public function searchPeopleByName($name)
    {
        return Persona::select(
            'id_persona as value',
            DB::raw("CONCAT(pnombre_persona,' - ',snombre_persona) AS label")
        )->where('pnombre_persona', 'like', '%' . $name . '%')
            ->get();
    }
}