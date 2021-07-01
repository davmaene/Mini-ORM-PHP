<?php 
    class CRUD__ {
        public $db = null;
        public function __construct(){

        }
        private function __SQLCreateInstance($tablename, $properties){
            $createTable = "CREATE TABLE 
            `_tbl_test` ( `id` INT NOT NULL AUTO_INCREMENT , 
            `fsname` VARCHAR(60) NOT NULL , 
            `lsname` VARCHAR(60) NOT NULL , 
            `country` VARCHAR(60) NOT NULL , 
            PRIMARY KEY (`id`)) ENGINE = InnoDB;";
        } 
        protected function __creteClass(){
            $prefixe = "__tbl_";
            $classname = get_class($this);
            if(!empty($classname)){
              if(!strpos($classname,"s",-1)) $classname .= "s";
              $classname = strtolower($prefixe.$classname);
            //   echo($classname);
            }else return new Response(500, ["dav.me i can't get the className property"]);
        }
        protected function declencher(){
            $conf = new Config();
            return $conf->onInit() ? new Response(202, []) : new Response(500, []);
        }
        public function onAdd(){
            $this->__creteClass();
            if(!is_object($this)) return new Response(401,[]);
            else{
                $inst = $this;
                return new Response(200,[]);
            }
        }
        public function onDelete($hdl){}
        public function onEdit($hdl){}
        public function onRetrive($hdl){}
        // public function
    }
?>