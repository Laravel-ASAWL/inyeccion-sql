@use('Illuminate\Support\Facades\Vite')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

        <style>
            {!! Vite::content('resources/css/app.css') !!}
        </style>
        <script>
            {!! Vite::content('resources/js/app.js') !!}
        </script>
    
    </head>
    <body class="flex flex-col items-center justify-center min-h-screen bg-gray-100">

    <div class="bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-4 text-center">👋 ¡Bienvenido! 😊</h1>
        <p class="text-gray-600">¡Has realizado el inicio de sesión correctamente!</p>
    </div>

    <form action="{{ route('logout') }}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" class="mt-8 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
            Cerrar Sesión
        </button>
    </form>

    </body>
</html>
