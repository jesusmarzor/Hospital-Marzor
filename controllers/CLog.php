<?php
require_once(__DIR__.'/../models/MLog.php');
require_once(__DIR__.'/../core/validacionFormulario.php');

class CLog extends AbstractController {
    protected $mdvacuna,$mdlog;
    public function __construct($req  ,$met,$par=null,$webpage=null){
        $this->mdlog = new Mlog(); 
        parent::__construct($req,$met,$par,$webpage);
    }

    public function list() {
        $this->page->crearHeader('id_nav_logs');
        $logs= $this->mdlog->get('','','','fecha','DESC');
        if(!$logs){
            $logs = [];
        }
        $this->page->addLogs($logs);
    }
}
?>