<?php
//subId is linked to oAuth Cloud Control account
$query = $pdo->prepare('SELECT * FROM recipesList WHERE createdBy=:parameter');
$query->bindParam(':parameter', $_SESSION["subID"], PDO::PARAM_STR);
$query->execute();

$results = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($results as $row) {
    $response =  json_encode($results);        
}
?>
