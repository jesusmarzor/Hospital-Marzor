<?php
class HTMLFooter{
    private $footer;
    protected $parrafo;
    public function __construct($atr=[])
    {
        $this->footer = new HTMLTag_container('footer',$atr,[new HTMLTag_container('p',['class'=>'m-0'],[new HTMLTag_container('a',['class'=>'text-white','href'=>'#'],['Documentación ']),' &copy; Jesús Martín Zorrilla'])]);
    }
    public function getFooter(){
        return $this->footer;
    }
}
?>
