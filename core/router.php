<?php
require_once(__DIR__.'/AbstractController.php');
class Router{
    protected $rutas;
    protected $request;

    public function __construct($req)
    {
        $this->request = $req;

        // Cargar configuracion de enrutado
        $json= file_get_contents(__DIR__.'/../config/routes.json');
        $this->rutas= json_decode($json, true);
        if(!$this->rutas){
            die("Error fatal en decodificacion de rutas de JSON");
        }
    }
    public static function compare_items($dato1,$dato2,$dato3){
        return true;
    }
    public function locateRoute(){
        $controlador = $this->request->getSection();
        if($controlador == '/calendario' || $controlador == '/' || isset($_POST['btn_resetFiltros'])){
            Session::acabarSession('busqueda');
            Session::acabarSession('order');
            Session::acabarSession('estado_busqueda');
        }
        $lista_rutas = array_keys($this->rutas); // Listo las posibles rutas
        foreach($lista_rutas as $ruta){          // Comparo si la ruta escrita en la url está en mis rutas
            if('/'.$ruta == $controlador){  // Si está
                return $this->rutas[$ruta];
            }
        }
        return null;
    }
    public function iniciarSesion(){
        if(!Session::existeSession('usuario')){
            @include_once(__DIR__ . "/../controllers/CUsuario.php");
            $sesionUsuario = new CUsuario($this->request,null,null,null);
            if(isset($_POST['dni']) && !empty($_POST['dni']) && isset($_POST['clave']) && !empty($_POST['clave']) ){
                $sesionUsuario->iniciarSesion($_POST['dni'],$_POST['clave']);
            }
        }else{
            if(isset($_POST['btnSalir'])){
                @include_once(__DIR__ . "/../controllers/CUsuario.php");
                $cerrarSesion = new CUsuario($this->request,null,null,null);
                $cerrarSesion->cerrarSesion();
            }
        }
    }
    public function execute($webpage) {
        $action = $this->locateRoute(); // Localizar el controlador solicitado
        // Añadir al objeto request los parámetros de la ruta (si hay)
        if(isset($action['controller']) && (($action['controller'] == 'CVacunacion' && $this->request->getSection() != '/estadisticas')|| $action['controller'] == 'CVacuna' || ($action['controller'] == 'CUsuario' && $this->request->getSection() != '/solicitud') || ($action['controller'] == 'CCalendario' && $this->request->getSection() != '/calendario')) && !Session::existeSession('usuario')){
            $action = null;
        }
        // $this->request->setParamsRoute($action['params']??null);
        $content = null;
        if ($action!=null) {   // Si la ruta existe
            // Intentar incluir el fichero del controlador
            @include_once(__DIR__ . "/../controllers/{$action['controller']}.php");
            // Y comprobar si existe el controlador y el método
            if (class_exists($action['controller']) && method_exists($action['controller'],$action['method'])) {
                $content = new $action['controller']($this->request,$action['method'],null,$webpage);
            }
        }
        return $content;
    }    
}
?>