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
            $prefixe = "tbl_";
            $classname = get_class($this);
            if(!empty($classname)){
            //   if(!strpos($classname,"s",-1)) $classname .= "s";
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
                        case 200: 
                            // $query = "SELECT * FROM $nclassname WHERE id = (SELECT LAST_INSERT_ID())";
                            $lastinsertedrow = "SELECT LAST_INSERT_ID()";
                            $resp = $conf->onFetchingOne($lastinsertedrow, $nclassname);
                            $row = $this->getOne(array(
                                "id" => $resp[0]["LAST_INSERT_ID()"]
                            ));
                            return  $row ?? new Response(200, $row);
                        case 503: return new Response(503, []);
                        default: return new Response(505, []);
                    }
                }else return new Response(405,["make sure you'v initialized values of $clname then try again !"]);
            }else return new Response(500,["error occured, make sure the $clname is correctely created !"]);
        }
        public function delete(){}
        // -------------------------- CRUD ``` EDIT ``` ---------------
        public function edit(Array $clauses, Array $sets){
            $nblines = 0;
            $tabProperties = [];
            $objectName = get_class($this);
            $properties = json_encode($this); 
            $properties = json_decode($properties, true);

            $conf = new Config();
            $nclassname = $this->__createClass();

            if(isset($clauses) && is_array($clauses) && isset($sets) && is_array($sets) && count($sets) > 0 && count($clauses) > 0){
                foreach ($properties as $key => $value) array_push($tabProperties, $key);
                foreach ($clauses as $key => $value) if(!in_array($key, $tabProperties, true)) return new Response(401, ["there is no property :: $key :: in Instance :: $objectName :: bad params in clause"]);

                // foreach ($properties as $key => $value) array_push($tabProperties, $key);
                foreach ($sets as $key => $value) if(!in_array($key, $tabProperties, true)) return new Response(401, ["there is no property :: $key :: in Instance :: $objectName :: bad params in SETS"]);

                $query = "UPDATE $nclassname SET ";
                $cls = " WHERE";
                foreach ($sets as $k => $val){
                    ++$nblines;
                    $value_ = is_numeric($val) ? $value : "'".$val."'";
                    $query .= ((int) $nblines === count($sets)) ? "`$key` = $value_" : "`$key` = $value_ AND "; 
                }

                $nblines = 0; // reinitilaize compter 

                foreach ($clauses as $key => $value) {
                    ++$nblines;
                    $value_ = is_numeric($value) ? $value : "'".$value."'";
                    $cls .= ((int) $nblines === count($clauses)) ? " `$key` = $value_" : " `$key` = $value_ AND ";            
                }
                $query .= $cls;
                $resp = $conf->onRunningQuery($query, $nclassname);
                if(is_bool($resp) && $resp === true){
                    $getit = $this->getOne($clauses);
                    return $getit;
                }else return new Response(500, ["Error occured when trying to run Query on TABLE :: $nclassname "]);
            }else return new Response(401, ["CLAUSE and SETS must be instance of array"]);
        }
        // -------------------------- CRUD ``` RETRIEVE ONE ``` -------
        public function getOne(Array $clauses = null, Array $joiture = null, $sort = null){
            $nblines = 0;
            $tabProperties = [];
            $objectName = get_class($this);
            $properties = json_encode($this); 
            $properties = json_decode($properties, true);
            $retResponse = [];

            $conf = new Config();
            $nclassname = $this->__createClass();

            if($clauses === null) return new Response(401, ["a getOne method must have a clause passed as param"]);
            if(!is_array($clauses)) return new Response(401, ["the passed in getOne method param must be an array"]);
            foreach ($properties as $key => $value) array_push($tabProperties, $key);
            foreach ($clauses as $key => $value) if(!in_array($key, $tabProperties, true)) return new Response(401, ["there is no property :: $key :: in Instance :: $objectName ::"]);

            $query = "SELECT * FROM `$nclassname` WHERE ";
            foreach ($clauses as $key => $value) {
                ++$nblines;
                $value_ = is_numeric($value) && strlen($value) < 2 ? $value : "'".$value."'";
                $query .= ((int) $nblines === count($clauses)) ? " `$key` = $value_" : " `$key` = $value_ AND ";            
            }
            $query .= " LIMIT 1";
            $rem = $conf->onFetchingOne($query, $nclassname);
            if($rem !== 500){
                for($i = 0; $i < count($rem); $i++){
                    foreach ($tabProperties as $key => $value) {
                        $this->$value = $rem[$i][$value];
                    }
                    $item = (object) get_object_vars($this);
                    array_push($retResponse, $item);
                }
                $result = count($retResponse) > 0 ? ((count($retResponse) === 1) ? ($retResponse[0]) : (object) ($retResponse)) : (object) $retResponse;
                return new Response(200, $result);
            }else return new Response(500, []);
        }
        // --------------------------
        public function getAll(Array $clauses = null, Array $jointure = null){
            $nblines = 0;
            $tabProperties = [];
            $objectName = get_class($this);
            $properties = json_encode($this); 
            $properties = json_decode($properties, true);
            $retResponse = [];
            $thereisjointure = false;

            $conf = new Config();
            $nclassname = $this->__createClass();
            $query = "SELECT * FROM `$nclassname`";

            // if($clauses === null) return new Response(401, ["a getOne method must have a clause passed as parame"]);
            if($clauses !== null){
                if(!is_array($clauses)) return new Response(401, ["the passed in getOne method param must be an array"]);
                foreach ($properties as $key => $value) array_push($tabProperties, $key);
                foreach ($clauses as $key => $value) if(!in_array($key, $tabProperties, true)) return new Response(401, ["there is no property :: $key :: in Instance :: $objectName ::"]);
            }
            if($jointure !== null){
                if(is_array($jointure)){
                    // && isset($jointure["on"]) && isset($jointure["table"])
                    $cl = "";

                    foreach ($jointure as $k => $va) {
                        $joiTable = $va['table'];
                        $handLeft = $va['on'][0];
                        $handRight = $va['on'][1];
                        // var_dump($va);
                        if(isset($va['clause'])){

                            $cl .= " WHERE ";
                            $thereisjointure = true;
                            foreach ($va['clause'] as $key => $value___) {
                                ++$nblines;
                                $value_ = is_numeric($value___) ? $value___ : "'".$value___."'";
                                $cl .= ((int) $nblines === count($va['clause'])) ? "$joiTable.$key = $value_" : "$joiTable.$key = $value_ AND "; 
                                // " $joiTable.$key = $value_ ";
                            }
                        }
                        if(isset($va['joinedto'])){
                            $joinedto = $va['joinedto'];
                            $query .= " JOIN `$joiTable` ON $joiTable.$handRight = $joinedto.$handLeft ";
                        }else $query .= " JOIN `$joiTable` ON $joiTable.$handRight = $nclassname.$handLeft ";
                    }
                    $query .= " $cl ";
                }
            }
            $nblines = 0;
            if(count($tabProperties) > 0){
                $query .= $thereisjointure ? " AND " : " WHERE ";
                foreach ($clauses as $key => $value) {
                    ++$nblines;
                    $value_ = is_numeric($value) ? $value : "'".$value."'";
                    $query .= ((int) $nblines === count($clauses)) ? "$nclassname.$key = $value_" : "$nclassname.$key = $value_ AND ";            
                }
                // $query .= " AND `int_type_id` = 1";
            }
            // else $query .= " WHERE `int_type_id` = 1";

            $rem = $conf->onFetchingOne($query, $nclassname);
            $results = [];
            if($rem !== 500){
                // var_dump($rem[0]);
                if(count($tabProperties) > 0){
                    for($i = 0; $i < count($rem); $i++){
                        foreach ($tabProperties as $key => $value) {
                            $this->$value = $rem[$i][$value];
                        }
                        $item = (object) get_object_vars($this);
                        array_push($retResponse, $item);
                    }
                    $results = count($retResponse) > 0 ? (count($retResponse) === 1 ? $retResponse[0] : $retResponse) : $retResponse;
                    return new Response(200, $results); 
                }else{
                    // var_dump($properties);
                    // return null;
                    for($i = 0; $i < count($rem); $i++){
                        foreach ($properties as $key => $value) {
                            $this->$key = $rem[$i][$key];
                        }
                        $item = (object) get_object_vars($this);
                        array_push($retResponse, $item);
                    }
                    $results = count($retResponse) > 0 ? (count($retResponse) === 1 ? $retResponse[0] : $retResponse) : $retResponse;
                    return new Response(200, $results);
                }
            }else return new Response(500, []);
        }
    }
?>