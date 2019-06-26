<?php
$query = $pdo->prepare('SELECT * FROM users WHERE authSub=:parameter');
$query->bindParam(':parameter', $_SESSION["subID"], PDO::PARAM_STR);
$query->execute();
//convert uuid to session var
foreach ($query as $row) {
    $_SESSION['UUID'] = $row['id'];
    $_SESSION['username'] = $row['userName'];
    $_SESSION['actualName'] = $row['name'];
    $_SESSION['email'] = $row['email'];
}

?>
