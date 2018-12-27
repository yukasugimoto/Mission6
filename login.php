<?php
//ログインページ。ログインしていないと表示される。
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
		<style>
			body{
				background: #ffffff;
			}
			header{
				text-align: center;
			}
			.head{
				background: #ffffff;
				max-width: 700px;
				padding: 10px;
				padding-bottom:60px;
				text-align: center;
				border: 1px solid #cccccc;
				margin: 30px auto;
			}
			.txt{
				display: inline-block;
				text-align: left;
			}			
		</style>
	</head>
	<body>
	<header>
		<h1><img src="table/logo.jpg"></h1>
	</header>
	<div class="head">
		<h2>ログインして写真をシェアしよう</h2>
		<br>
	<p class="txt">
		※ゲストログイン：「tryhard」
		<form action="" method="post">
		<input type= "text" name="name" size="30" placeholder="ユーザー名">
		<?php echo $err_msg1; ?> <br>
		<input type= "password" name="pass" size="30" placeholder="パスワード">
		<?php echo $err_msg2; ?> <br>
		<input type="submit" name="login" value="ログイン"><br>
		<?php echo $message; ?>
		<br>
		会員登録は<a href="register.php">こちら</a>
	</p>
	</div>
	</body>

</html>
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
		<h1>ログインして旅行の写真をシェアしよう</h1>
		<br>
		※ゲストログイン：ユーザー名＆パスワード＝「チーム名(小文字)」で入れます！
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
