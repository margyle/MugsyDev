<?php
$integrationType = 'api_access';
//todo get all includes into one call
//todo move to oAuth for API auth
//include 'vendor/autoload.php';
include '../inc/inc.db.php';
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
    $query = $pdo->prepare('SELECT * FROM twilio WHERE userId=:parameter');
    $query->bindParam(':parameter', $requestingUser, PDO::PARAM_STR);
    $query->execute();
    //add the fetch for associative array
    $twilio = $query->fetch(PDO::FETCH_ASSOC);

    foreach ($query as $row) {
        $codeword = $row['codeword'];
        $phoneNumber = $row['phoneNumber'];
    }
    echo json_encode($twilio);


}
//debug
