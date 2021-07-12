<?php
class Res{
    public function __construct()
    {}
    public function join(string $tablename, array $on)
    {
        if(!is_array($on) && !is_string($tablename)) return new Response(401, []);
        
    }
}
?>