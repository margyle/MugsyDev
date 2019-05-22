<?php
$integrationType = 'api_access';
//todo get all includes into one call
//todo move to oAuth for API auth
//include 'vendor/autoload.php';
include '../inc/inc.db.php';
//get capi access key from key detective 
include '../inc/inc.keyDetective.php';

$machineId = $_GET['machineID'];
$requestingUser = $_GET['userID'];
$userKey = $_GET['authKey'];
//debug
//echo $key;

//api listener
if ($integrationKey != $userKey) {
    echo "No Permissions";
} else {
    $query = $pdo->prepare('SELECT * FROM machines WHERE id=:parameter');
    $query->bindParam(':parameter', $machineId, PDO::PARAM_STR);
    $query->execute();
    //add the fetch for associative array
    $machine = $query->fetch(PDO::FETCH_ASSOC);


    $machineId = $machine['id'];
    $machineUserid = $machine['userId'];
    $machineName = $machine['machineName'];
    //echo json_encode(($query);
    //echo json_encode($dataArray);

    echo json_encode($machine);
    //debug
    //echo "</br>" . $machineId . " " . $machineUserid . " " . $machineName;
}
