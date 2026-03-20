<?php
/**
 * Formulario de ConfirmaciÃ³n (SÃ­/No)
 * 
 * Formulario genÃ©rico para pedir confirmaciÃ³n al usuario
 * antes de realizar acciones como eliminar
 */

namespace Application\Form;

use Laminas\Form\Form;
use Laminas\Form\Element\Submit;

class FormConfirmacion extends Form
{
    /**
     * Constructor del formulario
     */
    public function __construct()
    {
        parent::__construct('formConfirmacion');
        
        // BotÃ³n: SÃ­
        $this->add(array(
            'name' => 'si',
            'type' => Submit::class,
            'attributes' => array(
                'value' => 'SÃ­',
                'class' => 'btn btn-danger',
            ),
        ));
        
        // BotÃ³n: No
        $this->add(array(
            'name' => 'no',
            'type' => Submit::class,
            'attributes' => array(
                'value' => 'No',
                'class' => 'btn btn-secondary',
            ),
        ));
    }
}


