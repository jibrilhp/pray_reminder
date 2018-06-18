<?php
/*
Author : Jibril Hartri Putra, 1 Syawwal 1439 H

Reminder cron run every minutes.
*/

require_once('./secret/flag.php');
if (empty($_GET['key']) || $_GET['key'] !== cron_key) {
    
    $ar = array("status"=>"400","message"=>"key value is missing or invalid");
    
    http_response_code(400);
    error_log("Missing key value or invalid");
    header("Content-type: application/json");
    echo json_encode($ar);
    exit();
}


require_once('./func/reminder_cron_func.php');
$xd = new PrayReminder_Cron();

//test to sent broadcast...
if (isset($_GET['coba']) && $_GET['coba'] === 'ok') {
  
    $xd->sent_broadcast("<id line>","<Message>");

    exit();
}

//search athan time and reminds where athan time = local time
if (isset($_GET['run']) && $_GET['run'] == 'athan') {
    $xd->run_cron_update_athan() ;
} elseif (isset($_GET['run']) && $_GET['run'] == 'weather') {
    $xd->run_cron_update_weather() ;
} else {
    $xd->run_cron_athan();
}


?>