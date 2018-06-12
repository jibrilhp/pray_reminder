<?php
/*
Author: Jibril Hartri Putra, 28 Ramadhan 1439 H

*/

class PrayReminder
{
    //run on cron only to reduce exceeded API requests

    public function get_city_name($lat,$long) {
        //run on once a week

        return 0;
    }

    public function get_athan_data ($cityname) {
    //run on everyday
        return 0;
    }

    public function get_forecast_data($cityname) {
    //run on once a week
        return 0;
    }

    //run on user get data from database

    public function get_tmp_athan($userid) {
        return 0;
    }

    public function get_tmp_forecast($day,$userid) {
        return 0;
    }

    public function get_user_settings($userid) {
        return 0;
    }
    
}

?>