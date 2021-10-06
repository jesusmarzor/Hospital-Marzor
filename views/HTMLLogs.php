<?php
require_once(__DIR__ . '/../core/HTML/HTMLTag_logs.php');
class HTMLLogs{
    private $content;
    protected $logs;
    protected $titulo;
    public function __construct($logs,$atr=[])
    {
        $this->titulo = new HTMLTag_container('h1',['class'=>'title'],['Log de la página']);
        $this->logs = new HTMLTag_logs($logs);
        $this->content = new HTMLTag_container('div',['class'=>$atr],[$this->titulo,new HtmlTag_container('div',['class'=>'table-responsive'],[$this->logs])]);
    }

    public function getContent(){
        return $this->content;
    }

}
?>