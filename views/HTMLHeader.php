<?php
class HTMLHeader{
    private $header;
    protected $titulo, $botonNav, $elementos_menu=[], $menu,$barra,$registro;
    public function __construct($nav_menu,$nav_active,$atr=[])
    {
        $this->titulo = new HTMLTag_container('a',['class' => 'navbar-brand font-weight-bold',
                                        'href' => '']); 
        $this->botonNav = new HTMLTag_container('button',['class' => 'navbar-toggler', 'type' => 'button',
                                                'data-toggle' => 'collapse', 'data-target' => '#navbarSupportedContent',
                                                'aria-controls' => 'navbarSupportedContent', 'aria-expanded' => 'false',
                                                'aria-label' => 'Toggle navigation'],
                                                [new HTMLTag_container('span',['class' => 'navbar-toggler-icon'])]);
        foreach($nav_menu as $elemento){
            $activo = '';
            if($elemento[2] == $nav_active){$activo = 'active';};
            array_push($this->elementos_menu,new HTMLTag_container('li',['class'=>'nav-item mx-auto '.$activo],[new HTMLTag_container('a',['class'=>'nav-link',
            'href'=>$elemento[1]],[$elemento[0]])]));
        }
        if(Session::existeSession('usuario') && Session::getSession('usuario')['rol'] == 'administrador'){
            array_push($this->elementos_menu, new HTMLTag_container('li',['class'=>'nav-item mx-auto'],[
                                new HTMLTag_container('section',['class'=>'dropdown'],[ 
                                new HTMLTag_container('button',['class'=>'btn btn-sesion dropdown-toggle text-white','type'=>'button','id'=>'dropdownMenuButton','data-toggle'=>'dropdown','aria-haspopup'=>'true','aria-expanded'=>'false'],['Listado']),
                                new HTMLTag_container('div',['class'=>'dropdown-menu dropdown-menu-center','aria-labelledby'=>'dropdownMenuButton'],[
                                    new HTMLTag_container('a',['class'=>'dropdown-item','href'=>'user/list'],['Lista de Usuarios']),
                                    new HTMLTag_container('a',['class'=>'dropdown-item','href'=>'vacunas/list'],['Lista de Vacunas']),
                                    new HTMLTag_container('a',['class'=>'dropdown-item','href'=>'calendario/list'],['Vacunas del Calendario'])
                                ])
                            ])])
            );
        }
        $this->menu = new HTMLTag_container('ul',['class'=>'navbar-nav mr-auto'],$this->elementos_menu);

        // Formulario login
        if(Session::existeSession('usuario')){
            $usuario = Session::getSession('usuario');
            $nombre = new HTMLTag_container('a',['href'=>'/verUser?dni='.$usuario['dni']],[$usuario['nombre'],$usuario['apellidos']]); 
            if($usuario['fotografia']){
                $fotografia = new HTMLTag_void('img',['class'=>'ml-2','src'=>'data:image/jpeg;base64,'.($usuario['fotografia'])]);
            }else{
                $fotografia = new HTMLTag_void('img',['class'=>'ml-2','src'=>'views/css/img/perfil.jpg']);
            }
            $boton_salir = new HTMLTag_container('form',['class'=>'formInicioSesion','action'=>$_SERVER["REQUEST_URI"],'method'=>'POST'],
                [new HTMLTag_void('input',['class'=>'btn btn-registro btn-salir w-75 mx-auto mt-1','type'=>'submit','name'=>'btnSalir','value'=>'Salir'])]
            );
            $solicitudes ='';
            if($usuario['rol'] == 'sanitario')
                $solicitudes = new HTMLTag_container('a',['class'=>'dropdown-item','href'=>'solicitudes'],['Solicitudes']);
            $this->registro = new HTMLTag_container('section',['class'=>'dropdown my-2 my-lg-0 text-center justify-content-center'],[ 
                new HTMLTag_container('button',['class'=>'btn btn-sesion dropdown-toggle','type'=>'button','id'=>'dropdownMenuButton','data-toggle'=>'dropdown','aria-haspopup'=>'true','aria-expanded'=>'false'],[$nombre,$fotografia]),
                new HTMLTag_container('div',['class'=>'dropdown-menu dropdown-menu-center','aria-labelledby'=>'dropdownMenuButton'],[
                    new HTMLTag_container('a',['class'=>'dropdown-item','href'=>'verUser?dni='.$usuario['dni']],['Perfil']),
                    new HTMLTag_container('a',['class'=>'dropdown-item','href'=>'vacunacion?dni='.$usuario['dni']],['Cartilla Vacunación']),
                    $solicitudes,
                    $boton_salir 
                ])
            ]);
            
        }else{
            $this->registro = new HTMLTag_container('form',['class'=>'form-inline my-2 my-lg-0 justify-content-center','action'=>$_SERVER["REQUEST_URI"],'method'=>'POST'],[
                (new HTMLTag_void('input',['class'=>'form-control mr-sm-2 border-0','type'=>'text','placeholder'=>'DNI','name'=>'dni'])),
                (new HTMLTag_void('input',['class'=>'form-control mr-sm-2 mt-2 mt-sm-0 border-0','type'=>'password','placeholder'=>'Clave','name'=>'clave'])),
                (new HTMLTag_container('button',['class'=>'btn btn-registro pb-1','type'=>'submit'],['Iniciar']))
            ]);
        }

        $this->barra = new HTMLTag_container('div',['class'=>'collapse navbar-collapse',
                                                    'id' => 'navbarSupportedContent'],[$this->menu,$this->registro]);
        
        
        $this->header = new HTMLTag_container('header',$atr,[new HTMLTag_container('nav',['class' => 'navbar navbar-expand-lg navbar-dark'],[$this->titulo,$this->botonNav,$this->barra])]);
    }
    public function setTitle($t){ $this->titulo->setContent($t);}
    public function getHeader(){ return $this->header;}
}
?>