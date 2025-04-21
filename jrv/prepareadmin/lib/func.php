<?php

function x_na_n($x, $i) {
  $ret = $x;
  for($ii = 1; $ii < $i; $ii++) {
    $ret = $ret * $x;
  }
  return $ret;
}

function conv_num_byte($num, $pbyte) {
  $ret = '';
  $num1 = $num;
  for ($i = $pbyte - 1; $i > 0; $i--) {
    $ret .= chr(intval($num1 / (x_na_n(256, $i))));
    $num1 = ($num1 % x_na_n(256, $i));
//    echo $ret;
  }
  $ret .= chr(intval($num1));
  return $ret;
}

?>
