<?php
class HTMLTag_html{
    private $content=[];
    public function __construct(){}
    public function render(){
        echo "<html lang=es>";
        foreach ($this->content as $element){
            echo $element->render();
        };
        echo "</html>";
    }
    public function addContent($content){
        array_push($this->content,$content);
    }
}

?>