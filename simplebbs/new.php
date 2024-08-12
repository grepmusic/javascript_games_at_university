<?php
$page_title = '新的帖子' ;
include 'header.php' ;

if( !isset( $_SESSION['user_id'] ) or !isset( $_SESSION['name'] ) )
{
	echo '<p style="padding-top: 30px; color: green;">你还没有登录，请先点击 <a href="./login.php"><em>登录</em></a></p>' ;
	include 'footer.php' ;
	exit() ;
}

echo '<h2>新的帖子</h2>' ;

if( isset( $_POST['submit'] ) )
{
	require_once 'mysql_connection.inc.php' ;
	@mysql_select_db( 'microbbs' ) ;
	$errors = array() ;

	/* // 如果表单输入框.用户名 不是只读的，则还需验证用户名与当前登录的用户名是否一致
	if( $_POST['name'] != $_SESSION['name'] )
		$errors[] = '请不要修改用户名 <em>'.$_SESSION['name'].'</em> 或者 请重新点击 <a href="./login.php"><em>登录</em></a>' ;
	*/
	
	if( !empty( $_POST['title'] ) && !eregi( '^[[:punct:][:blank:]0-9]*$' , stripslashes( $_POST['title'] ) ) )
		$t = escape_data( $_POST['title'] ) ;
	else
		$errors[] = '请输入帖子 <em>标题</em>' ;
	
	if(!empty( $_POST['content'] ) && !eregi( '^[[:punct:][:space:]0-9]*$' , stripslashes( $_POST['content'] ) ) )
		$c = escape_data( nl2br( htmlspecialchars( $_POST['content'] ) ) ) ;
	else
		$errors[] = '请输入帖子 <em>内容</em>' ;
	
	if( empty( $errors ) )
	{
		$query = "SELECT invitation_id FROM invitations WHERE title = '$t' LIMIT 1" ;
		$result = @mysql_query( $query ) ;
		
		if( mysql_num_rows( $result ) > 0 )
		{
			$row = mysql_fetch_array( $result , MYSQL_NUM ) ;
			echo '<p class="prompt">对不起，标题为 "<em>'.$t.'</em>" 的帖子已存在，请点击 <a href="./invitation.php?id='.$row[0].'"><em>查看帖子</em></a>
			参与讨论<br />你还可以点击 <a href="./new.php"><em>重新发帖</em></a>' ;
			exit() ;			
		}
		else
		{
			$uid = (int)$_SESSION['user_id'] ;
			$query = "INSERT INTO invitations ( user_id , last_user_id , latest_date , new_date , title , content ) VALUES
			( $uid , $uid , NOW() , NOW() , '$t' , '$c' )" ;
			$result = @mysql_query( $query ) ;
			$id = mysql_insert_id() ;
			
			if( $id > 0 )
			{
				echo '<p class="prompt">你已成功发表帖子，现在你可以点击 <a href="./invitation.php?id='.$id.'"><em>查看帖子</em></a></p>' ;
				include 'footer.php' ;
				mysql_close() ;
				exit() ;
			}
			else
				$errors[] = '系统错误，我们为此带来的不便表示道歉，我们会尽快修复错误' ;
		}
	}
	
	if( !empty( $errors ) )
	{
		echo '<p class="error">Error!</p><ul style="color: green;">' ;
		foreach( $errors as $msg )
			echo "<li> -- $msg</li>\n" ;
		echo '</ul>' ;
	}
	mysql_close() ;
}

echo '<form action="'.$_SERVER['REQUEST_URI'].'" method="post"><table align="center" cellpadding="5" cellspacing="3"><tr>
<td align="right">用户名:</td><td><input type="text" name="name" size= "50" maxlength="16" value="'.$_SESSION['name'].'" readonly="readonly" />
'./*'(<em> 请不要修改 </em>)'.*/"</td></tr>\n" ;
?>

<tr><td align="right">标题:</td><td><input type="text" name="title" size="50" maxlength="100" value="<?php if( isset( $_POST['title'] ) )echo stripslashes( $_POST['title'] ) ; ?>" /></td></tr>

<tr><td align="right">内容:</td><td><textarea cols="80" rows="8" name="content"><?php if( isset( $_POST['content'] ) ) echo stripslashes( $_POST['content'] ) ; ?></textarea></td></tr>

<tr><td align="center" colspan="2"><input type="submit" name="submit" value="提交" /></td></tr>
</table>
</form>
<?php
include 'footer.php' ;
?>
