<?php 
    class CRUD__ implements Metier_{
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
        private function __createClass(){
            $prefixe = "__tbl_";
            $classname = get_class($this);
            if(!empty($classname)){
              if(!strpos($classname,"s",-1)) $classname .= "s";
              $classname = strtolower($prefixe.$classname);
              return $classname;
            //   echo($classname);
            }else return new Response(500, ["dav.me i can't get the className property"]);
        }
        private function declencher(){
            $conf = new Config();
            return $conf->onInit() ? new Response(202, []) : new Response(500, []);
        }
        private function onBuild(){ // create on instance in to the db
            if(!is_object($this)) 
                return new Response(401,[]);
            else{
                if($this->__SQLCreateInstance($this->__createClass(), $this))
                return new Response(200,[]);
                else 
                return new Response(500,["initialization error "]);
            }
        }
        public function save(){ // create instance and add record to db
            $conf = new Config();
            $nclassname = $this->__createClass();
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
                }else return new Response(405,["make sure you'v initialized values of $clname then try again !"]);
            }else return new Response(500,["error occured, make sure the $clname is correctely created !"]);
        }
        public function delete(){}
        public function edit(){}
        public function getOne(Array $clauses = null){
            $nblines = 0;
            $tabProperties = [];
            $objectName = get_class($this);
            $properties = json_encode($this); 
            $properties = json_decode($properties, true);
            $retResponse = [];

            $conf = new Config();
            $nclassname = $this->__createClass();

            if($clauses === null) return new Response(401, ["a getOne method must have a clause passed as parame"]);
            if(!is_array($clauses)) return new Response(401, ["the passed in getOne method param must be an array"]);
            foreach ($properties as $key => $value) array_push($tabProperties, $key);
            foreach ($clauses as $key => $value) if(!in_array($key, $tabProperties, true)) return new Response(401, ["there is no property $key in Instance $objectName "]);

            $query = "SELECT * FROM `$nclassname` WHERE ";
            foreach ($clauses as $key => $value) {
                ++$nblines;
                $value_ = is_numeric($value) ? $value : "'".$value."'";
                $query .= ((int) $nblines === count($clauses)) ? "`$key` = $value_" : "`$key` = $value_ AND ";            
            }
            $rem = $conf->onFetchingOne($query, $nclassname);
            if($rem !== 500){
                for($i = 0; $i < count($rem); $i++){
                    $tmp_ = null;
                    foreach ($tabProperties as $key => $value) {
                        $this->$value = $rem[$i][$value];
                    }
                    $item = (object) get_object_vars($this);
                    array_push($retResponse, $item);
                }
                return count($retResponse) > 0 ? count($retResponse) === 1 ? $retResponse[0] : $retResponse : $$retResponse;
            }else return new Response(500, []);
        }
        public function getAll(Array $clause = null){

        }
    }
?>