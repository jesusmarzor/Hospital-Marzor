<?php
require_once(__DIR__.'/session.php');
class Request{
    protected $root,$section;
    protected $domain, $method, $path, $scheme;
    protected $parameters, $query, $parametersroute;

    public function __construct() {
        
        Session::empezarSesion();
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->domain = $_SERVER['HTTP_HOST'];
        if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443)
            $this->scheme= 'https';
        else
            $this->scheme= 'http';
        $this->query = $_SERVER['QUERY_STRING'];
        $p = strpos($_SERVER['SCRIPT_NAME'],'index.php');
        $this->root = substr($_SERVER['SCRIPT_NAME'], 1, $p-1);
        $this->section = $this->calcSection();
    }
    public function getMethod()       { return $this->method; }
    public function getDomain()       { return $this->domain; }
    public function getPath()         { return $this->path;}
    public function getQueryString()  { return $this->query; }
    public function getScheme()       { return $this->scheme; }
    public function getRoot()       { return $this->root;}
    public function getSection()    { return $this->section;}

    public function calcSection(){
        $cadena_parametros = strlen($this->query); // Longitud de la cadena de parametros
        $cadena_root = strlen($this->root);
        if($cadena_parametros != 0){ // Si tiene parametros
            return substr($_SERVER['REQUEST_URI'],$cadena_root,-($cadena_parametros+1));
        }

        return substr($_SERVER['REQUEST_URI'],$cadena_root);
    }

    // Devuelve el valor del parámetro $p
    // Filtra el contenido con htmlspecialchars
    public function getParam($p) {
        if (($p!==null) and array_key_exists($p,$this->parameters)) {
            return htmlspecialchars($this->parameters[$p]);
        }
        else
            return null;

}
}
?>