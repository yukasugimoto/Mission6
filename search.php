<?php

header("Content-type: text/html; charset=utf-8");

if(empty($_POST)){
	header("Location: review_top.php");
	exit();
}else{
	if(!isset($_POST['search']) || $_POST['search'] === "" ) {
		$errors['search'] = "入力されていません。";
	}
}


if(count($erros) === 0){
	//データベース接続
$dsn  =  '';
$user  =  '';
$password  =  '';
	try{
		$pdo  =  new  PDO($dsn,$user,$password);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		array(PDO::ATTR_EMULATE_PREPARES => false);
		
			//検索！
			$sql = "SELECT * FROM Review WHERE post LIKE (:post) OR place LIKE (:place)";
			$stmt = $pdo->prepare($sql);
			if($stmt){
				$search = $_POST['search'];
				$like = '%'.$search.'%';
				$stmt->bindParam(':post' , $like, PDO::PARAM_STR);
				$stmt->bindParam(':place' , $like, PDO::PARAM_STR);
				if($stmt->execute()) {
					$row_count = $stmt->rowCount();
					$result = $stmt->fetchAll();
				}
				else{
					$errors['error'] = "検索失敗です。";
				}
				$pdo = null;
			}	
		
	} catch (PDOException $e) {
		echo("<p>Error</p>");
		exit($e->getMessage());
	}
}


?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<title>検索結果</title>
	</head>
	<body>
		<?php echo $search; ?>の検索結果 <?php echo $row_count; ?>件 <br><br>
		<?php if(count($errors) === 0): ?>
				<?php foreach($result as $row): ?>
					投稿者：<?php echo $row['name']." ".$row['date']; ?> <a href="post_view.php?id=<?php echo $row['id']; ?>">投稿を見る</a>
					<br><br>
				<?php endforeach; ?>
		<?php elseif(count($errors) > 0): ?>
			<?php foreach($errors as $value): ?>
				<?php echo $value; ?>
			<?php endforeach; ?>
		<?php endif; ?>

	</body>
</html>