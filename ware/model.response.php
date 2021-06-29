<?php 
class Response{

    public $status;
    public $statusText;
    public $body;

    public function __construct($status, $statusText, $body){
        $this->status = $status;
        $this->statusText = $statusText;
        $this->body = $body;
    }
    public function getStatus(){return $this->status;}
    public function getstatusText(){return $this->statusText;}
    public function getBody(){return $this->body;}
}
?>