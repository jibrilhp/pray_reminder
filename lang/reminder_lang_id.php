<?php
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                case 'text':
                
                if ($remind->create_new_user($userID,$name) != "user_exist") {
                 /*
                    $client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                'text' => "Selamat datang di Pray Reminder! Silahkan kirim location anda untuk memulai "
                            )
                        )
                    ));
                    */
                } else {
                    if (empty($profile->displayName)) {
                        $name = "none";
                    } else {
                        $name = $profile->displayName;
                    }
                   
                    $remind->chat_history($userID,$name,$message['text'],$event['replyToken']);
                    //reply the args
                    switch (strtolower($message['text'])) {
                        case "jadwal":
                        if ($remind->get_athan_data($userID) != 'failed') {
                            $res = $remind->get_athan_data($userID);
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                    array(
                                        'type' => 'text',
                                        'text' => 'Lokasi anda di ' . $res['city'] . chr(10) . 
                                        'Jadwal hari ini  : ' .  chr(10) .
                                        'Shubuh => ' . $res['fajr'] . chr(10) .
                                        'Dzuhur => ' . $res['dhuhr'] . chr(10) . 
                                        'Ashar  => ' . $res['asr'] . chr(10) .
                                        'Maghrib =>' . $res['maghrib'] . chr(10) .
                                        'Isya => ' . $res['isha'] . chr(10) .
                                        '-----'
                                        
                                    )
                                )
                            ));
                        }
                        break;

                        case "kiblat":
                        define("lat2",21.422487);
                        define("lon2",39.826206);


                        $retur = $remind->get_user_location($userID);
                        
                        $lon1 = $retur['lon'];
                        $lat1 = $retur['lat'];
                        $bearing = (rad2deg(atan2(sin(deg2rad(lon2) - deg2rad($lon1)) * cos(deg2rad(lat2)), cos(deg2rad($lat1)) * sin(deg2rad(lat2)) - sin(deg2rad($lat1)) * cos(deg2rad(lat2)) * cos(deg2rad(lon2) - deg2rad($lon1)))) + 360) % 360;
                        //$y = sin($lon_delta) * cos(Latitude_Kaaba);
                        //$x = cos($retur['lat']) * sin(Latitude_Kaaba) - sin($retur['lat']) * cos(Latitude_Kaaba) * cos($lon_delta);
                        //$brg = atan2($y,$x);
                        /*
                        float lonDelta = (lon2 - lon1);
                        float y = Math.sin(lonDelta) * Math.cos(lat2);
                        float x = Math.cos(lat1) * Math.sin(lat2) - Math.sin(lat1) * Math.cos(lat2) * Math.cos(lonDelta);
                        float brng = Math.atan2(y, x).toDeg();
                        */
                        $compass = $remind->getCompassDirection($bearing);
                        $client->replyMessage(array(
                            'replyToken' => $event['replyToken'],
                            'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => 'Lokasi : ' . $retur['city']  . chr(10) . chr(10) . 
                                    'Anda harus menghadap ' . $bearing . ' derajat ('.$compass .')dari utara lokasi anda '
                                    
                                )
                            )
                        ));
                        break;

                        case "doa harian":
                        $getd = file_get_contents("https://air34.000webhostapp.com/line/quran.php");
                        $client->replyMessage(array(
                            'replyToken' => $event['replyToken'],
                            'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => '[Doa Harian] '. chr(10) . chr(10) . $getd
                                    
                                )
                            )
                        ));
                        break;

                        case "hadis harian":
                        $get_hadidh = "http://selfreminder.000webhostapp.com/get_hadis.php";
                        $out = file_get_contents($get_hadidh);
                        $client->replyMessage(array(
                            'replyToken' => $event['replyToken'],
                            'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => '[Hadis Harian]' .chr (10) . chr(10).
                                    $out
                                    
                                )
                            )
                        ));
                        break;

                        case "bahasa":
                        $client->replyMessage(array(
                            'replyToken' => $event['replyToken'],
                            'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => 'Bantu kami terjemahkan bahasa di github.com/jibrilhp/pray_reminder'
                                    
                                )
                            )
                        ));
                        break;

                        case "tentang":
                        $client->replyMessage(array(
                            'replyToken' => $event['replyToken'],
                            'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => 'Jibril Hartri Putra' . chr(10) .
                                    'Juwi Wongso Putro' . chr(10) .  chr(10) .
                                    '-----------------' . chr(10) .
                                    'Aladhan' . chr(10) .
                                    'OpenWeather' . chr (10) .
                                    'Google' . chr (10) . chr(10) .
                                    '----------------' . chr(10) .
                                    'Pray Reminder v2 (28 Ramadhan 1439 H)' . chr(10) .
                                    'https://github.com/jibrilhp/pray_reminder'


                                    
                                )
                            )
                        ));
                        break;
                    
                    } 
                }
                    break;

                case 'location':
                    $city = $remind->get_city_name($event['message']['latitude'],$event['message']['longitude']);
                    $res = $remind->set_user_athan($city,$userID);
                    $remind->set_user_location($event['message']['latitude'],$event['message']['longitude'],$userID);
                    if ($res['status'] == 'ok') {

                    
                    $client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                'text' => 'Lokasi anda telah diatur ke ' . $city . chr(10) . 
                                'Latitude => '. $event['message']['latitude'] . chr(10).
                                'Longitude => '. $event['message']['longitude'] . chr(10) . chr(10) .
                                'Anda dalam zona Waktu  ' . $res['zone'] . ". " .  chr(10) .
                                'Shubuh => ' . $res['fajr'] . chr(10) .
                                'Dzuhur => ' . $res['dhuhr'] . chr(10) . 
                                'Ashar  => ' . $res['asr'] . chr(10) .
                                'Maghrib =>' . $res['maghrib'] . chr(10) .
                                'Isya => ' . $res['isha'] . chr(10) .
                                '-----'
                                
                            )
                        )
                    ));
                } else {
                    $client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                'text' => 'Maaf layanan Pray Reminder sedang tidak tersedia'
                                
                            )
                        )
                    ));
                }

                break;
                default:
                    if ($remind->create_new_user($userID,"-") != "user_exist") {
                        /*
                        $client->replyMessage(array(
                            'replyToken' => $event['replyToken'],
                            'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => "Selamat datang di Pray Reminder! Silahkan kirim location anda untuk memulai "
                                )
                            )
                        ));
                        */
                    } else {
                        $name = $profile->displayName;
                        $remind->chat_history($userID,$name,$message['text'],$event['replyToken']);

                    }
                    
                    break;
            }
            break;
        default:    
            
            if ($remind->create_new_user($userID,$name) != "user_exist") {
                /*
                $client->replyMessage(array(
                    'replyToken' => $event['replyToken'],
                    'messages' => array(
                        array(
                            'type' => 'text',
                            'text' => "Selamat datang di Pray Reminder! Silahkan kirim location anda untuk memulai "
                        )
                    )
                ));
                */
            } else {
                $name = $profile->displayName;
                $remind->chat_history($userID,$name,$message['text'],$event['replyToken']);
            }
            break;
    }
}; //end foreach
?>