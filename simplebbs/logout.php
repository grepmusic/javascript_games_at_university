<?php
$page_title = '用户注销' ;
include 'header.php' ;

if( isset( $_SESSION['user_id'] ) && isset( $_SESSION['name'] ) )
{
	$_SESSION = array() ;
	
	session_destroy() ;
	
	setcookie( session_name() , '' , time()-300 , '/' , '' , 0 ) ;
	
	echo '<p class="prompt">你已成功注销，欢迎下次再登录，现在你可以返回 <a href="./index.php"><em>首页</em></a></p>' ;
}
else
{
	$url = 'http://'.$_SERVER['HTTP_HOST'].dirname( $_SERVER['PHP_SELF'] ) ;
	
	if( '/' == substr( $url , -1 ) or '\\' == substr( $url , -1 ) )
		$url = substr( $url , 0 , -1 ) ;
	
	$url .= '/index.php' ;
	
	ob_end_clean() ;
	header( 'Location: '.$url ) ;
	
	exit() ;
}

include 'footer.php' ;
?>
