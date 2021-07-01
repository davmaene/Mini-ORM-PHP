<?php 
    class CRUD__ {
        public $db = null;
        public function __construct(){

        }
        protected function declencher(){
            $conf = new Config();
            return !$conf->__inst() ? new Response(202, []) : new Response(500, []);
        }
        public function onAdd(){
            if(!is_object($this)) return new Response(401,[]);
            else{
                $inst = $this;
                var_dump(__NAMESPACE__);
                return new Response(200,[$this]);
            }
        }
        public function onDelete($hdl){}
        public function onEdit($hdl){}
        public function onRetrive($hdl){}
        // public function
    }
?>