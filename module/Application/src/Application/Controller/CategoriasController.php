<?php
/**
 * Controlador de CategorÃ­as
 * 
 * Gestiona todas las operaciones administrativas de categorÃ­as:
 * crear, editar, eliminar, listar
 */

namespace Application\Controller;

use Laminas\Http\PhpEnvironment\Request;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Model\Database;
use Application\Model\Categorias;
use Application\Model\Sesion;
use Application\Form\FormCategoria;
use Application\Form\FormConfirmacion;

class CategoriasController extends AbstractActionController
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
     * AcciÃ³n principal - Lista todas las categorÃ­as
     * 
     * @return ViewModel|Response
     */
    public function indexAction()
    {
        // Solo administradores pueden ver la pÃ¡gina de categorÃ­as
        if (!Sesion::esAdmin()) {
            return $this->notFoundAction();
        }
        
        $db = Database::getConexion();
        $modeloCategorias = new Categorias($db);
        
        $categorias = $modeloCategorias->obtenerTodas();
        
        return new ViewModel(array(
            'categorias' => $categorias,
        ));
    }
    
    /**
     * AcciÃ³n para crear una nueva categorÃ­a
     * 
     * @return ViewModel|Response
     */
    public function newAction()
    {
        // Verificar autenticaciÃ³n y permisos admin
        if (!Sesion::esAdmin()) {
            return $this->notFoundAction();
        }
        
        $form = new FormCategoria();
        
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setData($post);
            
            if ($form->isValid()) {
                $db = Database::getConexion();
                $modeloCategorias = new Categorias($db);
                
                $modeloCategorias->crear($post['nombre']);
                
                return $this->redirect()->toRoute('categorias');
            }
        }
        
        return new ViewModel(array(
            'form' => $form,
        ));
    }
    
    /**
     * AcciÃ³n para editar una categorÃ­a
     * 
     * @return ViewModel|Response
     */
    public function editAction()
    {
        // Verificar autenticaciÃ³n y permisos admin
        if (!Sesion::esAdmin()) {
            return $this->notFoundAction();
        }
        
        $id = $this->params()->fromRoute('id');
        
        $db = Database::getConexion();
        $modeloCategorias = new Categorias($db);
        
        $categoria = $modeloCategorias->obtenerPorId($id);
        
        if (!$categoria) {
            return $this->notFoundAction();
        }
        
        $form = new FormCategoria();
        
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setData($post);
            
            if ($form->isValid()) {
                $modeloCategorias->actualizar($id, $post['nombre']);
                
                return $this->redirect()->toRoute('categorias');
            }
        } else {
            // Rellenar el formulario con datos actuales
            $form->get('nombre')->setValue($categoria['nombre']);
        }
        
        $viewModel = new ViewModel(array(
            'form' => $form,
            'categoria' => $categoria,
        ));
        $viewModel->setTemplate('application/categorias/new');

        return $viewModel;
    }
    
    /**
     * AcciÃ³n para eliminar una categorÃ­a
     * 
     * @return ViewModel|Response
     */
    public function deleteAction()
    {
        // Verificar autenticaciÃ³n y permisos admin
        if (!Sesion::esAdmin()) {
            return $this->notFoundAction();
        }
        
        $id = $this->params()->fromRoute('id');
        
        $db = Database::getConexion();
        $modeloCategorias = new Categorias($db);
        
        $categoria = $modeloCategorias->obtenerPorId($id);
        
        if (!$categoria) {
            return $this->notFoundAction();
        }
        
        $form = new FormConfirmacion();
        
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setData($post);
            
            if (isset($post['si'])) {
                $modeloCategorias->eliminar($id);
            }
            
            return $this->redirect()->toRoute('categorias');
        }
        
        return new ViewModel(array(
            'form' => $form,
            'categoria' => $categoria,
        ));
    }
}

/**
 * Factory para crear el controlador
 */
class CategoriasControllerFactory
{
    public function __invoke($sm)
    {
        return new CategoriasController();
    }
}


