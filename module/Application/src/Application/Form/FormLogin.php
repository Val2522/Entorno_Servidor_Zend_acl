<?php
/**
 * Formulario de Login
 * 
 * Formulario para que los usuarios inicien sesiÃ³n en la aplicaciÃ³n
 */

namespace Application\Form;

use Laminas\Form\Form;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Submit;

class FormLogin extends Form
{
    /**
     * Constructor del formulario
     */
    public function __construct()
    {
        parent::__construct('formLogin');
        
        // Campo: Nombre de usuario
        $this->add(array(
            'name' => 'username',
            'type' => Text::class,
            'options' => array(
                'label' => 'Nombre de usuario:',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Tu nombre de usuario',
            ),
        ));
        
        // Campo: ContraseÃ±a
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
        
        // BotÃ³n de envÃ­o
        $this->add(array(
            'name' => 'submit',
            'type' => Submit::class,
            'attributes' => array(
                'value' => 'Entrar',
                'class' => 'btn btn-primary btn-block',
            ),
        ));
    }
}


