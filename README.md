
## Pasos para instalar

Seguir los siguientes pasos para instalar el proyecto:

- Ejecutar comandos:
```
npm install
composer install
```
- Crear el archivo .env y copiar del .env.example
- Configurar conexión a base de datos (de ser necesario).
- Ejecutar el comando:
```
php artisan key:generate
```
- Reemplazar linea de codigo

Ir a la ruta
*/vendor/laravel/framework/src/Illuminate/Auth*

Modificar la linea 51 del archivo Authenticatable.php
Sustituir por la siguiente linea:
``
return $this->password_usuario;
``