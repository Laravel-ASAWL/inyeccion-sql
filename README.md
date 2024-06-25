# Laravel ASAWL

## Commit inicial

```bash
npm install -D tailwindcss postcss autoprefixer flowbite
npx tailwindcss init -p
code tailwind.config.js
```

```js
module.exports = {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
      "./node_modules/flowbite/**/*.js"
    ],
    theme: {
      extend: {},
    },
    plugins: [
        require('flowbite/plugin')
    ],
  }
  ```

## Inyección SQL

```bash
php artisan make:controller AutheticationController
code 
```

```php

```