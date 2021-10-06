<?php
require_once(__DIR__.'/../core/AbstractView.php');
require_once(__DIR__.'/../core/HTML/HTMLTag_container.php');
require_once(__DIR__.'/../core/HTML/HTMLTag_void.php');
require_once(__DIR__.'/HTMLHeader.php');
require_once(__DIR__.'/HTMLInicio.php');
require_once(__DIR__.'/HTMLEstadisticas.php');
require_once(__DIR__.'/HTMLFooter.php');
require_once(__DIR__.'/HTMLListado.php');
require_once(__DIR__.'/HTMLCalendario.php');
require_once(__DIR__.'/HTMLLogs.php');
require_once(__DIR__.'/HTMLPerfil.php');
require_once(__DIR__.'/HTMLVacunacion.php');
require_once(__DIR__.'/HTMLMensaje.php');
require_once(__DIR__.'/HTMLdb.php');
class WebPage extends AbstractView{
    protected $header,$content,$footer;
    protected $nav_menu= [
                    ['Inicio','','id_nav_index'],
                    ['Calendario','calendario','id_nav_calendario']
                ];
    protected $nav_active='id_nav_index';
    public function __construct($req)
    {
        parent::__construct($req);
        if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] != 'administrador'){
             array_push($this->nav_menu,['Listado Usuarios','user/list','id_nav_listado']);
        }
        if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] == 'administrador'){
            array_push($this->nav_menu,['Log','log','id_nav_logs']);
            array_push($this->nav_menu,['Copia de seguridad','copia_seguridad','id_nav_copia']);
            array_push($this->nav_menu,['Restaurar Base de Datos','restaurar_bd','id_nav_restaurar']);
        }
        $this->setPageTitle('Hospital Marzor');
        $this->header = new HTMLHeader($this->nav_menu,$this->nav_active);
        $this->content = new HTMLInicio();
        $this->header->setTitle(['Hospital Marzor']);
        $this->footer = new HTMLFooter(['class' => 'text-center']);
    }
    public function crearHeader($nav_active){
        $this->nav_active = $nav_active;
        $this->header = new HTMLHeader($this->nav_menu,$this->nav_active);
        $this->header->setTitle(['Hospital Marzor']);
    }
    public function cargarPaina(){
        $this->addMain($this->header->getHeader());
        $this->addMain($this->content->getContent());
        $this->addMain($this->footer->getFooter());
        $this->render();
    }
    public function addUsers($usuarios,$solicitudes = false){
        $this->content = new HTMLListado('usuarios',['class'=>'text-center']); 
        if(sizeof($usuarios)>0){       
            foreach ($usuarios as $usuario){
                $this->content->addUser($usuario['fotografia'],$usuario['nombre'],$usuario['apellidos'],$usuario['email'],$usuario['dni'],$usuario['rol'],
                                        'ver','editar','borrar','vacunas',$solicitudes);
            }
        }else{
            $this->content->addMensaje('No se han encontrado resultados');
        }
    }
    
    public function addVacunas($vacunas){
        $this->content = new HTMLListado('vacunas',['class'=>'text-center']); 
        if(sizeof($vacunas)>0){       
            foreach ($vacunas as $vacuna){
                $this->content->addVacuna($vacuna['ID'],$vacuna['nombre']);
            }
        }else{
            $this->content->addMensaje('No se han encontrado resultados');
        }
    }

    public function addCalendario($calendario,$vacunas){
        $this->content = new HTMLListado('calendario',['class'=>'text-center']); 
        if(sizeof($calendario) > 0 && sizeof($vacunas) > 0){       
            foreach ($calendario as $cal){
                foreach ($vacunas as $vacuna){
                    if($cal['IDvacuna'] == $vacuna['ID'])
                        $this->content->addCalendario($cal['ID'],$vacuna['nombre'],$cal['meses_ini'],$cal['meses_fin']);
                }
            }
        }else{
            $this->content->addMensaje('No se han encontrado resultados');
        }
    }

    public function createCalendario($calendario,$vacunas){
        $this->content = new HTMLCalendario();
        $this->content->addVacunas($vacunas);
        foreach($vacunas as $vacuna){
            $fila = [];
            foreach($calendario as $cal){
                if($cal['IDvacuna'] == $vacuna['ID']){
                    array_push($fila,$cal);
                }
            }
            if(!empty($fila)){
                $this->content->addfila($fila);
            }
        }
    }

    public function perfilUser($accion,$usuario=[],$error=[]){
        $this->content = new HTMLPerfil('usuarios',$accion,$usuario,$error);
    }

    public function cartillaVacunacion($lista_vacunacion,$calendario_completo,$vacunas){
        $this->content = new HTMLVacunacion($lista_vacunacion,$calendario_completo,$vacunas);
    }

    public function perfilVacunacion($accion,$vacunacion = [],$error=[]){
        $this->content = new HTMLPerfil('vacunacion',$accion,$vacunacion,$error);
    }
    public function perfilCalendario($accion,$calendario = [],$vacunas = [],$error=[]){
        $this->content = new HTMLPerfil('calendario',$accion,$calendario,$error,$vacunas);
    }

    public function perfilVacuna($accion,$vacuna=[],$error=[]){
        $this->content = new HTMLPerfil('vacunas',$accion,$vacuna,$error);
    }

    public function informar($mensaje,$url){
        $this->content = new HTMLMensaje($mensaje,$url);
    }
    public function addLogs($logs){
        $this->content = new HTMLLogs($logs,'container container-fluid text-center');
    }
    public function addEstadisticas($numUsuarios,$vacunaciones){
        $this->content = new HTMLEstadisticas($numUsuarios,$vacunaciones);
    }
    public function addDB($accion){
        $this->content = new HTMLdb($accion);
    }
}
?>