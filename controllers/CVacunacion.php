<?php
require_once(__DIR__.'/../models/MVacuna.php');
require_once(__DIR__.'/../models/MVacunacion.php');
require_once(__DIR__.'/../models/MCalendario.php');
require_once(__DIR__.'/../models/MLog.php');
require_once(__DIR__.'/../models/MUsuario.php');
require_once(__DIR__.'/../core/validacionFormulario.php');

class CVacunacion extends AbstractController {
    protected $mdvacunacion,$mdcalendario,$mdcalendarioTodo,$mdvacuna,$mdlog,$mduser;
    public function __construct($req  ,$met,$par=null,$webpage=null){
        $this->mdvacunacion = new MVacunacion();  
        $this->mdcalendario = new MCalendario(); 
        $this->mdvacuna = new MVacuna(); 
        $this->mdlog = new MLog();
        $this->mduser = new MUsuario();
        parent::__construct($req,$met,$par,$webpage);
    }
    
    public function cartillaVacunacion(){
        $this->page->crearHeader('');
        if($_GET['dni']){
            $dni = $_GET['dni'];
            if(Session::getSession('usuario')['rol'] == 'sanitario' || Session::getSession('usuario')['dni'] == $dni){
                $lista_vacunacion = [];
                $lista_vacunacion = $this->mdvacunacion->get('IDusuario','=',$dni);
                $calendario_completo= $this->mdcalendario->get('','','','meses_ini');
                if(isset($_POST['nombreVacuna_buscado'])){
                    $vacunas = $this->mdvacuna->get('nombre','LIKE',$_POST['nombreVacuna_buscado'].'%');
                    if(!$vacunas){
                        $vacunas = [];
                    }
                }else{
                    $vacunas = $this->mdvacuna->get();
                }
                $this->page->cartillaVacunacion($lista_vacunacion,$calendario_completo,$vacunas);
            }    
        }
    }

