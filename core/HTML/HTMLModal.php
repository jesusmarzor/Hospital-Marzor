<?php
class HTMLModal{
    protected $title,$content;
    public function __construct($title,$content=[])
    {
        $this->title = $title;
        $this->content = $content;
    }
    public function render(){
        echo <<< HTML
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-form" data-toggle="modal" data-target="#exampleModal">
                {$this->title}
            </button>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{$this->title}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
        HTML;
                foreach($this->content as $c){
                    $c->render();
                }
        echo <<< HTML
                </div>
                </div>
            </div>
            </div>
        HTML;
    }
}
?>