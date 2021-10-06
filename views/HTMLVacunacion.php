<?php
require_once(__DIR__.'/../core/HTML/HTMLListaVacunas.php');
class HTMLVacunacion{
    protected $listaVacunas;
    public function __construct($lista_vacunacion,$calendario_completo,$vacunas)
    {
        Session::acabarSession('vacunacionNueva');
        Session::acabarSession('vacunacionEditable');
        Session::acabarSession('datosNuevos');
        $this->listaVacunas = new HTMLTag_container('section',[],[new HTMLListaVacunas($lista_vacunacion,$calendario_completo,$vacunas)]);
    }
    public function getContent(){
        return $this->listaVacunas;
    }

}
?>