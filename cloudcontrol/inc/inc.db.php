<?php
$DB_HOST = '****************';
$DB_USER = '****************';
$DB_PASS = '****************';
$DB_NAME = '****************';

$error =  'obscured';


//connect
try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);
    //echo "Connected to $DB_NAME at $DB_HOST successfully.";
} catch (PDOException $pe) {
    die("Could not connect to the database $error :" . $pe->getMessage());
}
?>
