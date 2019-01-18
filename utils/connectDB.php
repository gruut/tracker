<?php
function connectDB() {

    static $connection;

    if(!isset($connection)) {
        $config = parse_ini_file('../config.ini'); 
        $connection = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
    }

    if($connection === false) {
        return mysqli_connect_error(); 
    }
    return $connection;
}