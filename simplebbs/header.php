<?php

ob_start() ;
session_start() ;

function error_page( )
{
	$url = 'http://'.$_SERVER['HTTP_HOST'].dirname( $_SERVER['PHP_SELF'] ) ;
	
	if( '/' == substr( $url , -1 ) || '\\' == substr( $url , -1 ) )
		$url = substr( $url , 0 , -1 ) ;
	
	$url .= '/error.php' ;

	ob_end_clean() ;
	header( "Location: $url" ) ;
	exit() ;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf8" />
	<title><?php echo $page_title ; ?></title>
	<link rel="shutcut icon" href="./windfantasy.ico" type="image/x-icon" />
	<!-- rel="shutcut icon" 中的“shutcut ”是可选的，这里是为了与IE兼容 -->
<style type = "text/css" media = "screen">

	/* 页面主体 */
	body	{ background-color: ##efe;   color: black ;   font-size: 1.0em ;   margin-left: 10px;   margin-right: 10px;   margin-top: 10px;   margin-bottom: 10px; }
	
	.prompt	{ color: green; } /* 提示部分 */
		
	/* 论坛logo部分 */
	.title	{ color: #ffffff;   background-color: #003366;   text-align: center;   font-size: 30px;   letter-spacing: 30px;   padding-top: 15px;   padding-bottom: 15px; }
	
	/* 登录或注销链接 */
	a.log	{ text-decoration: none; }
	
	/* 强调 */
	em	{ color: red; }
	
	/* 错误提示 */
	.error	{ color: red;   font-size: 1.5em ;   border-bottom: 2px solid red;   width: 500px;   letter-spacing: 2px; }
	
	/* 错误 */
	.Error	{ padding-top: 30px;   padding-left: 20px;   font-size: 1.1em;   color: green; }
	
	/* 注释 */
	.comment{ color: #2A7469 ;   font-size: 0.8em ; }
	
	/* 页面内容部分 */
	.content{ color: teal;   margin-top: 50px;   margin-left: 20px;   margin-right: 20px; }
	
	/* 页面二级标题 */
	h2	{ font-size: 1.4em;   color: #D2460E;   letter-spacing: 3px;   border-bottom: 3px solid #9FF80B;   padding-bottom: 4px;   text-align: center; }
	
	/* 主页帖子列表标题 */
	.intitle{
		background-color: pink;   border-bottom: 1px solid yellow;   color: #00f;   letter-spacing: 5px;
		padding-left: 5px;   padding-right: 5px;   padding-bottom: 2px; padding-top: 5px;
		}

	/* 帖子标题 */
	th	{
		color: yellow;   padding-left: 5px;   padding-right: 5px;   padding-bottom: 5px;
		padding-top: 5px;   background-color: #444;
		}
	
	.ititle	{ font-size: 1.2em;   letter-spacing: 4px;    border: 1px dotted yellow;   text-align: center; }
	
	/* 回复日期标题 */
	.ud	{ background-color: blue;   font-size: 0.9em;   color: white;   padding-left: 3px; padding-top: 2px; padding-bottom: 2px; }
	
	/* 引用、回复、返回帖子顶部 字体设置 */
	.qrt	{ color: red;   font-size: 0.8em;   letter-spacing: 0;   word-spacing: 0;   font-weight: normal; }
	
	/* 发帖或回帖用户信息 */
	.ileft	{
		font-size: 0.8em; padding-left: 3px;   padding-right: 3px;   padding-bottom: 5px; padding-top: 5px;
		background-color: #eee; color: green;   vertical-align: top;
		}
	
	/* 帖子内容 */
	.iright {
		font-size: 1em;   padding-left: 10px;   padding-right: 10px;   padding-top: 10px; padding-bottom: 10px;
		background-color: lightgreen;   color: black;   vertical-align: top;
		}

</style>
<script type = "text/javascript">
<!--
// add extra necessary javascript code here
// -->
</script>
</head>
<body>
<div class = "title">Micro BBS</div>
<div style="padding-left: 20px; padding-top: 10px; word-spacing: 10px;">
<a href="./index.php">论坛首页</a>
<?php
if( isset( $_SESSION['user_id'] ) && isset( $_SESSION['name'] ) && !strpos( $_SERVER['PHP_SELF'] , 'logout.php' ) )
	echo '<span>欢迎您，<em style="color: green;">'.$_SESSION['name'].'</em> <a class="log" href="#">我的空间</a> <a class="log" href="./logout.php?id='.$_SESSION['user_id'].'">注销</a></span>' ;
else
	echo '<span style="margin-right:0"><a class="log" href="./login.php">登录</a> <a class="log" href="./register.php">注册</a></span>' ;
?>
</div>
