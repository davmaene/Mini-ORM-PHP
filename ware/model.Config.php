<?php
/* 
// Auther : davmaene
// conatact : +243 970 284 772
// mail : kubuya.darone.david@gmail.com | davidmened@gmail.com
// created on june 27 2021 13H 31
*/
// cette class de configuration

class Config implements Init {

    private $_dialect = env['dialect'] ?? 'mysql';
    private $_dbname = env['dbname'];
    private $_username = env['username'];
    private $_password = env['password'];
    protected $db = null;

    private function retrievesColumn($table, $alias){
        $columnname = [];
        $tabColumn = $this->db->prepare("SHOW COLUMNS FROM $table");
        try {
            $tabColumn->execute();
            $tabColumn = $tabColumn->fetchAll();
            for($i = 0; $i < count($tabColumn); $i++){
                array_push($columnname, $tabColumn[$i]['Field']);
            }
            return implode(",", $columnname);
        } catch (PDOException $e) {
            $exc = new LogNotification([Date('d/m/Y, H:i:s')],["SHOW COLUMNS FROM $table"],['Failed'],[$e->getMessage()]);
            $this->onLog($exc,2);
            return false;
            // die($e->getMessage());
        }
    }

    public function onSynchronization($tbValues = [], $indentified, $table){
        if(is_array($tbValues) && (count($tbValues) > 0)){
            $cls = $this->retrievesColumn($table, false);
            $tabvalues = [];
            if(strlen($cls) > 0){
                $cls = substr($cls,strpos($cls,',',0) + 1);
                if($cls){
                    // array_push($tbValues, $indentified);
                    // array_push($tbValues, 0);
                    // array_push($tbValues, 0);
                    // array_push($tbValues, 1);
                    // array_push($tbValues, date('d/m/Y, H:i:s'));
                    foreach ($tbValues as $key => $value) {$val = ("'".$value."'");array_push($tabvalues, $val);}
                    try {
                        $vls = implode(',',$tabvalues);
                        $req = $this->db->prepare("INSERT INTO $table ($cls) VALUES ($vls)");
                        $req->execute();
                        return 200;
                    } catch (PDOException $e) {
                        $exc = new LogNotification([Date('d/m/Y, H:i:s')],["CRUD ERROR ON ADDING : $table"],['Failed'],[$e->getMessage()]);
                        $this->onLog($exc,2);
                        return 503; // violation constraint
                    }
                }return 500;
            }return 500;
        }return 500;
    }

    public function __construct(){
        $this->onInit();
    }

    public function __inst(){
        return $this;
    }

    public function onInit(){
        if($this->onConnexion()){
            // $this->addFiveExtraColumns();
            return true;
        } else {
            $this->onWriteMessage(false);
        } 
    }

    public function onFetchingOne($query, $tablename){
        try {
            // echo($query);
            // $this->addFiveExtraColumns(); // ceci est important quand il faut que j'ajoute les extras column avant d'ajouter les datas
            $req = $this->db->prepare($query);
            $req->execute();
            $req = $req->fetchAll();
            // var_dump($req);
            return !empty($req) && count($req) > 0 ? $req : array();
        } catch (PDOException $e) {
            $exc = new LogNotification([Date('d/m/Y, H:i:s')],["Error writting query in $tablename table"],['Failed'],[$e->getMessage()]);
            $this->onLog($exc,2);
            return 500; // faild writting
        }
    }

    public function onRunningQuery($query, $tablename){
        try {
            // echo($query);
            // $this->addFiveExtraColumns(); // ceci est important quand il faut que j'ajoute les extras column avant d'ajouter les datas
            $req = $this->db->prepare($query);
            $req->execute();
            return true; // done writting
        } catch (PDOException $e) {
            $exc = new LogNotification([Date('d/m/Y, H:i:s')],["Error writting query in $tablename table"],['Failed'],[$e->getMessage()]);
            $this->onLog($exc,2);
            return false; // faild writting
        }
    }

    public function onConnexion(){
        if($this->db === null){
            try {
                $conn = new PDO("mysql:host=localhost;dbname=$this->_dbname", "$this->_username", "$this->_password");
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->db = $conn;
                return true;
            } catch (PDOException $e) {
                $exc = new LogNotification([Date('d/m/Y, H:i:s')],["Connexion to DB ".$this->_dbname],['Failed'],[$e->getMessage()]);
                $this->onLog($exc,2);
                // var_dump($exc);
                return false;
            }
        }else return false;
    }

