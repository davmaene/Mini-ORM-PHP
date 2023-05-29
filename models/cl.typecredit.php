<?php 
class Typecredits extends CRUD__{
    public $id;
    public $type;
    public $echeance;
    public $libeleecheance;
    public $interet;
    public $min;
    public $max;
    public $penalite;

    public function __constructor($id, $type, $echeance, $libeleecheance, $interet, $min, $max, $penalite) {
        $this->id = $id;
        $this->type = $type;
        $this->echeance = $echeance;
        $this->libeleecheance = $libeleecheance;
        $this->interet = $interet;
        $this->min = $min;
        $this->max = $max;
        $this->penalite = $penalite;
    }
}
?>