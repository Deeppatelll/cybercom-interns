<?php

namespace Library\Database;

class Connection {
    public function connect() {
        echo "Connected to Database<br>";
    }
}

namespace Library\API;

class Connection {
    public function connect() {
        echo "Connected to API<br>";
    }
}

// --------- Testing inside same file ---------

namespace {

    use Library\Database\Connection as DBConnection;
    use Library\API\Connection as APIConnection;

    $db = new DBConnection();
    $api = new APIConnection();

    $db->connect();
    $api->connect();
}

//task 2 
use Library\Database\Connection as DBConnection;
use Library\API\Connection as APIConnection;

$db = new DBConnection();
$api = new APIConnection();

echo "<hr>";
$db->connect();
$api->connect();
?>