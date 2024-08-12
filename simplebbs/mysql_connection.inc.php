<?php
//ini_set('display_errors', 'on');
$db_host = '127.0.0.1';
$db_user = 'blog_backup';
$db_pass = '';
$db_name = 'test';

$fatal_error = '对不起, 系统错误, 请联系管理员.';

($dbc = @mysql_connect($db_host, $db_user, $db_pass)) || die( $fatal_error );

(@mysql_select_db($db_name)) || die( $fatal_error );

function escape_data($data) {
    global $dbc;
    if (get_magic_quotes_gpc()) {
        $data = stripcslashes($data);
    }
    return mysql_real_escape_string($data, $dbc);
}
?>
