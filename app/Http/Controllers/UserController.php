<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\User;

class UserController extends Controller
{
    public function viewList(){
        $users = User::select('id_usuario','nick_usuario','estado_usuario');
        //dd($users);
        return datatables($users)->make(true);
    }
}
