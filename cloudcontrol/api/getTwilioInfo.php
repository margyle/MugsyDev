<?php
//todo get all includes into one call
//todo move to oAuth for API auth
//include 'vendor/autoload.php';
include '../inc/inc.db.php';

$key = $_GET['userKey'];
//debug
//echo $key;

//api listener
if (!isset($key)) {
    echo "No Permissions";
} else {
    $query = $pdo->prepare('SELECT * FROM twilio WHERE userId=:parameter');
    $query->bindParam(':parameter', $key, PDO::PARAM_STR);
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