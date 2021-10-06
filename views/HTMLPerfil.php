<?php
require_once(__DIR__.'/../core/HTML/HTMLUserForm.php');
require_once(__DIR__.'/../core/HTML/HTMLCalendarioForm.php');
require_once(__DIR__.'/../core/HTML/HTMLVacunaForm.php');
require_once(__DIR__.'/../core/HTML/HTMLVacunacionForm.php');
class HTMLPerfil{
    protected $content;
    public function __construct($nombre,$accion,$listado=[],$error=[],$listado_aux = [])
    {
        if($nombre == 'usuarios')
            $this->content = new HTMLTag_container('section',['class'=>'ficha m-3 mx-auto text-center border rounded border-success'],[new HTMLUserForm($accion,$listado,$error)]);
        else if($nombre == 'vacunas')
            $this->content = new HTMLTag_container('section',['class'=>'ficha m-3 mx-auto text-center border rounded border-success'],[new HTMLVacunaForm($accion,$listado,$error)]);
        else if($nombre == 'calendario')    
            $this->content = new HTMLTag_container('section',['class'=>'ficha m-3 mx-auto text-center border rounded border-success'],[new HTMLCalendarioForm($accion,$listado,$listado_aux,$error)]);
        else if($nombre == 'vacunacion')
            $this->content = new HTMLTag_container('section',['class'=>'ficha m-3 mx-auto text-center border rounded border-success'],[new HTMLVacunacionForm($accion,$listado,$error)]);
    
    }
    public function getContent(){
        return $this->content;
    }
}
?>