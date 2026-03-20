# Actividad UT6 - Tienda Retro MVC (Zend/Laminas)

Proyecto backend MVC desarrollado en PHP con Laminas MVC (evolucion de Zend Framework), orientado a demostrar rutas, controladores, formularios, persistencia, sesion, autenticacion, ACL y cookies.

## 1. Alcance Del Proyecto

La aplicacion implementa una tienda de videojuegos retro con dos perfiles:

- Usuario: registro, login, catalogo, carrito, pedido, perfil.
- Administrador: gestion de categorias, articulos, stock, imagenes y pedidos.

## 2. Objetivos Cubiertos

Este proyecto cubre todos los puntos solicitados para el video:

- Rutas estaticas.
- Rutas dinamicas.
- Controladores para endpoints GET y POST.
- Gestion de formularios HTML desde controlador.
- Subida de archivos desde formulario HTML.
- Gestion de errores y redirecciones.
- Motor de plantillas con variables e instrucciones.
- Persistencia de datos en capa de modelo (PDO + SQLite).
- Gestion de sesion.
- Registro, login y logout.
- Control de acceso por permisos (ACL simple por rol).
- Gestion de cookies (carrito).


## 3. Stack Tecnologico

- PHP 8.1+ (recomendado 8.2).
- Laminas MVC 3 (compatibilidad con Zend via laminas-zendframework-bridge).
- SQLite (archivo local en data/dbase.db).
- Bootstrap 5 en vistas.

Dependencias principales en composer.json:

- laminas/laminas-mvc
- laminas/laminas-db
- laminas/laminas-form
- laminas/laminas-authentication
- laminas/laminas-permissions-acl
- laminas/laminas-view
- laminas/laminas-session
- laminas/laminas-zendframework-bridge

## 4. Estructura Del Repositorio

```text
Actividad_UT6_ZEND_acl/
├── config/
├── data/
│   ├── dbase.db
│   └── uploads/
├── module/Application/
│   ├── config/module.config.php
│   ├── src/Application/
│   │   ├── Controller/
│   │   ├── Form/
│   │   └── Model/
│   └── view/application/
├── public/index.php
├── install.php
├── verify-install.php
└── README.md
```

## 5. Puesta En Marcha Desde 0 (Ordenador Nuevo)

### 5.1 Requisitos Previos

- Git instalado.
- PHP 8.1+ (recomendado 8.2).
- Extensiones PHP habilitadas: openssl, mbstring, curl, pdo_sqlite, sqlite3.
- Composer instalado.

Comprobaciones rapidas:

```powershell
git --version
php -v
composer -V
php -m
```

### 5.2 Clonar El Repositorio

```powershell
git clone https://github.com/Val2522/Entorno_Servidor_Zend_acl.git
cd Entorno_Servidor_Zend_acl
```

### 5.3 Instalar Dependencias

```powershell
composer install
```

### 5.4 Inicializar Base De Datos Y Datos Demo

```powershell
php install.php
```

Credenciales de prueba:

- Usuario: admin
- Password: admin123

### 5.5 Arrancar Servidor En Local

Desde la raiz del proyecto:

```powershell
php -S localhost:8000 -t public
```

Abrir en navegador:

- http://localhost:8000/

### 5.6 Verificar Instalacion

```powershell
php verify-install.php
```

### 5.7 Comandos Equivalentes En Linux/macOS

```bash
git clone https://github.com/Val2522/Entorno_Servidor_Zend_acl.git
cd Entorno_Servidor_Zend_acl
composer install
php install.php
php -S localhost:8000 -t public
```

## 6. Como Se Usa El Framework (MVC)

### 6.1 Routing

Las rutas se declaran en module/Application/config/module.config.php con rutas literales y segmentadas.

- Ejemplo estatico: /login
- Ejemplo dinamico: /categoria/:id

### 6.2 Controladores

Cada endpoint se implementa como Action en module/Application/src/Application/Controller.

- GET: mostrar formularios, listados, detalle.
- POST: procesar validaciones, persistir y redirigir.

### 6.3 Formularios

Los formularios y validaciones estan en module/Application/src/Application/Form.

- Login, registro, perfil, cambio password.
- Alta/edicion de categorias y articulos.
- Confirmaciones y flujo de compra.

### 6.4 Vistas Y Plantillas

Las vistas phtml estan en module/Application/view/application.

- layout global
- vistas por modulo
- condicionales y render de variables

### 6.5 Persistencia

Capa de modelo en module/Application/src/Application/Model:

- Database.php: inicializacion de esquema y conexion.
- Usuarios.php, Articulos.php, Categorias.php, Sesion.php: logica de dominio.

### 6.6 Sesion, ACL Y Cookies

- Sesion: estado de autenticacion y perfil.
- ACL por rol: proteccion de rutas de administracion.
- Cookies: carrito asociado al usuario.

## 7. Mapa De Funcionalidades

| Bloque | Ruta/Modulo | Resultado |
|---|---|---|
| Catalogo | /, /categoria/:id | Listado y filtrado |
| Autenticacion | /login, /registro, /logout | Acceso de usuarios |
| Perfil | /perfil/:username, /changepassword/:username | Gestion de cuenta |
| Carrito | /carrito, /carrito/add/:id, /carrito/delete/:id | Compra y pedido |
| Admin categorias | /categorias, /categorias/new, /categorias/:id/edit | CRUD categorias |
| Admin articulos | /articulos/new, /articulos/:id/edit, /articulos/:id/delete | CRUD articulos |
| Pedidos | /articulos/pedidos | Consulta pedidos |

## 8. Flujo Recomendado Para Demo

1. Mostrar inicio y navegacion por categoria dinamica.
2. Registrar usuario y hacer login.
3. Anadir al carrito y confirmar pedido.
4. Cambiar a admin y mostrar CRUD de categorias/articulos.
5. Mostrar subida de imagen y listado de pedidos.
6. Enseñar redirecciones por permisos y gestion de errores.

## 9. Troubleshooting

### 9.1 Error Al Iniciar Servidor Con -t public

Causa: terminal fuera del directorio raiz del proyecto.

Solucion:

```powershell
cd Entorno_Servidor_Zend_acl
php -S localhost:8000 -t public
```

### 9.2 Error De Namespace O Caracteres Invisibles (BOM)

Causa: archivo guardado con UTF-8 BOM en PHP.

Solucion: guardar como UTF-8 sin BOM y reiniciar servidor.

### 9.3 Errores Intelephense De Tipos Zend/Laminas

Consultar guia especifica en SOLUCION_ERRORES_INTELEPHENSE.md.

### 9.4 Imagenes No Cargan

Revisar:

- permisos en public/uploads
- nombre de archivo en BD
- upload_max_filesize y post_max_size en php.ini

## 10. Documentacion Del Proyecto

- README.md: documento principal.
- QUICKSTART.md: arranque rapido.
- ARQUITECTURA.md: arquitectura y flujo MVC.
- SERVIDOR_CONFIG.md: configuracion de servidor para dev/prod.
- RESUMEN_PROYECTO.md: resumen ejecutivo para entrega.
- SOLUCION_ERRORES_INTELEPHENSE.md: soporte de editor.

## 11. Licencia

Uso academico y demostrativo.
