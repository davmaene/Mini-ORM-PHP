<?php 
    class Rubriques extends CRUD__{
        public $id;
        public $rubrique;
        public $haschils;
        public $isOther;
        public $unitedemesure;

        public function __construct(){

        }
        public function __constructor($id, $rubrique, $haschils, $isOther, $unitedemesure){
            $this->id = $id;
            $this->rubrique = $rubrique;
            $this->haschils = $haschils;
            $this->isOther = $isOther;
            $this->unitedemesure = $unitedemesure;
        }
    }
?>