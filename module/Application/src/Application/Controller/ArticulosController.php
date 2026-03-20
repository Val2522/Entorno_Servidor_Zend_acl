<?php
/**
 * Controlador de ArtÃ­culos
 * 
 * Gestiona todas las operaciones administrativas de artÃ­culos:
 * crear, editar, eliminar videojuegos
 */

namespace Application\Controller;

use Laminas\Http\PhpEnvironment\Request;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Model\Database;
use Application\Model\Articulos;
use Application\Model\Categorias;
use Application\Model\Sesion;
use Application\Form\FormArticulo;
use Application\Form\FormConfirmacion;

class ArticulosController extends AbstractActionController
{
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
     * AcciÃ³n para crear un nuevo artÃ­culo
     * 
     * @return ViewModel|Response
     */
    public function newAction()
    {
        // Solo administradores pueden crear artÃ­culos
        if (!Sesion::esAdmin()) {
            return $this->notFoundAction();
        }
        
        $db = Database::getConexion();
        $modeloCategorias = new Categorias($db);
        
        $categorias = $modeloCategorias->obtenerTodas();
        $form = new FormArticulo($categorias);
        
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $files = $this->getRequest()->getFiles();
            
            $form->setData($post);
            
            if ($form->isValid()) {
                // Procesar la imagen si fue recelada
                $nombreImagen = "";
                if (isset($files['photo']) && $files['photo']['error'] == 0) {
                    $nombreImagen = $this->guardarImagen($files['photo']);
                }
                
                $datos = [
                    'nombre' => $post['nombre'],
                    'precio' => $post['precio'],
                    'iva' => $post['iva'],
                    'descripcion' => $post['descripcion'],
                    'stock' => $post['stock'],
                    'CategoriaId' => $post['CategoriaId'],
                    'image' => $nombreImagen,
                ];
                
                $modeloArticulos = new Articulos($db);
                $modeloArticulos->crear($datos);
                
                return $this->redirect()->toRoute('home');
            }
        }
        
