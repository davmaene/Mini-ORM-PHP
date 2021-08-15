<?php 
    class User extends CRUD__{
        public $id;
        public $fsname;
        public $lsname;
        public $role;
        public $avatar;
        public $email;
        public $phone;
        public $pwd;

        public function __construct(int $id = null , string $fsname, string $lsname, int $role, string $avatar, string $email, string $phone, string $password){
            $this->id = $id;
            $this->fsname = $fsname;
            $this->lsname = $lsname;
            $this->role = $role;
            $this->avatar = $avatar;
            $this->email = $email;
            $this->phone = $phone;
            $this->pwd = $password;
        }
    }
?>