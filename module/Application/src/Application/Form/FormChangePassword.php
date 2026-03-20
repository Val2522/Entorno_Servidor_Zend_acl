<?php
/**
 * Formulario de Cambio de ContraseÃ±a
 * 
 * Formulario para que los usuarios cambien su contraseÃ±a
 */

namespace Application\Form;

use Laminas\Form\Form;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Submit;

class FormChangePassword extends Form
{
    /**
     * Constructor del formulario
     */
    public function __construct()
    {
        parent::__construct('formChangePassword');
        
        // Campo: Nueva contraseÃ±a
        $this->add(array(
            'name' => 'password',
            'type' => Password::class,
            'options' => array(
                'label' => 'Nueva contraseÃ±a:',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Nueva contraseÃ±a',
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


