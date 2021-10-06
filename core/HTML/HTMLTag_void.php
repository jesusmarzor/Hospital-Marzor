<?php
class HTMLTag_void{
    private $label, $atr = [];
    public function __construct($label,$atr = []){
        $this->label = $label;
        foreach($atr as $nombre => $valor){
            $this->atr[$nombre] = $valor;
        }
    }
    public function render(){
        echo '<'.$this->label;
        
        foreach($this->atr as $atributo => $valor){
            echo " $atributo= '$valor' ";
        }
        
        echo '>';
    }
}
?>