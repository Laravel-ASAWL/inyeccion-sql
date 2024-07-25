# Laravel ASAWL - 01 Inyección SQL

##	Inyección SQL

La inyección SQL en Laravel es una vulnerabilidad de seguridad que ocurre cuando un atacante logra insertar código malicioso en las consultas que la aplicación web envía a la base de datos. Esto puede suceder si no se validan y sanitizan adecuadamente las entradas del usuario antes de incluirlas en las consultas.

### Acceso a datos sin credenciales

1.	Entrada Maliciosa: Un atacante introduce datos maliciosos en un campo de entrada de la aplicación web (formularios, URL, etc.). Por ejemplo, en lugar de un correo electrónico válido, ingresan algo como: `' OR 1=1 --`
2.	Consulta Vulnerable: Si el código no está protegido, esta entrada maliciosa se incorpora directamente a la consulta SQL. En el ejemplo anterior, la consulta podría transformarse en algo como: `SELECT * FROM users WHERE email = '' OR 1=1 --' AND password = '1234'`
3.	Ejecución del Código Malicioso: La base de datos interpreta el código SQL modificado. En este caso, `OR 1=1` siempre es verdadero, lo que significa que la condición se cumple para todos los registros. Además, `--` comenta el resto de la consulta, evitando la verificación de la contraseña.
4.	Consecuencias: El atacante podría obtener acceso no autorizado a datos sensibles, modificar información en la base de datos o incluso ejecutar comandos en el sistema operativo si la base de datos tiene permisos suficientes.

### Acceso a datos con credenciales

1. Entrada Maliciosa: Un atacante modifica la URL de la siguiente manera: `1 UNION SELECT username, password FROM users --`, en lugar de proporcionar un ID de producto válido, el atacante introduce una consulta SQL maliciosa que aprovecha la vulnerabilidad de la aplicación.

2. Consulta Vulnerable: Si la aplicación no sanitiza ni valida correctamente la entrada del usuario, esta entrada maliciosa se incorpora directamente en la consulta SQL que se envía a la base de datos. La consulta original, que debería ser: `SELECT name, description FROM productos WHERE id = 1`, se transforma en: `SELECT name, description FROM productos WHERE id = 1 UNION SELECT username, password FROM users --`.

3. Ejecución del Código Malicioso: La base de datos ejecuta la consulta SQL modificada. La primera parte de la consulta `SELECT name, description FROM productos WHERE id = 1` se ejecuta normalmente, pero debido al operador `UNION`, se combinan los resultados con los de la segunda consulta `SELECT username, password FROM users`. El comentario `--` anula el resto de la consulta original, evitando errores de sintaxis.

4. Consecuencias: La aplicación devuelve los resultados de ambas consultas combinados. Esto significa que, además de los detalles del producto (si existe uno con ID 1), la aplicación también mostrará los nombres de usuario y contraseñas almacenados en la tabla usuarios. El atacante ha obtenido acceso no autorizado a información sensible que no debería estar expuesta.

### Eliminar datos maliciosamente

1.	Entrada Maliciosa: Un atacante introduce datos maliciosos en un campo de entrada de la aplicación web (formularios, URL, etc.). Por ejemplo, en lugar de un correo electrónico válido, ingresan algo como: `fake@mail.com'; DROP TABLE users; --`
2.	Consulta Vulnerable: Si el código no está protegido, esta entrada maliciosa se incorpora directamente a la consulta SQL. En el ejemplo anterior, la consulta podría transformarse en algo como: `SELECT * FROM users WHERE email = 'fake@mail.com'; DROP TABLE users; -- AND password = '1234'`
3.	Ejecución del Código Malicioso: La base de datos interpreta el código SQL modificado. En este caso, `DROP TABLE users;`, lo que significa la tabla `users` se elimina de la base de datos. Además, `--` comenta el resto de la consulta, evitando la verificación de la contraseña.
4.	Consecuencias: El atacante podría eliminar todos los registros de la tabla `users` de la base de datos e incluso todas las tablas si la base de datos tiene permisos suficientes.

###	Directrices de inyección SQL en Laravel

Laravel proporciona herramientas y buenas prácticas para proteger las aplicaciones web contra inyecciones SQL.

```php
// Consulta vulnerable a inyección SQL
$user = DB::select("SELECT * FROM users WHERE email = '$request->email' AND password = '$request->password'");
```

#### 1. Validación de Entradas

[Validar](https://laravel.com/docs/11.x/validation) rigurosamente todas las entradas del usuario para asegurar que cumplan con los formatos y tipos de datos esperados. Laravel ofrece una variedad de reglas de validación.

```php
// Validación de entradas
$validate = $request->validate([
    'email' => 'required|email',
    'password' => 'required',
]);
```

#### 2. Sanitización de Entradas

Si se necesita incluir entradas del usuario directamente en consultas SQL (aunque no es recomendable), se debe utilizar las funciones [e()](https://laravel.com/docs/11.x/strings#method-e) de Laravel para sanitizar los datos  y evitar que se interpreten como código SQL. Adicional se puede utilizar tambien [trim()](https://laravel.com/docs/11.x/strings#method-fluent-str-trim) para eliminar espacios en blanco y [strip_tags()](https://www.php.net/manual/es/function.strip-tags.php) para eliminar tags HTML.

```php
// Sanitización de entradas
$email = strip_tags(trim(e($validated['email'])));
$password = strip_tags(trim(e($validated['password'])));
```
#### 3. Eloquent ORM

Utilizar el [ORM](https://laravel.com/docs/11.x/eloquent) de Laravel como constructor de consultas para parametrizar las consultas, Eloquent ayuda a evitar que las entradas maliciosas se interpreten como código SQL.

```php
// Eloquent ORM
$user = User::where('email', $request->email)->where('password', $request->password)->first();
```

#### 3.1 Consultas preparadas (Query Builder)

Si necesitas más flexibilidad que Eloquent, usa el Query Builder de Laravel, que también escapa los parámetros de forma segura.

```php
// Query Builder
$user = DB::table('users')->where('email', $request->email)->where('password', $request->password)->get();
```

#### 3.2 Consultas parametrizadas

Si debes construir consultas SQL dinámicas, utiliza parámetros con nombre o marcadores de posición (?) para evitar que el código malicioso se interprete como parte de la consulta.

```php
// Consultas parametrizadas
$user = DB::select('SELECT email FROM users WHERE email = ? AND password = ?', [$request->email, $request->password]);
```

###	Recomendaciones para prevenir la inyección SQL en Laravel

-	Nunca confiar en los datos de entrada del usuario.
-	Siempre validar y sanitizar todas las entradas antes de usarlas en consultas SQL.
-	Siempre utilizar Eloquent ORM en todas las consultas y ejecución de SQL.

### Mitigación de la inyección SQL en Laravel

La mitigación de inyección SQL se la realiza mediante:

-	Validación de entradas.
-	Sanitización de entradas, y.
-	Utilización de Eloquent ORM.

Como se muestra en el controlador: [app/Http/Controllers/AuthentificacionController.php](./app/Http/Controllers/AutheticationController.php)

```php
...

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

 ...
```
