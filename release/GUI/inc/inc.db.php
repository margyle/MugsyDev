<?php
$DB_HOST = '127.0.0.1';
$DB_USER = 'mugsy';
$DB_PASS = 'mugsy';
$DB_NAME = 'mugsy';
$error =  'obscured';
//connect
try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);
    //echo "Connected to $DB_NAME at $DB_HOST successfully.";
} catch (PDOException $pe) {
    die("Could not connect to the database $error :" . $pe->getMessage());
}
?>
