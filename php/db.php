<?php

class MySQLDatabase {
    // Variables
    private $ServerAddress = '127.0.0.1';
    private $UserName = 'root';
    private $UserPassword = '';
    private $DatabaseName = 'tilopi';
    private $db;

    //public $result;
    //public $row;

    // Methods
    function __construct() {
        $this->db = mysqli_connect($this->ServerAddress, $this->UserName, $this->UserPassword, $this->DatabaseName);
    }
    //print($mysqli->error);

    public function Query($query) {
        // Where should I put escape_string ?
        return $this->db->query($query);
    }

    public function Escape($str) {
        return $this->db->real_escape_string($str);
    }
}

?>
