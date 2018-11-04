<?php
//review_top.phpから画像をクリックするとここへとぶ。該当の投稿が表示される。

//セッション
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

}

?>

<!DOCTYPE HTML>
<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Review post</title>
	</head>
	<body>
		<h1>投稿内容</h1>
		<br>
		<img src="picture.php?target=<?php echo $result['image']; ?>", width="30%"> <br>
		名前：<?php echo $result['name']; ?> <br>
		場所：<?php echo $result['place']; ?> <br>
		<?php echo $result['post']; ?> <br>

		<br>
		
	</body>
</html>
