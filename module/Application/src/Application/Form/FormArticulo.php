<?php
/**
 * Formulario de ArtÃ­culos
 * 
 * Formulario para crear y editar videojuegos en la tienda
 */

namespace Application\Form;

use Laminas\Form\Form;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Submit;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\GreaterThanOrEqual;

class FormArticulo extends Form
{
    /**
     * Constructor del formulario
     * 
     * @param array $categorias Array de categorÃ­as disponibles
     */
    public function __construct($categorias = [])
    {
        parent::__construct('formArticulo');
        
        // Campo: Nombre del artÃ­culo
        $this->add(array(
            'name' => 'nombre',
            'type' => Text::class,
            'options' => array(
                'label' => 'Nombre del Videojuego:',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Ej: Tetris',
            ),
        ));
        
        // Campo: Precio
        $this->add(array(
            'name' => 'precio',
            'type' => Number::class,
            'options' => array(
                'label' => 'Precio:',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'step' => '0.01',
                'placeholder' => '9.99',
            ),
        ));
        
        // Campo: IVA (%)
        $this->add(array(
            'name' => 'iva',
            'type' => Number::class,
            'options' => array(
                'label' => 'IVA (%):',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'value' => '21',
            ),
        ));
        
        // Campo: DescripciÃ³n
        $this->add(array(
            'name' => 'descripcion',
            'type' => Textarea::class,
            'options' => array(
                'label' => 'DescripciÃ³n:',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'rows' => 5,
            ),
        ));
        
        // Campo: Stock
        $this->add(array(
            'name' => 'stock',
            'type' => Number::class,
            'options' => array(
                'label' => 'Stock disponible:',
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
        
        // Campo: CategorÃ­a
        $opciones = [];
        foreach ($categorias as $cat) {
            $opciones[$cat['id']] = $cat['nombre'];
        }
        
        $this->add(array(
            'name' => 'CategoriaId',
            'type' => Select::class,
            'options' => array(
                'label' => 'CategorÃ­a:',
                'value_options' => $opciones,
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
        
        // Campo: Imagen
        $this->add(array(
            'name' => 'photo',
            'type' => File::class,
            'options' => array(
                'label' => 'Selecciona imagen:',
            ),
            'attributes' => array(
                'class' => 'form-control',
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


