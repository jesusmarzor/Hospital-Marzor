<?php
class HTMLListaVacunas{
    protected $listaVacunacion = [],$calendarioPuesto = [],$calendarioFalta = [],$vacunas = []; 
    public function __construct($lista_vacunacion = [],$calendario_completo = [],$vacunas = []){
        $this->listaVacunacion = $lista_vacunacion;
        if(!empty($this->listaVacunacion)){
            foreach($this->listaVacunacion as $vacunacion){
                foreach($calendario_completo as $cal){
                    if($cal['ID'] == $vacunacion['IDcalendario'])
                        array_push($this->calendarioPuesto,$cal);
                }
            }
        }
        if(!empty($this->listaVacunacion)){
            foreach($this->calendarioPuesto as $cal){
                if (($clave = array_search($cal, $calendario_completo)) !== false) {
                    unset($calendario_completo[$clave]);
                }
            }
        }
        $this->calendarioFalta = $calendario_completo;
        $this->vacunas = $vacunas;
    }
    public function render(){
        if(Session::existeSession('vacunacionNueva')){
            Session::acabarSession('vacunacionNueva');
        }
        $nombre_buscado = '';
        if(isset($_POST['nombreVacuna_buscado'])){
            $nombre_buscado = $_POST['nombreVacuna_buscado'];
        }
        echo <<< HTML
            <a href="user/list" class="btn btn-sm btn-form ml-5 mt-5 mb-5">Atras</a>
            <form class = 'row justify-content-end mb-5 mr-5' action = '{$_SERVER["REQUEST_URI"]}' method = 'POST'>
                <input type ='text' name = 'nombreVacuna_buscado' class = 'input_vacuna' placeholder = 'Nombre...' value = $nombre_buscado>
                <input class = 'btn-vacuna btn-form btn mb-1 pt-1 pb-1 pr-2 pl-2' type = 'submit' value = 'Buscar'>
            </form>
        HTML;
        echo "<h1 class='title text-center'>Vacunas Puestas</h1>";
        echo "<div class='text-center'>";
            if(empty($this->listaVacunacion)){
                echo '<p class="mensajeVacuna text-center font-weight-bold p-5">No tiene puesta ninguna vacuna</p>';
            }else{
                if(empty($this->listaVacunacion)){
                    echo '<p class="mensajeVacuna text-center font-weight-bold p-5">No existe ninguna vacuna con ese nombre</p>';
                }
                echo '<section class="listado">';
                foreach($this->calendarioPuesto as $cal){
                    foreach($this->listaVacunacion as $vacunacion){
                        if($cal['ID'] == $vacunacion['IDcalendario']){
                            $this->crearListado($cal,$vacunacion['ID']);
                        }
                    }
                }
                echo '</section>';
            }
            echo "<h1 class='title text-center'>Vacunas que Faltan</h1>";
            if(empty($this->vacunas)){
                echo '<p class="mensajeVacuna text-center font-weight-bold p-5">No existe ninguna vacuna con ese nombre</p>';
            }
            echo '<section class="listado">';
            foreach($this->calendarioFalta as $cal){
                $this->crearListado($cal);
            }
            echo '</section>';
        echo '</div>';
    }
    public function crearListado($cal,$vacunacion = ''){
        foreach($this->vacunas as $vacuna){
            if($vacuna['ID'] == $cal['IDvacuna']){
                echo "<div class='mx-auto mt-3 mb-3 card' style='width: 18rem;'>";
                echo '<img class="card-img-top" src="views/css/img/vacuna.jpg" alt="Card image cap">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">'.$vacuna['nombre'].'</h5>';
                echo '<p class="card-text">Vacuna de los ';
                if($cal['meses_ini'] == $cal['meses_fin']){
                    if($cal['meses_ini'] > 15){
                        if($cal['meses_ini'] > 780){
                            echo 'mayores de 65 años';
                        }else{
                            echo ($cal['meses_ini']/12).' años</p>';
                        }
                    }else{
                        if($cal['meses_ini'] == -1){
                            echo 'recien nacidos</p>';
                        }else{
                            echo $cal['meses_ini'].' meses</p>';        
                        }
                    
                    }
                }else{
                    if($cal['meses_ini'] > 15){
                        if($cal['meses_ini'] > 780){
                            echo 'mayores de 65 años a los ';
                        }else{
                            echo ($cal['meses_ini']/12).' años a los ';
                        }
                    }else{
                        if($cal['meses_ini'] == -1){
                            echo 'recien nacidos a los';
                        }else{
                         echo $cal['meses_ini'].' meses a los ';     
                        }
                       
                    }
                    if($cal['meses_fin'] > 15){
                        if($cal['meses_ini'] > 780){
                            echo 'mayores de 65 años</p>';
                        }else{
                            echo ($cal['meses_fin']/12).' años</p>';
                        }
                    }else{
                        if($cal['meses_ini'] == -1){
                            echo 'recien nacidos</p>';
                        }else{
                            echo $cal['meses_fin'].' meses</p>';        
                        }
                    
                    }
                }
                if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] == 'sanitario'){
                    if($vacunacion != ''){
                        echo '<a class= "btn btn-sm btn-form ml-2" href = "verVacunacion?vac='.$vacunacion.'">Ver</a>';
                        echo '<a class= "btn btn-sm btn-form ml-2" href = "editVacunacion?vac='.$vacunacion.'">Editar</a>';
                        echo '<a class= "btn btn-sm btn-form ml-2" href = "delVacunacion?vac='.$vacunacion.'">Borrar</a>';
                    }else{
                        echo '<a class= "btn btn-sm btn-form ml-2" href = "addVacunacion?dni='.$_GET['dni'].'&c='.$cal['ID'].'">Añadir</a>';
                    }
    
                }
                echo "</div></div>";
            }
        }
    }
}
