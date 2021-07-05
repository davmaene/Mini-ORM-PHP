<?php 
    class CRUD__ {
        public function __construct(){}

        private function __SQLCreateInstance($tablename, Object $properties){
            $hasIdPropety = false;
            $properties = json_encode($properties); 
            $properties = json_decode($properties, true);

            $line = "";
            if(!is_array($properties)) return new Response(500, ["dav.me i can't get the className property"]);
            foreach($properties as $key => $property){      
                if($key === "id") $hasIdPropety = true;
                if($key !== "id") $line .= ",`$key` VARCHAR(60) NOT NULL";
            }

            if(!$hasIdPropety) return new Response(400, ["dav.me i can't find id property in the class $tablename"]);
            $query = "CREATE TABLE IF NOT EXISTS
            `$tablename` ( `id` INT NOT NULL AUTO_INCREMENT
            $line
            , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
            $r = new Config();
            $r = $r->onRunningQuery($query, $tablename);
            if($r) return true;
            else return false;
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
        public function onBuild(){ // create on instance in to the db
            if(!is_object($this)) return new Response(401,[]);
            else{
                if($this->__SQLCreateInstance($this->__creteClass(), $this))
                return new Response(200,[]);
                else 
                return new Response(500,["initialization error "]);
            }
        }
        public function onAdd(){ // create instance and add record to db
            $conf = new Config();
            $nclassname = $this->__creteClass();
            $vals_vers_db = [];
            $clname = get_class($this);
            $properties = $this;
            if($this->onBuild()->status === 200){
                $properties = json_encode($properties); 
                $properties = json_decode($properties, true);
                foreach($properties as $key => $property) if($key !== "id") array_push($vals_vers_db, $property);     
                if(count($vals_vers_db) > 0 && is_string($nclassname)){
                    $resp = (int) $conf->onSynchronization($vals_vers_db,1,$nclassname);
                    switch ($resp) {
                        case 200: return new Response(200, []);
                        case 503: return new Response(503, []);
                        default: return new Response(505, []);
                    }
                }else return new Response(405,["make sure u initialize values of $clname then try again !"]);
            }else return new Response(500,["error occured, make sure the $clname is correctely created !"]);
        }
        public function onDelete($hdl){}
        public function onEdit($hdl){}
        public function onRetrive($hdl){}
    }
?>