<?php
/*
Author: Jibril Hartri Putra, 28 Ramadhan 1439 H

*/

class PrayReminder_Cron
{
    //run on cron only to reduce exceeded API requests

    public function sent_broadcast($line_id,$message) {
    require($_SERVER['DOCUMENT_ROOT'] . '/new/linebot_sdk/LINEBotTiny.php');
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
            
            if ($time_now == $fajr) {
                $remind = new PrayReminder();
                $out = $remind->get_forecast_data($cityweather);
                $res = $pp->get_status_weather($out['cuaca'],$lang,$line);

                $final_res =  get_message_to_sent($lang,"1",$city) . chr(10) . chr(10) . $res;
                $pp->sent_broadcast($line,$final_res);
            } elseif ($time_now == $dhuhr) {
                if ($jumat == true) {

                } else {

                }
            } elseif ($time_now == $asr) {
                # code...
            } elseif ($time_now == $maghrib) {
                # code...
            } elseif ($time_now == $isha) {
                
            } elseif ($time_now == "09:15") {
                if ($dhuha == "1") {

                }
            } elseif ($time_now == "02:00") {
                if ($tahaj == "1") {

                }
            }
        }
        
        
    }

    public function get_message_to_sent ($langid,$status_salat,$city_name) {
        $remind = new PrayReminder_Cron();

        switch ($langid) {
            case "0x0421":
            //indonesian
            require_once($_SERVER['DOCUMENT_ROOT'] ."/new/lang/cron_lang_id.php");
            break;

            case "0x0C09":
            require_once($_SERVER['DOCUMENT_ROOT']  ."/new/lang/cron_lang_en.php");
            //english
            break;

            default:
            return "error_unknown_lang_id";
        }
    }

    public function run_cron_update_athan () {
    //run on once a day
        return 0;
    }

    public function run_cron_update_weather () {
    //run on every 5 days
    }

    public function get_status_weather($weather_code,$langid,$line_id)  {
        switch ($langid) {
            case "0x0421":
            //indonesian
            require_once($_SERVER['DOCUMENT_ROOT'] ."/new/lang/cron_weather_lang_id.php");
            break;

            case "0x0C09":
            require_once($_SERVER['DOCUMENT_ROOT']  ."/new/lang/cron_weather_lang_en.php");
            //english
            break;

            default:
            return "error_unknown_lang_id";
        }
    }

    
    
}

?>
