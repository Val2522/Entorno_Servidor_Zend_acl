# Arquitectura MVC

## 1. Vision General

La aplicacion sigue arquitectura MVC sobre Laminas MVC:

- Modelo: acceso a datos y reglas de negocio.
- Vista: plantillas phtml con layout compartido.
- Controlador: orquestacion de requests HTTP.

## 2. Componentes Principales

### 2.1 Entrada

- public/index.php inicializa la aplicacion y gestiona el ciclo MVC.

### 2.2 Configuracion

- config/application.config.php: modulos cargados.
- module/Application/config/module.config.php: rutas, controladores y vistas.

### 2.3 Controladores

- IndexController: catalogo y filtrado.
- AuthController: registro, login, logout, perfil, password.
- CarritoController: gestion de carrito y pedido.
- CategoriasController: CRUD de categorias (admin).
- ArticulosController: CRUD de articulos y pedidos (admin/usuario autenticado segun accion).

### 2.4 Modelos

- Database: conexion SQLite e inicializacion de esquema.
- Usuarios: autenticacion y gestion de usuarios.
- Categorias: operaciones de categorias.
- Articulos: operaciones de articulos e inventario.
- Sesion: utilidades de sesion y permisos.

### 2.5 Vistas

Ubicacion base: module/Application/view/application

- layout/layout.phtml: estructura comun.
- vistas por dominio: index, auth, carrito, categorias, articulos, error.

## 3. Ciclo De Una Peticion

1. El navegador solicita una URL.
2. Laminas Router resuelve la ruta.
3. Se ejecuta la action del controlador.
4. El controlador consulta modelos y valida permisos.
5. Se devuelve ViewModel o redireccion.
6. El motor de vistas renderiza HTML.

## 4. Seguridad Y Estado

- Password hash con password_hash.
- Consultas preparadas PDO.
- Control de acceso por rol.
- Sesion para estado autenticado.
- Cookie para carrito de compra.

## 5. Persistencia

- SQLite local: data/dbase.db.
- Inicializacion y datos demo mediante install.php.
- Entidades principales: usuarios, categorias, articulos, pedidos, lineas_pedido.

## 6. Rutas Clave

- /, /categoria/:id
- /login, /registro, /logout
- /carrito, /carrito/add/:id, /carrito/delete/:id
- /categorias, /categorias/new, /categorias/:id/edit
- /articulos/new, /articulos/:id/edit, /articulos/pedidos

## 7. Decisiones De Diseno

- Laminas como base moderna compatible con ecosistema Zend.
- SQLite para simplicidad academica y portabilidad.
- Separacion clara por capas para facilitar explicacion en videotutorial.
