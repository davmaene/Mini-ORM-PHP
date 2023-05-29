<?php 
interface Metier_{
    public function save();
    public function delete();
    public function edit(Array $clause, Array $sets);
    public function getOne(Array $clause = null, Array $jointure = null, Array $sort = null, $connection);
    public function getAll(Array $clause = null, Array $jointure = null, $connection);
    public function runSQL(Array $options = null);
}
?>