<?php

$requestingUser = $_GET['userID'];
$machineId = $_GET['machineID'];

$query = $pdo->prepare('SELECT * FROM keyDetective WHERE machineId=:machineId AND creatorId=:creatorId AND integrationType=:integrationType');
$query->bindParam(':machineId', $machineId, PDO::PARAM_STR);
$query->bindParam(':creatorId', $requestingUser, PDO::PARAM_STR);
$query->bindParam(':integrationType', $integrationType, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);


$integrationKey = $result['apiKey'];



