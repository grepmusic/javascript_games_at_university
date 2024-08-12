<?php
$page_title = "Micro BBS" ;
include 'header.php' ;
?>
<div style="padding-left: 20px; padding-top: 20px; word-spacing: 10px;"><a href="./new.php">新的帖子</a></div>
<div class="content">

<?php

require_once 'mysql_connection.inc.php' ;
@mysql_select_db( 'microbbs' ) ;

$query = 'SELECT invitation_id , user_id , last_user_id , new_date , title , latest_date FROM invitations' ;
$result = @mysql_query( $query ) ;
$row = mysql_fetch_array( $result , MYSQL_NUM ) ;

if( $row[0] > 0 )
{
	list( $invitation_id , $user_id , $last_user_id , $new_date , $title , $latest_date ) = $row ;
	$query1 = "SELECT user_id , name FROM users WHERE user_id = $user_id LIMIT 1" ;
	$result1 = @mysql_query( $query1 ) ;
	$row1 = mysql_fetch_array( $result1 , MYSQL_ASSOC ) ;
	
	if( $row1['user_id'] > 0 )
	{
		$name = $row1['name'] ;
		if( $user_id == $last_user_id )
			$last_user_name = $name ;
		else
		{
			$query2 = "SELECT user_id , name FROM users WHERE user_id = $last_user_id LIMIT 1" ;
			$result2 = @mysql_query( $query2 ) ;
			$row2 = mysql_fetch_array( $result2 , MYSQL_ASSOC ) ;
			
			if( $row2['user_id'] > 0 )
				$last_user_name = $row2['name'] ;
			else
			{
				echo '<p class="prompt">系统错误，我们为此带来的不便表示道歉，我们会尽快修复错误</p>' ;
				include 'footer.php' ;
				mysql_close() ;
				exit() ;
			}
			mysql_free_result( $result2 ) ;
		}
		mysql_free_result( $result1 ) ;
	}
	else
	{
		echo '<p class="prompt">系统错误，我们为此带来的不便表示道歉，我们会尽快修复错误</p>' ;
		include 'footer.php' ;
		mysql_close() ;
		exit() ;
	}
	echo '<table cellspacing="5" cellpadding="5" align="center" width="75%">
		<tr>
			<td>
			<a href="./invitation.php?id='.$invitation_id.'"><h3 class="intitle">'.$title.'</h3></a>
			<div style="font-size: 0.8em; margin-top: -10px;"></em>发表于'.$new_date.' By <em style="color: green;">'.$name.'</em>
				最后回复于'.$latest_date.' By <em style="color: green;">'.$last_user_name.'
			</div>
			</td>
		</tr>'."\n" ;
}

	while( $row = mysql_fetch_array( $result , MYSQL_NUM ) )
	{
		if( $row[0] > 0 )
		{
			list( $invitation_id , $user_id , $last_user_id , $new_date , $title , $latest_date ) = $row ;
			$query1 = "SELECT user_id , name FROM users WHERE user_id = $user_id LIMIT 1" ;
			$result1 = @mysql_query( $query1 ) ;
			$row1 = mysql_fetch_array( $result1 , MYSQL_ASSOC ) ;
	
			if( $row1['user_id'] > 0 )
			{
				$name = $row1['name'] ;
				if( $user_id == $last_user_id )
					$last_user_name = $name ;
				else
				{
					$query2 = "SELECT user_id , name FROM users WHERE user_id = $last_user_id LIMIT 1" ;
					$result2 = @mysql_query( $query2 ) ;
					$row2 = mysql_fetch_array( $result2 , MYSQL_ASSOC ) ;

					if( $row2['user_id'] > 0 )
					{
						$last_user_name = $row2['name'] ;
						
					}
					else
					{
						echo '<p class="prompt">系统错误，我们为此带来的不便表示道歉，我们会尽快修复错误</p>' ;
						include 'footer.php' ;
						mysql_close() ;
						exit() ;
					}
					mysql_free_result( $result2 ) ;
				}
				mysql_free_result( $result1 ) ;
			}
			
		}
		else
		{
			echo '<p class="prompt">系统错误，我们为此带来的不便表示道歉，我们会尽快修复错误</p>' ;
			include 'footer.php' ;
			mysql_close() ;
			exit() ;
		}
		echo '
		<tr>
			<td>
			<a href="./invitation.php?id='.$invitation_id.'"><h3 class="intitle">'.$title.'</h3></a>
			<div style="font-size: 0.8em; margin-top: -10px;"></em>发表于'.$new_date.' By <em style="color: green;">'.$name.'</em>
				最后回复于'.$latest_date.' By <em style="color: green;">'.$last_user_name.'
			</div>
			</td>
		</tr>'."\n" ;
	}
	
echo "</table>\n</div>\n" ;

include 'footer.php' ;
?>
