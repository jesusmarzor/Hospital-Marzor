<?php
class HTMLTag_logs{
    private $logs;
    public function __construct($logs){
        $this->logs = $logs;
    }
    public function render(){
        echo <<< HTML
            <table class='tabla_logs table table-striped table-bordered text-center table-responsive-sm'>
                <thead>
                    <tr class = 'thead'>
                        <th>Fecha</th>
                        <th>Descripción</th>
                    <tr>
                </thead>
                <tbody>
        HTML;
                foreach($this->logs as $log){
                    $color_fondo = 'bg-success';
                    if($log['descripcion'] == 'Se ha intentado iniciar sesión')
                        $color_fondo = 'bg-danger';
                    echo '<tr><th class="'.$color_fondo.'">'.$log['fecha'].'</th><th class="'.$color_fondo.'">'.$log['descripcion'].'</th><tr>';
                }
        echo <<< HTML
                <tbody>
            </table>
        HTML;
    }
    
}