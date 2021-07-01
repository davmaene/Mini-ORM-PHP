<?php 
    class CRUD__ {
        public $db = null;
        public function __construct(){
          $classname = get_class($this);
          if(!empty($classname)){

          }else return new Response(500, ["dav.me i can't get the className sorry"]);
        }
        protected function __creteClass(){

        }
        protected function declencher(){
            $conf = new Config();
            return !$conf->__inst() ? new Response(202, []) : new Response(500, []);
        }
        public function onAdd(){
            if(!is_object($this)) return new Response(401,[]);
            else{
                $inst = $this;
                return new Response(200,[$this]);
            }
        }
        public function onDelete($hdl){}
        public function onEdit($hdl){}
        public function onRetrive($hdl){}
        // public function
    }
?>