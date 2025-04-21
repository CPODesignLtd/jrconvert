<?php
/*$progress = apc_fetch($_GET[id]);
echo ($progress ? number_format(100 * $progress["current"] / $progress["total"], 2) . "%" : "");*/
/*    $info = uploadprogress_get_info($_GET['id']);
    $kbytes_total = round($info['bytes_total'] / 1024);
    $kbytes_uploaded = round($info['bytes_uploaded'] / 1024);
    echo $kbytes_uploaded.'/'.$kbytes_total.' KB';*/
/*$key = ini_get("UPLOAD_IDENTIFIER") . $_POST[ini_get($_GET['id'])];
var_dump($_SESSION[$key]);*/
  $progress_key = "upload_progress_".$_GET[id];
 
  if ( !isset( $_SESSION[$progress_key] ) ) exit( "uploading..." );
 
  $upload_progress = $_SESSION[$progress_key];
  /* get percentage */
  $progress = round( ($upload_progress['bytes_processed'] / $upload_progress['content_length']) * 100, 2 );
 
  echo "Upload progress: $progress%";
?>
