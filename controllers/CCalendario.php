<?php
require_once(__DIR__.'/../models/MVacuna.php');
require_once(__DIR__.'/../models/MCalendario.php');
require_once(__DIR__.'/../core/AbstractController.php');
require_once(__DIR__.'/../core/validacionFormulario.php');
require_once(__DIR__.'/../models/MLog.php');
class CCalendario extends AbstractController{
    protected $mdcalendario,$mdvacuna,$mdlog;
    public function __construct($req  ,$met,$par=null,$webpage=null){  //
        $this->mdcalendario= new MCalendario();                // Construir modelo de datos
        $this->mdvacuna = new MVacuna();
        $this->mdlog = new MLog();
        parent::__construct($req,$met,$par,$webpage);  // Constructor de superclase
    }
    public function showCalendar() {
        $this->page->crearHeader('id_nav_calendario');
        $calendario= $this->mdcalendario->get('','','',"meses_ini",'ASC');
        $vacunas = $this->mdvacuna->get();
        $this->page->createCalendario($calendario,$vacunas);
    }
    public function list() {
        $this->page->crearHeader('id_nav_listado');
            
        $calendario= $this->mdcalendario->get('','','',"meses_ini",'ASC');
        $vacunas= $this->mdvacuna->get();
        if(!$calendario){
            $calendario = [];
        }
        if(!$vacunas){
            $vacunas = [];
        }
        $this->page->addCalendario($calendario,$vacunas);
    }
    public function borrarCalendario(){
        $this->page->crearHeader('');
        if(isset($_POST['btnEnviarDatos'])){
            if($_GET['c']){
                if($this->mdcalendario->delete($_GET['c'])>0){
                    $log['fecha'] = date("Y-m-d H:i:s");
                    $log['descripcion'] = 'Se ha borrado una vacuna del calendario';
                    $this->mdlog->insert($log);
                    $this->page->informar("Vacuna del calendario borrada correctamente",'calendario/list');
                }else{
                    $this->page->informar("Error al borrar la vacuna del calendario",'calendario/list');
                }
            }
        }else{
            if($_GET['c']){
                $id = $_GET['c'];
                if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] == 'administrador'){
                    $calendario = $this->mdcalendario->get('ID','=',$id,'meses_ini','ASC');
                    if($calendario){
                        $this->page->perfilCalendario('borrar',$calendario[0]);
                    }
                }
            }
        }
    }

    public function editarCalendario(){
        $this->page->crearHeader('');
        if(isset($_POST['btnEnviarDatos'])){
            if(Session::existeSession('datosNuevos')){
                if($this->mdcalendario->update(Session::getSession('datosNuevos'),$_GET['c'])>0){
                    $log['fecha'] = date("Y-m-d H:i:s");
                    $log['descripcion'] = 'Se ha editado una vacuna del calendario';
                    $this->mdlog->insert($log);
                    $this->page->informar("Vacuna del calendario editada correctamente",'calendario/list');
                }else
                    $this->page->informar("Error al editar la vacuna del calendario",'calendario/list');
            }else{
                $datos = [];
                $error = [];
                if(isset($_POST['sexo']))
                    $datos['sexo'] = $_POST['sexo'];
                else
                    $datos['sexo'] = Session::getSession('calendarioEditable')['sexo'];
                if(isset($_POST['meses_ini']))
                    $datos['meses_ini'] = $_POST['meses_ini'];
                else
                    $datos['meses_ini'] = Session::getSession('calendarioEditable')['meses_ini'];
                if(isset($_POST['meses_fin']))
                    $datos['meses_fin'] = $_POST['meses_fin'];
                else
                    $datos['meses_fin'] = Session::getSession('calendarioEditable')['meses_fin'];
                if(isset($_POST['tipo']))
                    $datos['tipo'] = $_POST['tipo'];
                else
                    $datos['tipo'] = Session::getSession('calendarioEditable')['tipo'];
                if(isset($_POST['comentario']) && !empty($_POST['comentario']))    
                    $datos['comentario'] = $_POST['comentario'];
                else
                    $datos['comentario'] = Session::getSession('calendarioEditable')['comentario'];
                $calendario_todo = $this->mdcalendario->get('IDvacuna','=',Session::getSession('calendarioEditable')['IDvacuna'],'meses_ini','ASC');
                $calendario = [];
                if(!empty($calendario_todo)){
                    foreach($calendario_todo as $cal){
                        if($cal['ID'] != Session::getSession('calendarioEditable')['ID']){
                            array_push($calendario,$cal);
                        }
                    }
                }
                $error = validacionFormularioCalendario($datos['sexo'],$_POST['meses_ini'],$_POST['meses_fin'],$datos['tipo'],$calendario);
                if(sizeof($error) == 0){
                    Session::crearSession('datosNuevos',$datos);
                    $this->page->perfilCalendario('comprobar',Session::getSession('datosNuevos'),$error);
                }else{
                    if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] == 'administrador')
                        $this->page->perfilCalendario('editar',$datos,$error);
                }
            }
        }else{
            if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] == 'administrador'){
                $calendario = $this->mdcalendario->get('ID','=',$_GET['c'],'meses_ini','ASC');
                Session::crearSession('calendarioEditable',$calendario[0]);
                if($calendario){
                    $this->page->perfilCalendario('editar',$calendario[0]);  
                }
            }
        }
    }

    public function addCalendario(){
        $this->page->crearHeader('');
        $vacunas = $this->mdvacuna->get();
        if(isset($_POST['btnEnviarDatos'])){
            if(Session::existeSession('calendarioNuevo')){
                if($this->mdcalendario->insert(Session::getSession('calendarioNuevo'))>0){
                    $log['fecha'] = date("Y-m-d H:i:s");
                    $log['descripcion'] = 'Se ha insertado una vacuna al calendario';
                    $this->mdlog->insert($log);
                    $this->page->informar("Vacuna añadida correctamente al calendario",'calendario/list');
                }else
                    $this->page->informar("Error al añadir vacuna en el calendario",'calendario/list');
            }else{
                $datos = [];
                $error = [];
                if(isset($_POST['idvacuna']))
                    $datos['IDvacuna'] = $_POST['idvacuna'];
                $datos['sexo'] = !empty($_POST['sexo']) ? $_POST['sexo'] : NULL;
                if(isset($_POST['meses_ini']))
                    $datos['meses_ini'] = $_POST['meses_ini'];
                if(isset($_POST['meses_fin']))
                    $datos['meses_fin'] = $_POST['meses_fin'];
                $datos['tipo'] = !empty($_POST['tipo']) ? $_POST['tipo'] : NULL;
                if(isset($_POST['comentario']) && !empty($_POST['comentario']))    
                    $datos['comentario'] = $_POST['comentario'];
                $calendario = $this->mdcalendario->get('IDvacuna','=',$_POST['idvacuna'],'meses_ini','ASC');
                $error = validacionFormularioCalendario($datos['sexo'],$_POST['meses_ini'],$_POST['meses_fin'],$datos['tipo'],$calendario);
                if(sizeof($error) == 0){
                    Session::crearSession('calendarioNuevo',$datos);
                    $this->page->perfilCalendario('comprobar',Session::getSession('calendarioNuevo'),$error,$vacunas);
                }else{
                    if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] == 'administrador')
                        $this->page->perfilCalendario('añadir',$datos,$error,$vacunas);
                }
            }
        }else{
            if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] == 'administrador')
                $this->page->perfilCalendario('añadir',[],$error=[],$vacunas);  
        }
    }
}
?>