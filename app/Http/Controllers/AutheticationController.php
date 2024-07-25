<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AutheticationController extends Controller
{
    public function login(Request $request)
    {
        // Validación de entradas
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ],[
            'email.required' => 'Correo requerido',
            'email.email' => 'Correo inválido',
            'password.required' => 'Contraseña requerida',
        ]);

        // Sanitización de entradas
        $email = strip_tags(trim(e($validated['email'])));
        $password = strip_tags(trim(e($validated['password'])));

        // Utilizando Eloquent ORM
        $user = User::where('email', $email)->first();

        // validando credenciales
        if (!$user || !Auth::attempt([
            'email' => $user->email,
            'password' => $password,
        ])) {
            throw ValidationException::withMessages([
                'email' => ['Credenciales inválidas'], 
            ]);
        }

        // generar sesión
        $request->session()->regenerate();

        // redireccionar a dashboard
        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->intended('/');
    }
}
