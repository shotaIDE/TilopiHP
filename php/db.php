<?php

class MySQLDatabase {
    // Variables
    private $ServerAddress = '127.0.0.1';
    private $UserName = 'root';
    private $UserPassword = '';
    private $DatabaseName = 'tilopi';
    // さくらインターネット上での設定
    //private $ServerAddress = 'mysql528.db.sakura.ne.jp';
    //private $UserName = 'ide';
    //private $UserPassword = '143rienzi';
    //private $DatabaseName = 'ide_tilopi';
    private $db;

    // Methods
    function __construct() {
        $this->db = mysqli_connect($this->ServerAddress, $this->UserName, $this->UserPassword, $this->DatabaseName);
        
        // Set encoding: UTF-8
        $query = "SET NAMES utf8";
        $this->Query($query);
    }
    //print($mysqli->error);

    public function Query($query) {
        // Where should I put escape_string ?
        return $this->db->query($query);
    }

    public function Escape($str) {
        //return $str;
        return $this->db->real_escape_string($str);
    }
}

?>
