# Laravel ASAWL - 01 Inyección SQL

##	Inyección SQL

La inyección SQL en Laravel es una vulnerabilidad de seguridad que ocurre cuando un atacante logra insertar código malicioso en las consultas que la aplicación web envía a la base de datos. Esto puede suceder si no se sanitizan y validan adecuadamente las entradas del usuario antes de incluirlas en las consultas.

1.	Entrada Maliciosa: Un atacante introduce datos maliciosos en un campo de entrada de la aplicación web (formularios, URL, etc.). Por ejemplo, en lugar de un correo electrónico válido, ingresan algo como: `' OR 1=1 --`
2.	Consulta Vulnerable: Si el código no está protegido, esta entrada maliciosa se incorpora directamente a la consulta SQL. En el ejemplo anterior, la consulta podría transformarse en algo como: `SELECT * FROM users WHERE email = '' OR 1=1 --' AND password = '1234'`
3.	Ejecución del Código Malicioso: La base de datos interpreta el código SQL modificado. En este caso, `OR 1=1` siempre es verdadero, lo que significa que la condición se cumple para todos los registros. Además, `--` comenta el resto de la consulta, evitando la verificación de la contraseña.
4.	Consecuencias: El atacante podría obtener acceso no autorizado a datos sensibles, modificar información en la base de datos o incluso ejecutar comandos en el sistema operativo si la base de datos tiene permisos suficientes.

###	Directrices de inyección SQL en Laravel

Laravel proporciona herramientas y buenas prácticas para proteger las aplicaciones web contra inyecciones SQL.

```php
// Consulta vulnerable a inyección SQL
$user = DB::select("SELECT * FROM users WHERE email = '$request->email' AND password = '$request->password'");
```

Validación de Entradas: [Validar](https://laravel.com/docs/11.x/validation) rigurosamente todas las entradas del usuario para asegurar que cumplan con los formatos y tipos de datos esperados. Laravel ofrece una variedad de reglas de validación.

```php
// Validación de entradas
$validate = $request->validate([
    'email' => 'required|email',
    'password' => 'required',
]);
```

Sanitización de Entradas: Si se necesita incluir entradas del usuario directamente en consultas SQL (aunque no es recomendable), se debe utilizar las funciones de [escape](https://laravel.com/docs/11.x/strings#method-e) de Laravel para sanitizar los datos  y evitar que se interpreten como código SQL.

```php
// Sanitización de entradas
$email = e($request->email);
$Password = e($request->password);
```

Eloquent: Utilizar el [ORM](https://laravel.com/docs/11.x/eloquent) de Laravel como constructor de consultas para parametrizar las consultas, Eloquent ayuda a evitar que las entradas maliciosas se interpreten como código SQL.

```php
// Eloquent
$user = User::where('email', $request->email)->where('password', $request->password)->first();
```

###	Recomendaciones para prevenir la inyección SQL en Laravel
-	Nunca confiar en los datos de entrada del usuario.
-	Siempre sanitizar y validar todas las entradas antes de usarlas en consultas SQL.
-	Siempre utilizar Eloquent en todas las consultas y ejecución de SQL.

### Mitigación de la inyección SQL

```php
// Validación de entradas
$validate = $request->validate([
    'email' => 'required|email',
    'password' => 'required',
]);

// Sanitización de entradas
$email = e($validate->email);
$Password = e($validate->password);

// Eloquent
$user = User::where('email', $email)->where('password', $password)->first();
```