        return new ViewModel(array(
            'form' => $form,
            'categorias' => $categorias,
        ));
    }
    
    /**
     * AcciÃ³n para editar un artÃ­culo existente
     * 
     * @return ViewModel|Response
     */
    public function editAction()
    {
        // Solo administradores pueden editar artÃ­culos
        if (!Sesion::esAdmin()) {
            return $this->notFoundAction();
        }
        
        $id = $this->params()->fromRoute('id');
        
        $db = Database::getConexion();
        $modeloArticulos = new Articulos($db);
        $modeloCategorias = new Categorias($db);
        
        $articulo = $modeloArticulos->obtenerPorId($id);
        
        if (!$articulo) {
            return $this->notFoundAction();
        }
        
        $categorias = $modeloCategorias->obtenerTodas();
        $form = new FormArticulo($categorias);
        
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $files = $this->getRequest()->getFiles();
            
            $form->setData($post);
            
            if ($form->isValid()) {
                // Procesar la imagen si fue subida
                $nombreImagen = $articulo['image'];
                if (isset($files['photo']) && $files['photo']['error'] == 0) {
                    // Eliminar imagen anterior si existe
                    if ($nombreImagen) {
                        $this->eliminarImagen($nombreImagen);
                    }
                    $nombreImagen = $this->guardarImagen($files['photo']);
                }
                
                $datos = [
                    'nombre' => $post['nombre'],
                    'precio' => $post['precio'],
                    'iva' => $post['iva'],
                    'descripcion' => $post['descripcion'],
                    'stock' => $post['stock'],
                    'CategoriaId' => $post['CategoriaId'],
                    'image' => $nombreImagen,
                ];
                
                $modeloArticulos->actualizar($id, $datos);
                
                return $this->redirect()->toRoute('home');
            }
        } else {
            // Rellenar el formulario con datos actuales
            $form->get('nombre')->setValue($articulo['nombre']);
            $form->get('precio')->setValue($articulo['precio']);
            $form->get('iva')->setValue($articulo['iva']);
            $form->get('descripcion')->setValue($articulo['descripcion']);
            $form->get('stock')->setValue($articulo['stock']);
            $form->get('CategoriaId')->setValue($articulo['CategoriaId']);
        }
        
        $viewModel = new ViewModel(array(
            'form' => $form,
            'articulo' => $articulo,
            'categorias' => $categorias,
        ));
        $viewModel->setTemplate('application/articulos/new');

        return $viewModel;
    }
    
    /**
     * AcciÃ³n para eliminar un artÃ­culo
     * 
     * @return ViewModel|Response
     */
    public function deleteAction()
    {
        // Solo administradores pueden eliminar artÃ­culos
        if (!Sesion::esAdmin()) {
            return $this->notFoundAction();
        }
        
        $id = $this->params()->fromRoute('id');
        
        $db = Database::getConexion();
        $modeloArticulos = new Articulos($db);
        
        $articulo = $modeloArticulos->obtenerPorId($id);
        
        if (!$articulo) {
            return $this->notFoundAction();
        }
        
        $form = new FormConfirmacion();
        
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setData($post);
            
            if (isset($post['si'])) {
                // Eliminar imagen si existe
                if ($articulo['image']) {
                    $this->eliminarImagen($articulo['image']);
                }
                $modeloArticulos->eliminar($id);
            }
            
            return $this->redirect()->toRoute('home');
        }
        
        return new ViewModel(array(
            'form' => $form,
            'articulo' => $articulo,
        ));
    }

    /**
     * Lista de pedidos para administración.
     *
     * @return ViewModel|Response
     */
    public function pedidosAction()
    {
        if (!Sesion::usuarioAutenticado()) {
            return $this->redirect()->toRoute('login');
        }

        $db = Database::getConexion();

        $esAdmin = Sesion::esAdmin();
        $usuarioId = (int) Sesion::obtenerIdUsuario();

        if ($esAdmin) {
            $stmtPedidos = $db->query(
                'SELECT p.id, p.usuario_id, u.username, p.total, p.created_at
                 FROM pedidos p
                 LEFT JOIN usuarios u ON u.id = p.usuario_id
                 ORDER BY p.id DESC'
            );
            $pedidos = $stmtPedidos->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            $stmtPedidos = $db->prepare(
                'SELECT p.id, p.usuario_id, u.username, p.total, p.created_at
                 FROM pedidos p
                 LEFT JOIN usuarios u ON u.id = p.usuario_id
                 WHERE p.usuario_id = :usuario_id
                 ORDER BY p.id DESC'
            );
            $stmtPedidos->execute(['usuario_id' => $usuarioId]);
            $pedidos = $stmtPedidos->fetchAll(\PDO::FETCH_ASSOC);
        }

        $lineasPorPedido = [];
        $stmtLineas = $db->prepare(
            'SELECT lp.pedido_id, a.nombre, lp.cantidad, lp.precio_unitario, lp.iva
             FROM lineas_pedido lp
             LEFT JOIN articulos a ON a.id = lp.articulo_id
             WHERE lp.pedido_id = :pedido_id'
        );

        foreach ($pedidos as $pedido) {
            $stmtLineas->execute(['pedido_id' => (int) $pedido['id']]);
            $lineasPorPedido[(int) $pedido['id']] = $stmtLineas->fetchAll(\PDO::FETCH_ASSOC);
        }

        return new ViewModel([
            'pedidos' => $pedidos,
            'lineasPorPedido' => $lineasPorPedido,
            'esAdmin' => $esAdmin,
        ]);
    }
    
    /**
     * Guarda una imagen subida en el servidor
     * 
     * @param array $file InformaciÃ³n del archivo subido
     * @return string Nombre del archivo guardado
     */
    private function guardarImagen($file)
    {
        $pathUpload = __DIR__ . '/../../../../../public/uploads/';
        if (!is_dir($pathUpload)) {
            mkdir($pathUpload, 0777, true);
        }
        $nombreOriginal = basename($file['name']);
        $nombreSeguro = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $nombreOriginal);
        
        if (move_uploaded_file($file['tmp_name'], $pathUpload . $nombreSeguro)) {
            return $nombreSeguro;
        }
        
        return "";
    }
    
    /**
     * Elimina una imagen del servidor
     * 
     * @param string $nombreImagen Nombre de la imagen a eliminar
     */
    private function eliminarImagen($nombreImagen)
    {
        $pathImagen = __DIR__ . '/../../../../../public/uploads/' . $nombreImagen;
        if (file_exists($pathImagen)) {
            unlink($pathImagen);
        }
    }
}

/**
 * Factory para crear el controlador
 */
class ArticulosControllerFactory
{
    public function __invoke($sm)
    {
        return new ArticulosController();
    }
}


