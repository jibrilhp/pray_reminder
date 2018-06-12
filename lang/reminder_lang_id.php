<?php
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                case 'text':
                    $client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                'text' => $message['text']
                            )
                        )
                    ));
                    break;

                case 'location':
                    $client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                'text' => 'Lokasi anda telah diatur ke ' . chr(10) . 
                                'Latitude => '. $event['message']['latitude'] . chr(10).
                                'Longitude => '. $event['message']['longitude'] . chr(10) . chr(10) .
                                'Waktu shalat dalam zona Waktu  ' .   " : " .  chr(10) .
                                'Shubuh => ' . chr(10) .
                                'Dzuhur => ' . chr(10) . 
                                'Ashar  => ' . chr(10) .
                                'Maghrib =>' . chr(10) .
                                'Isya => ' . chr(10) .
                                '-----'
                                
                            )
                        )
                    ));
                break;
                default:
        
                    $client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                'text' => $message['latitude'] . " "  . $event['type']
                            )
                        )
                    ));
                    break;
            }
            break;
        default:    
            $client->replyMessage(array(
                'replyToken' => $event['replyToken'],
                'messages' => array(
                    array(
                        'type' => 'text',
                        'text' =>  $event['type']
                    )
                )
            ));
            break;
    }
}; //end foreach
?>