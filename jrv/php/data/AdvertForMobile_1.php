<?php
Header('Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    Header('Access-Control-Allow-Methods: GET');
    Header('Access-Control-Allow-Headers: ' . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
    Header('Access-Control-Max-Age: 86400');
    die;
}
$url = $_GET['url'];
?>
<!--<meta name="viewport" content="initial-scale=1.0, user-scalable=yes">-->

<?php
if (isset($_GET['id'])) {
    $dbname = 'savvy_mhdspoje';

    if ($p = mysql_connect('fssoftware.brn.savvy.cz:3306', 'savvy_mhdspoje', '13FO4mCL')) {

        mysql_query("SET NAMES 'utf-8';");
        //mysql_query("SET NAMES 'cp1250';");
        mysql_select_db($dbname);

        $sql = "select headertxt, txt from reklama where id = " . $_GET['id'];
        $result = mysql_query($sql);
        $row = mysql_fetch_row($result);

        $res = '';
        if ($row[0] != '') {
            $res .= '<div style="width: 100%; white-space: pre-wrap; text-align: left;">
                <h1 style="font-size: 50px;">' . $row[0] . '</h1>
            </div>';
        }
        if ($row[1] != '') {
            $res .= '<div style="width: 100%; font-size: 30px; white-space: pre-wrap; text-align: left;">' . $row[1] . '</div>';
        }
        mysql_close($p);
    }
} else {
    $res = '<table align="center" style="height: 100%;">
            <tr> 
                <td><img style="max-width:100%; max-height:100%;" src="' . $url . '"></td>
            </tr>
        </table>';
}
//  $res = '<div style="text-align: center; vertical-align: middle; height: 100%; width: 100%; display: block;"> <img style="" src="' . $url . '"></div>';
echo $res;
?>