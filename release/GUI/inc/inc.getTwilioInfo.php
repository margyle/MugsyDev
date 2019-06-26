<?php
$query = $pdo->prepare('SELECT * FROM twilio WHERE userId=:parameter');
$query->bindParam(':parameter', $_SESSION["UUID"], PDO::PARAM_STR);
$query->execute();
//convert uuid to session var
foreach ($query as $row) {
    $_SESSION['phoneNumber'] = $row['phoneNumber'];
    $_SESSION['twilioSharedKey'] = $row['apiKey'];
    $_SESSION['codeword'] = $row['codeword'];

}

?>
