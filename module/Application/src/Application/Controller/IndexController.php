<?php
/**
 * Controlador Principal (Index)
 * 
 * Gestiona la pÃ¡gina de inicio y visualizaciÃ³n de categorÃ­as
 * y artÃ­culos disponibles para la venta
 */

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Model\Database;
use Application\Model\Articulos;
use Application\Model\Categorias;
use Application\Model\Sesion;

class IndexController extends AbstractActionController
{
    /**
     * AcciÃ³n principal - Muestra todos los artÃ­culos
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $db = Database::getConexion();
        Database::inicializarBase();
        @Database::cargarDatos(); // @ para evitar error si ya existen datos
        
        $modeloArticulos = new Articulos($db);
        $modeloCategorias = new Categorias($db);
        
        $articulos = $modeloArticulos->obtenerTodos();
        $categorias = $modeloCategorias->obtenerTodas();
        
        return new ViewModel(array(
            'articles' => $articulos,
            'categories' => $categorias,
            'categorySelected' => null,
            'is_login' => \Application\Model\Sesion::usuarioAutenticado(),
            'is_admin' => \Application\Model\Sesion::esAdmin(),
        ));
    }
    
    /**
     * AcciÃ³n para filtrar artÃ­culos por categorÃ­a
     * 
     * @return ViewModel
     */
    public function categoriaAction()
    {
        $id = $this->params()->fromRoute('id', $_GET['id'] ?? 0);
        
        $db = Database::getConexion();
        $modeloArticulos = new Articulos($db);
        $modeloCategorias = new Categorias($db);
        
        $categoria = $modeloCategorias->obtenerPorId($id);
        
        if (!$categoria) {
            return $this->notFoundAction();
        }
        
        $articulos = $modeloArticulos->obtenerPorCategoria($id);
        $categorias = $modeloCategorias->obtenerTodas();
        
        $viewModel = new ViewModel(array(
            'articles' => $articulos,
            'categories' => $categorias,
            'categorySelected' => $categoria,
            'is_login' => \Application\Model\Sesion::usuarioAutenticado(),
            'is_admin' => \Application\Model\Sesion::esAdmin(),
        ));
        $viewModel->setTemplate('application/index/index');

        return $viewModel;
    }
}

/**
 * Factory para crear el controlador
 */
class IndexControllerFactory
{
    public function __invoke($sm)
    {
        return new IndexController();
    }
}


