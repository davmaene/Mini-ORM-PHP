<?php 
class Credits extends CRUD__{

    public $id;
    public $idaccount;
    public $montant;
    public $montantdu;
    public $montantpaye;
    public $devise;
    public $type;
    public $createdon;
    public $updatedon;
    public $status;

    public function __construct() {}
        
    public function __constructor($id, $idaccount, $montant, $montantdu, $montantpaye, $devise, $type, $createdon, $updatedon, $status){
        $this->id = $id;
        $this->idaccount = $idaccount;
        $this->montantdu = $montantdu;
        $this->montantpaye = $montantpaye;
        $this->devise = $devise;
        $this->type = $type;
        $this->montant = $montant;
        $this->createdon = $createdon;
        $this->updatedon = $updatedon;
        $this->status = $status;
    }   
}

?>