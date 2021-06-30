<?php 
    class User extends CRUD__{
        public $id;
        public $fsname;
        public $lsname;

        public function __construct($id, $fsname, $lsname){
            $this->id = $id;
            $this->fsname = $fsname;
            $this->lsname = $lsname;
        }
    }
?>