<?php
/**
 * Controlador de AutenticaciÃ³n
 * 
 * Gestiona todas las operaciones de autenticaciÃ³n:
 * login, logout, registro, perfil, cambio de contraseÃ±a
 */

namespace Application\Controller;

use Laminas\Http\PhpEnvironment\Request;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Model\Database;
use Application\Model\Usuarios;
use Application\Model\Sesion;
use Application\Form\FormLogin;
use Application\Form\FormUsuario;
use Application\Form\FormChangePassword;

class AuthController extends AbstractActionController
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
     * AcciÃ³n de login - Autentica a los usuarios
     * 
     * @return ViewModel|Response
     */
    public function loginAction()
    {
        // Si ya estÃ¡ autenticado, redirige a inicio
        if (Sesion::usuarioAutenticado()) {
            return $this->redirect()->toRoute('home');
        }
        
        $form = new FormLogin();
        $error = null;
        
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setData($post);
            
            if ($form->isValid()) {
                $db = Database::getConexion();
                $modeloUsuarios = new Usuarios($db);
                
                $usuario = $modeloUsuarios->verificarCredenciales(
                    $post['username'],
                    $post['password']
                );
                
                if ($usuario) {
                    // Credenciales correctas
                    Sesion::iniciar($usuario);
                    return $this->redirect()->toRoute('home');
                } else {
                    $error = "Usuario o contraseÃ±a incorrectas.";
                }
            }
        }
        
        return new ViewModel(array(
            'form' => $form,
            'error' => $error,
        ));
    }
    
    /**
     * AcciÃ³n de logout - Cierra la sesiÃ³n del usuario
     * 
     * @return Response
     */
    public function logoutAction()
    {
        Sesion::cerrar();
        return $this->redirect()->toRoute('login');
    }
    
    /**
     * AcciÃ³n de registro - Permite crear nueva cuenta
     * 
     * @return ViewModel|Response
     */
    public function registroAction()
    {
        // Si ya estÃ¡ autenticado, redirige a inicio
        if (Sesion::usuarioAutenticado()) {
            return $this->redirect()->toRoute('home');
        }
        
        $form = new FormUsuario(false); // false = no es ediciÃ³n
        $error = null;
        
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setData($post);
            
            if ($form->isValid()) {
                $db = Database::getConexion();
                $modeloUsuarios = new Usuarios($db);
                
                // Verificar si el usuario ya existe
                if ($modeloUsuarios->usuarioExiste($post['username'])) {
                    $error = "El nombre de usuario ya existe.";
                } else {
                    // Crear nuevo usuario
                    $datos = [
                        'username' => $post['username'],
                        'password' => $post['password'],
                        'nombre' => $post['nombre'],
                        'email' => $post['email'],
                        'admin' => 0,
                    ];
                    
                    $modeloUsuarios->crear($datos);
                    
                    // Redirigir a login
                    return $this->redirect()->toRoute('login');
                }
            }
        }
        
        return new ViewModel(array(
            'form' => $form,
            'error' => $error,
        ));
    }
    
    /**
     * AcciÃ³n de perfil - Edita los datos del usuario
     * 
     * @return ViewModel|Response
     */
    public function perfilAction()
    {
        // Verificar que el usuario estÃ© autenticado
        if (!Sesion::usuarioAutenticado()) {
            return $this->redirect()->toRoute('login');
        }
        
        $username = $this->params()->fromRoute('username');
        $db = Database::getConexion();
        $modeloUsuarios = new Usuarios($db);
        
        $usuario = $modeloUsuarios->obtenerPorUsername($username);
        
        if (!$usuario) {
            return $this->notFoundAction();
        }
        
        $form = new FormUsuario(true); // true = es ediciÃ³n
        
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setData($post);
            
            if ($form->isValid()) {
                $datos = [
                    'nombre' => $post['nombre'],
                    'email' => $post['email'],
                ];
                
                $modeloUsuarios->actualizar($usuario['id'], $datos);
                
                return $this->redirect()->toRoute('home');
            }
        } else {
            // Rellenar el formulario con datos actuales
            $form->get('username')->setValue($usuario['username']);
            $form->get('nombre')->setValue($usuario['nombre']);
            $form->get('email')->setValue($usuario['email']);
        }
        
        return new ViewModel(array(
            'form' => $form,
            'usuario' => $usuario,
        ));
    }
    
    /**
     * AcciÃ³n para cambiar contraseÃ±a
     * 
     * @return ViewModel|Response
     */
    public function changepasswordAction()
    {
        // Verificar que el usuario estÃ© autenticado
        if (!Sesion::usuarioAutenticado()) {
            return $this->redirect()->toRoute('login');
        }
        
        $username = $this->params()->fromRoute('username');
        $db = Database::getConexion();
        $modeloUsuarios = new Usuarios($db);
        
        $usuario = $modeloUsuarios->obtenerPorUsername($username);
        
        if (!$usuario) {
            return $this->notFoundAction();
        }
        
        $form = new FormChangePassword();
        
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setData($post);
            
            if ($form->isValid()) {
                $modeloUsuarios->cambiarPassword($usuario['id'], $post['password']);
                
                return $this->redirect()->toRoute('home');
            }
        }
        
        return new ViewModel(array(
            'form' => $form,
            'usuario' => $usuario,
        ));
    }
}

/**
 * Factory para crear el controlador
 */
class AuthControllerFactory
{
    public function __invoke($sm)
    {
        return new AuthController();
    }
}


