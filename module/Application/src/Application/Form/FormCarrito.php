<?php
/**
 * Formulario de Carrito
 * 
 * Formulario para agregar productos al carrito de compra
 */

namespace Application\Form;

use Laminas\Form\Form;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Submit;

class FormCarrito extends Form
{
    /**
     * Constructor del formulario
     */
    public function __construct()
    {
        parent::__construct('formCarrito');
        
        // Campo oculto: ID del artÃ­culo
        $this->add(array(
            'name' => 'id',
            'type' => Hidden::class,
        ));
        
        // Campo: Cantidad
        $this->add(array(
            'name' => 'cantidad',
            'type' => Number::class,
            'options' => array(
                'label' => 'Cantidad:',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'min' => '1',
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


