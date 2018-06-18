<?php
/*
Author: Jibril Hartri Putra, 28 Ramadhan 1439 H

*/

class PrayReminder_Cron
{
    //run on cron only to reduce exceeded API requests

    public function sent_broadcast($line_id,$message) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/new/linebot_sdk/LINEBotTiny.php');
    require($_SERVER['DOCUMENT_ROOT'] . '/new/secret/database.php');
    require($_SERVER['DOCUMENT_ROOT'] . '/new/secret/flag.php');
        //sent a broadcast

        $client = new LINEBotTiny($channelAccessToken, $channelSecret);
        $replyToken=$line_id;
        
        $rep = array(
            'to' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $message
                    )));
        $client->pushMessage($rep);
        $line_id = mysqli_real_escape_string($conn,$line_id);
        date_default_timezone_set("Asia/Jakarta");
        $timestamp = time();
        $sql = "UPDATE `tb_line` SET `p_sent` = '$timestamp' WHERE `tb_line`.`line_id` = '$line_id';";
        mysqli_query($conn,$sql);

        sleep(1);
  
    return "success";
    }

    public function run_cron_athan () {
    require("reminder_main_func.php");
    
    require_once($_SERVER['DOCUMENT_ROOT'] . "/new/secret/database.php");
    $pp = new PrayReminder_Cron();
    $jumat = false; //set false to jumat

    if (date("D") == "Fri") {
        $jumat = true;
    } else {
        $jumat = false;
    }

    //run on every minutes
    //+5 minutes  to delay sql process
    date_default_timezone_set("Asia/Jakarta");
    //$expired = date('Y-m-d H:i:s', strtotime("+5 min"));
    //$sql = "SELECT `line_id`,`w_fajr`,`w_dhuhr`,`w_asr`,`w_maghrib`,`w_isha`,`i_tahajud`,`i_dhuha`,`p_zone`,`p_sent`,`p_city`,`p_city_weather` FROM `tb_line` WHERE w_fajr='11:55' or w_dhuhr ='11:55' or w_asr='11:55' or w_maghrib = '11:55' or w_isha = '11:55'";
     $sql = "SELECT `line_id`,`w_fajr`,`w_dhuhr`,`w_asr`,`w_maghrib`,`w_isha`,`i_tahajud`,`i_dhuha`,`p_zone`,`p_sent`,`p_city`,`p_city_weather`,`p_lang` FROM `tb_line` ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($line,$fajr,$dhuhr,$asr,$maghrib,$isha,$tahaj,$dhuha,$zone,$sended,$city,$cityweather,$lang);

        while ($stmt->fetch()) {
            date_default_timezone_set($zone);
            $time_now = date("H:i");
            $remind = new PrayReminder();
            $out = $remind->get_forecast_data($cityweather);
            
            $res =  chr(10) . $pp->get_status_weather($out['cuaca'],$lang,$line);
           
            if ($time_now == $fajr) {
               
                    $final_res =  $pp->get_message_to_sent($lang,"1",$city) . chr(10) . chr(10) . $res;
                    $pp->sent_broadcast($line,$final_res);

            } elseif ($time_now == $dhuhr) {
                if ($jumat == true) {
                    $final_res =  $pp->get_message_to_sent($lang,"2a",$city) . chr(10) . chr(10) . $res;
                    $pp->sent_broadcast($line,$final_res);
    
                } else {
                    $final_res =  $pp->get_message_to_sent($lang,"2b",$city) . chr(10) . chr(10) . $res;
                    $pp->sent_broadcast($line,$final_res);
    
                }
            } elseif ($time_now == $asr) {
                $final_res =  $pp->get_message_to_sent($lang,"3",$city) . chr(10) . chr(10) . $res;
                $pp->sent_broadcast($line,$final_res);

            } elseif ($time_now == $maghrib) {
                $final_res =  $pp->get_message_to_sent($lang,"4",$city) . chr(10) . chr(10) . $res;
                $pp->sent_broadcast($line,$final_res);

            } elseif ($time_now == $isha) {
                $final_res =  $pp->get_message_to_sent($lang,"5",$city) . chr(10) . chr(10) . $res;
                $pp->sent_broadcast($line,$final_res);

            } elseif ($time_now == "09:15") {
                if ($dhuha == "1") {
                $final_res =  $pp->get_message_to_sent($lang,"6",$city) . chr(10) . chr(10) . $res;
                $pp->sent_broadcast($line,$final_res);
    
                }
            } elseif ($time_now == "02:00") {
                if ($tahaj == "1") {
                $final_res =  $pp->get_message_to_sent($lang,"7",$city) . chr(10) . chr(10) . $res;
                $pp->sent_broadcast($line,$final_res);
    
                }
            }
        }
        
        
    }

    public function get_message_to_sent ($langid,$status,$city_name) {
        $GLOBALS['status'] = $status;
        $GLOBALS['city_name'] = $city_name;
        switch ($langid) {
            case "0x0421":
            //indonesian
            
            require_once($_SERVER['DOCUMENT_ROOT'] ."/new/cron/cron_lang_id.php");
            return $pes;
            break;

            case "0x0C09":
            require_once($_SERVER['DOCUMENT_ROOT']  ."/new/cron/cron_lang_en.php");
            return $pes;
            //english
            break;

            default:
            return "error_unknown_lang_id";
        }
    }

    public function run_cron_update_athan () {
    //run on once a day
    require($_SERVER['DOCUMENT_ROOT'] . "/new/secret/database.php");
        
        $sql = "SELECT `line_id`,`p_city` FROM `tb_line`";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($line_id,$cityname1);
        while ($stmt->fetch()) {


            $aa2 = "http://api.aladhan.com/timingsByAddress?address=" . urlencode($cityname1) . "&method=5";

            $hasil2 = file_get_contents($aa2);
            

            $js_hasil2 = json_decode($hasil2,true);
            
            $px1 = mysqli_real_escape_string($conn,$js_hasil2['data']['timings']['Fajr']);
            $px2 = mysqli_real_escape_string($conn,$js_hasil2['data']['timings']['Dhuhr']);
            $px3 = mysqli_real_escape_string($conn,$js_hasil2['data']['timings']['Asr']);
            $px4 = mysqli_real_escape_string($conn,$js_hasil2['data']['timings']['Maghrib']);
            $px5 = mysqli_real_escape_string($conn,$js_hasil2['data']['timings']['Isha']);

            //updating city athan schedule...

            $sqld = "UPDATE `tb_line` SET `w_fajr` = '$px1', `w_fajr` = '$px1', `w_dhuhr` = '$px2', `w_asr` = '$px3', `w_maghrib` = '$px4', `w_isha` = '$px5' WHERE `tb_line`.`line_id` = '$line_id'";
            mysqli_query($conn,$sqld);

        }
    return true;
    }

    public function run_cron_update_weather () {
    //run on every 5 days
    require($_SERVER['DOCUMENT_ROOT'] . "/new/secret/database.php");
        
        $sql = "SELECT `p_city` FROM `tb_weather` ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($cityname);

        while ($stmt->fetch()) {
            //updating weather statistics

            $link = "http://api.openweathermap.org/data/2.5/forecast?q=" . $cityname."&units=metric&appid=" . op_id;
            date_default_timezone_set("Asia/Jakarta");
            $timestamp = time();
            $getdata = file_get_contents($link);
            $ext = json_decode($getdata,true);
            $cityname = $ext['city']['name'];

            $sqld = "UPDATE `tb_weather` SET `p_json` = '$getdata',`p_updated`='$timestamp' WHERE `tb_weather`.`p_city` = '$cityname';";
            mysqli_query($conn,$sqld);
        }

    return true;
    }

    public function get_status_weather($weathercode,$langid,$line_id)  {
        $pes = '';
        switch ($langid) {
            case "0x0421":
            //indonesian
            require_once($_SERVER['DOCUMENT_ROOT'] ."/new/cron/cron_weather_lang_id.php");
            return $pes;
            break;

            case "0x0C09":
            require_once($_SERVER['DOCUMENT_ROOT']  ."/new/cron/cron_weather_lang_en.php");
            return $pes;

            //english
            break;

            default:
            return "error_unknown_lang_id";
        }
    }

    
    
}

?>
