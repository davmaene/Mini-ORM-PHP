<?php 
    class CRUD__ {
        public $db = null;
        public function __construct(){}

 
        private function __SQLCreateInstance($tablename, Object $properties){
            $properties = json_encode($properties); 
            $properties = json_decode($properties, true);
            $line = "";
            if(!is_array($properties)) return new Response(500, ["dav.me i can't get the className property"]);
            for($i = 0; $i < count($properties); $i++){}
            $createTable = "CREATE TABLE 
            `_tbl_test` ( `id` INT NOT NULL AUTO_INCREMENT , 
 
            PRIMARY KEY (`id`)) ENGINE = InnoDB;";
            var_dump($properties);
        } 
        protected function __creteClass(){
            $prefixe = "__tbl_";
            $classname = get_class($this);
            if(!empty($classname)){
              if(!strpos($classname,"s",-1)) $classname .= "s";
              $classname = strtolower($prefixe.$classname);
              return $classname;
            //   echo($classname);
            }else return new Response(500, ["dav.me i can't get the className property"]);
        }
        protected function declencher(){
            $conf = new Config();
            return $conf->onInit() ? new Response(202, []) : new Response(500, []);
        }
        public function onAdd(){
            if(!is_object($this)) return new Response(401,[]);
            else{
                $inst = $this;
                $this->__SQLCreateInstance($this->__creteClass(), $this);
                return new Response(200,[$this]);
            }
        }
        public function onDelete($hdl){}
        public function onEdit($hdl){}
        public function onRetrive($hdl){}
        // public function
    }
?>