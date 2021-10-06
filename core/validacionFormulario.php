<?php
    function validacionFormularioVacunacion($fecha,$fabricante){
        $error = [];
        if(!verificarFecha_nac($fecha)){
            $error['fecha'] = "Fecha incorrecta";
        }
        if(empty($fabricante)){
            $error['fabricante'] = "Fabricante incorrecto";
        }
        return $error;
    }
    function validacionFormularioCalendario($sexo,$meses_ini,$meses_fin,$tipo,$calendario = []){
        $error = [];
        if(sizeof($calendario)>0){
            foreach($calendario as $c){
                if($c['meses_ini'] == $meses_ini || $c['meses_ini'] == $meses_fin || $c['meses_fin'] == $meses_ini || $c['meses_fin'] == $meses_fin || $meses_ini > $meses_fin || ($meses_ini < $c['meses_ini'] && $meses_fin > $c['meses_ini']) || ($meses_ini < $c['meses_fin'] && $meses_fin > $c['meses_fin'])){
                    $error['meses_ini'] = "Mes inicial incorrecto";
                    $error['meses_fin'] = "Mes final incorrecto";
                }    
            }
        }
        if(!isset($error['meses_ini']) ){
            if($meses_ini < -1 || (empty($meses_ini) && $meses_ini != 0) ){
                $error['meses_ini'] = "Mes inicial incorrecto";
            }
        }
        if(!isset($error['meses_fin'])){
            if($meses_fin < -1 || (empty($meses_fin) && $meses_ini != 0)){
                $error['meses_fin'] = "Mes final incorrecto";
            }
        }
        if(!verificarSexo($sexo)){
            $error['sexo'] = "sexo incorrecto";
        }
        if(empty($tipo)){
            $error['tipo'] = "Tipo incorrecto";
        }
        return $error;
    }
    function validacionFormularioVacuna($nombre,$acronimo){
        $error = [];
        if(empty($nombre)){
            $error['nombre'] = "Nombre incorrecto";
        }
        if(empty($acronimo)){
            $error['acronimo'] = "Acronimo incorrecto";
        }
        return $error;
    }
    function validacionFormulario($accion,$nombre,$apellidos,$dni,$email,$telefono,$fecha,$sexo,$clave1,$clave2,$rol,$estado){
        $error = [];
        if(!verificarNombre($nombre)){
            $error['nombre'] = "Nombre incorrecto";
        }
        if(!verificarApellidos($apellidos)){
            $error['apellidos'] = "Apellido incorrecto";
        }
        if($accion == 'añadir'){
            if(!verificarPassword($clave1,$clave2)){
                $error['clave'] = "Las claves no coinciden";
            }
            if(!verificarDni($dni)){
                $error['dni'] = "Dni incorrecto";
            }
        }
        if($accion == 'editar'){
            if(!verificarPassword($clave1,$clave2) && !(empty($clave1) && empty($clave2))){
                $error['clave'] = "Las claves no coinciden";
            }else{
                $_SESSION['usuarioEditable']['clave'] = password_hash($clave1,PASSWORD_DEFAULT,array("cost"=>12));
            }
        }
        if(!verificarEmail($email)){
            $error['email'] = "Email incorrecto";
        }
        if(!verificarTelefono($telefono)){
            $error['telefono'] = "Telefono incorrecto";
        }
        if(!verificarSexo($sexo)){
            $error['sexo'] = "Selecciona alguna opción";
        }
        if(!verificarFecha_nac($fecha)){
            $error['fechaNac'] = "Fecha incorrecta";
        }
        if(!verificarRol($rol)){
            $error['rol'] = "Selecciona alguna opción";
        }
        if(!verificarEstado($estado)){
            $error['estado'] = "Selecciona alguna opción";
        }
        return $error;
    }

    function verificarNombre($nombre){
        if(empty($nombre)){
            return false;
        }
        return true;
    }
    function verificarApellidos($apellidos){
        if(empty($apellidos)){
            return false;
        }
        return true;
    }
    function verificarDni($dni){
        if(empty($dni) || strlen($dni) != 9 || !is_numeric(substr($dni,0,8)) || !ctype_alpha(strtoupper(substr($dni,-1)))){
            return false;
        }
        return true;
    }
    function verificarEmail($email){
        if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
            return false;
        }
        return true;
    }
    function verificarTelefono($telefono){
        if(empty($telefono) || strlen($telefono) != 9 || !is_numeric($telefono)){
            return false;
        }
        return true;
    }
    function verificarFecha_nac($fecha){
        $fecha_array = explode('-', $fecha);
        if(!empty($fecha_array[2]) && !empty($fecha_array[1]) && !empty($fecha_array[0])){
            if(($fecha_array[2] < 1 || $fecha_array[2] > 31) || ($fecha_array[1] < 1 || $fecha_array[1] > 12 || ($fecha_array[0] < 1900 || $fecha_array[0] > date("Y")))){
                return false;
            }
            if($fecha_array[0] == date("Y")){
                if($fecha_array[1] > date("m")){
                    return false;
                }else if($fecha_array[1] == date("m")){
                    if($fecha_array[2] > date("d")){
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
    }
    function verificarSexo($sexo){
        if(empty($sexo)){
            return false;
        }
        return true;
    }
    function verificarPassword($clave1,$clave2){
        if($clave1 != $clave2 || strlen($clave1) < 6){
            return false;
        }
        return true;
    }
    function verificarRol($rol){
        if(empty($rol) || ($rol != 'paciente' && $rol != 'administrador' && $rol != 'sanitario')){
            return false;
        }
        return true;
    }
    function verificarEstado($estado){
        if(empty($estado) || ($estado != 'activo' && $estado != 'inactivo')){
            return false;
        }
        return true;
    }
?>