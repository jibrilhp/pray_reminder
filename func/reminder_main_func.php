<?php
/*
Author: Jibril Hartri Putra, 28 Ramadhan 1439 H

*/

class PrayReminder
{
    public function __construct()
    {
        require_once($_SERVER['DOCUMENT_ROOT'] . "/new/secret/flag.php");
    }

    public function get_city_name($lat,$long) {
    //from googleapis    
    //example link : http://maps.googleapis.com/maps/api/geocode/json?latlng=-6.284604,106.804262&sensor=true
    //fetch contents with json encode
    $lat = urlencode($lat);
    $long = urlencode($long);
    $link = "https://maps.googleapis.com/maps/api/geocode/json?latlng=". $lat.",".$long."&sensor=true&key=" . google_key;
            
    $data_googleapis = json_decode(file_get_contents($link),true);
    
    if ($data_googleapis['status'] == 'OK') {
        $ret = $data_googleapis['results'][1]['address_components'][0]['long_name'];
        return $ret;
    } else {
        return "Failed to fetch data! please try again later";
    } //endif
        
    }

    public function get_athan_data ($line_id) {
    //get data from database
    require($_SERVER['DOCUMENT_ROOT'] . "/new/secret/database.php");

    $sql = "SELECT w_fajr,w_dhuhr,w_asr,w_maghrib,w_isha,p_zone,p_city FROM tb_line WHERE line_id =  '$line_id'";
    $stmt = $conn->prepare($sql);
 
    if ($stmt->execute()) {
        $stmt->bind_result($fajr,$dhuhr,$asr,$maghrib,$isha,$zone,$city);
        while ($stmt->fetch()) {
        $ret = array("fajr"=>$fajr,"dhuhr"=>$dhuhr,"asr"=>$asr,"maghrib"=>$maghrib,"isha"=>$isha,"zone"=>$zone,"city"=>$city);
        }
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

        require($_SERVER['DOCUMENT_ROOT'] . "/new/secret/database.php");
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
                if (date("Y-m-d H:i:s") < new DateTime($dlocal)) {
                    $ar = array("id_code"=>$dt_weather,"cuaca"=>$datares['weather'][0]['id']);
                    return $ar;
                    break;
                    }

                }

            } else {
                return "city_not_found";
            }
            
                
            
        }
            
        
    }

    public function get_timezone($cityname) {
        require($_SERVER['DOCUMENT_ROOT'] . "/new/secret/database.php");

        
            $sql = "SELECT p_zone FROM tb_line WHERE p_city_weather = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s",$cityname);
            if ($stmt->execute()) {
                $stmt->bind_result($arg1);
            while ($stmt->fetch()) {
                $tzone = $arg1;
            }
            
            return $tzone;
        
        } else {
            return "error get time zone";
        }
    }

    public function get_user_settings($userid) {
    require($_SERVER['DOCUMENT_ROOT'] . "/new/secret/database.php");
    $userid = mysqli_real_escape_string($conn,$userid);

    $sqld = "SELECT * FROM `tb_line` WHERE line_id = '$userid'";
    
    //check if user exist?
    
    if ($result=mysqli_query($conn,$sqld)) {
        $rowcount = mysqli_num_rows($result);
        if ($rowcount > 0) {
            return "user_exist";
        } else {
            return "user_not_found";
        }
    } 
    
    
        
    }

    public function get_user_language($userid) {
    //check user language..
    require($_SERVER['DOCUMENT_ROOT'] . "/new/secret/database.php");
    $userid = mysqli_real_escape_string($conn,$userid);
    $sqld = "SELECT p_lang FROM `tb_line` WHERE line_id = '$userid'";
    
    //check if user exist?
    
    if ($result=mysqli_query($conn,$sqld)) {
        $row=mysqli_fetch_array($result,MYSQLI_ASSOC);

        $rowcount = mysqli_num_rows($result);
        if ($rowcount > 0) {
            //0x0421 -> Indonesian
            return $row['p_lang'];
        } else {
            return "0x0421";
        }
    } 
    


    }

    public function create_new_user($userid,$line_name) {
    $pp = new PrayReminder();
    
        if ($pp->get_user_settings($userid) == "user_not_found") {

        
            
            require($_SERVER['DOCUMENT_ROOT'] . "/new/secret/database.php");
            //create a new user, athan will remind if the user sent a location 
            
            date_default_timezone_set("Asia/Jakarta");
            
            if (!strlen($line_name) > 0 )  {
                $line_name = "-";
            }

            $timestamp = time();
            $sql = "INSERT INTO `tb_line`( `line_id`, `line_name`, `p_lang`,`x_first_seen`) VALUES (?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $p_lang = "0x0421";
            $stmt->bind_param("ssss",$userid,$line_name,$p_lang,$timestamp);
            if ($stmt->execute()) {
                return "success";
            } else {
                return  "failed";
            }

        } else {
            return "user_exist";
        }
    }

    public function set_user_location($lat,$long,$line_id) {
        require($_SERVER['DOCUMENT_ROOT'] . "/new/secret/database.php");

        $sql = "UPDATE `tb_line` SET `p_latitude` = ?, `p_longitude` = ?    WHERE `tb_line`.`line_id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss",$lat,$long,$line_id);
        if($stmt->execute()) {
            return "success";
        } else {
            return "failed";
        }
    }

    public function get_user_location($line_id) {
        require($_SERVER['DOCUMENT_ROOT'] . "/new/secret/database.php");

        $sql = "SELECT p_city,p_latitude,p_longitude FROM `tb_line` WHERE `line_id` = '$line_id'";
        $result=mysqli_query($conn,$sql);
        $row=mysqli_fetch_array($result,MYSQLI_ASSOC);

        $ar = array("city"=>$row['p_city'],"lat"=>$row['p_latitude'],"lon"=>$row['p_longitude']);
        return $ar;

    }

    public function getCompassDirection($bearing) {
        //thanks to https://www.dougv.com/2009/07/calculating-the-bearing-and-compass-rose-direction-between-two-latitude-longitude-coordinates-in-php/
        $tmp = round($bearing / 22.5);
        switch($tmp) {
           case 1:
              $direction = "NNE";
              break;
           case 2:
              $direction = "NE";
              break;
           case 3:
              $direction = "ENE";
              break;
           case 4:
              $direction = "E";
              break;
           case 5:
              $direction = "ESE";
              break;
           case 6:
              $direction = "SE";
              break;
           case 7:
              $direction = "SSE";
              break;
           case 8:
              $direction = "S";
              break;
           case 9:
              $direction = "SSW";
              break;
           case 10:
              $direction = "SW";
              break;
           case 11:
              $direction = "WSW";
              break;
           case 12:
              $direction = "W";
              break;
           case 13:
              $direction = "WNW";
              break;
           case 14:
              $direction = "NW";
              break;
           case 15:
              $direction = "NNW";
              break;
           default:
              $direction = "N";
        }
        return $direction;
     }

    public function set_user_weather($lat,$lon,$userid) {
        //$cityname1  = urlencode(html_entity_decode($cityname));
        require($_SERVER['DOCUMENT_ROOT'] . "/new/secret/database.php");
        $userid = mysqli_real_escape_string($conn,$userid);
        date_default_timezone_set("Asia/Jakarta"); // can adjusted later..

        $link = "http://api.openweathermap.org/data/2.5/forecast?lat=".$lat."&lon=".$lon."&units=metric&appid=" . op_id;
        $timestamp = time();
        $getdata = file_get_contents($link);
        $ext = json_decode($getdata,true);
        $cityname = $ext['city']['name'];
        
        $sql = "INSERT INTO `tb_weather` (`p_city`, `p_json`, `p_updated`) VALUES ( ?, ?, ?);";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss",$cityname,$getdata,$timestamp);
        if ($stmt->execute()) {
            $sqd = "UPDATE `tb_line` SET `p_city_weather` = '$cityname' WHERE `tb_line`.`line_id` = '$userid'";
            mysqli_query($conn,$sqd);
            return "success";
        } else {
            return "failed";
        }
        

    }

    public function chat_history($userid,$linename,$message,$replytoken)  {
        require($_SERVER['DOCUMENT_ROOT'] . "/new/secret/database.php");

        date_default_timezone_set("Asia/Jakarta");

        $timestamp = time();
        $sql = "INSERT INTO `tb_chat`( `id_line`, `line_name`, `reply_token`, `message`, `timestamp`) VALUES (?,?,?,?,?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss",$userid,$linename,$replytoken,$message,$timestamp);
        $stmt->execute();

    }

    public function set_user_athan($cityname,$userid) {
        $cityname1  = urlencode(html_entity_decode($cityname));
        
        require($_SERVER['DOCUMENT_ROOT'] . "/new/secret/database.php");

        $aa1 = "http://api.aladhan.com/addressInfo?address=" . $cityname1;
        $aa2 = "http://api.aladhan.com/timingsByAddress?address=" . $cityname1. "&method=5";
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

        $sql = "UPDATE `tb_line` SET `w_fajr` = ? , w_dhuhr = ? , w_asr = ? , w_maghrib = ?, w_isha = ?, p_zone = ?, p_city = ? WHERE `tb_line`.`line_id` = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss",$px1,$px2,$px3,$px4,$px5,$tzone,$cityname,$userid);
        if ($stmt->execute()) {
            //Check if city is available or not.
                $sqld = "SELECT * FROM `tb_weather` WHERE p_city = '$cityname'";
                if ($result=mysqli_query($conn,$sqld)) {
                    $rowcount = mysqli_num_rows($result);
                    if ($rowcount > 0) {
                        $std = "UPDATE `tb_line` SET `p_city_weather` = '$cityname' WHERE `tb_line`.`line_id` = '$userid'";
                        mysqli_query($conn,$std);
                        $ar = array("status"=>"ok","fajr"=>$px1,"dhuhr"=>$px2,"asr"=>$px3,"maghrib"=>$px4,"isha"=>$px5,"zone"=>$tzone);

                        return $ar;
                    } else {
                        $pp = new PrayReminder();
                        $ext = $pp->get_user_location($userid);
                        $pp->set_user_weather($ext['lat'],$ext['lon'],$userid);
                        $ar = array("status"=>"ok","fajr"=>$px1,"dhuhr"=>$px2,"asr"=>$px3,"maghrib"=>$px4,"isha"=>$px5,"zone"=>$tzone);
                        return $ar;
                    }
                }    
                    
        } else {
            return "failed";
        }

        


    }


    
    
}


?>