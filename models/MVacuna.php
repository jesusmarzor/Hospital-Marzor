<?php
require_once(__DIR__.'/../core/AbstractModel.php');
class MVacuna extends AbstractModel {
    function __construct()
    {
        parent::__construct();      
    }

    public function get($parametro='',$condicion='',$valor='',$datos = '*'){ 
        $this->from('vacunas');
        $this->return($datos);
        if(!empty($parametro) && !empty($condicion) && !empty($valor))
            $this->where($parametro,$condicion,$valor);
        $lista = $this->sqlSelect();
        return empty($lista) ? null : $lista;
    }

    public function insert($vacuna){
        try{
            $this->from('vacunas');
            $id = $this->sqlInsert($vacuna);
            return $id;
        }catch(Exception $e){
            echo $e->getTraceAsString();
        }
    }
    
    public function update($vacuna,$id){
        try{
            $this->from('vacunas');
            $this->where('ID','=',$id);
            $id = $this->sqlUpdate($vacuna);
            return $id;
        }catch(Exception $e){
            echo $e->getTraceAsString();
        }
    }

    public function delete($id){
        try{
            $this->from('vacunas');
            $this->where('ID','=',$id);
            $id = $this->sqlDelete();
            return $id;
        }catch(Exception $e){
            echo $e->getTraceAsString();
        }
    }
    

}
?>