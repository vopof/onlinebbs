<?php


//データーベースに接続
	if (!$link){
	die('データベースに接続できません：'.mysql_error());
	}




//データベースを選択する
mysql_select_db('online_bbs',$link);


//INSERT INTO `LAA0433175-f6h7zi`.`online_bbs` (`id`, `name`, `comment`, `create_at`) VALUES (NULL, 'aaaaaaa', 'bbbbbbb', '');

	$errors = array();


//POSTなら保存処理実行

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
//名前が正しく入力されているかチェック
	$name = null;

//戻り値の配列を受け取る
	 if (!isset($_POST['name']) || !strlen($_POST['name'])){
		$errors['name'] = '名前を入力して下さい';
	 }else if (strlen($_POST['name']) > 40){
	 	$errors['name'] = '名前は40文字以内で入力して下さい。';
	 }else{
	 	$name = $_POST['name'];
	 }


//ひとことが正しく入力されているかチェック
	$comment = null;
	if(!isset($_POST['comment']) || !strlen($_POST['comment'])){
	$error['comment'] = 'ひとことを入力して下さい';
	}
	else if (strlen($_POST['comment']) > 200){
	$error['comment'] = 'ひとことは200文字以内で入力して下さい。';
	}
	else{
	$comment = $_POST['comment'];
	}


//エラーが無ければ保存　要チェック
if (count($errors) === 0){
	//保存するためのSQL文を保存
	$sql = "INSERT INTO `post` (`name`,`comment`,`created_at`) VALUES('";
	$sql .= mysql_real_escape_string($name)."','";
	$sql .= mysql_real_escape_string($comment)."','";
	$sql .= date('Y-m-d H:i:s')."')";
	


//保存する
	mysql_query($sql,$link);
	mysql_close($link);
	header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	}
}

?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>ひとこと掲示板</title>
	</head>
<body>


<h3>ひとこと掲示板</h3>

<form action="onlinebbs.php" method="post">
<?php if (count($errors) > 0): ?>
	<ul class="error_list">
		<?php foreach ($errors as $error): ?>
		<li>
			<?php echo htmlspecialchars($error,ENT_QUOTES,'UTF-8'); ?>
		</li>
		<?php endforeach; ?>
	</ul>	
<?php endif; ?>


	おなまえ:<input type="text" name="name" /><br />
	ひとこと：<input type="text" name="comment" size="60" /> <br />
	<input type="submit" name="submit" value="送信" />
</form>


<?php
//投稿された内容を取得するSQLを作成して結果を取得
	$sql = "SELECT * FROM `post` ORDER BY `created_at` DESC";
	$result = mysql_query($sql,$link);
?>

<?php if($result !== false && mysql_num_rows($result)): ?>
	<ul>
	<?php while ($post = mysql_fetch_assoc($result)): ?>
		<li>

<?php echo htmlspecialchars($post['name'],ENT_QUOTES,'UTF-8'); ?>
<?php echo htmlspecialchars($post['comment'],ENT_QUOTES,'UTF-8'); ?>
<?php echo htmlspecialchars($post['created_at'],ENT_QUOTES,'UTF-8'); ?>
	
		</li>

	<?php endwhile; ?>
	</ul>
<?php endif; ?>


<?php 
 //取得結果を開放して接続を閉じる
 	mysql_free_result($result);
	mysql_close($link);
?>


	</body>
</html>
