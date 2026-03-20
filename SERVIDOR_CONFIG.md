# Configuracion De Servidor

Documento de referencia para ejecutar el proyecto en desarrollo y preparar despliegue basico.

## 1. Desarrollo Local Recomendado (Windows)

```powershell
cd C:\Users\alvar\Desktop\entorno_servidor_cursor\Actividad_UT6_ZEND_acl
php -S localhost:8000 -t C:\Users\alvar\Desktop\entorno_servidor_cursor\Actividad_UT6_ZEND_acl\public
```

Acceso:

- http://localhost:8000/

## 2. Requisitos PHP

En php.ini, comprobar:

```ini
extension=openssl
extension=mbstring
extension=curl
extension=pdo_sqlite
extension=sqlite3
memory_limit=256M
upload_max_filesize=20M
post_max_size=20M
```

Tras cambios, reiniciar terminal/servidor PHP.

## 3. Apache (Referencia)

- Activar mod_rewrite.
- Configurar VirtualHost apuntando a la carpeta public.

Ejemplo minimo:

```apache
<VirtualHost *:80>
    ServerName retro.local
    DocumentRoot "C:/ruta/Actividad_UT6_ZEND_acl/public"

    <Directory "C:/ruta/Actividad_UT6_ZEND_acl/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

## 4. Nginx (Referencia)

Ejemplo minimo:

```nginx
server {
    listen 80;
    server_name retro.local;
    root /ruta/Actividad_UT6_ZEND_acl/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_pass 127.0.0.1:9000;
    }
}
```

## 5. Permisos Y Directorios

Verificar existencia y escritura en:

- data/
- public/uploads/

## 6. Comandos Utiles

Verificar entorno:

```powershell
php -v
php -m
composer -V
php verify-install.php
```

## 7. Incidencias Frecuentes

- Error "Directory public does not exist": usar ruta absoluta con -t.
- Error de tipos en editor: revisar SOLUCION_ERRORES_INTELEPHENSE.md.
- Imagenes no visibles: revisar public/uploads y nombre de archivo persistido.
