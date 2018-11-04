<?php
session_start();
if(!isset($_SESSION["name"])) {
    $no_login_url = "login.php";
    header("Location: {$no_login_url}");
    exit;
}
		//データベース接続
		$dsn = '';
		$user = '';
		$password = '';
		try{
			$pdo  =  new  PDO($dsn,$user,$password);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			array(PDO::ATTR_EMULATE_PREPARES => false);
		} catch (PDOException $e) {
			echo("<p>Error</p>");
			exit($e->getMessage());
		}
		
		$sql = "SELECT * FROM Review ORDER BY id DESC;";
		$stmt = $pdo->prepare($sql);
		$stmt -> execute();
		$result = $stmt -> fetchAll( );

?>

<!DOCTYPE HTML>
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Review top</title>
	</head>
	<body>
		<h1>旅行の写真をシェアしよう</h1>
		<br>
		投稿は<a href="review_post.php">コチラ</a> <br>
		<?php foreach ($result as $row): ?>
			<a href="post_view.php?id=<?php echo $row['id']; ?>"><img src="picture.php?target=<?php echo $row['image']; ?>", width="30%">
			<br><br>
		<?php endforeach; ?>
		<a href="logout.php?logout">ログアウト</a>


	</body>
</html>
