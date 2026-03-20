<?php
/**
 * Servicio de Sesión
 * 
 * Gestiona las operaciones de sesión del usuario
 * como login, logout, verificación de permisos
 */

namespace Application\Model;

/**
 * Clase para gestionar la sesión del usuario
 */
class Sesion
{
    /**
     * Inicia sesión para un usuario
     * 
     * @param array $usuario Datos del usuario
     */
    public static function iniciar($usuario)
    {
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['username'] = $usuario['username'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['admin'] = $usuario['admin'];
        $_SESSION['email'] = $usuario['email'];
    }
    
    /**
     * Cierra la sesión del usuario actual
     */
    public static function cerrar()
    {
        session_destroy();
        $_SESSION = [];
    }
    
    /**
     * Verifica si hay un usuario autenticado
     * 
     * @return bool True si hay usuario autenticado
     */
    public static function usuarioAutenticado()
    {
        return isset($_SESSION['id']) && !empty($_SESSION['id']);
    }
    
    /**
     * Verifica si el usuario actual es administrador
     * 
     * @return bool True si el usuario es administrador
     */
    public static function esAdmin()
    {
        if (!isset($_SESSION['admin'])) {
            return false;
        }

        $admin = $_SESSION['admin'];
        return $admin === true || $admin === 1 || $admin === '1';
    }
    
    /**
     * Obtiene el ID del usuario actual
     * 
     * @return int|null ID del usuario o null si no hay sesión
     */
    public static function obtenerIdUsuario()
    {
        return $_SESSION['id'] ?? null;
    }
    
    /**
     * Obtiene el nombre de usuario del usuario actual
     * 
     * @return string|null Nombre de usuario o null si no hay sesión
     */
    public static function obtenerUsername()
    {
        return $_SESSION['username'] ?? null;
    }
    
    /**
     * Obtiene el nombre completo del usuario actual
     * 
     * @return string|null Nombre o null si no hay sesión
     */
    public static function obtenerNombre()
    {
        return $_SESSION['nombre'] ?? null;
    }
}
