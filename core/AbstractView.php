<?php
require_once(__DIR__.'/HTML/HTMLHead.php');
require_once(__DIR__.'/HTML/HTMLBody.php');
require_once(__DIR__.'/HTML/HTMLTag_html.php');
class AbstractView{
    protected $web, $request;
    public function __construct($req) // poner req
    {
        $this->request = $req;
        $this->web=[];
        $this->web['head'] = new HTMLHead();
        $this->web['head']->setBase($this->preURL($req));
        $this->web['body'] = new HTMLBody();
        $this->web['html'] = new HTMLTag_html();
        $this->web['html']->addContent($this->web['head']);
        $this->web['html']->addContent($this->web['body']);
    }
    public function setPageTitle($tit){
        $this->web['head']->setTitle($tit);
    }
    public function addCSSLink($css){
        $this->web['head']->addCSS($css);
    }
    public function addMain($content){
        $this->web['body']->addBody($content);
    }
    private function preURL($req){
        return $req->getScheme().'://'.$req->getDomain().'/'.$req->getRoot();
    }
    // Método principal que devuelve el HTML
    public function render(){
        return '<!DOCTYPE html>'.PHP_EOL.$this->web['html']->render();
    }
}
?>