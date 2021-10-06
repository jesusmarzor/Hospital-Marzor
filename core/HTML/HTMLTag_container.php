<?php
class HTMLTag_container{
    private $label, $atr = [], $content = [];
    public function __construct($label,$atr = [],$elementos=[]){
        $this->label = $label;
        foreach($atr as $nombre => $valor){
            $this->atr[$nombre] = $valor;
        }
        foreach($elementos as $elemento){
            array_push($this->content,$elemento);
        }
    }
    public function render(){
        echo '<'.$this->label;
        
        foreach($this->atr as $atributo => $valor){
            if($atributo == null){
                echo " $valor";
            }else{
                echo " $atributo= '$valor' ";
            }
        }        
        echo '>';
        foreach($this->content as $elemento){
            if(is_string($elemento)){
                echo $elemento;
                if($elemento != $this->content[(sizeof($this->content)-1)]){
                    echo " ";
                }
            }else{
                $elemento->render();
            }
        }
        echo '</'.$this->label.'>';
    }
    public function setContent($content){
        $this->content=$content;
    }
    public function addContent($content){
        array_push($this->content, $content);
    }
    public function getContent(){
        return $this->content;
    }
}
?>