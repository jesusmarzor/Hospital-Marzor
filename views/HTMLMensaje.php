<?php
class HTMLMensaje{
    protected $content;
    public function __construct($mensaje,$url)
    {
        $this->content = new HTMLTag_container('section',['class'=>'text-center'],[new HTMLTag_container('p',['class'=>'font-weight-bold p-4'],[$mensaje]),new HTMLTag_container('a',['class'=>'btn btn-form','href'=>$url],['Aceptar'])]);
        
    }
    public function getContent(){
        return $this->content;
    }
}
?>