<?php 
class Main extends Config{

    public function __construct(){}
    public function getConn(){
        return $this->onInit();
    }
}
?>