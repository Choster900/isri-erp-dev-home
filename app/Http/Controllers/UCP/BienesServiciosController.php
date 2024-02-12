<?php

namespace App\Http\Controllers\UCP;

use App\Http\Controllers\Controller;
use App\Models\LineaTrabajo;
use Illuminate\Http\Request;

class BienesServiciosController extends Controller
{
    //Controlador de biene y servicios tabla: produccto_adquisicion


    function getAllLineaTrabajo() : Object {


        return LineaTrabajo::all();
    }

}
