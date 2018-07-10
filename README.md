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
![alt text](https://bl3301files.storage.live.com/y4pgysAx9d0VFtwXRK6hlVyo6xaU_lJH_F3zEorwvwFNGQ26ztzB-6tB0osL8JwyB0NyT8qOosp_eypAFK023hda2KIvqL2AeIOYdQAYwt0bpFZLoGbr9RBqfra6pRL1h0S5p7HBcBSG_emmZ_UzjOSoQa2o55ASAjIPwD5hTNNmXXESHxoefdECL97AH3aQj7E/thumbnail_91F8468412D44390959FD99CEFA13720.png?psid=1&width=1026&height=403)
