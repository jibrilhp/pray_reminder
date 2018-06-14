<?php
/*
Author: Jibril Hartri Putra, 28 Ramadhan 1439 H

*/

class PrayReminder
{
    public function __construct()
    {
        require_once("../secret/flag.php");
    }

    public function get_city_name($lat,$long) {
    //from googleapis    
    //example link : http://maps.googleapis.com/maps/api/geocode/json?latlng=-6.284604,106.804262&sensor=true
    //fetch contents with json encode
    $lat = urlencode($lat);
    $long = urlencode($long);
    $link = "http://maps.googleapis.com/maps/api/geocode/json?latlng=". $lat.",".$long."&sensor=true";
            
    $data_googleapis = json_decode(file_get_contents($link),true);
    
    if ($data_googleapis['status'] == 'OK') {
        $ret = $data_googleapis['results'][0]['address_components'][2]['short_name'];
        return $ret;
    } else {
        return "Failed to fetch data! please try again later";
    } //endif
        
    }

    public function get_athan_data ($cityname) {
    //get data from database
    require("../secret/database.php");

    $sql = "SELECT w_fajr,w_dhuhr,w_asr,w_maghrib,w_isya,w_dhuha FROM tb_line WHERE p_city REGEXP ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s",$cityname);
    if ($stmt->execute()) {
        $stmt->bind_result($fajr,$dhuhr,$asr,$maghrib,$isha);
        $ret = array("fajr"=>$fajr,"dhuhr"=>$dhuhr,"asr"=>$asr,"maghrib"=>$maghrib,"isya"=>$isha);
        return $ret;
    } else {
        return "Failed !";
    }

        
    }

    public function get_forecast_data($cityname) {
    //get data from database and get time zone
        $xpp =0;
        $pp = new PrayReminder();
        $tzone = $pp->get_timezone($cityname,NULL);
        date_default_timezone_set($tzone); //set the time zone, maybe not same with server time zone

        //OpenWeather is UTC time, convert to local time zone.

        require("../secret/database.php");
        $sql = "SELECT p_json FROM `tb_weather` WHERE p_city REGEXP ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$cityname);
        if ($stmt->execute()) {
            //search with time()
            $stmt->bind_result($arg1);
            while ($stmt->fetch()) {
                $xpp++;
                $result_json = $arg1;
            }

            if ($xpp > 0) {
            
            $res = json_decode($result_json,true);
            //dt = date/time approximation to start rain (?)
            
            foreach($res['list'] as $key => $datares) {
                $dt_weather = $datares['weather'][0]['id'];
                $dt_UTC =  $datares['dt_txt'] . " +00";
                $dt_local = new DateTime($dt_UTC);
                $dt_local->setTimezone(new DateTimeZone($tzone));
                $dlocal = $dt_local->format("Y-m-d H:i:s");
                if (new DateTime() > new DateTime($dlocal)) {
                    $ar = array("id_code"=>$dt_weather,"cuaca"=>$datares['weather'][0]['main']);
                    return $ar;
                    break;
                    }

                }

            } else {
                return "city_not_found";
            }
            
                
            
        }
            
        
    }

    public function get_timezone($cityname,$userid) {
        require("../secret/database.php");

        if (isset($cityname)) {
            $sql = "SELECT p_zone FROM tb_line WHERE p_city REGEXP ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s",$cityname);
            if ($stmt->execute()) {
                $stmt->bind_result($arg1);
            while ($stmt->fetch()) {
                $tzone = $arg1;
            }
            return $tzone;
            }
                    
        } else if (isset($userid)) {
            $sql = "SELECT p_zone FROM tb_line WHERE line_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s",$userid);
            if ($stmt->execute()) {
                $stmt->bind_result($arg1);
            while ($stmt->fetch()) {
                $tzone = $arg1;
            }
            return $tzone;
            }

        } else {
            return "error get time zone";
        }
    }

    public function get_user_settings($userid) {
    //check if user exist?
        return 0;
    }

    public function get_user_language($userid) {
    //check user language..
        return 0;
    }

    public function set_user_weather($cityname) {
        require("../secret/database.php");

        date_default_timezone_set("Asia/Jakarta"); // can adjusted later..

        $link = "http://api.openweathermap.org/data/2.5/forecast?q=".$cityname."&units=metric&appid=" . op_id;
        $timestamp = time();
        $getdata = file_get_contents($link);
        var_dump($getdata);
        $sql = "INSERT INTO `tb_weather` (`p_city`, `p_json`, `p_updated`) VALUES ( ?, ?, ?);";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss",$cityname,$getdata,$timestamp);
        if ($stmt->execute()) {
            return "success";
        } else {
            return "failed";
        }
        

    }

    public function set_user_athan($cityname,$userid) {
        require("../secret/database.php");

        $aa1 = "http://api.aladhan.com/addressInfo?address=" . $cityname;
        $aa2 = "http://api.aladhan.com/timingsByAddress?address=" . $cityname. "&method=5";
        $hasil1 = file_get_contents($aa1);
        $hasil2 = file_get_contents($aa2);
        
        $js_hasil1 = json_decode($hasil1,true);
        $js_hasil2 = json_decode($hasil2,true);
        
        $px1 = $js_hasil2['data']['timings']['Fajr'];
        $px2 = $js_hasil2['data']['timings']['Dhuhr'];
        $px3 = $js_hasil2['data']['timings']['Asr'];
        $px4 = $js_hasil2['data']['timings']['Maghrib'];
        $px5 = $js_hasil2['data']['timings']['Isha'];
        $terbit = $js_hasil2['data']['timings']['Sunrise'];
        
        
        $tzone = $js_hasil1['data']['timezone'];

        $sql = "UPDATE `tb_line` SET `w_fajr` = ? , w_dhuhr = ? , w_asr = ? , w_maghrib = ?, w_isha = ?, p_zone = ? WHERE `tb_line`.`line_id` = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss",$px1,$px2,$px3,$px4,$px5,$tzone,$userid);
        if ($stmt->execute()) {
            //Check if city is available or not.
                $sqld = "SELECT * FROM `tb_weather` WHERE p_city = '$cityname'";
                if ($result=mysqli_query($conn,$sqld)) {
                    $rowcount = mysqli_num_rows($result);
                    if ($rowcount > 0) {
                        $ar = array("status"=>"ok","fajr"=>$px1,"dhuhr"=>$px2,"asr"=>$px3,"maghrib"=>$px4,"isha"=>$px5);
                        return $ar;
                    } else {
                        $pp = new PrayReminder();
                        $pp->set_user_weather($cityname);
                        $ar = array("status"=>"ok","fajr"=>$px1,"dhuhr"=>$px2,"asr"=>$px3,"maghrib"=>$px4,"isha"=>$px5);
                        return $ar;
                    }
                }    
                    
        } else {
            return "failed";
        }

        


    }

    
}
$pp = new PrayReminder();
var_dump ($pp->set_user_athan("cilandak","q"));

?>