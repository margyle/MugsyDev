<?php
$DB_HOST = '****************';
$DB_USER = '****************';
$DB_PASS = '****************';
$DB_NAME = '****************';
//GRANT ALL PRIVILEGES ON cloudControl.* To '8DEEA34BF994B'@'localhost' IDENTIFIED BY '94D6F1EB62CCC';
//CREATE USER '8DEEA34BF994B'@'localhost';

//connect
try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);
    //echo "Connected to $DB_NAME at $DB_HOST successfully.";
} catch (PDOException $pe) {
    die("Could not connect to the database $DB_NAME :" . $pe->getMessage());
}
?>
