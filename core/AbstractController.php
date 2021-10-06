<?php
abstract class AbstractController{
    protected $request;
    protected $page;

    public function __construct($req,$met=null,$par=null,$webpage=null){ //variables,metodo,parametros(para pasarle al metodo),vista
        $this->request = $req; // Nos olvidamos del $_.....

        // if ($webpage===null and !isset($this->page))    
        //     die('AbstractController: no está definida la página web');
        if ($webpage!==null)
            $this->page = $webpage;  // Página web
        if (is_string($met) and $met!=='') {    // Ejecutar acción (método)
            if ($par)   // Comprobar si se le pasan parámetros o no
                $this->$met($par);
            else{
                $this->$met(); // Si le pasas un metodo lo ejecuta
            }
        }
    }

    
}
?>