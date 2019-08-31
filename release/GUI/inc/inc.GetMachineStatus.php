<?php
$query = $pdo->prepare('SELECT * FROM machineStatus WHERE machineId=:parameter');
$query->bindParam(':parameter', $_SESSION["machineId"], PDO::PARAM_STR);
$query->execute();

foreach ($query as $row) {
    $onlineStatus = $row['onlineStatus'];
    $waterStatus = $row['waterStatus'];
    $beanStatus = $row['beanStatus'];
    $mugStatus = $row['mugStatus'];
    $hardwareStatus = $row['hardwareStatus'];

}


//online status display
    if ($onlineStatus == 1){
        $onlineStatusDisplay = '<i class="fas fa-circle text-success"> </i>';
    } 
    else {
        $onlineStatusDisplay = '<i class="fas fa-circle text-danger"> </i>';
    }
//water status
    if ($waterStatus == 1){
        $waterStatusDisplay = '<i class="fas fa-circle text-success"> </i>';
    } 
    else {
        $waterStatusDisplay = '<i class="fas fa-circle text-danger"> </i>';
    }
//bean status
    if ($beanStatus == 1){
        $beanStatusDisplay = '<i class="fas fa-circle text-success"> </i>';
    } 
    else {
        $beanStatusDisplay = '<i class="fas fa-circle text-danger"> </i>';
    }
//mug status
    if ($mugStatus == 1){
        $mugStatusDisplay = '<i class="fas fa-circle text-success"> </i>';
    } 
    else {
        $mugStatusDisplay = '<i class="fas fa-circle text-danger"> </i>';
    }


    //if hardware is ok, show statuses. If hardware is not ok show all red
    if ($hardwareStatus==1) {
        $statusDisplay = $onlineStatusDisplay .' '. $waterStatusDisplay .' '. $beanStatusDisplay .' '. $mugStatusDisplay;
        }
    else{
        $statusDisplay = '<i class="fas fa-circle text-danger"></i>
                        <i class="fas fa-circle text-danger"></i>
                        <i class="fas fa-circle text-danger"></i>
                        <i class="fas fa-circle text-danger"></i>';
        }




?>


