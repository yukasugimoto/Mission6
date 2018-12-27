<?php
//投稿完了後のページ

session_start();
if(!isset($_SESSION["name"])){
	$no_login_url = "login.php";
	header("Location: {$no_login_url}");
	exit;
}
//データベース接続
$dsn  =  'mysql:dbname=tt_463_99sv_coco_com;host=localhost;charset=utf8';
$user  =  'tt-463.99sv-coco';
$password  =  'Rk8FEYJA';
try{
	$pdo = new PDO($dsn,$user,$password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	array(PDO::ATTR_EMULATE_PREPARES => false);
		}
		catch (PDOException $e) {
		echo("<p>Error</p>");
		exit($e->getMessage());
		}

if(isset($_GET['id'])){
	$id = $_GET['id'];
	echo $id;
}

?>

<!DOCTYPE HTML>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<title>edit completed</title>
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
	投稿が編集されました！<br>
	<a href="review_top.php">トップに戻る</a>
	</body>

</html>
