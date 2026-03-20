<?php
/**
 * Servicio de Base de Datos
 * 
 * Gestiona la conexión a SQLite y proporciona el acceso
 * a la base de datos para toda la aplicación
 */

namespace Application\Model;

use PDO;
use PDOException;

class Database
{
    /**
     * @var PDO Conexión a la base de datos
     */
    private static $conexion = null;
    
    /**
     * @var string Ruta al archivo de base de datos SQLite
     */
    private static $dbPath = null;
    
    /**
     * Obtiene la conexión a la base de datos (patrón Singleton)
     * 
     * @param string|null $dbPath Ruta del archivo de BD (opcional)
     * @return PDO Conexión a la base de datos
     * @throws PDOException Si hay error al conectar
     */
    public static function getConexion($dbPath = null)
    {
        if (self::$conexion === null) {
            if ($dbPath === null) {
                // Ruta al archivo dentro de la carpeta data del proyecto.
                $dbPath = __DIR__ . '/../../../../../data/dbase.db';
            }

            $dbDir = dirname($dbPath);
            if (!is_dir($dbDir)) {
                mkdir($dbDir, 0777, true);
            }
            
            self::$dbPath = $dbPath;
            
            try {
                self::$conexion = new PDO('sqlite:' . $dbPath);
                self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die('Error de conexión a la base de datos: ' . $e->getMessage());
            }
        }
        
        return self::$conexion;
    }
    
    /**
     * Cierra la conexión a la base de datos
     */
    public static function cerrarConexion()
    {
        self::$conexion = null;
    }
    
    /**
     * Inicializa la base de datos creando las tablas necesarias
     * 
     * @return bool True si la inicialización fue exitosa
     */
    public static function inicializarBase()
    {
        $db = self::getConexion();
        
        try {
            // Crear tabla de categorías
            $db->exec("
                CREATE TABLE IF NOT EXISTS categorias (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    nombre VARCHAR(100) NOT NULL
                )
            ");
            
            // Crear tabla de artículos
            $db->exec("
                CREATE TABLE IF NOT EXISTS articulos (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    nombre VARCHAR(100) NOT NULL,
                    precio FLOAT DEFAULT 0,
                    iva INTEGER DEFAULT 21,
                    descripcion VARCHAR(255),
                    image VARCHAR(255),
                    stock INTEGER DEFAULT 0,
                    CategoriaId INTEGER NOT NULL,
                    FOREIGN KEY (CategoriaId) REFERENCES categorias(id)
                )
            ");
            
            // Crear tabla de usuarios
            $db->exec("
                CREATE TABLE IF NOT EXISTS usuarios (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    username VARCHAR(100) NOT NULL UNIQUE,
                    password_hash VARCHAR(200) NOT NULL,
                    nombre VARCHAR(200) NOT NULL,
                    email VARCHAR(200) NOT NULL,
                    admin BOOLEAN NOT NULL DEFAULT 0
                )
            ");

            // Cabecera de pedidos
            $db->exec(" 
                CREATE TABLE IF NOT EXISTS pedidos (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    usuario_id INTEGER NOT NULL,
                    total FLOAT NOT NULL DEFAULT 0,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
                )
            ");

            // Líneas de cada pedido
            $db->exec(" 
                CREATE TABLE IF NOT EXISTS lineas_pedido (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    pedido_id INTEGER NOT NULL,
                    articulo_id INTEGER NOT NULL,
                    cantidad INTEGER NOT NULL,
                    precio_unitario FLOAT NOT NULL,
                    iva INTEGER NOT NULL,
                    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
                    FOREIGN KEY (articulo_id) REFERENCES articulos(id)
                )
            ");
            
            return true;
        } catch (PDOException $e) {
            die('Error al crear las tablas: ' . $e->getMessage());
        }
    }
    
    /**
     * Carga datos de demostración en la base de datos
     * 
     * @return bool True si la carga fue exitosa
     */
    public static function cargarDatos()
    {
        $db = self::getConexion();
        
        try {
            // Verificar si ya hay datos
            $resultado = $db->query("SELECT COUNT(*) as total FROM categorias");
            $fila = $resultado->fetch();
            
            if ($fila['total'] > 0) {
                // Garantiza que el usuario admin exista y tenga permisos de administrador.
                $stmtAdmin = $db->prepare("SELECT id FROM usuarios WHERE username = ? LIMIT 1");
                $stmtAdmin->execute(['admin']);
                $admin = $stmtAdmin->fetch();

                if ($admin) {
                    $db->prepare("UPDATE usuarios SET admin = 1 WHERE id = ?")
                       ->execute([$admin['id']]);
                } else {
                    $passwordHash = password_hash("admin123", PASSWORD_BCRYPT);
                    $db->prepare(
                        "INSERT INTO usuarios (username, password_hash, nombre, email, admin)
                         VALUES (?, ?, ?, ?, 1)"
                    )->execute(["admin", $passwordHash, "Administrador", "admin@tienda.local"]);
                }

                return true; // Ya hay datos
            }
            
            // Insertar categorías
            $categorias = ["Deportes", "Arcade", "Carreras", "Acción"];
            foreach ($categorias as $cat) {
                $db->prepare("INSERT INTO categorias (nombre) VALUES (?)")
                   ->execute([$cat]);
            }
            
            // Insertar artículos (videojuegos)
            $articulos = [
                ["Fernando Martín Basket", 12, 21, "Fernando Martín Basket Master es un videojuego de baloncesto, uno contra uno, publicado por Dinamic Software en 1987", 10, 1, "basket.jpeg"],
                ["Hyper Soccer", 10, 21, "Konami Hyper Soccer fue el primer videojuego de fútbol de Konami para una consola Nintendo", 7, 1, "soccer.jpeg"],
                ["Arkanoid", 15, 21, "Arkanoid es un videojuego de arcade desarrollado por Taito en 1986. Está basado en los Breakout de Atari", 1, 2, "arkanoid.jpeg"],
                ["Tetris", 6, 21, "Tetris es un videojuego de puzzle originalmente diseñado y programado por Alekséi Pázhitnov", 5, 2, "tetris.jpeg"],
                ["Road Fighter", 15, 21, "Road Fighter es un videojuego de carreras producido por Konami. Fue el primer juego de carreras desarrollado por esta compañía", 10, 3, "road.jpeg"],
                ["Out Run", 10, 21, "Out Run es un videojuego de carreras creado en 1986 por Yu Suzuki y Sega-AM2", 3, 3, "outrun.jpeg"],
                ["Army Moves", 8, 21, "Army Moves es un arcade diseñado por Víctor Ruiz, de Dinamic Software en 1986", 8, 4, "army.jpeg"],
                ["La Abadia del Crimen", 4, 21, "La Abadía del Crimen es un videojuego desarrollado y publicado por la Academia Mister Chip en 1987", 10, 4, "abadia.jpeg"],
            ];
            
            foreach ($articulos as $art) {
                $db->prepare("
                    INSERT INTO articulos (nombre, precio, iva, descripcion, stock, CategoriaId, image)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ")->execute($art);
            }
            
            // Crear usuario administrador de prueba
            $passwordHash = password_hash("admin123", PASSWORD_BCRYPT);
            $db->prepare("
                INSERT INTO usuarios (username, password_hash, nombre, email, admin)
                VALUES (?, ?, ?, ?, 1)
            ")->execute(["admin", $passwordHash, "Administrador", "admin@tienda.local"]);
            
            return true;
        } catch (PDOException $e) {
            die('Error al cargar datos: ' . $e->getMessage());
        }
    }
}
