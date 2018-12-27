<?php 
//投稿を削除したあとに表示される
session_start();
if(!isset($_SESSION["name"])) {
    $no_login_url = "login.php";
    header("Location: {$no_login_url}");
    exit;
}

?>

<!DOCTYPE HTML>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<title>Review post</title>
		<style>
			body{
				background:#ffffff;
				max-width: 700px;
				padding:10px;
				padding-bottom:60px;
				text-align:center;
				border:1px solid #cccccc;
				margin:30px auto;
			}
			
		</style>

	</head>
	<body>
		<h1>削除完了</h1>
		投稿は削除されました。
		<br>
		<a href="review_top.php">戻る</a>
	</body>
</html>
