<?php
/**
 * Modelo de Artículos (Videojuegos)
 * 
 * Gestiona todas las operaciones relacionadas con los videojuegos
 * en la tienda, incluyendo precio, stock, imágenes, etc.
 */

namespace Application\Model;

use PDO;

class Articulos
{
    /**
     * @var PDO Conexión a la base de datos
     */
    private $db;
    
    /**
     * Constructor del modelo Articulos
     * 
     * @param PDO $db Conexión a la base de datos
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    /**
     * Obtiene todos los artículos
     * 
     * @return array Lista de todos los artículos
     */
    public function obtenerTodos()
    {
        $stmt = $this->db->query("SELECT * FROM articulos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene un artículo por ID
     * 
     * @param int $id ID del artículo
     * @return array Datos del artículo o null
     */
    public function obtenerPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM articulos WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene todos los artículos de una categoría
     * 
     * @param int $categoriaId ID de la categoría
     * @return array Lista de artículos de la categoría
     */
    public function obtenerPorCategoria($categoriaId)
    {
        $stmt = $this->db->prepare("SELECT * FROM articulos WHERE CategoriaId = :categoriaId");
        $stmt->execute(['categoriaId' => $categoriaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crea un nuevo artículo
     * 
     * @param array $datos Array con los datos del artículo
     * @return int ID del artículo creado
     */
    public function crear($datos)
    {
        $stmt = $this->db->prepare("
            INSERT INTO articulos (nombre, precio, iva, descripcion, image, stock, CategoriaId)
            VALUES (:nombre, :precio, :iva, :descripcion, :image, :stock, :CategoriaId)
        ");
        $stmt->execute($datos);
        return $this->db->lastInsertId();
    }
    
    /**
     * Actualiza un artículo existente
     * 
     * @param int $id ID del artículo
     * @param array $datos Array con los datos a actualizar
     * @return bool True si la actualización fue exitosa
     */
    public function actualizar($id, $datos)
    {
        $datos['id'] = $id;
        $stmt = $this->db->prepare("
            UPDATE articulos 
            SET nombre = :nombre, 
                precio = :precio, 
                iva = :iva, 
                descripcion = :descripcion,
                image = :image,
                stock = :stock,
                CategoriaId = :CategoriaId
            WHERE id = :id
        ");
        return $stmt->execute($datos);
    }
    
    /**
     * Elimina un artículo
     * 
     * @param int $id ID del artículo a eliminar
     * @return bool True si la eliminación fue exitosa
     */
    public function eliminar($id)
    {
        $stmt = $this->db->prepare("DELETE FROM articulos WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Reduce el stock de un artículo
     * 
     * @param int $id ID del artículo
     * @param int $cantidad Cantidad a reducir
     * @return bool True si la operación fue exitosa
     */
    public function reducirStock($id, $cantidad)
    {
        $stmt = $this->db->prepare("UPDATE articulos SET stock = stock - :cantidad WHERE id = :id");
        return $stmt->execute(['id' => $id, 'cantidad' => $cantidad]);
    }
    
    /**
     * Calcula el precio final con IVA incluido
     * 
     * @param float $precio Precio base
     * @param int $iva Porcentaje de IVA
     * @return float Precio final con IVA
     */
    public static function calcularPrecioFinal($precio, $iva)
    {
        return $precio + ($precio * $iva / 100);
    }
}
