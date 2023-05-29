<?php 
    class Contributions extends CRUD__ {
        public $id;
        public $amount;
        public $idaccount;
        public $createdon;
        public $updatedon;
        public $status;

        public function __constructor($id, $amount, $idaccount, $createdon, $updatedon, $status){
            $this->id = $id;
            $this->amount = $amount;
            $this->idaccount = $idaccount;
            $this->createdon = $createdon;
            $this->updatedon = $updatedon;
            $this->status = $status;
        }
    }
?>