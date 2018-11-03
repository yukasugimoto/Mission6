<?php
session_start();

//定義
$name = ( isset( $_POST['name'] ) === true ) ?$_POST['name']: "";
$pass = ( isset( $_POST['pass'] ) === true ) ?$_POST['pass']: "";
$message ="";
$error ="";
$err_msg1 = "";
$err_msg2 = "";




//データベース接続
$dsn = 'mysql:dbname=tt_463_99sv_coco_com;host=localhost;charset=utf8';
$user = 'tt-463.99sv-coco';
$password = 'Rk8FEYJA';
try{
	$pdo = new PDO($dsn,$user,$password);
	array(PDO::ATTR_EMULATE_PREPARES => false);
} catch (PDOException $e) {
	exit('データベース接続失敗'.$e->getMessage());
}

//ログインボタンが押されたら
if ( isset($_POST['login']) === true) {
	if( $name === "") $err_msg1="ユーザー名が未入力です";
	if( $pass === "") $err_msg2="パスワードが未入力です";
	if( $err_msg1 ==="" && $err_msg2 === ""){
		//パスワードを暗号化
		$res = null;
		$salt = 'wsedrftgyhujikvgbhnjmk';
		$cost = 31;
		$password = crypt ( $pass, '$2a$' . $cost. '$' . $salt . '$' );
		
		//テーブルにアクセス
		$sql = "SELECT * FROM USER " ;
		$stmt = $pdo->query($sql);
		while($item = $stmt->fetch()) {
			if($item["name"] === $name && $item["pass"] === $password ){

				//セッション
				$_SESSION["name"] = $_POST["name"];
				$login_success_url = "review_top.php";
	      		header("Location: {$login_success_url}");
        		$message ="ログイン成功！";
  	    		exit;
				
			}else{
				$message ="ログイン失敗";
			}
		}
	}
}
		



?>
<!DOCTYPE HTML>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<title>Login</title>
	</head>
	<body>
		ログインして旅行の写真をシェアしよう
		<form action="" method="post">
		<input type= "text" name="name" size="30" placeholder="ユーザー名">
		<?php echo $err_msg1; ?> <br>
		<input type= "text" name="pass" size="30" placeholder="パスワード">
		<?php echo $err_msg2; ?> <br>
		<input type="submit" name="login" value="ログイン"><br>
		<?php echo $message; ?>
		<br>
		会員登録は<a href="register.php">こちら</a>

	</body>

</html>