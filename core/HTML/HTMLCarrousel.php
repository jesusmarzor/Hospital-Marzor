<?php
class HTMLCarrousel{
    private $imagenes = [];
    public function __construct($imagenes){
        foreach($imagenes as $imagen){
            array_push($this->imagenes,$imagen);
        }
    }
    public function render(){
        echo <<< HTML
        <section>
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
        HTML;
            foreach($this->imagenes as $imagen){
                $activo = '';
                if($imagen == $this->imagenes[0]){
                    $activo = 'active';
                }
                echo '<div class="carousel-item '.$activo.'">
                        <img src="views/css/img/'.$imagen.'" class="d-block w-100" alt="imagen">
                    </div>
                ';
            }
        echo <<< HTML
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
            </a>
        </div>
        </section>
        HTML;
    }
    
}