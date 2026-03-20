<?php
/**
 * Modelo de Categorías
 * 
 * Gestiona todas las operaciones relacionadas con las categorías
 * de videojuegos en la tienda
 */

namespace Application\Model;

use PDO;

class Categorias
{
    /**
     * @var PDO Conexión a la base de datos
     */
    private $db;
    
    /**
     * Constructor del modelo Categorias
     * 
     * @param PDO $db Conexión a la base de datos
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    /**
     * Obtiene todas las categorías
     * 
     * @return array Lista de todas las categorías
     */
    public function obtenerTodas()
    {
        $stmt = $this->db->query("SELECT * FROM categorias");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene una categoría por ID
     * 
     * @param int $id ID de la categoría
     * @return array Datos de la categoría o null
     */
    public function obtenerPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM categorias WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crea una nueva categoría
     * 
     * @param string $nombre Nombre de la categoría
     * @return int ID de la categoría creada
     */
    public function crear($nombre)
    {
        $stmt = $this->db->prepare("INSERT INTO categorias (nombre) VALUES (:nombre)");
        $stmt->execute(['nombre' => $nombre]);
        return $this->db->lastInsertId();
    }
    
    /**
     * Actualiza una categoría existente
     * 
     * @param int $id ID de la categoría
     * @param string $nombre Nuevo nombre de la categoría
     * @return bool True si la actualización fue exitosa
     */
    public function actualizar($id, $nombre)
    {
        $stmt = $this->db->prepare("UPDATE categorias SET nombre = :nombre WHERE id = :id");
        return $stmt->execute(['id' => $id, 'nombre' => $nombre]);
    }
    
    /**
     * Elimina una categoría
     * 
     * @param int $id ID de la categoría a eliminar
     * @return bool True si la eliminación fue exitosa
     */
    public function eliminar($id)
    {
        $stmt = $this->db->prepare("DELETE FROM categorias WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
