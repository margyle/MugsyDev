<?php
//update brewing status. 1 = currently brewing
$fp = fopen('../coffeeNowProcess/mgmt/status.txt', 'w');
fwrite($fp, '1');
fclose($fp);
?>