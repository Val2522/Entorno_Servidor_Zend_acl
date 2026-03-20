<?php
/**
 * Controlador de Carrito
 * 
 * Gestiona todas las operaciones del carrito de compra:
 * agregar productos, ver carrito, eliminar productos, pedido
 */

namespace Application\Controller;

use Laminas\Http\PhpEnvironment\Request;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use PDOException;
use Application\Model\Database;
use Application\Model\Articulos;
use Application\Model\Sesion;
use Application\Form\FormCarrito;

class CarritoController extends AbstractActionController
{
    /**
     * Nombre de cookie por usuario para evitar conflictos.
     *
     * @param int $usuarioId
     * @return string
     */
    private function nombreCookieCarrito($usuarioId)
    {
        return 'carrito_' . (int) $usuarioId;
    }

    /**
     * Devuelve la request HTTP tipada para analisis estatico.
     *
     * @return Request
     */
    public function getRequest()
    {
        /** @var Request $request */
        $request = parent::getRequest();
        return $request;
    }

    /**
     * Obtiene el carrito almacenado en cookies
     * 
     * @param int $usuarioId ID del usuario
     * @return array Array con los items del carrito
     */
    private function obtenerCarrito($usuarioId)
    {
        $cookie = $_COOKIE[$this->nombreCookieCarrito($usuarioId)] ?? ($_COOKIE[(string) $usuarioId] ?? null);
        if ($cookie) {
            $carrito = json_decode($cookie, true);
            return is_array($carrito) ? $carrito : [];
        }
        return [];
    }
    
    /**
     * Guarda el carrito en una cookie
     * 
     * @param int $usuarioId ID del usuario
     * @param array $carrito Array con los items del carrito
     */
    private function guardarCarrito($usuarioId, $carrito)
    {
        setcookie($this->nombreCookieCarrito($usuarioId), json_encode($carrito), 0, '/', '', false, true);
        // Limpiar cookie antigua para evitar inconsistencias.
        setcookie((string) $usuarioId, '', time() - 3600, '/');
    }
    
    /**
     * AcciÃ³n para agregar producto al carrito
     * 
     * @return ViewModel|Response
     */
    public function addAction()
    {
        // Verificar que el usuario estÃ© autenticado
        if (!Sesion::usuarioAutenticado()) {
            return $this->redirect()->toRoute('login');
        }
        
        $id = (int) $this->params()->fromRoute('id', $_GET['id'] ?? 0);
        $db = Database::getConexion();
        $modeloArticulos = new Articulos($db);
        
        $articulo = $modeloArticulos->obtenerPorId($id);
        
        if (!$articulo) {
            return $this->notFoundAction();
        }
        
        $form = new FormCarrito();
        $form->get('id')->setValue($id);
        
        $usuarioId = Sesion::obtenerIdUsuario();
        
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setData($post);
            
            if ($form->isValid()) {
                $cantidad = (int) $post['cantidad'];
                
                // Verificar que hay stock
                if ($articulo['stock'] >= $cantidad) {
                    $carrito = $this->obtenerCarrito($usuarioId);
                    
                    // Verificar si el producto ya estÃ¡ en el carrito
                    $actualizado = false;
                    foreach ($carrito as &$item) {
                        if ($item['id'] == $id) {
                            $item['cantidad'] = $cantidad;
                            $actualizado = true;
                            break;
                        }
                    }
                    
                    // Si no estaba, lo agregamos
                    if (!$actualizado) {
                        $carrito[] = [
                            'id' => $id,
                            'cantidad' => $cantidad,
                        ];
                    }
                    
                    $this->guardarCarrito($usuarioId, $carrito);
                    
                    return $this->redirect()->toRoute('home');
                } else {
                    $form->get('cantidad')->setMessages(['No hay artÃ­culos suficientes.']);
                }
            }
        }
        
