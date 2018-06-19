<?php
$status_salat =$GLOBALS['status'] ;
$city_name = $GLOBALS['city_name'];
switch ($status_salat) {
    case "1":
    $pes = "[Shubuh Reminder] -  Shubuh telah tiba! untuk wilayah ". $city_name . chr(10) . chr(10) 
    . "Barangsiapa yang shalat isya berjamaah maka seolah-olah dia telah shalat malam selama separuh malam. Dan barangsiapa yang shalat shubuh berjamaah maka seolah-olah dia telah shalat seluruh malamnya. (HR. Muslim no. 656)";
    break;

    case "2a":
    $pes = "[Jumat Reminder] - Waktu Dzuhur telah tiba! untuk wilayah ". $city_name . chr(10) . chr(10) .
    "Hai orang-orang beriman, apabila diseru untuk menunaikan shalat Jumat, maka bersegeralah kamu kepada mengingat Allah dan tinggalkanlah jual beli. Yang demikian itu lebih baik bagimu jika kamu mengetahui (Al-Jumu'ah Ayat 9)";
    
    break;

    case "2b":
    $pes = "[Dzuhur Reminder] - Waktu Dzuhur telah tiba! untuk wilayah " . $city_name . chr(10) . chr(10) 
    . "Dzuhur telah tiba! untuk zona waktu ". $tzone . "
    Shalat rawâtib Zhuhur termasuk yang tidak pernah ditinggalkan Nabi Shallallahu ‘alaihi wa sallam , kecuali ketika dalam keadaan bersafar. Shalat ini memiliki keutamaan seperti keumuman shalat rawâtib lainnya. Namun ada beberapa hadits yang menjelaskan keutamaannya, khususnya seperti hadits Ummu Habîbah yang berbunyi: Barang siapa yang menjaga empat rakaat sebelum Zhuhur dan empat rakaat setelahnya, maka Allah mengharamkannya dari neraka";
    break;

    case "3":
    $pes = "[Ashar Reminder] - Waktu Ashar telah tiba! untuk wilayah " . $city_name . chr(10) . chr(10) .
    "Peliharalah semua shalat(mu), dan (peliharalah) shalat wusthaa. Berdirilah untuk Allah (dalam shalatmu) dengan khusyu'. [QS. Al-Baqarah [2]: 238]";
    break;

    case "4":
    $pes = "[Maghrib Reminder] -  Waktu Maghrib telah tiba! untuk wilayah " . $city_name . chr(10) . chr(10)
    . "لَا إِلَهَ إِلَّا اللَّهُ وَحْدَهُ لَا شَرِيكَ لَهُ لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ بِيَدِهِ الْخَيْرُ يُحْيِي وَيُمِيتُ وَهُوَ عَلَى كُلِّ شَيْءٍ قَدِيرٍ" . chr(10) 
    ."Tidak ada Tuhan selain Allah Dzat yang Maha Esa yang tidak ada sekutu bagi-Nya, Dzat yang mematikan dan menghidupkan, bagi-Nya segala kerajaan dan pujian, Dia Maha Kuasa atas segala sesuatu";
    break;

    case "5":
    $pes = "[Isya Reminder] - Waktu Isya telah tiba! untuk wilayah ". $city_name . chr(10) . chr(10) . "Seandainya mereka mengetahui keutamaan yang ada pada shala Isya’ dan shalat Shubuh, tentu mereka akan mendatanginya sambil merangkak.” (HR. Bukhari no. 615 dan Muslim no. 437)" ;
    break;

    case "6":
    $pes = "[Dhuha Reminder] " . chr(10) . chr(10) .
    "قَالَ اللَّهُ عَزَّ وَجَلَّ يَا ابْنَ آدَمَ لاَ تَعْجِزْ عَنْ أَرْبَعِ رَكَعَاتٍ مِنْ أَوَّلِ النَّهَارِ أَكْفِكَ آخِرَهُ" .chr(10) .
    "Allah Ta’ala berfirman: Wahai anak Adam, janganlah engkau tinggalkan empat raka’at shalat di awal siang (di waktu Dhuha). Maka itu akan mencukupimu di akhir siang";
    break;

    case "7":
    $pes = "[Tahajud Reminder] " .  chr(10) . chr(10) .
    "وَاعْلَمْ أَنَّ شَرَفَ الْـمُؤْمِنِ قِيَامُهُ بِاللَّيْلِ" .chr(10) . 
    "Dan ketahuilah, bahwa kemuliaan dan kewibawaan seorang mukmin itu ada pada shalat malamnya";
    break;

}
?>