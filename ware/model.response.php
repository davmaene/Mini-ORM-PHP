<?php 
class Response {

    public $status;
    public $statusText;
    public $body;

    public function __construct($status, $body){
        $this->status = $status;
        $this->statusText = $this->writeResponse($status);
        $this->body = $body;
    }
    public function print(){
        // return $this;
        return (json_encode($this, JSON_PRETTY_PRINT));
    }
    private function writeResponse($code = 0){
        $code = (int) $code;
        switch ($code) {
            case 200:
                return "=> success execution";
                // break;
            case 500:
                return "=> server error";
                // break;
            case 401:
                return "=> missing valid or invalid param passed throught the function";
                // break;
            case 404:
                return "=> client request could not be process";
                // break;
            case 403:
                return "=> dont have access to ressource";
                // break;
            default:
                return "=> unknown server error ";
                // break;
        }
    }
    public function getStatus(){return (int) $this->status;}
    public function getstatusText(){return $this->statusText;}
    public function getBody(){return $this->body;}
}
?>