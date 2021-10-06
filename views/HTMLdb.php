<?php
require_once(__DIR__.'/../core/db_backup.php');
class HTMLdb
{
    private $pagina,$accion;
    public function __construct($accion)
    {
        $this->accion = $accion;
        $this->pagina = new HTMLTag_container('section', [], [new HTMLTag_container('div',['class'=>''],[$this->accion])]);
    }
    public function getContent()
    {
        return $this->pagina;
    }
}