    public function onLog($array, $to){
        $file = ($to === 1) ? './middleware/log/ini.initialize.ini' : './middleware/log/log.file.ini';
        $res = array();
        foreach($array as $key => $val)
        {
            if(is_array($val))
            {
                $res[] = "[$key]";
                foreach($val as $skey => $sval) $res[] = (is_numeric($sval) ? $sval : '"'.$sval.'"');
            }
            else $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
        }
        $res[] = '-------------------------------------------------------------'.PHP_EOL;
        $this->safefilerewrite(implode("\r\n", $res),$file);
    }

    private function safefilerewrite($dataToSave, $fileName){
        $fp = null;
        try {
            if(file_exists($fileName)){
                $fp = fopen($fileName, 'a++');
            }
            else{
                $fileName = "ini.initialize.ini";
                $fp = fopen($fileName, "a++");
            }
        } catch (\Throwable $th) {
            $fileName = "ini.initialize.ini";
            $fp = fopen($fileName, "a++");
        }
        if ($fp)
        {
            chmod($fileName, 0777);
            $startTime = microtime(TRUE);
            do
            {            
                $canWrite = flock($fp, LOCK_EX);
                // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
                if(!$canWrite) usleep(round(rand(0, 100)*1000));
            } while ((!$canWrite)and((microtime(TRUE)-$startTime) < 5));
            //file was locked so now we can store information
            if ($canWrite){          
                // fwrite($fp, $dataToSave);
                flock($fp, LOCK_UN);
                file_put_contents($fileName, $dataToSave.PHP_EOL,FILE_APPEND);
            }
            fclose($fp);
        }
    }

    private function verifyAndWriteExtraColumns($conn,$table){
        $tabExtraColumn = array('createby', 'modifiedby', 'deletedby', 'datastatus', 'createdon');
        $tabColumn = $conn->prepare("SHOW COLUMNS FROM $table");
        $fictiveTable = [];
        try {
            $tabColumn->execute();
            $tabColumn = $tabColumn->fetchAll();
            $lastColumn = end($tabColumn);
            $lastColumn = $lastColumn[0];

            for($i = 0; $i < count($tabColumn); $i++){
                array_push($fictiveTable, $tabColumn[$i]['Field']);
            }
            foreach ($tabExtraColumn as $key => $column) {
                if(!(in_array($column, $fictiveTable, true))){
                    $re = ($column === 'createdon')
                    ? $conn->prepare("ALTER TABLE `$table` ADD `$column` VARCHAR(45) NOT NULL DEFAULT 'mid' AFTER `$lastColumn`")
                    : $conn->prepare("ALTER TABLE `$table` ADD `$column` INT(11) NOT NULL DEFAULT 18041995 AFTER `$lastColumn`");
                    try {
                        $re->execute();
                        $lastColumn = $column;
                    } catch (PDOException $e) {
                        $exc = new LogNotification([Date('d/m/Y, H:i:s')],["Error adding column to : $table"],['Failed'],[$e->getMessage()]);
                        $this->onLog($exc,2);
                    }
                }
            }

        } catch (PDOException $e) {
            $exc = new LogNotification([Date('d/m/Y, H:i:s')],["Error showing columns on $table"],['Failed'],[$e->getMessage()]);
            $this->onLog($exc,2);
        }
    }

    private function addFiveExtraColumns(){
        $conn = $this->db;
        $req = $conn->prepare('SHOW TABLES');
        try {
            $req->execute();
            $req = $req->fetchAll();
            if(count($req) > 0){
                foreach ($req as $key => $table) {
                    $this->verifyAndWriteExtraColumns($conn, $table[0]);
                }
            }
        } catch (PDOException $e){
            $exc = new LogNotification([Date('d/m/Y, H:i:s')],["Error shown Table from db : ".$this->_dbname],['Failed'],[$e->getMessage()]);
            $this->onLog($exc,2);
        } 
    }

    private function onWriteMessage($args){
        die("
            <h3 style='color: red; text-align: center'>Une erreur vient de produire lors de la tentative de connexion à la base des données :: <span style='color: black'>". $this->_dbname."</span></h3>
            <p style='text-align: center; font-weight: bold'>for more informations <a href='./middleware/log/log.file.ini'>log file</a></p>
        ");
        return false; // facultative
    }
}
?>