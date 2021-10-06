<?php
class HTMLUserForm{
    protected $accion,$usuario=[],$error = []; 
    public function __construct($accion,$usuario = [],$error = []){
        $this->accion = $accion;
        foreach($usuario as $datos => $valor){
            $this->usuario[$datos] = $valor;
        }
        $this->error = $error;
    }
    public function render(){
        if ($this->accion == 'ver' || $this->accion == 'borrar' || $this->accion == 'comprobar'){
            $disabled='disabled="disabled"';
            $hidden = 'hidden';
        }else{
            $disabled='';
            $hidden = '';
        }
        // variables deshabilitar en editar
        $disabled_dni = '';
        $disabled_paciente='';
        if($this->accion == 'editar'){
            $disabled_dni = "disabled";
            if(Session::getSession('usuario')['rol'] == 'paciente' || Session::getSession('usuario')['rol'] == 'sanitario'){
                $disabled_paciente = 'disabled = "disabled"';
            }
        }
        // variables check sexo
        $checked_M = '';
        $checked_F = '';
        $checked_O = '';
        
        // variables selecciona rol
        $selected_A = '';
        $selected_P = '';
        $selected_S = '';
        
        // variables selecciona estado
        $selected_act = '';
        $selected_inact = '';
    
        // variables errores
        $nombre_error = '';
        $apellidos_error = '';
        $dni_error = '';
        $telefono_error = '';
        $fecha_error = '';
        $email_error = '';
        $sexo_error = '';
        $clave_error = '';
        $rol_error = '';
        $estado_error = '';
    
        // variables values
        $value_nombre = '';
        $value_apellidos = '';
        $value_dni = '';
        $value_email = '';
        $value_telefono = '';
        $value_fechaNac = '';
    
        if(isset($this->usuario['nombre'])){
            $value_nombre ="value='{$this->usuario['nombre']}'";
        }
        if(isset($this->usuario['apellidos'])){
            $value_apellidos ="value='{$this->usuario['apellidos']}'";
        }
        if(isset($this->usuario['dni'])){
            $value_dni ="value='{$this->usuario['dni']}'";
        }
        if(isset($this->usuario['email'])){
            $value_email ="value='{$this->usuario['email']}'";
        }
        if(isset($this->usuario['telefono'])){
            $value_telefono ="value='{$this->usuario['telefono']}'";
        }
        if(isset($this->usuario['fechaNac'])){
            $value_fechaNac ="value='{$this->usuario['fechaNac']}'";
        }
        if(isset($this->usuario['fotografia'])){
            $fotografia = 'src="data:image/jpeg;base64,'.($this->usuario["fotografia"]).'"';
        }else{
            $fotografia = 'src="views/css/img/perfil.jpg"';
        }
        if(isset($this->usuario['sexo'])){
            switch($this->usuario['sexo']){
                case 'M':
                    $checked_M = 'checked';
                    break;
                case 'F':
                    $checked_F = 'checked';
                    break;
                case 'O':
                    $checked_O = 'checked';
            }
        }
        if(isset($this->usuario['rol'])){
            switch($this->usuario['rol']){
                case 'paciente':
                    $selected_P = 'selected';
                    break;
                case 'administrador':
                    $selected_A = 'selected';
                    break;
                case 'sanitario':
                    $selected_S = 'selected';
                    break;
            }
        }
        if(isset($this->usuario['estado'])){
            switch($this->usuario['estado']){
                case 'activo':
                    $selected_act = 'selected';
                    break;
                case 'inactivo':
                    $selected_inact = 'selected';
            }
        }
        if(isset($this->error['nombre'])){
            $nombre_error = 'style="border: 0.2em solid red; "';
        }
        if(isset($this->error['apellidos'])){
            $apellidos_error = 'style="border: 0.2em solid red; "';
        }
        if(isset($this->error['dni'])){
            $dni_error = 'style="border: 0.2em solid red; "';
        }
        if(isset($this->error['telefono'])){
            $telefono_error = 'style="border: 0.2em solid red; "';
        }
        if(isset($this->error['fechaNac'])){
            $fecha_error = 'style="border: 0.2em solid red; "';
        }
        if(isset($this->error['email'])){
            $email_error = 'style="border: 0.2em solid red; "';
        }
        if(isset($this->error['sexo'])){
            $sexo_error = 'style="box-shadow: 0 0 0.3em red; "';
        }
        if(isset($this->error['clave']) || sizeof($this->error) != 0 && $this->error != '' && $this->accion != 'editar'){
            $clave_error = 'style="border: 0.2em solid red; "';
        }
        if(isset($this->error['rol'])){
            $rol_error = 'style="border: 0.2em solid red; "';
        }
        if(isset($this->error['estado'])){
            $estado_error = 'style="border: 0.2em solid red; "';
        }
        
        echo <<< HTML
            <h2 class="title">Ficha de Usuario</h2>
            <form class="formularioUsuario" action='{$_SERVER["REQUEST_URI"]}' method="POST" enctype="multipart/form-data">
                <div class ="row">
                    <a href="user/list" class="btn btn-sm btn-form ml-5">Atras</a>
                </div>
                <div class="row justify-content-center align-items-center">
                    <img class="" width="180" $fotografia/>
                </div>
                <div class="row justify-content-center align-items-center">
                    <section class="seccionFoto">
                        <label class="labelFotografia" for="imagen">Fotografía</label>
                        <label for="imagen" class="botonImagen $hidden">Select file</label>
                        <input type="file" class="usuarioFile" id="imagen" name="imagen"/>
                    </section>
                </div>
                <div class="row justify-content-center align-items-center mt-3 mb-3">
                <label class='' for="nombre">Nombre: </label>    
                <input class='ml-3 input_usuario ' $nombre_error $disabled_paciente id="nombre" type='text' placeholder='Nombre' name='nombre' $disabled $value_nombre/>
                </div>
                <div class="row justify-content-center align-items-center mt-3 mb-3">
                <label class='' for="apellidos">Apellidos:</label>
                <input class='ml-2 input_usuario' $apellidos_error $disabled_paciente id="apellidos" type='text' placeholder='Apellidos' name='apellidos' $disabled $value_apellidos/>
                </div>   
                <div class="row justify-content-center align-items-center mt-3 mb-3">
                    <label class='' for="dni">Dni:</label>
                    <input class='ml-5 input_usuario' $disabled_dni $dni_error id="dni" $disabled_paciente type='text' placeholder='DNI' name='dni' $disabled $value_dni/>
                </div>
                
                <div class="row justify-content-center align-items-center mt-3 mb-3">
                        <label class='' for="email">Email:</label>
                        <input class='ml-4 input_usuario' $email_error id="email" type='text' placeholder='Email' name='email' $disabled $value_email/>
                </div>
                <div class="row justify-content-center align-items-center mt-3 mb-3">
                    <label class='' for="telefono">Teléfono:</label>
                    <input class='ml-3 input_usuario' $telefono_error id="telefono" type='text' placeholder='Teléfono' name='telefono' $disabled $value_telefono/>
                </div>
                <div class="row justify-content-center align-items-center mt-3 mb-3">
                        <label class='' for="fechaNac">Fecha Nac:</label>
                        <input class='ml-1 input_usuario' $fecha_error id="fechNac" type='text' $disabled_paciente placeholder='aaaa-mm-dd' name='fechaNac' $disabled $value_fechaNac/>
                </div>
                <div class="row justify-content-center align-items-center mt-3 mb-3">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-2 align-content-center">
                                <label class='' for="sexo">Sexo: </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class='form-check-input ml-2' $disabled $disabled_paciente $sexo_error $checked_M type='radio' id='masculino' name='sexo' value='M' />
                                <label class='form-check-label' for='masculino'>Masculino</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class='form-check-input' $disabled $disabled_paciente $sexo_error $checked_F type='radio' id='femenino' name='sexo' value='F' />
                                <label class='form-check-label' for='femenino'>Femenino</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class='form-check-input' $disabled $disabled_paciente $sexo_error $checked_O type='radio' id='otro' name='sexo' value='O' />
                                <label class='form-check-label' for='otro'>Otro</label>
                            </div>
                        </div>
                </div>
                <div class="row justify-content-center align-items-center m-3">
                    <label class=''>Clave:</label>
                    <input type='password' $clave_error class='ml-2' placeholder='Clave' name='clave1' $disabled/>
                    <input type='password' $clave_error class='ml-3' placeholder='Repetir Clave' name='clave2' $disabled/>
                </div>
        HTML;
                if($this->accion == 'añadir' || (($this->accion == 'ver' || $this->accion == 'editar') && Session::getSession('usuario')['rol'] == 'administrador')){
                    echo <<< HTML
                        <div class="row justify-content-center align-items-center m-3">
                            <label class='' for='rol'>Rol:</label>
                            <select class='form-control' $rol_error name="rol">
                                <option value='paciente' $disabled $selected_P>Paciente</option>
                                <option value='sanitario' $disabled $selected_S>Sanitario</option>
                                <option value='administrador' $disabled $selected_A>Administrador</option>
                            </select>
                        </div>
                        <div class="row justify-content-center align-items-center mt-3 mb-3">
                            <label class='' for='estado'>Estado:</label>
                            <select class='form-control m-4' $estado_error name="estado">
                                <option value='activo' $disabled $selected_act>Activo</option>
                                <option value='inactivo' $disabled $selected_inact>Inactivo</option>
                            </select>
                        </div>
                    HTML;
                }
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