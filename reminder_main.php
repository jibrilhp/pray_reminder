<?php

/**
 * Copyright 2016 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

//Init LinebotSDK and get secret key
require_once('./linebot_sdk/LINEBotTiny.php');
require_once('./secret/flag.php');

$client = new LINEBotTiny($channelAccessToken, $channelSecret);


$userID 	= $client->parseEvents()[0]['source']['userId'];

$replyToken = $client->parseEvents()[0]['replyToken'];
$timestamp	= $client->parseEvents()[0]['timestamp'];


$message 	= $client->parseEvents()[0]['message'];
$messageid 	= $client->parseEvents()[0]['message']['id'];

$profile = $client->profile($userID);
$name = $profile->displayName;


require_once('./func/reminder_main_func.php');

$remind = new PrayReminder();
//get the language from userid and set the language
if ($remind->get_user_settings($userID) == "user_exist") {
$lang = $remind->get_user_language($userID);
    switch($lang) {
        case "0x0421":
            include('./lang/reminder_lang_id.php');
        break;

        case "0x0C09":
            include('./lang/reminder_lang_en.php');
        break;

        default:
            include('./lang/reminder_lang_id.php');

    }
  
} else {
    include('./lang/reminder_lang_id.php');
}

?>