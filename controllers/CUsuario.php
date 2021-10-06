<?php
require_once(__DIR__.'/../models/MUsuario.php');
require_once(__DIR__.'/../models/MLog.php');
require_once(__DIR__.'/../core/validacionFormulario.php');
class CUsuario extends AbstractController {
    protected $mduser,$mdvacunacion,$mdlog;
    public function __construct($req  ,$met,$par=null,$webpage=null){
        $this->mduser= new MUsuario(); 
        $this->mdlog = new Mlog(); 
        parent::__construct($req,$met,$par,$webpage);
    }
    private function filtro_where(){
        // busqueda por nombre, dni, fecha min y fecha max
        $array_busqueda = ['nombreOapellidos'=>'','dni'=>'','fechamin'=>'','fechamax'=>''];
        if(isset($_POST['nombreOapellidos'])){
            $array_busqueda['nombreOapellidos'] = $_POST['nombreOapellidos'];
        }else{
            if(Session::existeSession('busqueda')){
                $array_busqueda['nombreOapellidos'] = Session::getSession('busqueda')['nombreOapellidos'];
            }
        }
        if(isset($_POST['dni_filtro'])){
            $array_busqueda['dni'] = $_POST['dni_filtro'];
        }else{
            if(Session::existeSession('busqueda')){
                $array_busqueda['dni'] = Session::getSession('busqueda')['dni'];
            }
        }
        if(isset($_POST['fechamin'])){
            $array_busqueda['fechamin'] = $_POST['fechamin'];
        }else{
            if(Session::existeSession('busqueda')){
                $array_busqueda['fechamin'] = Session::getSession('busqueda')['fechamin'];
            }
        }
        if(isset($_POST['fechamax'])){
            $array_busqueda['fechamax'] = $_POST['fechamax'];
        }else{
            if(Session::existeSession('busqueda')){
                $array_busqueda['fechamax'] = Session::getSession('busqueda')['fechamax'];
            }
        }
        Session::crearSession('busqueda',$array_busqueda);
        
        $where = [];
        array_push($where,['nombre','LIKE',Session::getSession('busqueda')['nombreOapellidos'].'%']);
        array_push($where,'OR');
        array_push($where,['apellidos','LIKE',Session::getSession('busqueda')['nombreOapellidos'].'%']);
        if(Session::getSession('busqueda')['dni'] !=''){
            array_push($where,'AND');
            array_push($where,['dni','=',Session::getSession('busqueda')['dni']]);
        }
        if(Session::getSession('busqueda')['fechamin'] != ''){
            array_push($where,'AND');
            array_push($where,['fechaNac','>=',Session::getSession('busqueda')['fechamin']]);
        }
        if(Session::getSession('busqueda')['fechamax'] != ''){
            array_push($where,'AND');
            array_push($where,['fechaNac','<=',Session::getSession('busqueda')['fechamax']]);
        }

        // boton estado
        $array_estado=['indiferente'=>'','activo'=>'','inactivo'=>''];

        if(isset($_POST['filtro_estado'])){
            if($_POST['filtro_estado'] == 'indiferente'){
                $array_estado['indiferente'] = "selected";
            }
            if($_POST['filtro_estado']=='activo'){
                $array_estado['activo'] = "selected";
            }
            if($_POST['filtro_estado'] == 'inactivo'){
                $array_estado['inactivo'] = "selected";
            }
        }else{
            if(Session::existeSession('estado_busqueda')){
                $array_estado = ['indiferente'=>Session::getSession('estado_busqueda')['indiferente'],'activo'=>Session::getSession('estado_busqueda')['activo'],'inactivo'=>Session::getSession('estado_busqueda')['inactivo']];
            }
        }

        Session::crearSession('estado_busqueda',$array_estado);
        
        if(array_search('selected',Session::getSession('estado_busqueda')) == 'activo'){
            array_push($where,'AND');
            array_push($where,['estado','=','activo']);
        }
        if(array_search('selected',Session::getSession('estado_busqueda')) == 'inactivo'){
            array_push($where,'AND');
            array_push($where,['estado','=','inactivo']);
        }
        return $where;
    }
    private function filtro_order(){
        // boton ordenar
        $array_order = ['nombre'=>'','apellidos'=>'','edadmayor'=>'','edadmenor'=>''];
        if(isset($_POST['ordenar'])){
            if($_POST['ordenar'] == 'nombre'){
                $array_order['nombre'] = 'selected';
            }
            if($_POST['ordenar']=='apellidos'){
                $array_order['apellidos'] = 'selected';
            }
            if($_POST['ordenar'] == 'edadmenor'){
                $array_order['edadmenor'] = 'selected';
            }
            if($_POST['ordenar']=='edadmayor'){
                $array_order['edadmayor'] = 'selected';
            }
        }else{
            if(Session::existeSession('order')){
                $array_order = ['nombre'=>Session::getSession('order')['nombre'],'apellidos'=>Session::getSession('order')['apellidos'],'edadmayor'=>Session::getSession('order')['edadmayor'],'edadmenor'=>Session::getSession('order')['edadmenor']];
            }
        }
        Session::crearSession('order',$array_order);
        
        if(array_search('selected',Session::getSession('order')) == 'edadmenor'){
            $order = ['fechaNac','DESC'];
        }else if(array_search('selected',Session::getSession('order')) == 'edadmayor'){
            $order = ['fechaNac','ASC'];
        }else if(array_search('selected',Session::getSession('order')) == 'apellidos'){
            $order = ['apellidos','ASC'];
        }else{
            $order = ['nombre','ASC'];
        }
        return $order;
    }
    public function list() {
        $this->page->crearHeader('id_nav_listado');
        $where = $this->filtro_where();
        $order = $this->filtro_order();
            
        $usuarios= $this->mduser->get($where,$order[0],$order[1]);
        if(!$usuarios){
            $usuarios = [];
        }
        $this->page->addUsers($usuarios);
    }
    public function solicitudes(){
        $this->page->crearHeader('');
        $where = $this->filtro_where();
        array_push($where,'AND');
        array_push($where,['estado','=','inactivo']);
        $order = $this->filtro_order();
        $usuarios= $this->mduser->get($where,$order[0],$order[1]);
        if(!$usuarios){
            $usuarios = [];
        }
        $this->page->addUsers($usuarios,true);
    }
    public function aceptarSolicitud(){
        $this->page->crearHeader('id_nav_listado');
        if(isset($_GET['dni'])){
            $dni = $_GET['dni'];
            $usuario = $this->mduser->get([['DNI','=',$dni]]);
            if($usuario && $usuario[0]['estado'] == 'inactivo'){
                $usuario[0]['estado'] = 'activo';
                if($this->mduser->update($usuario[0],$_GET['dni']) > 0){
                    $log['fecha'] = date("Y-m-d H:i:s");
                    $log['descripcion'] = 'Un sanitario ha aceptado la solicitud de un paciente';
                    $this->mdlog->insert($log);
                    $this->page->informar("Usuario aceptado",'user/list');
                }else{
                    $this->page->informar("Error al aceptar al usuario",'user/list');
                }
            }
        }
    }
    public function iniciarSesion($dni,$clave){
        $usuario_coincide = ($this->mduser->get([['dni','=',$dni]]));
        if($usuario_coincide){
            if(isset($usuario_coincide[0]['clave']) && password_verify($clave,$usuario_coincide[0]['clave']) && $usuario_coincide[0]['estado'] == 'activo'){
                $log['fecha'] = date("Y-m-d H:i:s");
                $log['descripcion'] = 'Se ha iniciado sesión';
                $this->mdlog->insert($log);
                return Session::iniciarSesion($usuario_coincide[0]);
            }
        }
        $log['fecha'] = date("Y-m-d H:i:s");
        $log['descripcion'] = 'Se ha intentado iniciar sesión';
        $this->mdlog->insert($log);
        return false;
    }
    public function cerrarSesion(){
        $log['fecha'] = date("Y-m-d H:i:s");
        $log['descripcion'] = 'Se ha cerrado sesión';
        $this->mdlog->insert($log);
        Session::acabarSesiones();
    }
    public function verUsuario(){
        $this->page->crearHeader('');
        if($_GET['dni']){
            $dni = $_GET['dni'];
            if(Session::getSession('usuario')['rol'] == 'administrador' || Session::getSession('usuario')['dni'] == $dni){
                $usuario = $this->mduser->get([['DNI','=',$dni]]);
                if($usuario){
                    $this->page->perfilUser('ver',$usuario[0]);
                }
            }
        }
    }
    public function editarUsuario(){
        $this->page->crearHeader('');
        if($_GET['dni']){
            if(isset($_POST['btnEnviarDatos'])){
                if(Session::existeSession('datosNuevos')){
                    if($this->mduser->update(Session::getSession('datosNuevos'),$_GET['dni']) > 0){
                        $log['fecha'] = date("Y-m-d H:i:s");
                        $log['descripcion'] = 'Se ha editado un usuario';
                        $this->mdlog->insert($log);
                        $this->page->informar("Usuario editado correctamente",'user/list');
                        if($_SESSION['datosNuevos']['dni'] == $_SESSION['usuario']['dni']){
                            $_SESSION['usuario'] = $_SESSION['datosNuevos'];
                        }
                    }else{
                        $this->page->informar("Error al editar usuario",'user/list');
                    }
                }else{
                    $datos = [];
                    if($_FILES['imagen']['error'] != 0){
                        if(Session::existeSession('usuarioEditable') && Session::getSession('usuarioEditable')['fotografia']!=null)
                            $datos['fotografia'] = Session::getSession('usuarioEditable')['fotografia'];
                    }else{
                        $datos['fotografia'] = base64_encode(file_get_contents($_FILES['imagen']['tmp_name']));
                        Session::getSession('usuarioEditable')['fotografia'] = base64_encode(file_get_contents($_FILES['imagen']['tmp_name']));
                    }
                    if(isset($_POST['nombre'])){
                        $datos['nombre'] = $_POST['nombre'];
                    }else{
                        $datos['nombre'] = Session::getSession('usuarioEditable')['nombre'];
                    }
                    if(isset($_POST['apellidos'])){
                        $datos['apellidos'] = $_POST['apellidos'];
                    }else{
                        $datos['apellidos'] = Session::getSession('usuarioEditable')['apellidos'];
                    }
                    $datos['dni'] = Session::getSession('usuarioEditable')['dni'];
                    $datos['email'] = $_POST['email'];
                    if(!empty($_POST['clave1'])){
                        $datos['clave'] = password_hash($_POST['clave1'],PASSWORD_DEFAULT,array("cost"=>12));
                    }else{
                        $datos['clave'] = Session::getSession('usuarioEditable')['clave'];
                    }
                    $datos['telefono'] = $_POST['telefono'];
                    if(isset($_POST['sexo'])){
                        $datos['sexo'] = $_POST['sexo'];
                    }else{
                        $datos['sexo'] = Session::getSession('usuarioEditable')['sexo'];
                    }
                    if(isset($_POST['fechaNac'])){
                        $datos['fechaNac'] = $_POST['fechaNac'];
                    }else{
                        $datos['fechaNac'] = Session::getSession('usuarioEditable')['fechaNac'];
                    }
                    if(isset($_POST['rol'])){
                        $datos['rol'] = $_POST['rol'];
                    }else{
                        $datos['rol'] = Session::getSession('usuarioEditable')['rol'];
                    }
                    if(isset($_POST['estado'])){
                        $datos['estado'] = $_POST['estado'];
                    }else{
                        $datos['estado'] = Session::getSession('usuarioEditable')['estado'];
                    }
                    $error = validacionFormulario("editar",$datos['nombre'],$datos['apellidos'],$datos['dni'],$datos['email'],$datos['telefono'],$datos['fechaNac'],$datos['sexo'],$_POST['clave1'],$_POST['clave2'],$datos['rol'],$datos['estado']);
                    if(sizeof($error) == 0){
                        Session::crearSession('datosNuevos',$datos);
                        $this->page->perfilUser('comprobar',Session::getSession('datosNuevos'),$error);
                    }else{
                        $this->page->perfilUser('editar',$datos,$error);              
                    }
                } 
            }else{
                $dni = $_GET['dni'];
                if(Session::getSession('usuario')['rol'] == 'administrador' || Session::getSession('usuario')['dni'] == $dni){
                    $usuario = $this->mduser->get([['DNI','=',$dni]]);
                    Session::crearSession('usuarioEditable',$usuario[0]);
                    if($usuario){
                        $this->page->perfilUser('editar',$usuario[0]);
                    }
                }
            }
            
        }
    }
    public function borrarUsuario(){
        $this->page->crearHeader('');
        if(isset($_POST['btnEnviarDatos'])){
            if($_GET['dni']){
                if($this->mduser->delete($_GET['dni'])>0){
                    $log['fecha'] = date("Y-m-d H:i:s");
                    $log['descripcion'] = 'Se ha borrado un usuario';
                    $this->mdlog->insert($log);
                    $this->page->informar("Usuario borrado correctamente",'user/list');
                }else{
                    $this->page->informar("Error al borrar usuario",'user/list');
                }
            }
        }else{
            if($_GET['dni']){
                $dni = $_GET['dni'];
                if(Session::getSession('usuario')['rol'] == 'administrador' && Session::getSession('usuario')['dni'] != $dni){
                    $usuario = $this->mduser->get([['DNI','=',$dni]]);
                    if($usuario){
                        $this->page->perfilUser('borrar',$usuario[0]);
                    }
                }
            }
        }
    }
    public function addUsuario(){
        $this->page->crearHeader('');
        if(isset($_POST['btnEnviarDatos'])){
            if(Session::existeSession('usuarioNuevo')){
                if($this->mduser->insert(Session::getSession('usuarioNuevo'))>0){
                    if(Session::existeSession('usuario')){
                        $log['fecha'] = date("Y-m-d H:i:s");
                        $log['descripcion'] = 'Se ha añadido un nuevo usuario';
                        $this->mdlog->insert($log);
                        $this->page->informar("Usuario añadido correctamente",'user/list');
                    }else
                        $this->page->informar("Solicitud enviada correctamente",'user/list'); 
                    
                }else{
                    if(Session::existeSession('usuario'))
                        $this->page->informar("Error al añadir usuario",'user/list');
                    else
                        $this->page->informar("Error al enviar la solicitud",'user/list');
                }
            }else{
                $datos = [];
                if($_FILES['imagen']['error'] != 0){
                    if(!empty($_SESSION['fotografia']))
                        $datos['fotografia'] = $_SESSION['fotografia'];
                }else{
                    $datos['fotografia'] = base64_encode(file_get_contents($_FILES['imagen']['tmp_name']));
                    Session::crearSession('fotografia',$datos['fotografia']);
                }
                $datos['nombre'] = $_POST['nombre'];
                $datos['apellidos'] = $_POST['apellidos'];
                $datos['dni'] = $_POST['dni'];
                $datos['email'] = $_POST['email'];
                $datos['clave'] = password_hash($_POST['clave1'],PASSWORD_DEFAULT,array("cost"=>12));
                $datos['telefono'] = $_POST['telefono'];
                $datos['sexo'] = !empty($_POST['sexo']) ? $_POST['sexo'] : NULL;
                $datos['fechaNac'] = $_POST['fechaNac'];
                if(Session::existeSession('usuario')){
                    $datos['rol'] = $_POST['rol'];
                    $datos['estado'] = $_POST['estado'];
                }else{
                    $datos['rol'] = 'paciente';
                    $datos['estado'] = 'inactivo';
                }
                $error = validacionFormulario("añadir",$datos['nombre'],$datos['apellidos'],$datos['dni'],$datos['email'],$datos['telefono'],$datos['fechaNac'],$datos['sexo'],$_POST['clave1'],$_POST['clave2'],$datos['rol'],$datos['estado']);
                if($this->mduser->get([['DNI','=',$datos['dni']]])){
                    $error['dni'] = "Dni incorrecto";
                }
                if(sizeof($error) == 0){
                    Session::crearSession('usuarioNuevo',$datos);
                    $this->page->perfilUser('comprobar',Session::getSession('usuarioNuevo'),$error);
                }else{
                    if(Session::existeSession('usuario'))
                        $this->page->perfilUser('añadir',$datos,$error);
                    else
                        $this->page->perfilUser('solicitud',$datos,$error);     
                }
            }
        }else{
            if(Session::existeSession('usuario'))
                $this->page->perfilUser('añadir',[],$error=[]);
            else
                $this->page->perfilUser('solicitud',[],$error=[]);    
        }
    }
}
?>