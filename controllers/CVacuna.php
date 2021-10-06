<?php
require_once(__DIR__.'/../models/MVacuna.php');
require_once(__DIR__.'/../models/MLog.php');
require_once(__DIR__.'/../core/validacionFormulario.php');

class CVacuna extends AbstractController {
    protected $mdvacuna,$mdlog;
    public function __construct($req  ,$met,$par=null,$webpage=null){
        $this->mdvacuna = new MVacuna();
        $this->mdlog = new Mlog(); 
        parent::__construct($req,$met,$par,$webpage);
    }

    public function list() {
        $this->page->crearHeader('id_nav_listado');
            
        $vacunas= $this->mdvacuna->get();
        if(!$vacunas){
            $vacunas = [];
        }
        $this->page->addVacunas($vacunas);
    }

    public function borrarVacuna(){
        $this->page->crearHeader('');
        if(isset($_POST['btnEnviarDatos'])){
            if($_GET['vac']){
                if($this->mdvacuna->delete($_GET['vac'])>0){
                    $log['fecha'] = date("Y-m-d H:i:s");
                    $log['descripcion'] = 'Se ha borrado una vacuna';
                    $this->mdlog->insert($log);
                    $this->page->informar("Vacuna borrada correctamente",'vacunas/list');
                }else{
                    $this->page->informar("Error al borrar vacuna",'vacunas/list');
                }
            }
        }else{
            if($_GET['vac']){
                $id = $_GET['vac'];
                if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] == 'administrador'){
                    $vacuna = $this->mdvacuna->get('ID','=',$id);
                    if($vacuna){
                        $this->page->perfilVacuna('borrar',$vacuna[0]);
                    }
                }
            }
        }
    }

    public function editarVacuna(){
        $this->page->crearHeader('');
        if(isset($_POST['btnEnviarDatos'])){
            if(Session::existeSession('datosNuevos')){
                if($this->mdvacuna->update(Session::getSession('datosNuevos'),$_GET['vac'])>0){
                    $log['fecha'] = date("Y-m-d H:i:s");
                    $log['descripcion'] = 'Se ha borrado una vacuna';
                    $this->mdlog->insert($log);
                    $this->page->informar("Vacuna editada correctamente",'vacunas/list');
                }else
                    $this->page->informar("Error al editar vacuna",'vacunas/list');
            }else{
                $datos = [];
                $error = [];
                if(isset($_POST['nombre']))
                    $datos['nombre'] = $_POST['nombre'];
                else
                    $datos['nombre'] = Session::getSession('vacunaEditable')['nombre'];
                if(isset($_POST['acronimo']))
                    $datos['acronimo'] = $_POST['acronimo'];
                else
                    $datos['acronimo'] = Session::getSession('vacunaEditable')['acronimo'];
                if(isset($_POST['descripcion']) && !empty($_POST['descripcion']))    
                    $datos['descripcion'] = $_POST['descripcion'];
                else
                    $datos['descripcion'] = Session::getSession('vacunaEditable')['descripcion'];
                $error = validacionFormularioVacuna($_POST['nombre'],$_POST['acronimo']);
                if(sizeof($error) == 0){
                    Session::crearSession('datosNuevos',$datos);
                    $this->page->perfilVacuna('comprobar',Session::getSession('datosNuevos'),$error);
                }else{
                    if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] == 'administrador')
                        $this->page->perfilVacuna('editar',$datos,$error);
                }
            }
        }else{
            if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] == 'administrador'){
                $vacuna = $this->mdvacuna->get('ID','=',$_GET['vac']);
                Session::crearSession('vacunaEditable',$vacuna[0]);
                if($vacuna)
                    $this->page->perfilVacuna('editar',$vacuna[0]);  
              
            }
        }
    }

    public function addVacuna(){
        $this->page->crearHeader('');
        if(isset($_POST['btnEnviarDatos'])){
            if(Session::existeSession('vacunaNueva')){
                if($this->mdvacuna->insert(Session::getSession('vacunaNueva'))>0){
                    $log['fecha'] = date("Y-m-d H:i:s");
                    $log['descripcion'] = 'Se ha añadido una nueva vacuna';
                    $this->mdlog->insert($log);
                    $this->page->informar("Vacuna añadida correctamente",'vacunas/list');
                }else
                    $this->page->informar("Error al añadir vacuna",'vacunas/list');
            }else{
                $datos = [];
                $error = [];
                if(isset($_POST['nombre']))
                    $datos['nombre'] = $_POST['nombre'];
                if(isset($_POST['acronimo']))
                    $datos['acronimo'] = $_POST['acronimo'];
                if(isset($_POST['descripcion']) && !empty($_POST['descripcion']))    
                    $datos['descripcion'] = $_POST['descripcion'];
                $error = validacionFormularioVacuna($_POST['nombre'],$_POST['acronimo']);
                if(sizeof($error) == 0){
                    Session::crearSession('vacunaNueva',$datos);
                    $this->page->perfilVacuna('comprobar',Session::getSession('vacunaNueva'),$error);
                }else{
                    if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] == 'administrador')
                        $this->page->perfilVacuna('añadir',$datos,$error);
                }
            }
        }else{
            if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] == 'administrador')
                $this->page->perfilVacuna('añadir',[],$error=[]);  
        }
    }
}
?>