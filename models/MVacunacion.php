<?php
require_once(__DIR__.'/../core/AbstractModel.php');
class MVacunacion extends AbstractModel {
    function __construct()
    {
        parent::__construct();
    }

    public function get($parametro='',$condicion='',$valor='',$datos = '*',$count = ''){ 
        $this->from('vacunacion');
        $this->return($datos);
        if(!empty($parametro) && !empty($condicion) && !empty($valor))
            $this->where($parametro,$condicion,$valor);
        if($count == 'count')
            $lista = $this->sqlCount();
        else
            $lista = $this->sqlSelect();
        return empty($lista) ? null : $lista;
    }

    public function getParametros($datos = '*'){ 
        $this->from('vacunacion');
        $this->return($datos);
        if(!empty($parametro) && !empty($condicion) && !empty($valor))
            $this->where($parametro,$condicion,$valor);
        $lista = $this->sqlSelect();
        return empty($lista) ? null : $lista;
    }

    public function insert($vacunacion){
        try{
            $this->from('vacunacion');
            $id = $this->sqlInsert($vacunacion);
            return $id;
        }catch(Exception $e){
            echo $e->getTraceAsString();
        }
    }

    public function update($vacunacion,$id){
        try{
            $this->from('vacunacion');
            $this->where('ID','=',$id);
            $id = $this->sqlUpdate($vacunacion);
            return $id;
        }catch(Exception $e){
            echo $e->getTraceAsString();
        }
    }

    public function delete($id){
        try{
            $this->from('vacunacion');
            $this->where('ID','=',$id);
            $id = $this->sqlDelete();
            return $id;
        }catch(Exception $e){
            echo $e->getTraceAsString();
        }
    }

}
?>