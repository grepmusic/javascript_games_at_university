<?php
$page_title = '用户注册' ;
include 'header.php' ;
echo '<h2>用户注册</h2>' ;

if( isset( $_POST[ 'submit' ] ) )
{
	require_once 'mysql_connection.inc.php' ;
	@mysql_select_db( 'microbbs' ) ;
	
	$errors = array() ;
	
	if( eregi( '^[a-z]{1}[a-z0-9]{5,15}$' , $_POST[ 'name' ] ) )
		$n = escape_data( $_POST[ 'name' ] ) ;
	else
		$errors[] = '请输入合法的 <em>用户名</em>' ;

	if( eregi( '^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$' , stripslashes( trim( $_POST['email'] ) ) ) )
		$e = escape_data( $_POST['email'] ) ;
	else
		$errors[] = '请输入合法的 <em>电子邮件地址</em>' ;

	if( eregi( '^[a-z0-9]{6,20}$' , $_POST['password1'] ) )
		if( $_POST['password1'] == $_POST['password2'] )
			$p = escape_data( $_POST['password1'] ) ;
		else
			$errors[] = '请输入匹配的 <em>确认密码</em>' ;
	else
		$errors[] = '请输入合法的 <em>密码</em>' ;

	if( isset( $_POST['sex'] ) )
		$sx = $_POST['sex'] ;
	else
		$errors[] = '请选择合适的 <em>性别</em>' ;

	if( isset( $_POST['sign'] ) )
		$sn = "'".escape_data( $_POST['sign'] )."'" ;
	else
		$sn = 'NULL' ;

	if( empty( $errors ) )
	{
		$query1 = "SELECT user_id FROM users WHERE email = '$e'" ;
		$result1 = @mysql_query( $query1 ) ;
		
		if( mysql_num_rows( $result1 ) > 0 )
			$errors[] = '对不起，电子邮件地址 <em>'.stripslashes( trim( $_POST['email'] ) ).'</em> 已被使用' ;
		else
		{
			$query2 = "SELECT user_id FROM users WHERE name = '$n'" ;
			$result2 = @mysql_query( $query2 ) ;
			if( mysql_num_rows( $result2 ) > 0 )
				$errors[] = '对不起，用户名 <em>'.stripslashes( $_POST['name'] ).'</em> 已被使用' ;
			else
			{
				$query = "INSERT INTO users (name , email , password , sex , sign , registration_date) VALUES
				( '$n' , '$e' , SHA( '$p' ) , '$sx' , $sn , NOW() )" ;
				$result = @mysql_query( $query ) ;
				if( $result )
				{
					echo '<h3 style="color: green;">你已成功注册，感谢你对 <em>Micro BBS</em> 的支持!</h3>' ;
					echo '<p>现在你可以点击 <a href="./login.php"><em>登录</em></a> 或者点击 <a href="./index.php"><em>返回首页</em></a></p>' ;
					include 'footer.php' ;
					mysql_close() ;
					exit() ;
				}
				else
					$errors[] = '系统错误，我们为此带来的不便表示道歉，我们会尽快修复错误' ;
			}
		}
	}
	
	if( !empty( $errors ) )
	{
		echo '<p class="error">Error!<ul class="prompt">' ;
		foreach( $errors as $msg )
			echo "<li> -- $msg</li>\n" ;
		echo '</ul></p>' ;
	}
	
	mysql_close() ;
}
?>

<form action = "./register.php" method = "post">
<fieldset style = "border-color: pink; margin-top: 40px;">
<legend>请输入你的信息(带<span style = "color: red">*</span>的为必填项): </legend>

<p><span style = "color: red;">*</span>用户名: <input type = "text" name = "name" size = "16" maxlength = "16" value = "<?php if( isset( $_POST[ 'name' ] ) ) echo stripslashes( $_POST[ 'name' ] ) ; ?>" />
<span class = "comment">( 由6～16个英文字符和数字字符组成并且以英文字符开头 )</span></p>

<p><span style = "color: red;">*</span>电子邮件: <input type = "text" name = "email" size = "20" maxlength = "40" value = "<?php if( isset( $_POST[ 'email' ] ) ) echo stripslashes( $_POST[ 'email' ] ) ; ?>" /></p>

<p><span style = "color: red;">*</span>密码: <input type = "password" name = "password1" size = "20" maxlength = "20" />
<span class = "comment">( 由6～20个英文字符和数字字符组成 )</span></p>

<p><span style = "color: red;">*</span>确认密码: <input type = "password" name = "password2" size = "20" maxlengh = "20" /></p>

<p><span style = "color: red;">*</span>性别: <input type = "radio" name = "sex" value = "M" <?php if( $_POST[ 'sex' ] == 'M' ) echo 'checked="checked"' ; ?> /> 男
<input type = "radio" name = "sex" value = "F" <?php if( $_POST[ 'sex' ] == 'F' ) echo 'checked="checked"' ; ?> /> 女</p>

<p>个性签名: <textarea name = "sign" cols = "50" rows = "2"><?php if( isset( $_POST['sign'] ) ) echo stripslashes( $_POST['sign'] ) ; ?></textarea></p>
</fieldset>

<p align = "center"><input type = "submit" name = "submit" value = "提交" />
<input type = "button" name = "rt" value = "重置" onclick="document.forms[0].reset()" /></p>
</form>

<?php
include 'footer.php' ;
?>