        return new ViewModel(array(
            'form' => $form,
            'articulo' => $articulo,
        ));
    }
    
    /**
     * AcciÃ³n para ver el carrito
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        // Verificar que el usuario estÃ© autenticado
        if (!Sesion::usuarioAutenticado()) {
            return $this->redirect()->toRoute('login');
        }
        
        $usuarioId = Sesion::obtenerIdUsuario();
        $carrito = $this->obtenerCarrito($usuarioId);
        
        $db = Database::getConexion();
        $modeloArticulos = new Articulos($db);
        
        $articulos = [];
        $cantidades = [];
        $total = 0;
        
        foreach ($carrito as $item) {
            $articulo = $modeloArticulos->obtenerPorId($item['id']);
            if ($articulo) {
                $articulos[] = $articulo;
                $cantidades[] = $item['cantidad'];
                
                $precioFinal = Articulos::calcularPrecioFinal($articulo['precio'], $articulo['iva']);
                $total += $precioFinal * $item['cantidad'];
            }
        }
        
        return new ViewModel(array(
            'articulos' => $articulos,
            'cantidades' => $cantidades,
            'total' => $total,
        ));
    }
    
    /**
     * AcciÃ³n para eliminar un producto del carrito
     * 
     * @return Response
     */
    public function deleteAction()
    {
        // Verificar que el usuario estÃ© autenticado
        if (!Sesion::usuarioAutenticado()) {
            return $this->redirect()->toRoute('login');
        }
        
        $id = (int) $this->params()->fromRoute('id', $_GET['id'] ?? 0);
        if ($id <= 0) {
            return $this->redirect()->toRoute('carrito');
        }
        $usuarioId = Sesion::obtenerIdUsuario();
        
        $carrito = $this->obtenerCarrito($usuarioId);
        
        // Eliminar el producto del carrito
        $nuevoCarrito = [];
        foreach ($carrito as $item) {
            if ((int) $item['id'] !== $id) {
                $nuevoCarrito[] = $item;
            }
        }
        
        $this->guardarCarrito($usuarioId, $nuevoCarrito);
        
        return $this->redirect()->toRoute('carrito');
    }
    
    /**
     * AcciÃ³n para finalizar la compra (pedido)
     * 
     * @return ViewModel|Response
     */
    public function pedidoAction()
    {
        // Verificar que el usuario estÃ© autenticado
        if (!Sesion::usuarioAutenticado()) {
            return $this->redirect()->toRoute('login');
        }
        
        $usuarioId = Sesion::obtenerIdUsuario();
        $carrito = $this->obtenerCarrito($usuarioId);
        
        $db = Database::getConexion();
        $modeloArticulos = new Articulos($db);
        
        $total = 0;
        
        if (empty($carrito)) {
            return $this->redirect()->toRoute('carrito');
        }

        try {
            $db->beginTransaction();

            // Crear pedido cabecera
            $stmtPedido = $db->prepare(
                'INSERT INTO pedidos (usuario_id, total, created_at) VALUES (:usuario_id, 0, CURRENT_TIMESTAMP)'
            );
            $stmtPedido->execute(['usuario_id' => (int) $usuarioId]);
            $pedidoId = (int) $db->lastInsertId();

            // Procesar líneas y stock
            $stmtLinea = $db->prepare(
                'INSERT INTO lineas_pedido (pedido_id, articulo_id, cantidad, precio_unitario, iva)
                 VALUES (:pedido_id, :articulo_id, :cantidad, :precio_unitario, :iva)'
            );

            foreach ($carrito as $item) {
                $articuloId = (int) $item['id'];
                $cantidad = (int) $item['cantidad'];
                $articulo = $modeloArticulos->obtenerPorId($articuloId);

                if (!$articulo) {
                    continue;
                }

                if ((int) $articulo['stock'] < $cantidad) {
                    $db->rollBack();
                    return new ViewModel(array(
                        'total' => 0,
                        'error' => 'No hay stock suficiente para completar el pedido.',
                    ));
                }

                $precioFinal = Articulos::calcularPrecioFinal($articulo['precio'], $articulo['iva']);
                $total += $precioFinal * $cantidad;

                $modeloArticulos->reducirStock($articuloId, $cantidad);

                $stmtLinea->execute([
                    'pedido_id' => $pedidoId,
                    'articulo_id' => $articuloId,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $articulo['precio'],
                    'iva' => $articulo['iva'],
                ]);
            }

            $db->prepare('UPDATE pedidos SET total = :total WHERE id = :id')
               ->execute(['total' => $total, 'id' => $pedidoId]);

            $db->commit();
        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            return new ViewModel(array(
                'total' => 0,
                'error' => 'Error al procesar el pedido: ' . $e->getMessage(),
            ));
        }

        // Limpiar carrito tras procesar correctamente.
        $this->guardarCarrito($usuarioId, []);
        
        // Se muestra el pedido procesado
        return new ViewModel(array(
            'total' => $total,
        ));
    }
    
    /**
     * AcciÃ³n para finalizar el pedido (limpiar carrito)
     * 
     * @return Response
     */
    public function finPedidoAction()
    {
        // Verificar que el usuario estÃ© autenticado
        if (!Sesion::usuarioAutenticado()) {
            return $this->redirect()->toRoute('login');
        }
        
        $usuarioId = Sesion::obtenerIdUsuario();
        
        // Limpiar el carrito
        $this->guardarCarrito($usuarioId, []);
        
        return $this->redirect()->toRoute('home');
    }
}

/**
 * Factory para crear el controlador
 */
class CarritoControllerFactory
{
    public function __invoke($sm)
    {
        return new CarritoController();
    }
}


