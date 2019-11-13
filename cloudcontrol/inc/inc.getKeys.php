<?php
//need to add get key by machine id, integration and type
$query = $pdo->prepare('SELECT * FROM keyDetective WHERE creatorId=:parameter');
$query->bindParam(':parameter', $_SESSION["UUID"], PDO::PARAM_STR);
$query->execute();

$results = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $row) {
        $keyDetectiveResponse =  json_encode($results);        
    }

?>
