<?php
class HTMLBody{
    protected $body = [];
    public function __construct(){
    }
    public function render(){
        echo "<body class='min-vh-100'>";
        foreach($this->body as $block){
            $block->render();
        }
        echo "<script src='views/js/jquery-3.6.0.min.js'></script>";
        echo "<script src='views/js/main.js'></script>";
        echo "<script src='views/js/bootstrap-4.6.0-dist/bootstrap.min.js'></script>";
        echo "</body>";
    }
    public function addBody($block){
        array_push($this->body,$block);
    }
}
?>