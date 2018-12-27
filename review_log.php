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
		<meta charset=utf-8>
		<title>Review log</title>
		<style>
			body{
				background: #ffffff;
				font-family: Meiryo;
			}
			header{
				text-align: center;
			}
			.main{
				background: #ffffff;
				max-width:700px;
				padding: 10px
				padding-bottom: 60px;
				text-align: center;
				border:1px solid #cccccc;
				margin:30px auto;
			}
			table{
				display: inline-block;
			}
			.foot{
				text-align: center;
				
			}
			footer{
				font-size:10px;
				text-align: center;
			}
		</style>
	</head>
	<body>
		<header>
			<h1><img src="table/logo.jpg"></h1>
		</header>
		<div class="main">
		<h2>いままでの投稿が見られます</h2><br>
		<a href="review_top.php">トップへ戻る</a><br>
		<br>
		<br>
		<table border="1">
			<tr>
				<th>投稿者</th>
				<th>場所</th>
				<th>おすすめ度</th>
				<th>日時</th>
				<th>投稿を見る</th>
			</tr>
			<tr>
				<?php foreach($result as $row): ?>
				<td><?php echo $row['name']; ?></td>
				<td><?php echo $row['place']; ?></td>
				<td><?php echo $row['star']; ?></td>
				<td><?php echo $row['date']; ?></td>
				<td><a href="post_view.php?id=<?php echo $row['id']; ?>">こちら</a></td>
			</tr>
			<?php endforeach; ?>
		</table>
		<br><br>
		</div>
		
	</body>
</html>
