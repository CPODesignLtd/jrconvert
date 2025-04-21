<?php

require_once 'lib/param.php';
require_once 'lib/functions.php';

$location = 6;
$packet = 188;
$datumJR = "28_2_2017";

$path = "../jrstructure/" . $location . "/" . $packet . '/';

$sMil = round(microtime(true) * 1000);
createStructure($location, $packet);

if (connect_DB($mysqli)) {

    $varGRF = getVARGRF($datumJR, $location, $packet, $mysqli);

    echo round(microtime(true) * 1000) - $sMil . "<br><br>";

// ------ READ ------
    $sMil = round(microtime(true) * 1000);

    $SPOJE = loadStructure($path, "spoje.dat");
    $CHRONO = loadStructure($path, "chrono.dat");
    $ZASTAVKY_LINKY = loadStructure($path, "zastavky_linky.dat");
    $LINKY_ZASTAVKY = loadStructure($path, "linky_zastavky.dat");
//  $PRESTUPY = loadStructure($path, "prestupy.dat");
    $ZASTAVKY = loadStructure($path, "zastavky.dat");
    $PESOBUS = loadStructure($path, "pesobus.dat");

    echo round(microtime(true) * 1000) - $sMil . "<br><br>";

    echo count($SPOJE) . "<br>";
    echo count($CHRONO) . "<br>";
    echo count($ZASTAVKY_LINKY) . "<br>";
    echo count($LINKY_ZASTAVKY) . "<br>";
    echo count($PRESTUPY) . "<br>";
    echo count($ZASTAVKY) . "<br>";
    echo "peso " . count($PESOBUS) . "<br>";

    close_DB($mysqli);
} else {
    echo "Not Connected DB<br>";
}
?>
