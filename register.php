<?php
//登録画面

//定義
$name = ( isset($_POST['name'] ) === true ) ?$_POST['name']: "";
$mail = ( isset($_POST['mail'] ) === true ) ?$_POST['mail']: "";
$pass = ( isset( $_POST['pass'] ) === true ) ?$_POST['pass']: "";
$err_msg1 ="";
$err_msg2 ="";
$err_msg3 ="";
$err_msg4 ="";
$error = "";
$RegisterMessage ="";

//セッション開始
//session_start( );
	//すでにログインしている場合にはメインページに遷移
//	if (isset( $_SESSION['USERID'])) {
//		header( 'Location: main.php') ;
//		exit;
//	}

//データベース接続
$dsn = '';
$user = '';
$password = '';
try{
	$pdo = new PDO($dsn,$user,$password);
	array(PDO::ATTR_EMULATE_PREPARES => false);
} catch (PDOException $e) {
	exit('データベース接続失敗'.$e->getMessage());
}
//テーブルつくる
//$sql = "CREATE TABLE IF NOT EXISTS `USER`"
//."("
//."`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
//."`name` char( 25 ) NOT NULL,"
//."`mail` VARCHAR( 35 ) NOT NULL,"
//."`pass` VARCHAR( 100 ) NOT NULL,"
//."UNIQUE KEY `mail` (`mail`)"
//.");";
//$stmt = $pdo->query($sql);


//新規登録ボタンが押されたら
if ( isset( $_POST['register']) === true ){
	if( $name === "" ) $err_msg1="ユーザー名が未入力です";
	if( $mail === "") $err_msg2="メールアドレスが未入力です";
	if( $pass === "") $err_msg3= "パスワードが未入力です";
	if( $err_msg1 === "" && $err_msg2 === "" && $err_msg3 === "" ){
		//文字数チェック
		$sname = strlen($name);
		$spass = strlen($pass);
		if ($sname < 4) { $error = "ユーザー名は4文字以上で設定してください";  }
		if ($spass < 4) { $error = "パスワードは4文字以上で設定してください"; }
		//重複チェック
		$sql = "SELECT * FROM USER ";
		$stmt = $pdo->query($sql);
		while($item = $stmt->fetch()) {
			if($item["name"] === $name ){
				$error="このユーザー名はすでに使われています";
			}elseif($item["mail"] === $mail ){
				$error="このメールアドレスはすでに登録済みです";
			}
		}
		if($error == "") {
				//パスワードを暗号化する
				$res = null;
				$salt = 'wsedrftgyhujikvgbhnjmk';
				$cost = 31;
				$password = crypt ( $pass, '$2a$' . $cost. '$' . $salt . '$' );
					//insertする
					$sql = $pdo -> prepare("INSERT INTO USER (id, name, mail, pass) VALUES (:id, :name, :mail, :pass)" );			
					$sql -> bindParam(':id', $id, PDO::PARAM_STR);
					$sql -> bindParam (':name', $name, PDO::PARAM_STR);
					$sql -> bindParam (':mail', $mail, PDO::PARAM_STR);
					$sql -> bindParam (':pass', $password, PDO::PARAM_STR);
					$sql -> execute();
					$RegisterMessage = "登録が完了しました。";
		}


		//INSERTできたか確認する
//		$check=$sql->execute();
//		$var_dump($check);
//		if($check){
//			$RegisterMessage = "登録が完了しました。";
//		}else{
//			$err_msg4= "登録に失敗しました";
//		}				

	}
}
?>

<!DOCTYPE HTML>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<title>Register</title>
	</head>
	<body>
		会員登録をしてください。
		<form action="" method="post">
		<input type="text" name="name" size="30" placeholder="ユーザー名" value="<?php echo $name; ?>" >
		<?php echo $err_msg1; ?> <br>
		<input type="text" name="mail" size="30" placeholder="メールアドレス" value ="<?php echo $mail; ?>">
		<?php echo $err_msg2; ?> <br>
		<input type="text" name="pass" size="30" placeholder="パスワード" value ="<?php echo $pass; ?>">
		<?php echo $err_msg3; ?> <br>
		<input type="submit" name="register" value="登録"><br>
		ログインは<a href="http://tt-463.99sv-coco.com/mission6/login.php">こちら</a>
		<br><br>
		<?php echo $RegisterMessage; ?>
		<?php echo $error; ?>
		<br><br>
				<?php
			//データベース接続
			$dsn  =  'mysql:dbname=tt_463_99sv_coco_com;host=localhost;charset=utf8';
			$user  =  'tt-463.99sv-coco';
			$password  =  'Rk8FEYJA';
			try{
				$pdo  =  new  PDO($dsn,$user,$password);
				array(PDO::ATTR_EMULATE_PREPARES => false);
			} catch (PDOException $e) {
			 exit('データベース接続失敗。'.$e->getMessage());
			}

			//show the table
//			$sql = "SELECT * FROM USER ORDER BY id DESC";
//			$results = $pdo -> query($sql);
//			foreach ($results as $row){
				//$rowの中にはテーブルのカラム名が入る
//				echo  $row['id'].' ';
//				echo  $row['name'].' '; 
//				echo  $row['mail'].' ';
//				echo  $row['pass'].'<br>'; 

//			}

		?>
	</body>
</html>
