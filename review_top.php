<?php
//トップ

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
		<style>
			body{
				background: #ffffff;
				font-family: Meiryo;
			}
			header{
				background: #ffffff;
				text-align: center;
			}
			.main{
				background: #ffffff;
				max-width: 700px;
				padding: 10px;
				padding-bottom: 60px;
				text-align: center;
				border: 1px solid #cccccc;
				margin: 30px auto;
			}
	

			footer{
				font-size: 10px;
				text-align: center;
			}
				
		</style>
	</head>
	<body>
		<header>
			<h1><img src="table/logo.jpg"></h1>
		</header>
		<div class="main">
		<br>
		<h2>旅行の写真をシェアしよう</h2>
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
				<a href="post_view.php?id=<?php echo $row['id']; ?>"><img src="picture.php?target=<?php echo $row['image']; ?>" width="200" height="150"></a>
			<?php elseif($row['ext'] == "mp4" || $row['ext'] == "MP4") : ?>
				<a href="post_view.php?id=<?php echo $row['id']; ?>"><video src="picture.php?target=<?php echo $row['image']; ?>" width="200" height="150"></video></a>
			<?php endif; ?>
		<?php endforeach; ?>
		<br><br>
		過去の投稿を見る<a href="review_log.php">→</a>
		<br><br>
		<br>
		</div>
		<footer>
			<a href="logout.php?logout">ログアウト</a>
			<br>
			Copyright 2018 YS
			<br>
		</footer>
	</body>
</html>
