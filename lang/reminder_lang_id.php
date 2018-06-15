<?php
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                case 'text':
                
                if ($remind->create_new_user($userID,$name) != "user_exist") {
                    $client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                'text' => "Selamat datang di Pray Reminder! Silahkan kirim location anda untuk memulai "
                            )
                        )
                    ));
                } else {
                    if (empty($profile->displayName)) {
                        $name = "none";
                    } else {
                        $name = $profile->displayName;
                    }
                   
                    $remind->chat_history($userID,$name,$message['text'],$event['replyToken']);
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
                        $client->replyMessage(array(
                            'replyToken' => $event['replyToken'],
                            'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => "Selamat datang di Pray Reminder! Silahkan kirim location anda untuk memulai "
                                )
                            )
                        ));
                    } else {
                        $name = $profile->displayName;
                        $remind->chat_history($userID,$name,$message['text'],$event['replyToken']);

                    }
                    
                    break;
            }
            break;
        default:    
            
            if ($remind->create_new_user($userID,$name) != "user_exist") {
                $client->replyMessage(array(
                    'replyToken' => $event['replyToken'],
                    'messages' => array(
                        array(
                            'type' => 'text',
                            'text' => "Selamat datang di Pray Reminder! Silahkan kirim location anda untuk memulai "
                        )
                    )
                ));
            } else {
                $name = $profile->displayName;
                $remind->chat_history($userID,$name,$message['text'],$event['replyToken']);
            }
            break;
    }
}; //end foreach
?>