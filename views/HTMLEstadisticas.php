<?php
class HTMLEstadisticas
{
    private $estadisticas,$numUsuarios, $vacunaciones;
    public function __construct($numUsuarios,$vacunaciones)
    {
        $this->numUsuarios = new HTMLTag_container('section',['class'=>'text-center'],[new HTMLTag_container('h1',['class'=>''],['Usuarios totales del sistema']),new HTMLTag_container('p',['class'=>'estadisticas'],[$numUsuarios.' usuarios'])]);
        $this->vacunaciones = new HTMLTag_container('section',['class'=>'text-center'],[new HTMLTag_container('h1',['class'=>''],['Vacunas puestas en los ultimos 30 días']),new HTMLTag_container('p',['class'=>'estadisticas'],[$vacunaciones.' vacunas'])]);
        $this->estadisticas = new HTMLTag_container('section', [], [$this->numUsuarios,$this->vacunaciones]);
    }
    public function getContent()
    {
        return $this->estadisticas;
    }
}
