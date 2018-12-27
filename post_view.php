<?php
//投稿のページ

//セッション
session_start();
if(!isset($_SESSION["name"])) {
	$no_login_url = "login.php";
	header("Location: {$no_login_url}");
	exit;
}


//
$er = "";

//データベース接続
$dsn  =  '';
$user  =  '';
$password  =  '';
try{
	$pdo = new PDO($dsn,$user,$password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	array(PDO::ATTR_EMULATE_PREPARES => false);
		}
		catch (PDOException $e) {
		echo("<p>Error</p>");
		exit($e->getMessage());
}
	
	$sql = "SELECT * FROM Review WHERE image = :target;";
	$stmt = $pdo->prepare($sql);
   	$stmt -> bindValue(":target", $target, PDO::PARAM_STR);
	$stmt -> execute();
	$row = $stmt -> fetch(PDO::FETCH_ASSOC);
	header("Content-Type: ".$mime[$row["ext"]] );
	$picture = $row["raw_data"];
	

if( isset($_GET['id'])){
	$post_id = ($_GET['id']);
	$stmt = $pdo->prepare('SELECT * FROM Review WHERE id=?');
	$params[ ] = $post_id;
	$stmt->execute($params);
	$result  = $stmt->fetch(PDO::FETCH_ASSOC);

	//おすすめ評価
	if( $result['star'] === "1" ) { $star = "★☆☆☆☆" ;}
	if( $result['star'] === "2" ) { $star = "★★☆☆☆" ;}
	if( $result['star'] === "3" ) { $star = "★★★☆☆" ;}
	if( $result['star'] === "4" ) { $star = "★★★★☆" ;}
	if( $result['star'] === "5" ) { $star = "★★★★★" ;}

$pass = $_POST['pass'];
if( isset($_POST['edit'])){
	$sql = "SELECT * FROM Review WHERE id=".$post_id;
	$result2 = $pdo->query($sql);
	foreach($result2 as $row2){
		if($row2["pass"] === $pass){
			$er = "おっけー";
			header(sprintf("Location: post_edit.php?id=%s", urlencode($post_id)));
			exit( );
		}
		else{
			$er = "パスワードが違います。";
		}
	}
}
if( isset($_POST['delete'])){
	$sql3 = "SELECT * FROM Review WHERE id=".$post_id;
	$result3 = $pdo->query($sql3);
	foreach($result3 as $row3){
		if($row3["pass"] === $pass){
			$sql = "DELETE from Review WHERE id =".$post_id;
			$result = $pdo->query($sql);
			header(sprintf("Location: post_delete.php?id=%s", urlencode($post_id)));
			exit( );
		}
		else{
			$er = "パスワードが違います。";
		}
	}
}
}

?>

<!DOCTYPE HTML>
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Review post</title>
		<style>
			body{
				background: #ffffff;
			}
			header{
				text-align: center;			
			}
			.main{
				background: #ffffff;
				max-width: 700px;
				padding: 10px;
				padding-bottom:60px;
				text-align: center;
				border: 1px solid #cccccc;
				margin: 30px auto;
			}
			.text{
				display: inline-block;
				text-align: left;
			}
			img{
				width: 40%;
			}
			footer{
				text-align: center;
			}
			.foot{
				font-size: 10px;
			}
			
		</style>

	</head>
	<body>
		<header>
			<h1><img src="table/logo.jpg"></h1>
		</header>
		<div class="main">
		<br>
		<br>
		<?php if($result['ext'] == "jpg" || $result['ext'] == "JPG" || $result['ext'] == "png" || $result['ext'] == "PNG") : ?>
			<img src="picture.php?target=<?php echo $result['image']; ?>" width="50%">
		<?php elseif($result['ext'] == "mp4" || $result['ext'] == "MP4") : ?>
			<video src="picture.php?target=<?php echo $result['image']; ?>" width="50%" controls></video>
		<?php endif; ?>
		<br>
		<p class="text">
		日時：<?php echo $result['date']; ?> <br>
		名前：<?php echo $result['name']; ?> <br>
		場所：<?php echo $result['place']; ?> <br>
		<?php echo $star; ?> <br><br>
		<?php echo nl2br($result['post']); ?> <br>
		<br><br>
		</p>
		<form action="" method="post">
			<input type="password" name="pass" size="15" placeholder="パスワード">
			<input type="submit" name="edit" value="編集">
			<input type="submit" name="delete" value="削除">
		</form>
		<br>
		<?php echo $er; ?>
		<br>
		</div>
		<footer>
			<a href="review_top.php">戻る</a>
			<br>
			<p class="foot">Copyright 2018 YS</p>
			<br>
		</footer>
	</body>
</html>
