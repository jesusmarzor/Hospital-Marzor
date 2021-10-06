<?php
class HTMLCircles{
    protected $svg=[],$titles=[],$subtitles=[],$enlaces=[],$nombresBtn=[];
    public function __construct($svg,$titles=[],$subtitles=[],$enlaces=[],$nombresBtn=[]){
        foreach($svg as $s){
            array_push($this->svg,$s);
        }
        foreach($titles as $title){
            array_push($this->titles,$title);
        }
        foreach($subtitles as $subtitle){
            array_push($this->subtitles,$subtitle);
        }
        foreach($enlaces as $enlace){
            array_push($this->enlaces,$enlace);
        }
        foreach($nombresBtn as $nombreBtn){
            array_push($this->nombresBtn,$nombreBtn);
        }
    }
    public function render(){
        echo '<div class="container text-center p-5">
            <div class="row">';
           for($i=0;$i<3;$i++){
               echo '<div class="col-lg-4">
                        '.$this->svg[$i].'
                        <h2>'.$this->titles[$i].'</h2>
                        <p>'.$this->subtitles[$i].'</p>
                        <p><a class="btn" href="'.$this->enlaces[$i].'" style="background-color: #e1c699;">'.$this->nombresBtn[$i].' »</a></p>
                    </div>';
           }
        echo '</div></div>';
    }
    
}