    public function borrarVacunacion(){
        $this->page->crearHeader('');
        if(isset($_POST['btnEnviarDatos'])){
            if($_GET['vac']){
                $dni_vacunacion = ($this->mdvacunacion->get('ID','=',$_GET['vac']))[0]['IDusuario'];
                if($this->mdvacunacion->delete($_GET['vac'])>0){
                    $log['fecha'] = date("Y-m-d H:i:s");
                    $log['descripcion'] = 'Un sanitario ha eliminado una vacuna a un usuario';
                    $this->mdlog->insert($log);
                    $this->page->informar("Vacunación borrada correctamente",'vacunacion?dni='.$dni_vacunacion);
                }else{
                    $this->page->informar("Error al borrar vacunación",'vacunacion?dni='.$dni_vacunacion);
                }
            }
        }else{
            if($_GET['vac']){
                $id = $_GET['vac'];
                if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] == 'sanitario'){
                    $vacunacion = $this->mdvacunacion->get('ID','=',$id);
                    if($vacunacion){
                        $this->page->perfilVacunacion('borrar',$vacunacion[0]);
                    }
                }
            }
        }
    }

    public function verVacunacion(){
        $this->page->crearHeader('');
        if($_GET['vac']){
            $id = $_GET['vac'];
            if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] == 'sanitario'){
                $vacunacion = $this->mdvacunacion->get('ID','=',$id);
                if($vacunacion){
                    $this->page->perfilVacunacion('ver',$vacunacion[0]);
                }
            }
        }
    }

    public function editarVacunacion(){
        $this->page->crearHeader('');
        if(isset($_POST['btnEnviarDatos'])){
            if(Session::existeSession('datosNuevos')){
                if($this->mdvacunacion->update(Session::getSession('datosNuevos'),$_GET['vac'])>0){
                    $log['fecha'] = date("Y-m-d H:i:s");
                    $log['descripcion'] = 'Un sanitario ha editado una vacuna a un usuario';
                    $this->mdlog->insert($log);
                    $this->page->informar("Vacunación editada correctamente",'vacunacion?dni='.Session::getSession('datosNuevos')['IDusuario']);
                }else
                    $this->page->informar("Error al editar vacunación",'vacunacion?dni='.Session::getSession('datosNuevos')['IDusuario']);
            }else{
                $datos = [];
                $error = [];
                $datos['IDusuario'] = Session::getSession('vacunacionEditable')['IDusuario'];
                $datos['IDcalendario'] = Session::getSession('vacunacionEditable')['IDcalendario'];
                if(isset($_POST['fecha']))
                    $datos['fecha'] = $_POST['fecha'];
                else
                    $datos['fecha'] = Session::getSession('vacunacionEditable')['fecha'];
                if(isset($_POST['fabricante']))
                    $datos['fabricante'] = $_POST['fabricante'];
                else
                    $datos['fabricante'] = Session::getSession('vacunacionEditable')['fabricante'];
                if(isset($_POST['comentario']) && !empty($_POST['comentario']))    
                    $datos['comentario'] = $_POST['comentario'];
                else
                    $datos['comentario'] = Session::getSession('vacunacionEditable')['comentario'];
                $error = validacionFormularioVacunacion($datos['fecha'],$_POST['fabricante']);
                if(sizeof($error) == 0){
                    Session::crearSession('datosNuevos',$datos);
                    $this->page->perfilVacunacion('comprobar',Session::getSession('datosNuevos'),$error);
                }else{
                    if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] == 'sanitario')
                        $this->page->perfilVacunacion('editar',$datos,$error);
                }
            }
        }else{
            if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] == 'sanitario'){
                $vacunacion = $this->mdvacunacion->get('ID','=',$_GET['vac']);
                Session::crearSession('vacunacionEditable',$vacunacion[0]);
                if($vacunacion)
                    $this->page->perfilVacunacion('editar',$vacunacion[0]);  
              
            }
        }
    }

    public function addVacunacion(){
        $this->page->crearHeader('');
        if(isset($_POST['btnEnviarDatos'])){
            if(Session::existeSession('vacunacionNueva')){
                if($this->mdvacunacion->insert(Session::getSession('vacunacionNueva'))>0){
                    $log['fecha'] = date("Y-m-d H:i:s");
                    $log['descripcion'] = 'Un sanitario le ha pinchado una vacuna a un usuario';
                    $this->mdlog->insert($log);
                    $this->page->informar("Vacunación añadida correctamente",'vacunacion?dni='.Session::getSession('vacunacionNueva')['IDusuario']);
                }else
                    $this->page->informar("Error al añadir vacunación",'vacunacion?dni='.Session::getSession('vacunacionNueva')['IDusuario']);
            }else{
                $datos = [];
                $error = [];
                if(isset($_GET['dni']))
                    $datos['IDusuario'] = $_GET['dni'];
                if(isset($_GET['c']))
                    $datos['IDcalendario'] = $_GET['c'];
                if(isset($_POST['fecha']))
                    $datos['fecha'] = $_POST['fecha'];
                else
                    $datos['fecha'] = strftime("%Y-%m-%d", time());
                if(isset($_POST['fabricante']))
                    $datos['fabricante'] = $_POST['fabricante'];
                if(isset($_POST['comentario']) && !empty($_POST['comentario']))    
                    $datos['comentario'] = $_POST['comentario'];
                $error = validacionFormularioVacunacion($datos['fecha'],$_POST['fabricante']);
                if(sizeof($error) == 0){
                    Session::crearSession('vacunacionNueva',$datos);
                    $this->page->perfilVacunacion('comprobar',Session::getSession('vacunacionNueva'),$error);
                }else{
                    if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] == 'sanitario')
                        $this->page->perfilVacunacion('añadir',$datos,$error);
                }
            }
        }else{
            if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] == 'sanitario')
                $this->page->perfilVacunacion('añadir',[],$error=[]);  
        }
    }
    public function estadisticas(){
        $this->page->crearHeader('');
        $numUsuarios = $this->mduser->get([],[],'','*','count');
        $vacunaciones = $this->mdvacunacion->get('','','','*','count');
        if($numUsuarios && $vacunaciones){
            $this->page->addEstadisticas($numUsuarios[0]['COUNT(*)'],$vacunaciones[0]['COUNT(*)']);
        }
    }
}
?>