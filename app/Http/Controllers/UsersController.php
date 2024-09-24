<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(string $type)
    {
        // if ($type != "cliente") {
        //     return dd("usrs ad");
        // } else {
        //     return dd(User::where("id_typeuser", 3));
        // }
    }
    public function formUser()
    {
        return view('admin.users.formcreate');
    }
}
