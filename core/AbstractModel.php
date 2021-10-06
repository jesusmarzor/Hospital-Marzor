<?php
require_once(__DIR__.'/db.php');
abstract class AbstractModel{  // Clase abstracta
    protected $db;
    private $table,$sql,$where,$return,$order;
    
    public function __construct()
    {
        $this->db = Database::getInstance(); // Conexion Base de Datos
    }

    protected function sqlCount(){
        try{
            $this->sql = "SELECT COUNT({$this->return}) FROM {$this->table} {$this->where} {$this->order}";
            return $this->ejecutar();
        }catch(PDOException $e){
            echo $e->getTraceAsString();
        }
    }

    protected function sqlSelect(){
        try{
            $this->sql = "SELECT {$this->return} FROM {$this->table} {$this->where} {$this->order}";
            return $this->ejecutar();
        }catch(PDOException $e){
            echo $e->getTraceAsString();
        }
    }

    protected function sqlInsert($obj){
        try{
            $campos = implode("`, `", array_keys($obj)); // `nombre`, `acronimo`, `descripcion`...
            $valores = ":".implode(", :", array_keys($obj)); // :nombre, :acronimo, :descripcion
            $this->sql = "INSERT INTO {$this->table} (`{$campos}`) VALUES ({$valores})";
            // $id = $this->db->lastInsertId();
            return $this->ejecutar($obj);
        }catch(Exception $e){
            echo $e->getTraceAsString();
        } 
    }

    protected function sqlUpdate($obj){
        try{
            $campos = "";
            foreach($obj as $llave => $valor){
                $campos .= "`$llave`=:$llave ,"; // `nombre`=:nombre, `apellidos`=:apellidos
            }
            $campos = rtrim($campos, ","); // eliminar la ultima coma
            $this->sql = "UPDATE {$this->table} SET {$campos} {$this->where}";
            $filasAfectadas = $this->ejecutar($obj);
            return $filasAfectadas;
        }catch(Exception $e){
            echo $e->getTraceAsString();
        }
    }

    protected function sqlDelete(){
        try{
            $this->sql = "DELETE FROM {$this->table} {$this->where}";
            $filasAfectadas = $this->ejecutar();
            return $filasAfectadas;
        }catch(Exception $e){
            echo $e->getTraceAsString();
        }
    }

    protected function ejecutar($obj = null){
        $pq = $this->db->prepare($this->sql);
        if($obj != null){
            foreach ($obj as $parametro => $valor){
                if(empty($valor)){
                    $valor = null;
                }
                $pq->bindValue(":$parametro", $valor);
            }
        }
        $pq->execute();
        if($obj == null && substr($this->sql,0,6) == "SELECT"){
            $this->resetValores();
            return $pq->fetchAll(PDO::FETCH_ASSOC); // forma de array con los resultados
        }
        
        $this->resetValores();
        return $pq->rowCount();
    }
    
    // Parametros
    protected function from($table){
        $this->table = $table;
    }
    protected function where($parametro,$condicion,$valor,$parentesis = ''){
        $this-> where .= "WHERE {$parentesis} `$parametro` $condicion ".((is_string($valor)) ? "\"$valor\"" : $valor);
    }
    protected function order($valor,$ordenacion){
        $this-> order .= "ORDER by ".((is_string($valor)) ? $valor : "\"$valor\"")." ".$ordenacion;
    }
    protected function andwhere($parametro,$condicion,$valor){
        $this-> where .= " AND `$parametro` $condicion ".((is_string($valor)) ? "\"$valor\"" : $valor);
        return $this;
    }
    protected function orWhere($parametro,$condicion,$valor,$parentesis=''){
        $this-> where .= " OR `$parametro` $condicion ".((is_string($valor)) ? "\"$valor\"" : $valor).$parentesis;
        return $this;
    }
    protected function return($valores){
        $this->return = $valores;
    }
    
    private function resetValores(){
        $this->table = "";
        $this->sql = null;
        $this->where = '';
        $this->return = "*";
    }
}
?>