<?php

if( isset( $_GET['id'] ) )
	$id = (int)$_GET['id'] ;
else
	$id = 0 ;

if( $id < 1 ) :
	include 'header.php' ;
	error_page() ;
endif ;

require_once 'mysql_connection.inc.php' ; // 连接mysql数据库
@mysql_select_db( 'microbbs' ) ; // 选择microbbs数据库

$query = "SELECT user_id , new_date , title , content FROM invitations WHERE invitation_id = $id LIMIT 1" ; // 依据帖子id查找帖子信息
if( !($result = @mysql_query($query)) ) // 如果查询有问题
{
	include 'header.php' ;
	error_page() ;
}

if( list( $user_id , $new_date , $title , $content ) = mysql_fetch_array( $result , MYSQL_NUM ) ) // 如果帖子存在，则记录帖子信息：发帖人id、发帖日期、标题、帖子内容
{
	$page_title = 'Micro BBS -- '.$title ; // 设置页面标题为帖子标题
	include 'header.php' ;
	
	mysql_free_result( $result ) ;
	
	$query = "SELECT name , sign , registration_date FROM users WHERE user_id = $user_id LIMIT 1" ;
	if( !($result = @mysql_query($query)) )
		error_page() ;
	
	if( list( $name , $sign , $registration_date ) = mysql_fetch_array( $result , MYSQL_NUM ) ) // 如果发帖的用户存在，则获取用户信息并显示用户所发帖子
	{
		echo '
		<div class="content">
			<table align="center" width="90%" cellpadding="0" cellspacing="1" id="top">
				<tr>
					<th colspan="2" class="ititle">'.$title.'</th>
				</tr>'."\n" ;
		//if( $get_page_No == 1 )
				echo '
				<tr>
					<td class="ud" id="0">楼主 '.$name.'</td>
					<td class="ud">发表于：'.$new_date.'<span style="float: right; word-spacing: 5px;">
						<a href="#"><span style="color: yellow;">引用</span></a>
						<a href="#"><span style="color: yellow;">回复</span></a>
						<a href="#top"><span style="color: yellow;">Top</span></a>&nbsp;
						</span>
					</td>
				</tr>
				<tr>
					<td width="20%" class="ileft">
						发表于：<br />'.$new_date.'<br /><br />'.$name.'<br />('.( empty( $sign ) ? '该用户很懒，没有设置签名。' : $sign ).')<br /><br />注册日期：<br />'.$registration_date.'<br />
					</td>
					<td class="iright">'.$content.'</td>
				</tr>'."\n" ;
		
		mysql_free_result( $result ) ;
		
		
		// 如果帖子存在，用户已经登录，并且表单已经提交，则将用户的回帖插入到表invitation_unit中
		$error = $msg = FALSE ; // 默认用户的回复内容合法、用户发表帖子失败
		if( isset( $_POST['submit'] ) && isset( $_SESSION['user_id'] ) )
			if(!empty( $_POST['Content'] ) && !eregi( '^[[:punct:][:space:]0-9]*$' , stripslashes( $_POST['Content'] ) ) )
			{
				if( !( $Result = @mysql_query( "SELECT COUNT(*) FROM invitation_unit WHERE invitation_id = $id GROUP BY invitation_id" ) ) )
					error_page() ;
				
				list( $floor ) = mysql_fetch_array( $Result , MYSQL_NUM ) ;
				$floor += 1 ; // 获取楼号
				
				$C = escape_data( nl2br( htmlspecialchars( $_POST['Content'] ) ) ) ; // 将特殊html标记转换成html实体符号,将换行符后插入<br />
				
				$insert_query = "INSERT INTO invitation_unit( invitation_id , user_id , floor , reply_date , content ) VALUES
				( $id , {$_SESSION['user_id']} , $floor , NOW() , '$C' )" ;
				if( !@mysql_query( $insert_query ) )
					error_page() ;
				
				if( mysql_affected_rows( /* $dbc */ ) > 0 ) // 如果插入成功，则给出相应提示，并且清空 回复内容
				{
					$invitationUnitId = mysql_insert_id() ;
					$update_query = "UPDATE invitations SET last_user_id = {$_SESSION['user_id']} , latest_date = ( SELECT reply_date FROM invitation_unit WHERE invitation_unit_id = $invitationUnitId LIMIT 1 ) WHERE invitation_id = $id LIMIT 1" ;
					if( !@mysql_query( $update_query ) )
						error_page() ;
					
					if( mysql_affected_rows( /* $dbc */ ) < 1 )
						error_page() ;
						
					$msg = '恭喜回复 <em>成功</em>！' ;
				}
			}
			else
				$error = '请输入合法的 <em>回复内容</em>！' ;
		
		// 读取所有的回帖
		$query2 = "SELECT invitation_unit_id , user_id , floor , reply_date , content FROM invitation_unit WHERE invitation_id = $id ORDER BY reply_date ASC" ;
		if( !($result2 = @mysql_query($query2)) )
			error_page() ;

		while( list( $iuid , $uid , $f , $rd , $c ) = mysql_fetch_array( $result2 , MYSQL_NUM ) )
		{
			$query3 = "SELECT name , sign , registration_date FROM users WHERE user_id = $uid LIMIT 1" ;
			if( !($result3 = @mysql_query($query3)) )
				error_page() ;
			
			if( list( $n , $s , $reg_date ) = mysql_fetch_array( $result3 , MYSQL_NUM ) )
			{
				echo '
				<tr>
					<td class="ud" id="'.$f.'">#'.$f.'楼 '.$n.'</td>
					<td class="ud">回复于：'.$rd.'<span style="float: right; word-spacing: 5px;">
						<a href="#"><span style="color: yellow;">引用</span></a>
						<a href="#"><span style="color: yellow;">回复</span></a>
						<a href="#top"><span style="color: yellow;">Top</span></a>&nbsp;
						</span>
					</td>
				</tr>
				<tr>
					<td width="20%" class="ileft">
						回复于：<br />'.$rd.'<br /><br />'.$n.'<br />('.( empty( $s ) ? '该用户很懒，没有设置签名。' : $s ).')<br /><br />
						注册日期：<br />'.$reg_date.'<br />
					</td>
					<td class="iright">'.$c.'</td>
				</tr>'."\n" ;
			}
			else
				error_page() ;
			
			mysql_free_result( $result3 ) ;
		}
		mysql_free_result( $result2 ) ;
	}
	else
		error_page() ;
}
else
{
	include 'header.php' ;
	error_page() ;
}

