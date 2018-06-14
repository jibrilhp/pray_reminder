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

    
    
}

?>