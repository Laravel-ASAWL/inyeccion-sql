<?php

namespace App\Http\Controllers;

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
        // Consulta SQL vulnerable
        //$user = DB::select("SELECT * FROM users WHERE email = '$request->email' AND password = '$request->password'");

        // Consulta sanitizada
        //$user = DB::select("SELECT * FROM users WHERE email = ? AND password = ?", [$request->email, $request->password]);

        // Validación de entradas
        $validate = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Consulta sanitizada
        //$user = DB::select("SELECT * FROM users WHERE email = ? AND password = ?", [$request->email, $request->password]);
        // Consulta validada y sanitizada
        $user = User::where('email', $validate('email'))->where('password', $validate('password'))->first();

        return dd($user);
    }
}
