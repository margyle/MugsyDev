<?php
session_start();
include 'inc.db.php';

//check form data
    if ( !in_array($_POST['integrationType'], ['Coffee Now','D.E.C.A.F API','Developer'], true ) ) {
        echo "Key Creation Security Violation<br>";
        echo $_SERVER['REMOTE_ADDR'];
        $query = $pdo->prepare("INSERT INTO `keySecurityLogs`(`creatorId`, `insertAttempt`, `ip`) VALUES (?,?,?)");
        $query->execute([$_SESSION['UUID'], $_POST['integrationType'],$_SERVER['REMOTE_ADDR']]);
    }
    else {
        //insert new key
        $query = $pdo->prepare("INSERT INTO `keyDetective`(`creatorId`, `integrationType`, `machineId`) VALUES (?,?,?)");
        $query->execute([$_SESSION['UUID'], $_POST['integrationType'],$machineId]);
        header('Location: ../integrations.php?created=1');
        // echo $_SESSION["UUID"]." ! ". $_POST['integrationType'];
        // $foo = "connection working yo";
    }
?>
