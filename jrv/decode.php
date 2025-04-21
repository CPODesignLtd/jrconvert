<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html  xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1250"/>
  </head>
<?php

class Base64 {

    /**
      * I have changed letter placement (P <=> x, S <=> 9) and the cases
      * You can completely redo the mapping table
      */

    private static $BinaryMap = array(
         '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
         'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
         'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
         'U', 'V', 'W', 'X', 'Y', 'Z',
         'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
         'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
         'u', 'v', 'w', 'x', 'y', 'z', '+', '/'
     );

    public function __construct() {}

    public function base64_encode($input) {

        $output = "";
         $chr1 = $chr2 = $chr3 = $enc1 = $enc2 = $enc3 = $enc4 = null;
         $i = 0;

//        $input = self::utf8_encode($input);

        while($i < strlen($input)) {
             $chr1 = ord($input[$i++]);
             $chr2 = ord($input[$i++]);
             $chr3 = ord($input[$i++]);

            $enc1 = $chr1 >> 2;
             $enc2 = (($chr1 & 3) << 4) | ($chr2 >> 4);
             $enc3 = (($chr2 & 15) << 2) | ($chr3 >> 6);
             $enc4 = $chr3 & 63;

            if (is_nan($chr2)) {
                 $enc3 = $enc4 = 64;
             } else if (is_nan($chr3)) {
                 $enc4 = 64;
             }

            $output .=  self::$BinaryMap[$enc1]
                       . self::$BinaryMap[$enc2]
                       . self::$BinaryMap[$enc3]
                       . self::$BinaryMap[$enc4];
        }

        return $output;
     }

    public function utf8_encode($input) {
         $utftext = null;

        for ($n = 0; $n < strlen($input); $n++) {

            $c = ord($input[$n]);

            if ($c < 128) {
                 $utftext .= chr($c);
             } else if (($c > 128) && ($c < 2048)) {
                 $utftext .= chr(($c >> 6) | 192);
                 $utftext .= chr(($c & 63) | 128);
             } else {
                 $utftext .= chr(($c >> 12) | 224);
                 $utftext .= chr((($c & 6) & 63) | 128);
                 $utftext .= chr(($c & 63) | 128);
             }
         }

        return $utftext;
     }
 }

/* Data */
    $key = 'fssoftware000000';
$password = $key;
// 32 byte binary blob
//$aes256Key = hash("SHA256", $password, true);
// generate random iv
//$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);

$filename = "prepareadmin/data/99/chronometr.aes";
$handle = fopen($filename, "r");
$contents = /*base64_decode*/((fread($handle, filesize($filename))));
fclose($handle);


$stuff=$contents;
 $key='#jrw_fssoftware_581003##';

function nl() {
     echo "<br/> \n";
 }
 $iv = mcrypt_create_iv (mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB)/*mcrypt_get_block_size (MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_DEV_RANDOM*/);
// Encrypting
 function encrypt($string, $key) {
     $enc = "";
     global $iv;
//     $enc=mcrypt_cbc (MCRYPT_RIJNDAEL_128, $key, $string, MCRYPT_ENCRYPT, $iv);
     $enc=trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $string, MCRYPT_MODE_ECB, $iv));

  return base64_encode($enc);
 }

// Decrypting
function decrypt($string, $key) {
     $dec = "";
     $string = trim(base64_decode($string));
     global $iv;
//     $dec = mcrypt_cbc (MCRYPT_RIJNDAEL_128, $key, $string, MCRYPT_DECRYPT, $iv);
     $dec = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $string, MCRYPT_MODE_ECB, $iv));
/*     $dec = trim(str_replace("\xBA", '', $dec));
     $dec = trim(str_replace("\xAB", '', $dec));
     $dec = trim(str_replace("\xF0", '', $dec));*/
   return $dec;
 }

//$encrypted = encrypt(("delete from linky where idlocation = 99 and packet = 0;INSERT INTO linky (C_LINKY, NAZEV_LINKY, C_LINKYSORT, DOPRAVA, JR_OD, JR_DO, IDLOCATION, PACKET) VALUES ('728', '     7', 1, 'T', '2012-9-1', '2012-12-8', 99, 0);COMMIT;"), $key);

$decrypted = decrypt($contents, $key);

//echo "iv is</br> ".$iv . nl();
//echo "Encrypted is</br> ".$encrypted. nl();
echo "Decrypted is</br> ".$decrypted . nl();
//echo "Decrypted is</br> ".decrypt($contents/*$encrypted*/, $key) . nl();
//$key = substr(sha1($key), 0, 24);
//echo "</br>dd is</br>".trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($contents), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND)));

$filename = "prepareadmin/data/99/chronometr.rij";
$handle = fopen($filename, "w");
fwrite($handle, ($decrypted));
fclose($handle);




//$crypted = fnEncrypt($contents, $password);


/*$filename = "data/linky.rij";
$handle = fopen($filename, "w");
fwrite($handle, $ReAry[1]);
fclose($handle);*/

//$filename = "data/linky.rij";
//$handle = fopen($filename, "r");
//$contents = (fread($handle, filesize($filename)));
//fclose($handle);
//$newClear = fnDecrypt(($contents), $password);
//echo
//"IV:        <code>".$iv."</code><br/>".
//"Encrypred: <code>".$crypted."</code><br/>".
//"<br/>Decrypred: <code>".($newClear)."</code><br/>";

 function fnEncrypt($sValue, $sSecretKey) {
  global $iv;
  return rtrim((mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $sSecretKey, base64_decode($sValue), MCRYPT_MODE_CBC, $iv)), "\0\3");
  }

  function fnDecrypt($sValue, $sSecretKey) {
  global $iv;
  return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $sSecretKey, ($sValue), MCRYPT_MODE_CBC, $iv), "\0\3");
  }
?>
</html>