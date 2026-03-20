<?php
/**
 * Formulario de Usuario
 * 
 * Formulario para registro de nuevos usuarios y ediciÃ³n de perfil
 */

namespace Application\Form;

use Laminas\Form\Form;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Submit;

class FormUsuario extends Form
{
    /**
     * Constructor del formulario
     * 
     * @param bool $esEdicion True si es para editar perfil, False si es para registro
     */
    public function __construct($esEdicion = false)
    {
        parent::__construct('formUsuario');
        
        // Campo: Nombre de usuario (username)
        $this->add(array(
            'name' => 'username',
            'type' => Text::class,
            'options' => array(
                'label' => 'Nombre de usuario:',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Tu nombre de usuario',
                'readonly' => $esEdicion ? 'readonly' : null,
            ),
        ));
        
        // Campo: ContraseÃ±a (solo en registro)
        if (!$esEdicion) {
            $this->add(array(
                'name' => 'password',
                'type' => Password::class,
                'options' => array(
                    'label' => 'ContraseÃ±a:',
                ),
                'attributes' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Tu contraseÃ±a',
                ),
            ));
        }
        
        // Campo: Nombre completo
        $this->add(array(
            'name' => 'nombre',
            'type' => Text::class,
            'options' => array(
                'label' => 'Nombre completo:',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Tu nombre completo',
            ),
        ));
        
        // Campo: Email
        $this->add(array(
            'name' => 'email',
            'type' => Email::class,
            'options' => array(
                'label' => 'Email:',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'tu@email.com',
            ),
        ));
        
        // BotÃ³n de envÃ­o
        $this->add(array(
            'name' => 'submit',
            'type' => Submit::class,
            'attributes' => array(
                'value' => 'Aceptar',
                'class' => 'btn btn-primary',
            ),
        ));
    }
}


