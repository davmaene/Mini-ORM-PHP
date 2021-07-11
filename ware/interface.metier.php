<?php 
interface Metier_{
    public function save();
    public function delete();
    public function edit();
    public function getOne(Array $clause = null);
    public function getAll(Array $clause = null);
}
?>