mysql_close() ;
?>

			</table>
	<?php
		if( $error )
			echo '
			<div style="padding-left: 20%;"><p class="error">注意</p>'.$error.'</div><br />' ;
		elseif( $msg )
			echo '
			<div style="padding-left: 20%;">'.$msg.'</div><br />' ;
	?>

		<form action="<?php echo $_SERVER['REQUEST_URI'].'?id='.$id.( ($floor) ? ('#'.$floor) : '#e' ) ; ?>" method="post">
			<table align="center" width="90%" cellpadding="0" cellspacing="10" id="e">
				<tr>
					<td style="width: 15%; text-align: right; color: blue;">用户名：</td>
					<td>
						<input type="text" size="30" value="<?php if( isset( $_SESSION['name'] ) ) echo $_SESSION['name'] ; ?>" readonly="readonly" />
						<?php if( !isset( $_SESSION['name'] ) ) echo '<span style="color: red; font-size: 0.8em;">( 您还没有登录，不能回复帖子，请先<a class="log" href="./login.php"><em>登录</em></a> )</span>' ; ?>
					</td>
				</tr>
				<tr>
					<td style="width: 15%; text-align: right; color: blue;">回复内容：</td>
					<td>
						<textarea cols="75" rows="6" name="Content"><?php if( !$msg && isset( $_POST['Content'] ) ) echo stripslashes( $_POST['Content'] ) ; // 如果回帖不成功，则显示回帖内容 ?></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: center">
						<input type="submit" name="submit" value="回复" <?php if( !isset( $_SESSION['user_id'] ) || !isset( $_SESSION['name'] ) ) echo 'disabled="disabled"' ; ?> />
						<input type="hidden" name="id" value="<?php echo $id ; ?>" />
					</td>
				</tr>
			</table>
		</form>
	</div>
<?php
include 'footer.php' ;
?>
