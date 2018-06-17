<?php
$status_salat =$GLOBALS['status'] ;
$city_name = $GLOBALS['city_name'];
switch ($status_salat) {
    case "1":
    $pes = "[Shubuh Reminder] -  ". $city_name . chr(10) . chr(10) 
    . "Barangsiapa yang shalat isya berjamaah maka seolah-olah dia telah shalat malam selama separuh malam. Dan barangsiapa yang shalat shubuh berjamaah maka seolah-olah dia telah shalat seluruh malamnya. (HR. Muslim no. 656)";
    break;

    case "2a":
    $pes = "[Dzuhur Reminder] - " . $city_name . chr(10) . chr(10) 
    . "Yuk, segera langkahkan ke masjid dan jangan lupa shalat rawatibnya ya!";
    break;

    case "2b":
    $pes = "[Jumat Reminder] - ". $city_name . chr(10) . chr(10) .
    "Hai orang-orang beriman, apabila diseru untuk menunaikan shalat Jumat, maka bersegeralah kamu kepada mengingat Allah dan tinggalkanlah jual beli. Yang demikian itu lebih baik bagimu jika kamu mengetahui (Al-Jumu'ah Ayat 9)";
    break;

    case "3":
    $pes = "[Ashar Reminder] - " . $city_name . chr(10) . chr(10) .
    "Peliharalah semua shalat(mu), dan (peliharalah) shalat wusthaa. Berdirilah untuk Allah (dalam shalatmu) dengan khusyu'. [QS. Al-Baqarah [2]: 238]";
    break;

    case "4":
    $pes = "[Maghrib Reminder] - " . $city_name . chr(10) . chr(10) .
    "Yuk, zikir petangnya dibaca :)";
    break;

    case "5":
    $pes = "[Isya Reminder] - ". $city_name . chr(10) . chr(10) ;
    break;

    case "6":
    $pes = "[Dhuha Reminder]";
    break;

    case "7":
    $pes = "[Tahajud Reminder]";
    break;

}
?>