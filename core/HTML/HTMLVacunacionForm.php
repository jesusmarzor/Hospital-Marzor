<?php
class HTMLVacunacionForm{
    protected $accion,$vacunacion=[],$error = []; 
    public function __construct($accion,$vacunacion = [],$error = []){
        $this->accion = $accion;
        foreach($vacunacion as $datos => $valor){
            $this->vacunacion[$datos] = $valor;
        }
        $this->error = $error;
    }
    public function render(){
        if ($this->accion == 'ver' || $this->accion == 'borrar' || $this->accion == 'comprobar'){
            $disabled='disabled="disabled"';
        }else{
            $disabled='';
        }
        // variables errores
        $fecha_error = '';
        $fabricante_error = '';
        
        // variables values
        if(isset($_GET['dni']))
            $value_dni = "value='{$_GET['dni']}'";
        else{
            if(isset($this->vacunacion['IDusuario']))
                $value_dni ="value='{$this->vacunacion['IDusuario']}'";
        }
        if(isset($_GET['c']))
            $value_calendario = "value={$_GET['c']}";
        else{
            if(isset($this->vacunacion['IDcalendario']))
                $value_calendario ="value='{$this->vacunacion['IDcalendario']}'";
        }
        if(isset($this->vacunacion['fecha'])){
                $value_fecha ="value='{$this->vacunacion['fecha']}'";   
        }else{
            $date = strftime("%Y-%m-%d", time());
            $value_fecha ="value={$date}";
        }
        if(isset($this->vacunacion['fabricante'])){
            $value_fabricante ="value='{$this->vacunacion['fabricante']}'";
        }else{
            $value_fabricante = '';
        }
        if(isset($this->vacunacion['comentario'])){
            $value_comentario =$this->vacunacion['comentario'];
        }else{
            $value_comentario = '';
        }
        
        if(isset($this->error['fecha'])){
            $fecha_error = 'style="border: 0.2em solid red; "';
        }
        if(isset($this->error['fabricante'])){
            $fabricante_error = 'style="border: 0.2em solid red; "';
        }
        
        echo <<< HTML
            <h2 class="title">Ficha de Vacunación</h2>
            <form class="formularioUsuario" action='{$_SERVER["REQUEST_URI"]}' method="POST" enctype="multipart/form-data">
                <div class ="row">
        HTML;
                    echo '<a href="vacunacion?dni='.substr($value_dni,7,-1).'" class="btn btn-sm btn-form ml-5">Atras</a>';
        echo <<< HTML
                </div>
                <div class="row justify-content-center align-items-center mt-3 mb-3">
                    <label class='' for="dni">Dni: </label>    
                    <input class='ml-5' id="dni" type='text' name='dni' disabled $value_dni/>
                </div>
                <div class="row justify-content-center d-none align-items-center mt-3 mb-3">
                    <label class='' for="calendario">Calendario:</label>
                    <input class='ml-3' id="calendario" type='text' name='calendario' disabled $value_calendario />
                </div>   
                <div class="row justify-content-center align-items-center mt-3 mb-3">
                    <label class='' for="fecha">Fecha:</label>
                    <input class='ml-5' $fecha_error id="fecha" type='text' name='fecha' $disabled $value_fecha />
                </div>
                
                <div class="row justify-content-center align-items-center mt-3 mb-3">
                        <label class='' for="fabricante">Fabricante:</label>
                        <input class='ml-4' $fabricante_error id="fabricante" type='text' placeholder='Fabricante' name='fabricante' $disabled $value_fabricante/>
                </div>
                <div class="row justify-content-center align-items-center mt-3 mb-3">
                    <label class='' for="comentario">Comentario:</label>
                    <textarea class='ml-4' id="comentario" name="comentario" $disabled>$value_comentario</textarea>    
                </div>
        HTML;
            if($this->accion != 'ver'){
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
    
}