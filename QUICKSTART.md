# Quickstart

Guia minima para ejecutar la aplicacion en menos de 5 minutos.

## 1. Requisitos

- PHP 8.1+
- Composer
- Extensiones: openssl, mbstring, curl, pdo_sqlite, sqlite3

## 2. Instalacion

```powershell
cd C:\Users\alvar\Desktop\entorno_servidor_cursor\Actividad_UT6_ZEND_acl
composer install
php install.php
```

## 3. Ejecucion

```powershell
php -S localhost:8000 -t C:\Users\alvar\Desktop\entorno_servidor_cursor\Actividad_UT6_ZEND_acl\public
```

Abrir:

- http://localhost:8000/

## 4. Credenciales Demo

- Usuario: admin
- Password: admin123

## 5. Verificacion Rapida

```powershell
php verify-install.php
```

Prueba funcional recomendada:

1. Entrar en /login
2. Autenticar con admin/admin123
3. Navegar a /categorias
4. Crear o editar una categoria

## 6. Si Algo Falla

- Revisar README.md para instalacion completa.
- Revisar SOLUCION_ERRORES_INTELEPHENSE.md para errores del editor.
- Reiniciar servidor tras cambios de configuracion o codificacion de archivos.
