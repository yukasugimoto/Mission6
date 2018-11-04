<?php
//定義
$logout = ( isset($_POST['logout'] ) === true ) ?$_POST['logout']: "";

//ログアウト
session_start();

	if( isset( $_GET['logout'])) {
		$_SESSION = array();
		session_destroy();
//		header("Location: login.php");
	}
	else{
		header("Location: login.php");
		}

?>

<!DOCTYPE HTML>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<title>Logout</title>
	</head>
	<body>
		ログアウトしました<br>
		<a href="review_top.php">戻る</a> <br>
	</body>
</html>