<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {

        $columns = ['id_usuario', 'nick_usuario', 'estado_usuario'];

        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $searchValue = $request->input('search');

        $query = User::select('id_usuario', 'nick_usuario', 'estado_usuario')->orderBy($columns[$column], $dir);

        if ($searchValue) {
            $query->where(function($query) use ($searchValue) {
                $query->where('nick_usuario', 'like', '%' . $searchValue . '%')
                ->orWhere('id_usuario', 'like', '%' . $searchValue . '%');
            });
        }

        $users = $query->paginate($length)->onEachSide(1);
        return ['data' => $users, 'draw' => $request->input('draw')];
    }
}
