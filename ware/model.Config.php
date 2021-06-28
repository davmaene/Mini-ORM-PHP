<?php
/* 
// Auther : davmaene
// conatact : +243 970 284 772
// mail : kubuya.darone.david@gmail.com | davidmened@gmail.com
// created on june 27 2021 13H 31
*/
// cette class est une configuration
class Config implements Init{

    private $_dbname = "_dbmidleware";
    private $_username = "root";
    private $_password = "";
    protected $db = null;

    public function __construct(){

    }
    public function onInit(){
        if($this->onConnexion()){
            $this->addFiveExtraColumns();
            return true;
        }else return false;
    }
    public function onConnexion(){
        try {
            $conn = new PDO("mysql:host=localhost;dbname=$this->_dbname", "$this->_username", "$this->_password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db = $conn;
            return true;
        } catch (PDOException $e) {
            $exc = new LogNotification([Date('d/m/Y, H:i:s')],["Connexion to DB ".$this->_dbname],['Failed'],[$e->getMessage()]);
            $this->onLog($exc,2);
            return false;
        }
    }
    public function onLog($array, $to){
        $file = ($to === 1) ? 'ini.initialize.ini' : 'log.file.ini';
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
        if ($fp = fopen($fileName, 'a++'))
        {
            $startTime = microtime(TRUE);
            do
            {            
                $canWrite = flock($fp, LOCK_EX);
                // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
                if(!$canWrite) usleep(round(rand(0, 100)*1000));
            } while ((!$canWrite)and((microtime(TRUE)-$startTime) < 5));
            //file was locked so now we can store information
            if ($canWrite)
            {          
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
                    $re = ($column === 'datastatus')
                    ? $conn->prepare("ALTER TABLE `$table` ADD `$column` INT(11) NOT NULL DEFAULT '1' AFTER `$lastColumn`")
                    : ($column === 'createdon')
                    ? $conn->prepare("ALTER TABLE `$table` ADD `$column` VARCHAR(45) NOT NULL DEFAULT 'mid' AFTER `$lastColumn`")
                    : $conn->prepare("ALTER TABLE `$table` ADD `$column` INT(11) NOT NULL DEFAULT '0' AFTER `$lastColumn`");
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
}
?>