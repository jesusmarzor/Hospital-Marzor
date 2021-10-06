<?php
require_once(__DIR__.'/../core/AbstractModel.php');
class MLog extends AbstractModel {
    function __construct()
    {
        parent::__construct();      
    }

    public function get($parametro='',$condicion='',$valor='',$order='',$ascOdesc='',$datos = '*'){ 
        $this->from('log');
        $this->return($datos);
        if(!empty($parametro) && !empty($condicion) && !empty($valor))
            $this->where($parametro,$condicion,$valor);
        if(!empty($order) && !empty($ascOdesc)){
            $this->order($order,$ascOdesc);
        }
        $lista = $this->sqlSelect();
        return empty($lista) ? null : $lista;
    }

    public function insert($log){
        try{
            $this->from('log');
            $id = $this->sqlInsert($log);
            return $id;
        }catch(Exception $e){
            echo $e->getTraceAsString();
        }
    }
    
    public function update($log,$id){
        try{
            $this->from('log');
            $this->where('ID','=',$id);
            $id = $this->sqlUpdate($log);
            return $id;
        }catch(Exception $e){
            echo $e->getTraceAsString();
        }
    }

    public function delete($id){
        try{
            $this->from('log');
            $this->where('ID','=',$id);
            $id = $this->sqlDelete();
            return $id;
        }catch(Exception $e){
            echo $e->getTraceAsString();
        }
    }
    

}
?>