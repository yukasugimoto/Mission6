<?php
session_start();
if(!isset($_SESSION["name"])) {
    $no_login_url = "login.php";
    header("Location: {$no_login_url}");
    exit;
}
//定義
$name = ( isset( $_POST['name'] ) === true ) ?$_POST['name']: "";
$place = ( isset( $_POST['place'] ) === true ) ?$_POST['place']: "";
$post = ( isset ( $_POST['post'] ) === true ) ?$_POST['post']: "";
//$image = $_POST['image'];
//$image = ( isset( $_POST['image'] ) === true ) ?$_POST['image']: "";
$pass = ( isset( $_POST['pass'] ) === true ) ?$_POST['pass']: "";
$date = date("Y/m/d H:i:s");
$errmsgn="";
$errmsgp="";
$errmsgo="";
$errmsga="";
$er1="";
$er2="";
$er3="";
//日本語を省くための正規表現
$pattern="/^[a-z0-9A-Z\-_]+\.[a-zA-Z]{3}$/";
$RegisterMessage="";
$err_msg4="";

//edit
//該当する投稿内容を編集画面に反映させる
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

if( isset($_GET['id'] )){
	$post_id = ($_GET['id']);
	$stmt = $pdo->prepare('SELECT * FROM Review WHERE id=?');
	$params[ ] = $post_id;
	$stmt->execute($params);
	$result = $stmt->fetch(PDO::FETCH_ASSOC);


if( isset( $_POST['submit'])=== true ){
	if( $name === "") $errmsgn="名前を入力してください";
	if( $place === "") $errmsgp="場所を入力してください";
	if( $post === "") $errmsgo="内容を入力してください";
	if( $pass === "") $errmsga="パスワードを入力してください";
	if( $errmsgn === "" && $errmsgp === "" && $errmsgo === ""  && $errmsga === "" ){
		if( isset($_FILES)){
			$image=$_FILES["image"]["name"];
			if(!empty ($image)){
				//ファイル名エラー
				//if(!preg_match($pattern, $image)){
					//$er1="ファイル名に日本語は使用できません。";
				//}
				//拡張子エラー
				$ext=substr($image,-3);	
				strtolower('jpg') == strtolower('JPG');
				strtolower('png') == strtolower	('PNG');
				strtolower('mp4') == strtolower('MP4');
				if($ext!="jpg" && $ext!="png" && $ext!="JPG" && $ext!="PNG" &&  $ext!="mp4" && $ext!="MP4"){
					$er2="対応ファイルではありません。";
				}
				if( $er1 === "" && $er2 === "" ) {
					//画像をバイナリデータに
					$raw_data = file_get_contents($_FILES["image"]["tmp_name"]);
					
					//DBに格納するファイル名
					$imname = $_FILES["image"]["tmp_name"].date("Ymd-His");
					
					//DBを更新
					$sql = "UPDATE Review set name = :name , date = :date , place = :place , post = :post , ext = :ext , image = :image , raw_data = :raw_data ,  pass = :pass WHERE id = '$post_id' ";
					$stmt = $pdo->prepare($sql);
					$stmt -> bindParam(':name', $name, PDO::PARAM_STR);
					$stmt -> bindParam(':date', $date, PDO::PARAM_STR);
					$stmt -> bindParam(':place', $place, PDO::PARAM_STR);
					$stmt -> bindParam(':post', $post, PDO::PARAM_STR);
					$stmt -> bindParam(':image', $imname, PDO::PARAM_STR);
					$stmt -> bindParam(':ext', $ext, PDO::PARAM_STR);
					$stmt -> bindParam(':raw_data', $raw_data, PDO::PARAM_STR);
					$stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
					$stmt -> execute();

					$message = "投稿が編集されました。";
				}
			}
			else{
				//そのままDBをアップデート！
				$sql2 = "UPDATE Review set name='$name' , date='$date' , place='$place' , post='$post' , pass = '$pass' WHERE id = '$post_id' ";
			$result2 = $pdo->query($sql2);
			$message = "投稿が編集されました。";
			}
		}
	}
}
}

?>

<!DOCTYPE HTML>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<title>Review post</title>
	</head>
	<body>
		<h1>投稿を編集する</h1>
		<br>
		<?php echo $message; ?> <br>
		<a href="post_view.php?id=<?php echo $result['id']; ?>">戻る</a>
		<a href="review_top.php">投稿一覧へ</a>
		<form action="" method="post" enctype="multipart/form-data">
			名前
			<input type="text" name="name" size="20" value="<?php echo $result['name']; ?>">
			<?php echo $errmsgn; ?>
			<br>
			場所
			<input type="text" name="place" size="20" value="<?php echo $result['place']; ?>">
			<?php echo $errmsgp; ?>
			<br><br>
			写真を投稿する ※対応ファイル(jpg,png,mp4)<br>
			<input type="file" name="image" size="30">
			<br>
			<?php echo $er1; ?>
			<?php echo $er2; ?>
			<br>
			どんな写真ですか？<br>
			<textarea name="post" rows="20" cols="60"><?php echo $result['post']; ?></textarea>
			<br>
			<?php echo $errmsgo; ?>

			<br><br>
			パスワード（編集、削除時に使用します）<br>
			<input type="text" name="pass" size="20" value="<?php echo $result['pass']; ?>">
			<?php echo $errmsga; ?>
			<br>
			<input type="submit" name="submit" value="編集">
			<br><br>
		</form>
	</body>
</html>