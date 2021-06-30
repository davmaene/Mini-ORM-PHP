<?php 
    class CRUD__ {
        public function __construct(){

        }
        private function declencher(){
            $conf = new Config();
            return !$this->__inst() ? new Response(202, []) : new Response(500, []);
        }
        public function onAdd($hdl){

            if(!is_object($hdl)) return new Response(401,[]);
            else return new Response(200,[$this->db]);
        }
        public function onDelete($hdl){}
        public function onEdit($hdl){}
        public function onRetrive($hdl){}
    }
?>