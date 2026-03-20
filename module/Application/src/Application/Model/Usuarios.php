<?php
/**
 * Modelo de Usuarios
 * 
 * Gestiona todas las operaciones relacionadas con los usuarios
 * del sistema, incluyendo autenticación, registro y permisos
 */

namespace Application\Model;

use PDO;

class Usuarios
{
    /**
     * @var PDO Conexión a la base de datos
     */
    private $db;
    
    /**
     * Constructor del modelo Usuarios
     * 
     * @param PDO $db Conexión a la base de datos
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    /**
     * Obtiene todos los usuarios
     * 
     * @return array Lista de todos los usuarios
     */
    public function obtenerTodos()
    {
        $stmt = $this->db->query("SELECT id, username, nombre, email, admin FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene un usuario por ID
     * 
     * @param int $id ID del usuario
     * @return array Datos del usuario o null
     */
    public function obtenerPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene un usuario por nombre de usuario (username)
     * 
     * @param string $username Nombre de usuario a buscar
     * @return array Datos del usuario o null
     */
    public function obtenerPorUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crea un nuevo usuario
     * 
     * @param array $datos Array con los datos del usuario
     * @return int ID del usuario creado
     */
    public function crear($datos)
    {
        // Encriptamos la contraseña antes de guardarla
        $datos['password_hash'] = password_hash($datos['password'], PASSWORD_BCRYPT);
        unset($datos['password']);
        
        $stmt = $this->db->prepare("
            INSERT INTO usuarios (username, password_hash, nombre, email, admin)
            VALUES (:username, :password_hash, :nombre, :email, :admin)
        ");
        $stmt->execute($datos);
        return $this->db->lastInsertId();
    }
    
    /**
     * Actualiza los datos de un usuario
     * 
     * @param int $id ID del usuario
     * @param array $datos Array con los datos a actualizar
     * @return bool True si la actualización fue exitosa
     */
    public function actualizar($id, $datos)
    {
        $datos['id'] = $id;
        $stmt = $this->db->prepare("
            UPDATE usuarios 
            SET nombre = :nombre, email = :email
            WHERE id = :id
        ");
        return $stmt->execute($datos);
    }
    
    /**
     * Cambia la contraseña de un usuario
     * 
     * @param int $id ID del usuario
     * @param string $nuevaPassword Nueva contraseña
     * @return bool True si el cambio fue exitoso
     */
    public function cambiarPassword($id, $nuevaPassword)
    {
        $passwordHash = password_hash($nuevaPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE usuarios SET password_hash = :password_hash WHERE id = :id");
        return $stmt->execute(['id' => $id, 'password_hash' => $passwordHash]);
    }
    
    /**
     * Verifica las credenciales de un usuario (login)
     * 
     * @param string $username Nombre de usuario
     * @param string $password Contraseña ingresada
     * @return array|null Datos del usuario si las credenciales son correctas, null si no
     */
    public function verificarCredenciales($username, $password)
    {
        $usuario = $this->obtenerPorUsername($username);
        
        if (!$usuario) {
            return null; // Usuario no existe
        }
        
        // Verificamos que la contraseña ingresada coincida con el hash guardado
        if (password_verify($password, $usuario['password_hash'])) {
            return $usuario;
        }
        
        return null; // Contraseña incorrecta
    }
    
    /**
     * Verifica si un usuario es administrador
     * 
     * @param int $id ID del usuario
     * @return bool True si es administrador
     */
    public function esAdmin($id)
    {
        $stmt = $this->db->prepare("SELECT admin FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $resultado && $resultado['admin'] == 1;
    }
    
    /**
     * Verifica si un usuario existe por su username
     * 
     * @param string $username Nombre de usuario
     * @return bool True si el usuario existe
     */
    public function usuarioExiste($username)
    {
        $usuario = $this->obtenerPorUsername($username);
        // PDO::fetch devuelve false cuando no encuentra filas.
        return $usuario !== false && $usuario !== null;
    }
    
    /**
     * Establece el rol de administrador a un usuario
     * 
     * @param int $id ID del usuario
     * @param bool $esAdmin True para hacer admin, False para remover admin
     * @return bool True si la operación fue exitosa
     */
    public function establecerAdmin($id, $esAdmin)
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET admin = :admin WHERE id = :id");
        return $stmt->execute(['id' => $id, 'admin' => $esAdmin ? 1 : 0]);
    }
}
