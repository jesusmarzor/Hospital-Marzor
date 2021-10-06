<?php
require_once(__DIR__.'/../core/HTML/HTMLTag_calendario.php');
class HTMLCalendario{
    private $content,$content_calendario,$acciones_calendario='',$acciones_vacunas='';
    protected $titulo,$vacunas=[],$calendario;
    public function __construct($atr='')
    {
        $this->titulo = new HTMLTag_container('h1',['class'=>'title col-6-sm border-left border-right mx-auto m-0 p-2'],['Calendario de Vacunación']);
        $this->calendario = new HTMLTag_calendario(
                                            ['class' => 'table table-striped table-bordered text-center table-responsive-sm']
                                            );
        $this->content_calendario = new HTMLTag_container('div',['class'=>'container container-fluid text-center'],[$this->titulo,new HtmlTag_container('div',['class'=>'table-responsive'],[$this->calendario])]);
        $this->content = new HTMLTag_container('section',['class'=>$atr],[$this->content_calendario]);                                 
    }
    public function getContent(){
        return $this->content;
    }
    public function addfila($fila){
        $this->calendario->addFila($fila);
    }
    public function addVacunas($vacunas){
        $this->calendario->setVacunas($vacunas);
    }

}
?>