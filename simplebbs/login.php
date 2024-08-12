<?php
$page_title = '用户登录' ;
include 'header.php' ;
echo '<h2>用户登录</h2>' ;

$method = '' ;

if( isset( $_POST['submit'] ) )
{
	$content = FALSE ;
	$errors = array() ;
	require_once 'mysql_connection.inc.php' ;
	@mysql_select_db( 'microbbs' ) ;
	
	if( $_POST['log_method'] == 'name' )
	{
		$method = 'name' ;
		if( !empty( $_POST['name/email'] ) )
			$content = escape_data( $_POST['name/email'] ) ;
		else
			$errors[] = '请输入 <em>用户名</em>' ;
	}
	elseif( $_POST['log_method'] == 'email' )
	{
		$method = 'email' ;
		if( !empty( $_POST['name/email'] ) )
			$content = escape_data( $_POST['name/email'] ) ;
		else
			$errors[] = '请输入 <em>电子邮件地址</em>' ;
	}
	
	$p = FALSE ;
	if( !empty( $_POST['password'] ) )
		$p = escape_data( $_POST['password'] ) ; // don't use $password
	else
		$errors[] = '请输入 <em>密码</em>' ;
	
	if( $method && $content && $p )
	{
		$query = "SELECT user_id , name FROM users WHERE $method = '$content' AND password = SHA( '$p' ) LIMIT 1" ;
		$result = @mysql_query( $query ) ;
		$row = mysql_fetch_array( $result , MYSQL_NUM ) ;
		
		if( $row[0] > 0 )
		{
			$_SESSION['user_id'] = $row[0] ;
			$_SESSION['name'] = $row[1] ;
			
			$url = 'http://'.$_SERVER[ 'HTTP_HOST' ].dirname( $_SERVER["REQUEST_URI"] ) ;
			if( '/' == substr( $url , -1 ) or '\\' == substr( $url , -1 ) )
				$url = substr( $url , 0 , -1 ) ;
			$url .= '/index.php' ;
			
			ob_end_clean( ) ; // delete the buffer
			header( 'Location: '.$url ) ;
			exit( ) ;
		}
		else
			$errors[] = '请输入相匹配的 <em>'.( ( $method == 'name' ) ? '用户名' : '电子邮件地址' ).'</em> 和 <em>密码</em>' ;
	}
	
	echo '<p class="error">Error!<ul style="color: green;">' ;
	foreach( $errors as $msg )
		echo "<li> -- $msg</li>\n" ;
	echo '</ul></p>' ;
}

?>

<form action = "./login.php" method = "post">

<table align = "center" cellpadding = "10">
<tr><td><select name = "log_method">
<option value = "name" <?php
if( $method != 'email' )
	echo 'selected="selected">用户名</option><option' ;
else
	echo '>用户名</option><option selected="selected"' ;
?>
 value = "email">电子邮件地址</option></select>: </td>
<td><input type = "text" name = "name/email" size = "20" maxlength = "40" value="<?php if( isset( $_POST['name/email'] ) ) echo stripslashes( $_POST['name/email'] ) ; ?>" /></td></tr>

<tr><td align = "right">密码: </td><td><input type="password" name="password" size="20" maxlength="40" /></td></tr>

<tr><td colspan="2" align="center"><input type = "submit" name = "submit" value = "登录" /></td></tr>
</table>
</form>
<?php
include 'footer.php' ;
?>
