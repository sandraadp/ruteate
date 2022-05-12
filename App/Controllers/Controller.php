<?php
namespace App\Controllers;

use League\Plates\Engine;

/**
 * Controller
 * 
 * Indica la ruta raíz para poder mostrar las vistas
 * 
 * Controla la  
 */
class Controller {    
    /**
     * templates
     *
     * @var object contiene la ruta raíz de las vistas
     */
    protected $templates;
    
    /**
     * __construct
     * 
     * Instancia la clase Engine, indicándole la ruta donde están las vistas
     *
     * @return void
     */
    public function __construct() {
        $this->templates = new Engine('../views');
    }
}