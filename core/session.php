<?php
class Session{
    public function __construct(){}

    public static function empezarSesion(){
        session_start();
    }
    public static function iniciarSesion($usuario){
        $_SESSION['usuario'] = $usuario;
    }
    public static function crearSession($nombreSession,$datos){
        $_SESSION[$nombreSession] = $datos;
    }
    public static function existeSession($session){
        if(isset($_SESSION[$session])){
            return true;
        }
        return false;
    }
    public static function getSession($session){
        if(isset($_SESSION[$session])){
            return $_SESSION[$session];
        }
        return null;
    }

    public static function acabarSession($session){
        if(isset($_SESSION[$session])){
            unset($_SESSION[$session]);
        }
    }

    public static function acabarSesiones(){
        // La sesión debe estar iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Borrar variables de sesión
        session_unset();

        // Borrar el parámetro de ID de sesión
        if (isset($_GET[session_name()]))
            unset($_GET[session_name()]);
        if (isset($_POST[session_name()]))
            unset($_POST[session_name()]);

        // Destruir sesión
        session_destroy();
    }

}
?>