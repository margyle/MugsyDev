<?php
$integrationType = 'coffee_now';
//todo get all includes into one call
//todo move to oAuth for API auth
//include 'vendor/autoload.php';
include '../inc/inc.db.php';
//get coffee now key from key detective 
include '../inc/inc.keyDetective.php';

$machineId = $_GET['machineID'];
$requestingUser = $_GET['userID'];
$userKey = $_GET['authKey'];

$startBrewFlag = 1;

//api listener
if ($integrationKey != $userKey) {
    echo "No Permissions";
} else {
    //get current brew ready status
    $query = $pdo->prepare('SELECT * FROM machineStatuses WHERE machineId=:parameter');
    $query->bindParam(':parameter', $machineId, PDO::PARAM_STR);
    $query->execute();
    //add the fetch for associative array
    $brewStatus = $query->fetch(PDO::FETCH_ASSOC);

    $brewReadyStatus = $brewStatus['brewStatusCode'];
    $brewReadyMessage = $brewStatus['brewStatusMessage'];


    //update coffee now monitor to start brewing if ready status = 1

    if ($brewStatus['brewStatusCode'] == '1') {
        //update coffeeNowMonitor
        $updateCoffeeNowMonitor = "UPDATE coffeeNowMonitor SET startBrewStatus=?, requestingUser=? WHERE machineId=?";
        $pdo->prepare($updateCoffeeNowMonitor)->execute([$startBrewFlag, $requestingUser, $machineId]);

        //get resulting record after the update to return to requestor
        $brewReturn = $pdo->prepare('SELECT * FROM coffeeNowMonitor WHERE machineId=:parameter');
        $brewReturn->bindParam(':parameter', $machineId, PDO::PARAM_STR);
        $brewReturn->execute();
        $result = $brewReturn->fetch(PDO::FETCH_ASSOC);

        echo json_encode($result);
        // debug echo '<hr>BREWING'.$brewStatus['brewStatusCode'];
    } else {
        $brewReturn = $pdo->prepare('SELECT * FROM coffeeNowMonitor WHERE machineId=:parameter');
        $brewReturn->bindParam(':parameter', $machineId, PDO::PARAM_STR);
        $brewReturn->execute();
        $result = $brewReturn->fetch(PDO::FETCH_ASSOC);
        echo json_encode($result);
        // debug echo '<hr>Unable to brew';
    }

}
