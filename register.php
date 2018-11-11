<?php
//セッション開始
//session_start( );
	//すでにログインしている場合にはメインページに遷移
//	if (isset( $_SESSION['name'])) {
//		header( 'Location: .php') ;
//		exit;
//	}
	
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
$delete = ( isset($_POST['delete']) === true ) ?$_POST['delete']: "";
//$delmail = ( isset($_POST['delmail']) === true ) ?$_POST['delmail']: "";



//データベース接続
$dsn  =  '';
$user  =  '';
$password  =  '';
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
//delete
//if( isset($_POST['delsend']) === true ){
//		$sql = "SELECT * FROM USER ";
//		$result = $pdo->query($sql);
//		foreach($result as $row) {
//			if($row["id"] === $delete){						
//				$sql = "delete from USER where id = $delete "; 
//				$result = $pdo->query($sql);
//				$message1 = $delete."の投稿が削除されました。" ;
//			}
//		}
//	}


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
		ログインは<a href="login.php">こちら</a>
		<br><br>
		<?php echo $RegisterMessage; ?>
		<?php echo $error; ?>
		<br><br>

	</body>
</html>
