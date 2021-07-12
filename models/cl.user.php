<?php 
    class User extends CRUD__{
        public $id;
        public $fsname;
        public $lsname;

        public function __construct(int $id = null , string $fsname, string $lsname){
            $this->id = $id;
            $this->fsname = $fsname;
            $this->lsname = $lsname;
        }
    }
?>