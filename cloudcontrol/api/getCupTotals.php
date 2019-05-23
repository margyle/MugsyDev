<?php
$integrationType = 'api_access';
//todo get all includes into one call
//todo move to oAuth for API auth
//todo move brew counts from machine settings to user settings(two types of counts)
//include 'vendor/autoload.php';
include '../inc/inc.db.php';
//get capi access key from key detective 
include '../inc/inc.keyDetective.php';

$machineId = $_GET['machineID'];
$requestingUser = $_GET['userID'];
$userKey = $_GET['authKey'];

//api listener
if ($integrationKey != $userKey) {
    echo "No Permissions";
} else {
    //todo convert two queries to one join
    $query = $pdo->prepare('SELECT * FROM machines WHERE userId=:parameter');
    $query->bindParam(':parameter', $requestingUser, PDO::PARAM_STR);
    $query->execute();
    //add the fetch for associative array
    $machine = $query->fetch(PDO::FETCH_ASSOC);
    //get machine id for next query
    $machineId = $machine['id'];
    //debug
    $query2 = $pdo->prepare('SELECT brewCount FROM machineStatuses WHERE machineId=:parameter');
    $query2->bindParam(':parameter', $machineId, PDO::PARAM_STR);
    $query2->execute();
    //add the fetch for associative array
    $status = $query2->fetch(PDO::FETCH_ASSOC);

    $brewCount = $status['brewCount'];

    echo json_encode($status);
}
