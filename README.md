
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

**Importante: Debe reemplazar la carpeta Auth que esta dentro de vendor
por la que esta en la raiz del proyecto.
Si es para produccion, borrar la carpeta Auth de la raiz del proyecto 
una vez copiada en el vendor, si es para desarrollo, conservar la carpeta 
y mantenerla en el repositorio.**
