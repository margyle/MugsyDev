<?php
//todo get all includes into one call
//todo move to oAuth for API auth
//include 'vendor/autoload.php';
include '../inc/inc.db.php';

$key = $_GET['userKey'];
//$machineId = "foo";
//debug
//echo $key;

//api listener
if (!isset($key)) {
    echo "No Permissions";
} else {
    //todo convert two queries to one join
    $query = $pdo->prepare('SELECT * FROM machines WHERE userId=:parameter');
    $query->bindParam(':parameter', $key, PDO::PARAM_STR);
    $query->execute();
    //add the fetch for associative array
    $machine = $query->fetch(PDO::FETCH_ASSOC);
    //get machine id for next query
    $machineId = $machine['id'];
    //debug
    $query2 = $pdo->prepare('SELECT * FROM machineStatuses WHERE machineId=:parameter');
    $query2->bindParam(':parameter', $machineId, PDO::PARAM_STR);
    $query2->execute();
    //add the fetch for associative array
    $status = $query2->fetch(PDO::FETCH_ASSOC);

    $machineId = $status['machineId'];
    $onlineStatus = $status['onlineStatus'];
    $brewStatus = $status['brewStatus'];
    $waterLevel = $status['waterLevel'];
    $beanLevel = $status['beanLevel'];
    $brewCount = $status['brewCount'];

    echo json_encode($status);
}