<?php
/*$data['525117'][30] = array('30a', 302, 303);
$data['525117'][31] = array('31b', 312, 313);
$data['525118'][50] = array('50a', 502, 503);
$data['525118'][51] = array('51b', 512, 503);
$data['525118'][52] = array('52c', 522, 503);

print_r($data);

$target = "data/99/decode/test.txt";
$handle = @fopen($target, "w");
fwrite($handle, serialize($data));
fclose($handle);

$target = "data/99/decode/test.txt";
$handle = @fopen($target, "r");
$newdata = unserialize(fread($handle, filesize($target)));
fclose($handle);

echo "<br/>-------------------<br/>";

print_r($newdata);

echo "<br/>-------------------<br/>";

echo $newdata[525117][30][0];*/

echo exec("info.php"); 

?>
