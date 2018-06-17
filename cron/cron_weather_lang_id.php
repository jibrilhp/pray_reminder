<?php
/* $weather_code
==
Group 2xx: Thunderstorm
Group 3xx: Drizzle
Group 5xx: Rain
Group 6xx: Snow
Group 7xx: Atmosphere
Group 800: Clear
Group 80x: Clouds

*/

$shortd = substr($weathercode,0,1);
$longd = $weathercode;

switch ($shortd) {
    case "2":
    $pes = "اللَّهُمّ حَوَالَيْنَا وَلَا عَلَيْنَا,اللَّهُمَّ عَلَى الْآكَامِ وَالْجِبَالِ وَالظِّرَابِ وَبُطُونِ الْأَوْدِيَةِ وَمَنَابِتِ الشَّجَرِ" . chr(10) . "Allahumma haawalaina wa laa ’alaina. Allahumma ’alal aakami wal jibaali, wazh zhiroobi, wa buthunil awdiyati, wa manaabitisy syajari [Ya Allah, turunkanlah hujan di sekitar kami, bukan untuk merusak kami. Ya Allah, turukanlah hujan ke dataran tinggi, gunung-gunung, bukit-bukit, perut lembah dan tempat tumbuhnya pepohonan].";
    break;

    case "3":
    $pes =  "اُطْلُبُوا اسْتِجَابَةَ الدُّعَاءِ عِنْدَ ثَلَاثٍ : عِنْدَ الْتِقَاءِ الْجُيُوشِ ، وَإِقَامَةِ الصَّلَاةِ ، وَنُزُولِ الْغَيْثِ" .chr(10) . "Carilah do’a yang mustajab pada tiga keadaan : [1] Bertemunya dua pasukan, [2] Menjelang shalat dilaksanakan, dan [3] Saat hujan turun.";
    break;

    case "5":
    $pes = "اللَّهُمَّ صَيِّباً ناَفِعاً" .chr(10) . "Allahumma shoyyiban naafi’aa [Ya Allah, turunkanlah pada kami hujan yang bermanfaat].";
    break;

    case "6":
    $pes = "Salju sedang turun, jaga kondisi tubuh anda agar tetap hangat ya :)";
    break;

    case "7":
    $pes = "Siapkan jaket, payung, atau penerangan yang baik dalam perjalanan untuk beribadahmu ya :)";
    break;

    case "8":
        if ($longd == 800 ){
            $pes =  "Cuaca cerah, tetap jaga kondisi fisikmu ya";
        } else {
            $pes = "Berawan cerah, semangat beribadah!";
        }
    break;

    default:
    
    $pes = "Kami tidak dapat memprediksi cuaca saat ini";

} //end switch

?>