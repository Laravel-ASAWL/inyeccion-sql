<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutheticationController extends Controller
{
    /**
     * Manejar el inicio de sesión del usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Consulta vulnerable a inyección SQL
        // $user = DB::select("SELECT * FROM users WHERE email = '$request->email' AND password = '$request->password'");

        // Validación de entradas
        $validate = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Sanitización de entradas
        $email = e($validate['email']);
        $Password = e($validate['password']);

        // Eloquent
        $user = User::where('email', $email)->where('password', $password)->first();

        // retorno de información
        return dd($user);
    }
}
