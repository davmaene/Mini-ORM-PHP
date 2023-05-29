<?php 
class Membres extends CRUD__{
    public $id;
    public $nom;
    public $postnom;
    public $phone;
    public $pendingpassif;
    public $idaccount;
    public $status;
    public $createdon;

    public function __constructor($id, $nom, $postnom, $phone, $pendingpassif, $idaccount, $status, $createdon){
        $this->id = $id;
        $this->nom = $nom;
        $this->postnom = $postnom;
        $this->phone = $phone;
        $this->idaccount = $idaccount;
        $this->pendingpassif = $pendingpassif;
        $this->status = $status;
        $this->createdon = $createdon;
        
    }
}

?>