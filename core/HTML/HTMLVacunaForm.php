<?php
class HTMLVacunaForm{
    protected $accion,$vacuna=[],$error = []; 
    public function __construct($accion,$vacuna = [],$error = []){
        $this->accion = $accion;
        foreach($vacuna as $datos => $valor){
            $this->vacuna[$datos] = $valor;
        }
        $this->error = $error;
    }
    public function render(){
        if ($this->accion == 'borrar' || $this->accion == 'comprobar'){
            $disabled='disabled="disabled"';
        }else{
            $disabled='';
        }
        // variables errores
        $nombre_error = '';
        $acronimo_error = '';
        
        // variables values
        
        if(isset($this->vacuna['nombre'])){
                $value_nombre ="value='{$this->vacuna['nombre']}'";   
        }else{
            $value_nombre ='';
        }
        if(isset($this->vacuna['acronimo'])){
            $value_acronimo ="value='{$this->vacuna['acronimo']}'";
        }else{
            $value_acronimo = '';
        }
        if(isset($this->vacuna['descripcion'])){
            $value_descripcion =$this->vacuna['descripcion'];
        }else{
            $value_descripcion = '';
        }
        
        if(isset($this->error['nombre'])){
            $nombre_error = 'style="border: 0.2em solid red; "';
        }
        if(isset($this->error['acronimo'])){
            $acronimo_error = 'style="border: 0.2em solid red; "';
        }
        
        echo <<< HTML
            <h2 class="title">Ficha de Vacuna</h2>
            <form class="formularioUsuario" action='{$_SERVER["REQUEST_URI"]}' method="POST" enctype="multipart/form-data">
                <div class ="row">
        HTML;
                    echo '<a href="vacunas/list" class="btn btn-sm btn-form ml-5">Atras</a>';
        echo <<< HTML
                </div>
                <div class="row justify-content-center align-items-center mt-3 mb-3">
                    <label class='' for="nombre">Nombre: </label>    
                    <input class='ml-5' id="nombre" type='text' name='nombre' $disabled $value_nombre />
                </div>
                <div class="row justify-content-center align-items-center mt-3 mb-3">
                    <label class='' for="acronimo">Acrónimo:</label>
                    <input class='ml-3' id="acronimo" type='text' name='acronimo' $disabled $value_acronimo />
                </div>
                <div class="row justify-content-center align-items-center mt-3 mb-3">
                    <label class='' for="descripcion">Descripción:</label>
                    <textarea class='ml-4' id="descripcion" name="descripcion" $disabled>$value_descripcion</textarea>    
                </div>
        HTML;
            if($this->accion == 'comprobar'){
                echo <<< HTML
                        <div class="clearfix"></div>
                        <input type="submit" class="btn btn-sm btn-form m-3" value='Aceptar' name="btnEnviarDatos">
                    </form>
                HTML;
            }else{
                echo <<< HTML
                        <div class="clearfix"></div>
                        <input type="submit" class="btn btn-sm btn-form m-3" value=$this->accion name="btnEnviarDatos">
                    </form>
                HTML;
            }
    }
    
}