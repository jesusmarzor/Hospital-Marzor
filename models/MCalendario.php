<?php
require_once(__DIR__.'/../core/AbstractModel.php');
class MCalendario extends AbstractModel {
    function __construct()
    {
        parent::__construct();      
    }

    public function get($parametro='',$condicion='',$valor='',$order='',$ascOdesc='',$datos = '*'){ 
        $this->from('calendario');
        $this->return($datos);
        if(!empty($parametro) && !empty($condicion) && !empty($valor))
            $this->where($parametro,$condicion,$valor);
        if(!empty($order) && !empty($ascOdesc)){
            $this->order($order,$ascOdesc);
        }
        $lista = $this->sqlSelect();
        return empty($lista) ? null : $lista;
    }

    public function insert($calendario){
        try{
            $this->from('calendario');
            $id = $this->sqlInsert($calendario);
            return $id;
        }catch(Exception $e){
            echo $e->getTraceAsString();
        }
    }
    
    public function update($calendario,$id){
        try{
            $this->from('calendario');
            $this->where('id','=',$id);
            $id = $this->sqlUpdate($calendario);
            return $id;
        }catch(Exception $e){
            echo $e->getTraceAsString();
        }
    }

    public function delete($id){
        try{
            $this->from('calendario');
            $this->where('id','=',$id);
            $id = $this->sqlDelete();
            return $id;
        }catch(Exception $e){
            echo $e->getTraceAsString();
        }
    }
    

}
?>