<?php 
class Main extends Config{
    protected $conf = null;
    public function __construct(){
        $this->conf = $this->__inst();
    }
    public function getConn(){
        return !$this->onInit() ? new Response(202, []) : new Response(500, []);
    }
}
?>