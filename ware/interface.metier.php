<?php 
interface Metier_{
    public function save();
    public function delete();
    public function edit();
    public function getOne($clause = null);
    public function getAll($clause = null);
}
?>