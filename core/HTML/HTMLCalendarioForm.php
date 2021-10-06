<?php
class HTMLCalendarioForm{
    protected $accion,$calendario=[],$error = [],$vacunas=[]; 
    public function __construct($accion,$calendario = [],$error = [],$vacunas=[]){
        $this->accion = $accion;
        foreach($calendario as $datos => $valor){
            $this->calendario[$datos] = $valor;
        }
        $this->vacunas = $vacunas;
        $this->error = $error;
    }
    public function render(){
        if ($this->accion == 'borrar' || $this->accion == 'comprobar'){
            $disabled='disabled="disabled"';
        }else{
            $disabled='';
        }
        // variables errores
        $sexo_error = '';
        $meses_ini_error = '';
        $meses_fin_error = '';
        $tipo_error = '';
        
        // variables values
        if(isset($this->calendario['IDvacuna'])){
            $option_selected = $this->calendario['IDvacuna'];
        }else{
            $option_selected = '';
        }
        $checked_M='';
        $checked_F='';
        $checked_T='';
        if(isset($this->calendario['sexo'])){
            switch($this->calendario['sexo']){
                case 'M':
                    $checked_M = 'checked';
                    break;
                case 'F':
                    $checked_F = 'checked';
                    break;
                case 'T':
                    $checked_T = 'checked';
            }
        }
        if(isset($this->calendario['meses_ini'])){
            $value_meses_ini ="value='{$this->calendario['meses_ini']}'";
        }else{
            $value_meses_ini = '';
        }
        if(isset($this->calendario['meses_fin'])){
            $value_meses_fin ="value='{$this->calendario['meses_fin']}'";
        }else{
            $value_meses_fin = '';
        }
        $checked_A = '';
        $checked_S = '';
        if(isset($this->calendario['tipo'])){
            switch($this->calendario['tipo']){
                case 'S':
                    $checked_S = 'checked';
                    break;
                case 'A':
                    $checked_A = 'checked';
            }
        }
        if(isset($this->calendario['comentario'])){
            $value_comentario =$this->calendario['comentario'];
        }else{
            $value_comentario = '';
        }
        
        if(isset($this->error['sexo'])){
            $sexo_error = 'style="box-shadow: 0 0 0.3em red; "';
        }
        if(isset($this->error['meses_ini'])){
            $meses_ini_error = 'style="border: 0.2em solid red; "';
        }
        if(isset($this->error['meses_fin'])){
            $meses_fin_error = 'style="border: 0.2em solid red; "';
        }
        if(isset($this->error['tipo'])){
            $tipo_error = 'style="box-shadow: 0 0 0.3em red; "';
        }
        echo <<< HTML
            <h2 class="title">Ficha de vacuna del calendario</h2>
            <form class="formularioUsuario" action='{$_SERVER["REQUEST_URI"]}' method="POST" enctype="multipart/form-data">
                <div class ="row">
        HTML;
                    echo '<a href="calendario/list" class="btn btn-sm btn-form ml-5">Atras</a>';
                echo '</div>';
                    if($this->accion == 'añadir'){
                        echo '<div class="row justify-content-center align-items-center mt-3 mb-3">';
                        echo '<label class="" for="vacuna">Vacuna: </label>';
                        echo '<select class="form-control form-control-md w-50 ml-3" id="vacuna" name="idvacuna">';
                            foreach($this->vacunas as $vacuna){
                                echo '<option value="'.$vacuna['ID'].'" ';
                                if($option_selected == $vacuna['ID']){
                                    echo 'selected ';
                                }
                                echo $disabled;
                                echo '>'.$vacuna['nombre'].'</option>';
                            }
                        echo '</select></div>';
                    }
        echo <<< HTML
                <div class="row justify-content-center align-items-center mt-3 mb-3"> 
                    <div class="col-2 align-content-center">
                            <label class='' for="sexo">Sexo: </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class='form-check-input ml-2' $disabled $sexo_error $checked_M type='radio' id='masculino' name='sexo' value='M' />
                        <label class='form-check-label' for='masculino'>Masculino</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class='form-check-input' $disabled $sexo_error $checked_F type='radio' id='femenino' name='sexo' value='F' />
                        <label class='form-check-label' for='femenino'>Femenino</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class='form-check-input' $disabled $sexo_error $checked_T type='radio' id='otro' name='sexo' value='T' />
                        <label class='form-check-label' for='todos'>Todos</label>
                    </div>
                </div>
                <div class="row justify-content-center align-items-center mt-3 mb-3">
                    <label class='' for="meses_ini">Mes inicial:</label>
                    <input class='ml-3' id="meses_ini" type='text' name='meses_ini' $meses_ini_error $disabled $value_meses_ini />
                </div>
                <div class="row justify-content-center align-items-center mt-3 mb-3">
                    <label class='' for="meses_fin">Mes final:</label>
                    <input class='ml-3' id="meses_fin" type='text' name='meses_fin'$meses_fin_error $disabled $value_meses_fin />
                </div>
                <div class="row justify-content-center align-items-center mt-3 mb-3">
                    <div class="col-2 align-content-center">
                            <label class='' for="tipo">Tipo: </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class='form-check-input ml-2' $disabled $tipo_error $checked_S type='radio' id='sistemico' name='tipo' value='S' />
                        <label class='form-check-label' for='sistemico'>Sistemico</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class='form-check-input' $disabled $tipo_error $checked_A type='radio' id='asistemico' name='tipo' value='A' />
                        <label class='form-check-label' for='asistemico'>Asistemico</label>
                    </div>    
                </div>
                <div class="row justify-content-center align-items-center mt-3 mb-3">
                    <label class='' for="comentario">Comentario:</label>
                    <textarea class='ml-4' id="comentario" name="comentario" $disabled>$value_comentario</textarea>    
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