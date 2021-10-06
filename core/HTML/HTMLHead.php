<?php
class HTMLHead{
    private $title;
    private $css = [];
    private $base;

    public function __construct()
    {
        $this->title = 'Hospital Marzor';
        $this->addCSS('views/css/bootstrap-4.6.0-dist/bootstrap.min.css');
        $this->addCSS('views/css/styles.css');
    }

    public function setTitle($title){
        $this->title = $title;
    }

    public function addCSS($link){
        array_push($this->css,$link);
    }

    public function setBase($base){
        $this->base = $base;
    }

    public function render(){
        echo "<head>";
            echo "<meta charset='UTF-8'>";
            echo "<meta http-equiv='X-UA-Compatible' content='IE=edge'>";
            echo "<base href='".$this->base."'>";
            echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
            echo "<title>$this->title</title>";
            foreach ($this->css as $href){
                echo '<link rel="stylesheet" type="text/css" href="'.$href.'"/>';
            };
        echo "</head>";
    }

}

?>