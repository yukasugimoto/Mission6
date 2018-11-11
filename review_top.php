<?php
session_start();
if(!isset($_SESSION["name"])) {
    $no_login_url = "login.php";
    header("Location: {$no_login_url}");
    exit;
}
		//データベース接続
$dsn  =  '';
$user  =  '';
$password  =  '';
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
		<link rel="stylesheet" href="review_top.css">
	</head>
	<body>
		<h1>旅行の写真をシェアしよう</h1>
		<br>
		投稿は<a href="review_post.php">コチラ</a> <br>
		<br>
		検索したい語句を入力してください <br>
		<form action="search.php" method="post">
			<input type="text" name="search">
			<input type="submit" value="検索">
		</form>
		<br>
		<?php foreach ($result as $row): ?>
			<?php if ($row['ext'] == "jpg" || $row['ext'] == "JPG" || $row['ext'] == "png" || $row['ext'] == "PNG") : ?>
				<a href="post_view.php?id=<?php echo $row['id']; ?>"><img src="picture.php?target=<?php echo $row['image']; ?>"></a>
			<?php elseif($row['ext'] == "mp4" || $row['ext'] == "MP4") : ?>
				<a href="post_view.php?id=<?php echo $row['id']; ?>"><video src="picture.php?target=<?php echo $row['image']; ?>" width="200" height="150"></video></a>
			<?php endif; ?>
		<?php endforeach; ?>
		<br><br>
		<a href="logout.php?logout">ログアウト</a>
		<br>

	</body>
</html>