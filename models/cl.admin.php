<?php 
    class Admins extends CRUD__{
        public $id;
        public $nom;
        public $postnom;
        public $phone;
        public $password;
        public $createdon;
        public $status;
        
        public function __constructor($id, $nom, $postnom, $password, $phone){
            $this->id = $id;
            $this->nom = $nom;
            $this->postnom = $postnom;
            $this->password = $password;
            $this->phone = $phone;
            $this->createdon = date("Y-m-d, h:i:s");
            $this->status = 1;
        }
    }
?>