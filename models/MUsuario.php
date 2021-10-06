<?php
require_once(__DIR__.'/../core/AbstractModel.php');
class MUsuario extends AbstractModel {
    function __construct()
    {
        parent::__construct();
    }

    public function get($wheres=[],$order='',$ascOdesc='',$datos = '*',$count=''){ 
        $this->from('usuarios');
        $this->return($datos);
        for($i = 0;$i<sizeof($wheres);$i++){
            if($wheres[$i] == 'OR'){
                $i++;
                $this->orWhere($wheres[$i][0],$wheres[$i][1],$wheres[$i][2],')');
            }else if($wheres[$i] == 'AND'){
                $i++;
                $this->andWhere($wheres[$i][0],$wheres[$i][1],$wheres[$i][2]);
            }else{
                
                if(sizeof($wheres)<=1){
                    $this->where($wheres[$i][0],$wheres[$i][1],$wheres[$i][2]);
                }else{
                    $this->where($wheres[$i][0],$wheres[$i][1],$wheres[$i][2],'(');
                }
            }
        }
        // $this->where($parametro,$condicion,$valor);
        if(!empty($order) && !empty($ascOdesc)){
            $this->order($order,$ascOdesc);
        }
        if($count == 'count')
            $lista = $this->sqlCount();
        else
            $lista = $this->sqlSelect();
        return empty($lista) ? null : $lista;
    }

    public function getParametros($datos = '*'){ 
        $this->from('usuarios');
        $this->return($datos);
        if(!empty($parametro) && !empty($condicion) && !empty($valor))
            $this->where($parametro,$condicion,$valor);
        $lista = $this->sqlSelect();
        return empty($lista) ? null : $lista;
    }

    public function insert($usuario){
        try{
            $this->from('usuarios');
            $id = $this->sqlInsert($usuario);
            return $id;
        }catch(Exception $e){
            echo $e->getTraceAsString();
        }
    }

    public function update($usuario,$dni){
        try{
            $this->from('usuarios');
            $this->where('DNI','=',$dni);
            $id = $this->sqlUpdate($usuario);
            return $id;
        }catch(Exception $e){
            echo $e->getTraceAsString();
        }
    }

    public function delete($dni){
        try{
            $this->from('usuarios');
            $this->where('DNI','=',$dni);
            $id = $this->sqlDelete();
            return $id;
        }catch(Exception $e){
            echo $e->getTraceAsString();
        }
    }

}
?>