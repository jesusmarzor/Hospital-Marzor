<?php
require_once(__DIR__ . '/../core/HTML/HTMLModal.php');
class HTMLListado{
    private $struct;
    protected $titulo;
    protected $nombreListado;
    protected $listado;
    protected $btn_add = '';
    protected $mensaje;
    protected $btnFiltro='';
    public function __construct($nombreListado,$atr=[])
    {
        $this->nombreListado = $nombreListado;
        // $paginaActual = $_POST['partida'];
        // $numeroLotes = 3;
        // $numeroPaginas = ceil($this->numUsuarios,$numeroLotes);
        // $lista = '';
        // $table = '';
        // if($paginaActual > 1){
        //     $lista = $lista.'<li><a href="javascript:pagination('.($paginaActual-1).');">Anterior</a></li>';
        // }
        // for($i=1; $i<=$numeroPaginas;$i++){
            
        // }
        $this->titulo = new HTMLTag_container('h1',['class'=>'title'],['Listado de '.$this->nombreListado]);
        Session::acabarSession('datosNuevos');
        if($nombreListado == 'vacunas'){
            Session::acabarSession('vacunaNueva');
            Session::acabarSession('vacunaEditable');
        }
        if($nombreListado == 'calendario'){
            Session::acabarSession('calendarioNuevo');
            Session::acabarSession('calendarioEditable');
        }
        if($nombreListado == 'usuarios'){
            Session::acabarSession('usuarioNuevo');
            Session::acabarSession('fotografia');
            Session::acabarSession('usuarioEditable');
            $disabled_estado = '';
            if(Session::existeSession('estado_inactivo')){
                $disabled_estado = 'disabled';
            }
            if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] != 'paciente'){
                $this->btnFiltro = new HTMLModal('Filtros',[
                    new HTMLTag_container('form',['class'=>'','action'=>$_SERVER["REQUEST_URI"],'method'=>'POST'],[
                        new HTMLTag_container('div',['class'=>'row justify-content-center align-items-center mt-3 mb-3'],[
                            new HTMLTag_container('label',['class'=>'','for'=>'nombreOapellidos'],['Nombre o Apellidos:']),
                            new HTMLTag_void('input',['type'=>'text','class'=>'ml-3 form-control w-50','id'=>'nombreOapellidos','placeholder'=>'Nombre o Apellidos','name'=>'nombreOapellidos','value'=>Session::getSession('busqueda')['nombreOapellidos']])
                        ]),
                        new HTMLTag_container('div',['class'=>'row justify-content-center align-items-center mt-3 mb-3'],[
                            new HTMLTag_container('label',['class'=>'','for'=>'dni'],['Dni:']),
                            new HTMLTag_void('input',['type'=>'text','class'=>'ml-3 form-control w-25','id'=>'dni','placeholder'=>'Dni','name'=>'dni_filtro','value'=>Session::getSession('busqueda')['dni']])
                        ]),
                        new HTMLTag_container('div',['class'=>'row justify-content-center align-items-center mt-3 mb-3'],[
                            new HTMLTag_container('label',['class'=>'','for'=>'fechamin'],['Fecha min:']),
                            new HTMLTag_void('input',['type'=>'text','class'=>'ml-3 form-control w-25','id'=>'fechamin','placeholder'=>'aaaa-mm-dd','name'=>'fechamin','value'=>Session::getSession('busqueda')['fechamin']])
                        ]),
                        new HTMLTag_container('div',['class'=>'row justify-content-center align-items-center mt-3 mb-3'],[
                            new HTMLTag_container('label',['class'=>'','for'=>'fechamax'],['Fecha max:']),
                            new HTMLTag_void('input',['type'=>'text','class'=>'ml-3 form-control w-25','id'=>'fechamax','placeholder'=>'aaaa-mm-dd','name'=>'fechamax','value'=>Session::getSession('busqueda')['fechamax']])
                        ]),
                        new HTMLTag_container('div',['class'=>'row justify-content-center align-items-center mt-3 mb-3'],[
                            new HTMLTag_container('label',['class'=>''],['Estado:']),
                            new HTMLTag_container('select',['class'=>'form-control ml-3 w-50 ','name'=>'filtro_estado'],[
                                new HTMLTag_container('option',['value'=>'indiferente',$disabled_estado,Session::getSession('estado_busqueda')['indiferente']],['Indiferente']),
                                new HTMLTag_container('option',['value'=>'activo',$disabled_estado,Session::getSession('estado_busqueda')['activo']],['Activo']),
                                new HTMLTag_container('option',['value'=>'inactivo',$disabled_estado,Session::getSession('estado_busqueda')['inactivo']],['Inactivo'])
                            ])
                        ]),
                        new HTMLTag_container('div',['class'=>'row justify-content-center align-items-center mt-3 mb-3'],[
                            new HTMLTag_container('label',['class'=>''],['Ordenar por:']),
                            new HTMLTag_container('select',['class'=>'form-control ml-3 w-50','name'=>'ordenar'],[
                                new HTMLTag_container('option',['value'=>'nombre',Session::getSession('order')['nombre']],['Nombre']),
                                new HTMLTag_container('option',['value'=>'apellidos',Session::getSession('order')['apellidos']],['Apellidos']),
                                new HTMLTag_container('option',['value'=>'edadmenor',Session::getSession('order')['edadmenor']],['Menor a mayor edad']),
                                new HTMLTag_container('option',['value'=>'edadmayor',Session::getSession('order')['edadmayor']],['Mayor a menor edad'])
                            ])
                        ]),
                        // new HTMLTag_void('input',['type'=>'text','placeholder'=>'Vacunas pendientes...']),
                        // new HTMLTag_void('input',['type'=>'text','placeholder'=>'Vacunarse en X semanas...']),
                        new HTMLTag_void('input',['class'=>'btn btn-form ml-2','type'=>'submit','value'=>'Buscar'])
                    ]),
                    new HTMLTag_container('form',['class'=>'mt-2','action'=>$_SERVER["REQUEST_URI"],'method'=>'POST'],[
                        new HTMLTag_void('input',['class'=>'btn btn-form ml-2','type'=>'submit','name'=>'btn_resetFiltros','value'=>'Resetear filtros'])
                    ])
                ]);
            }
        }
        $this->listado = new HTMLTag_container('div',['class'=>'listado']);
        $pagina = new HTMLTag_container('section',['class'=>'pagina text-center'],[
            $this->listado
            // ,new HTMLTag_container('nav',['arial-label'=>'Page navigation example'],[
            //     new HTMLTag_container('ul',['class'=>'pagination ml-5'],[
            //         new HTMLTag_container('li',['class'=>'page-item'],[
            //             new HTMLTag_container('a',['class'=>'page-link','href'=>'#'],['Anterior'])
            //         ]),
            //         new HTMLTag_container('li',['class'=>'page-item'],[
            //             new HTMLTag_container('a',['class'=>'page-link','href'=>'#'],['1'])
            //         ]),
            //         new HTMLTag_container('li',['class'=>'page-item'],[
            //             new HTMLTag_container('a',['class'=>'page-link','href'=>'#'],['2'])
            //         ]),
            //         new HTMLTag_container('li',['class'=>'page-item'],[
            //             new HTMLTag_container('a',['class'=>'page-link','href'=>'#'],['3'])
            //         ]),
            //         new HTMLTag_container('li',['class'=>'page-item'],[
            //             new HTMLTag_container('a',['class'=>'page-link','href'=>'#'],['Siguiente'])
            //         ])
            //     ])
            // ])
        ]);
        if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] == 'administrador'){
            if($nombreListado == 'usuarios'){
                $url = 'addUser';
            }else if($nombreListado == 'vacunas'){
                $url = 'addVacuna';
            }else if($nombreListado == 'calendario'){
                $url = 'addCalendario';
            }else{
                $url = '#';
            }
            $this->btn_add = new HTMLTag_container('a',['class'=>'btn btn-form m-3','href'=>$url],['Añadir']);
        }
        $this->mensaje = new HTMLTag_container('p',['class'=>'mensaje_error']);
        $this->struct = new HTMLTag_container('div',$atr,[$this->titulo,new HTMLTag_container('section',['class'=>'m-5 row justify-content-end'],[$this->btnFiltro]),$pagina,$this->mensaje,$this->btn_add]);
    }
    public function getContent(){
        return $this->struct;
    }

    public function getListado(){
        return $this->listado;
    }

    public function addUser($fotografiaUsuario,$nombre,$apellidos,$email,$dni,$rol,$btnverlabel,$btneditlabel,$btnborrlabel,$btnvacunaslabel,$solicitudes){
        if($fotografiaUsuario != null){
            $fotografia = 'data:image/jpeg;base64,'.$fotografiaUsuario;
        }else{
            $fotografia = 'views/css/img/perfil.jpg';
        }
        if($rol == 'administrador'){
            $rol = 'admin';
        }
        $btnVer='';
        $btnEditar='';
        $btnBorrar='';
        $btnVacunas='';
        $btnAceptar='';
        if(!$solicitudes){
            if(Session::getSession('usuario')['rol'] == 'paciente'){
                if($dni == Session::getSession('usuario')['dni']){
                    $btnVer = new HTMLTag_container('a',['class'=>'btn btn-sm btn-form','href'=>'verUser?dni='.$dni],[$btnverlabel]);
                    $btnEditar = new HTMLTag_container('a',['class'=>'btn btn-sm btn-form ml-2','href'=>'editUser?dni='.$dni],[$btneditlabel]);
                    $btnVacunas = new HTMLTag_container('a',['class'=>'btn btn-sm btn-form ml-2','href'=>'vacunacion?dni='.$dni],[$btnvacunaslabel]);
                }
            }elseif(Session::getSession('usuario')['rol'] == 'sanitario'){
                if($dni == Session::getSession('usuario')['dni']){
                    $btnVer = new HTMLTag_container('a',['class'=>'btn btn-sm btn-form','href'=>'verUser?dni='.$dni],[$btnverlabel]);
                    $btnEditar = new HTMLTag_container('a',['class'=>'btn btn-sm btn-form ml-2','href'=>'editUser?dni='.$dni],[$btneditlabel]);
                }
                $btnVacunas = new HTMLTag_container('a',['class'=>'btn btn-sm btn-form ml-2','href'=>'vacunacion?dni='.$dni],[$btnvacunaslabel]);    
            }else{
                $btnVer = new HTMLTag_container('a',['class'=>'btn btn-sm btn-form','href'=>'verUser?dni='.$dni],[$btnverlabel]);
                $btnEditar = new HTMLTag_container('a',['class'=>'btn btn-sm btn-form ml-2','href'=>'editUser?dni='.$dni],[$btneditlabel]);
                if($dni != Session::getSession('usuario')['dni'])
                    $btnBorrar = new HTMLTag_container('a',['class'=>'btn btn-sm btn-form ml-2','href'=>'delUser?dni='.$dni],[$btnborrlabel]);
            }
        }else{
            $btnAceptar = new HTMLTag_container('a',['class'=>'btn btn-sm btn-form ml-2','href'=>'solicitudAceptada?dni='.$dni],['aceptar']);
        }
        $this->listado->addContent(new HTMLTag_container('div',['class'=>'card mx-auto mt-3 mb-3','style'=>'width: 18rem;'],[
                new HTMLTag_container('div',['class'=>'card-img-top marcoImagen mx-auto rounded-circle m-3'],[new HTMLTag_void('img',['class'=>'imagenUser','src'=>$fotografia]),new HTMLTag_container('p',['class'=>'rolUser bg-white text-capitalize font-weight-bold'],[$rol])]),
                new HTMLTag_container('div',['class'=>'card-body'],[
                    new HTMLTag_container('h5',['class'=>'card-title'],[$nombre,$apellidos]),
                    new HTMLTag_container('p',['class'=>'card-text'],[$email]),
                    $btnAceptar,
                    $btnVer,
                    $btnEditar,
                    $btnBorrar,
                    $btnVacunas
                ])
        ]));   
    }
    public function addVacuna($id,$nombre){
        $this->listado->addContent(new HTMLTag_container('div',['class'=>'card mx-auto mt-3 mb-3','style'=>'width: 18rem;'],[
                new HTMLTag_void('img',['class'=>'','src'=>'views/css/img/vacuna.jpg']),
                new HTMLTag_container('div',['class'=>'card-body'],[
                    new HTMLTag_container('h5',['class'=>'card-title'],[$nombre]),
                    new HTMLTag_container('a',['class'=>'btn btn-sm btn-form ml-2','href'=>'editVacuna?vac='.$id],['Editar']),
                    new HTMLTag_container('a',['class'=>'btn btn-sm btn-form ml-2','href'=>'delVacuna?vac='.$id],['Borrar'])
                ])
        ]));   
    }
    public function addCalendario($id,$nombre,$meses_ini,$meses_fin){
        $this->listado->addContent(new HTMLTag_container('div',['class'=>'card mx-auto mt-3 mb-3','style'=>'width: 18rem;'],[
                new HTMLTag_void('img',['class'=>'','src'=>'views/css/img/vacuna.jpg']),
                new HTMLTag_container('div',['class'=>'card-body'],[
                    new HTMLTag_container('h5',['class'=>'card-title'],[$nombre]),
                    new HTMLTag_container('p',['class'=>'card-text'],[$meses_ini.'-'.$meses_fin]),
                    new HTMLTag_container('a',['class'=>'btn btn-sm btn-form ml-2','href'=>'editCalendario?c='.$id],['Editar']),
                    new HTMLTag_container('a',['class'=>'btn btn-sm btn-form ml-2','href'=>'delCalendario?c='.$id],['Borrar'])
                ])
        ]));   
    }
    public function addMensaje($mensaje){
        $this->mensaje->setContent([$mensaje]);
    }

}
?>