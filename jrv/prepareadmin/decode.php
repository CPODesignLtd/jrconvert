<?php
//include_once 'RIJNDAEL.php';
//include_once 'aes128cbc.php';

error_reporting (0);

//include(dirname(__FILE__)."/phpCrypt.php");
//use PHP_Crypt\PHP_Crypt as PHP_Crypt;

class Base64 {

  private static $BinaryMap = array(
      '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
      'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
      'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
      'U', 'V', 'W', 'X', 'Y', 'Z',
      'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
      'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
      'u', 'v', 'w', 'x', 'y', 'z', '+', '/'
  );

  public function __construct() {

  }

  public function base64_encode1($input) {

    $output = "";
    $chr1 = $chr2 = $chr3 = $enc1 = $enc2 = $enc3 = $enc4 = null;
    $i = 0;

//        $input = self::utf8_encode($input);

    while ($i < strlen($input)) {
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

      $output .= self::$BinaryMap[$enc1]
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

/*$filename = "data/99/chronometr.aes";
$handle = fopen($filename, "r");
$contents = fread($handle, filesize($filename));
fclose($handle);

$stuff = $contents;
$key = '#jrw_fssoftware_581003##';*/

function nl() {
  echo "<br/> \n";
}

function encrypt($string, $key) {
  $enc = "";
  global $iv;
  $enc = trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $string, MCRYPT_MODE_ECB, $iv));

  return base64_encode($enc);
}

/*function decrypt($string, $key) {
  $dec = "";
  //$string = trim(base64_decode($string));
  echo $string;
  echo '<br><br>';
  global $iv;
  $decrypted = '';
  //openssl_private_decrypt ( $string , $decrypted, $key);
  //echo $decrypted;
  //$dec = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $string, MCRYPT_MODE_ECB, $iv));
/*  $ciphers             = openssl_get_cipher_methods();
  for ($i=0; $i < sizeof($ciphers); $i++) {
  echo '(' . $i . '/' . sizeof($ciphers) . ') ' . $ciphers[$i] . ' - ' . $dec = trim(openssl_decrypt($string, $ciphers[$i], $key, OPENSSL_RAW_DATA, $iv));
  echo '<br><br><br>';
  }*/
//  $dec = trim(openssl_decrypt($string, 'AES-128-CBC', $key, 0, $iv));
//  echo $dec;
/*  return $dec;
}*/

 function decrypt($data, $key = '', $file, $appid = '') {
     /*global $iv;
		$RIJNDAEL_CBC=new RIJNDAEL_CBC;                
                $data = trim(base64_decode($data));
                $RIJNDAEL_CBC->init('ecb',$key, "", 16);
                $decrypted = $RIJNDAEL_CBC->encrypt('ahoj');//decrypt('text');
                echo $decrypted . '<br>';
                $decrypted = $RIJNDAEL_CBC->decrypt('d20557a4caa422fa4fa4d307c70b50e1d20557a4caa422fa4fa4d307c70b50e1');//decrypt('text');
                echo $decrypted . '<br><br>';                
		return $decrypted;*/     
//     $data = trim(base64_decode($data));

/*$crypt = new PHP_Crypt($key, PHP_Crypt::CIPHER_RIJNDAEL_128, PHP_Crypt::MODE_ECB);
$cipher_block_sz = 256;//$crypt->cipherBlockSize();
$iv = $crypt->createIV();
$crypt->IV($iv);
$rhandle = fopen($file, "rb");
$whandle = fopen('' . $file . '.desql', "w+");
echo $file . '.desql' . '<br>';

$crypt->IV($iv);

while (!feof($rhandle))
{
	$bytes = fread($rhandle, $cipher_block_sz);
	$result = $crypt->decrypt($bytes);
	fwrite($whandle, $result);
}
fclose($rhandle);
fclose($whandle); */
/*$decrypt = $crypt->decrypt($data);
echo $decrypt . '<br><br>';*/
     
 return base64_decode($data);
	}

/*$decrypted = decrypt($contents, $key);

echo "Decrypted is</br> " . $decrypted . nl();*/

/*$filename = "data/zastavky.sql";

$handle = fopen($filename, "r");
        $contents = fread($handle, filesize($filename));
        fclose($handle);

        $stuff = $contents;
        $key = '#jrw_fssoftware_581003##';

        $decrypted = decrypt($contents, $key);

        echo $decrypted;*/
?>