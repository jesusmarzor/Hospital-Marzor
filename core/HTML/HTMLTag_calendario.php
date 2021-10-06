<?php
class HTMLTag_calendario{
    private $cabecera = [], $vacunas = [], $atr = [], $filas = [];
    public function __construct($atr = []){
        foreach($atr as $nombre => $valor){
            $this->atr[$nombre] = $valor;
        }
    }
    public function render(){
        $meses = $this->calcMeses(); 
        $this->calcCabecera($meses);
        echo '<table ';
        
        foreach($this->atr as $atributo => $valor){
            echo "$atributo= '$valor' ";
        }        
        echo '><thead><tr class = thead>';
        foreach($this->cabecera as $cab){
            echo '<th scope=col>'.$cab.'</th>';
        }
        echo '</tr></thead>';
        echo '<tbody>';
        $indice = 0;
        foreach($this->filas as $fila){
            echo '<tr>';
            echo '<th class="thVacunas" scope="row">'.$this->vacunas[$indice]['nombre'].'</th>';
            $i = 0;
            foreach($fila as $celda){
                while($celda['meses_ini'] != $meses[$i]){
                    echo '<td class="celda">';
                    echo '</td>';
                    $i++;
                }
                if($celda['meses_ini'] != $celda['meses_fin']){
                    echo '<td class="celda celdaAsistemico';
                    echo '" colspan="'.(array_search($celda['meses_fin'], $meses)-($i-1)).'">
                    <p class="colorCelda';
                    if($celda['tipo'] == 'A')
                        echo ' asistemico ';
                    echo ' ">'.$this->vacunas[$indice]['acronimo'].'</p>';
                    echo '</td>';
                    while($i != array_search($celda['meses_fin'], $meses)){
                        $i++;
                    }
                    $i++;
                    
                }else{
                    echo '<td class="celda';
                    if($celda['tipo'] == 'A')
                        echo ' asistemico ';
                    echo '"><p class="colorCelda'.'">'.$this->vacunas[$indice]['acronimo'].'</p>';
                    echo '</td>';
                    $i++;
                }
                
            }
            while($i < sizeof($meses)){
                echo '<td class="celda">';
                echo '</td>';
                $i++;
            }
            $indice++;
        }
        echo '</tr>';
        echo '</tbody></table>';
    }
    public function addFila($fila){
        array_push($this->filas,$fila);
    }
    public function setVacunas($vacunas){
        foreach($vacunas as $vacuna){
            array_push($this->vacunas,$vacuna);
        }
    }
    private function calcMeses(){
        $c = [];
        foreach($this->filas as $fila){
            foreach($fila as $celda){
                array_push($c,intval($celda['meses_ini']));
                array_push($c,intval($celda['meses_fin']));
            }
        }
        $c = array_unique($c);
        asort($c);
        return array_values($c);
    }
    private function calcCabecera($meses){
        array_push($this->cabecera,'Vacunas');
        foreach($meses as $mes){
            if($mes == -1){
                array_push($this->cabecera,'Pre natal');
            }else if($mes <= 12 || !is_int($mes/12) && $mes < '781'){
                array_push($this->cabecera,$mes.' meses');
            }else if($mes > 12 && is_int($mes/12)){
                array_push($this->cabecera,($mes/12).' años');
            }else{
                array_push($this->cabecera,'>65 años');
            }
        }
        return $this->cabecera;
    }
}
