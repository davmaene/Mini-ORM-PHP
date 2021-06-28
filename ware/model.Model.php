<?php 
    class LogNotification {
        // public $id;
        public $time;
        public $action;
        public $state = [];
        public $raison;
        // public $separator = '------------------------------';

        public function __construct($time, $action, $state = [], $raison){
            // list($time, $action, $state, $raison) = $notification;
            $this->time = $time;
            $this->action = $action;
            $this->state = $state;
            $this->raison = $raison;
        }
    }
?>