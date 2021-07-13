<?php
class Res{

    public $headers = [];
    public $status;
    public $message;

    public function __construct(string $status = null, string $message = null)
    {
        $this->headers = getallheaders();
        $this->status = $status ? $status : 200;
        $this->message = $message ? $message : "unknown case sorry ! repport this to kubuya.darone.david@gmail.com";
    }
    public function results(){
        return json_encode($this);
    }
    public function join(object $tablename, array $on)
    {
        if(!is_array($on) && !is_object($tablename)) return new Response(401, ["join expect Object model and array that represent jointure clause"]);
        
    }
}
?>