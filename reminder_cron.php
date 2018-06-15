<?php
/*
Author : Jibril Hartri Putra, 1 Syawwal 1439 H

Reminder cron run every minutes.
*/

require_once('./secret/flag.php');
if (empty($_GET['key']) || $_GET['key'] !== cron_key) {
    header("Content-type: application/json");
    $ar = array("status"=>"400","message"=>"key value is missing or invalid");
    echo json_encode($ar);
    http_response_code(400);
    exit();
}


require_once('./func/reminder_cron_func.php');

$xd = new PrayReminder_Cron();
$xd->sent_broadcast("Uc2873b83985161fca764bb1c858663d8","Hai Bril!!!");


?>