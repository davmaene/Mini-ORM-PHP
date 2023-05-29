<?php 
    class Accounts extends CRUD__{
        
        public $id;
        public $iscouple;
        public $parts;
        public $valuerpart;
        public $socials;
        public $valuerpartsocial;
        public $status;
        public $numcarnet;
        public $ispendingpassif;
        public $createdon;

        public function __constructor(
                $id, 
                $iscouple, 
                $parts, 
                $socials, 
                $valuerpart, 
                $valuerpartsocial, 
                $status, 
                $ispendingpassif, 
                $createdon,
                $numcarnet
            ){
                
            $this->numcarnet = $numcarnet;
            $this->id = $id;
            $this->iscouple = $iscouple;
            $this->parts = $parts;
            $this->valuerpart = $valuerpart;
            $this->valuerpartsocial = $valuerpartsocial;
            $this->socials = $socials;
            $this->status = $status;
            $this->ispendingpassif = $ispendingpassif;
            $this->createdon = $createdon;
        }
    }
?>