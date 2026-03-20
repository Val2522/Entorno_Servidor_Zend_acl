<?php
/**
 * Formulario de CategorÃ­as
 * 
 * Formulario para crear y editar categorÃ­as de videojuegos
 */

namespace Application\Form;

use Laminas\Form\Form;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Submit;

class FormCategoria extends Form
{
    /**
     * Constructor del formulario
     */
    public function __construct()
    {
        parent::__construct('formCategoria');
        
        // Campo: Nombre de la categorÃ­a
        $this->add(array(
            'name' => 'nombre',
            'type' => Text::class,
            'options' => array(
                'label' => 'Nombre de la categorÃ­a:',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Ej: Arcade',
            ),
        ));
        
        // BotÃ³n de envÃ­o
        $this->add(array(
            'name' => 'submit',
            'type' => Submit::class,
            'attributes' => array(
                'value' => 'Guardar',
                'class' => 'btn btn-primary',
            ),
        ));
    }
}


