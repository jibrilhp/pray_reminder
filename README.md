# Pray Reminder v2
Root from Ibadah Reminder (https://github.com/jibrilhp/ibadah_reminder)

I hope this application will reminds many people to Ibadah :)

## 1. Create conncection to database and API
> I'm using key from LINE, Google API and OpenWeather and create database.php and flag.php
### flag.php
```
<?php
$channelAccessToken = '<from LINE API, insert here>';
$channelSecret = '<from LINE API, insert here>';

define("op_id","<open weather key>");
define('google_key','<google key >');
define("cron_key",'<custom key, you can make own key>'); 

?>
```
### database.php
```
<?php
$servername ="localhost";
$username ="root";
$password = "";
$dbname = "db_reminder";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    exit(' :( ');
}
?>
```

## 2. Deploy on your server, check the require_once location.
> In my localhost, i'm using the "new" folder to serve.

## 3. Create your cron
```
Create for everyminutes
curl "<your_server>/reminder_cron.php?key=<key>"

Create for everyday
curl "<your_server>/reminder_cron.php?key=0816851c86&run=athan"

Create for every 5-day
curl "<your_server>/reminder_cron.php?key=0816851c86&run=weather"
```


## 4. Wait for athan reminder
![Screenshot](https://vz8x3g.bl.files.1drv.com/y4mUz7nqrIXAPniGbmjGcBbnph2ks1VRL5dO1zJ3YvhKVkW8B5JA1tc6QwBCPJpet6qIfmqE6qgappkQCw__-pOxoNZKrT5PnkJOQbv011J6lxDYuz4OWMSxgb80YtqhONnwgDDzywIe2LdMs-hI0_GxNsdnyOHhSfG4tagczLXv3Dx_hu22Ha2R7QmDcQ5QL6Jik5WRgM_fwYc2VbLOxTeHw?width=1026&height=403&cropmode=none)
