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
$image = ( isset( $_POST['image'] ) === true ) ?$_POST['image']: "";
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

//データベース接続
$dsn  =  '';
$user  =  '';
$password  =  '';
try{
	$pdo  =  new  PDO($dsn,$user,$password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	array(PDO::ATTR_EMULATE_PREPARES => false);
} catch (PDOException $e) {
 exit('データベース接続失敗。'.$e->getMessage());
}

//テーブルの中身を確認するコマンドを使って、意図した内容が作成されているか確認する
//$sql ='SHOW CREATE TABLE Review';
//$result = $pdo -> query($sql);
//foreach ($result as $row){
//	print_r($row);
//	}
//echo "<hr>";



//各項目エラーチェック→投稿完了
if( isset($_POST['submit']) === true) {
	if( $name === "") $errmsgn="名前を入力してください";
	if( $place === "") $errmsgp="場所を入力してください";
	if( $post === "") $errmsgo="内容を入力してください";
	if( $pass === "") $errmsga="パスワードを入力してください";
	if( $errmsgn === "" && $errmsgp === "" && $errmsgo === ""  && $errmsga === "" ){
		if ( isset($image)){
			$image=$_FILES["image"]["name"];
			if(!empty ($image)){
				//ファイル名えらー
				//if(!preg_match($pattern, $image)){
					//$er1="ファイル名に日本語は使用できません。";
				//}
				//拡張子えらー
				$ext=substr($image,-3);	
				strtolower('jpg') == strtolower('JPG');
				strtolower('png') == strtolower	('PNG');
				strtolower('mp4') == strtolower('MP4');
				if($ext!="jpg" && $ext!="png" && $ext!="JPG" && $ext!="PNG" &&  $ext!="mp4" && $ext!="MP4"){
					$er2="対応ファイルではありません。";
				}
			if( $er1 === "" && $er2 === "" && $er3 === ""){
				//画像をバイナリデータに
				$raw_data = file_get_contents($_FILES["image"]["tmp_name"]);
				
				//DBに格納するファイル名	
				$imname = $_FILES["image"]["tmp_name"].date("Ymd-His");

		
			//DBに格納
			$sql = $pdo -> prepare("INSERT INTO Review (id, name, date, place, post, image, ext, raw_data, pass) VALUES(:id, :name, :date, :place, :post, :image, :ext, :raw_data, :pass)" );
//			$image="images";
//			$ext="ext";
//			$raw_data="raw_data";
			$sql -> bindParam(':id', $id, PDO::PARAM_STR);
			$sql -> bindParam(':name', $name, PDO::PARAM_STR);
			$sql -> bindParam(':date', $date, PDO::PARAM_STR);
			$sql -> bindParam(':place', $place, PDO::PARAM_STR);
			$sql -> bindParam(':post', $post, PDO::PARAM_STR);
			$sql -> bindParam(':image', $imname, PDO::PARAM_STR);
			$sql -> bindParam(':ext', $ext, PDO::PARAM_STR);
			$sql -> bindParam(':raw_data', $raw_data, PDO::PARAM_STR);
			$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
			$sql -> execute();
			$message="投稿完了したよ！"; 
			
		//INSERTできたか確認する
//		$check=$sql->execute();
//		var_dump($check);
//		if($check){
//			$RegisterMessage = "登録が完了しました。";
		}
		else{
			$err_msg4= "登録に失敗しました";
		}
	
//			}
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
		<h1>旅行の写真をシェアしよう</h1>
		<br>
		<?php echo $message; ?> <br>
		<a href="review_top.php">投稿一覧へ</a>
		<?php echo $err_msg4; ?>
		<form action="" method="post" enctype="multipart/form-data">
			名前
			<input type="text" name="name" size="20" value="<?php echo $_SESSION["name"]; ?>">
			<?php echo $errmsgn; ?>
			<br>
			場所
			<input type="text" name="place" size="20" value="<?php echo $place; ?>">
			<?php echo $errmsgp; ?>
			<br><br>
			写真を投稿する ※対応ファイル(jpg,png,mp4)<br>
			<input type="file" name="image" size="30">
			<br>
			<?php echo $er1; ?>
			<?php echo $er2; ?>
			<br>
			どんな写真ですか？<br>
			<textarea name="post" rows="20" cols="60" value="<?php echo $post; ?>"></textarea>
			<br>
			<?php echo $errmsgo; ?>

			<br><br>
			パスワード（編集、削除時に使用します）<br>
			<input type="text" name="pass" size="20">
			<?php echo $errmsga; ?>
			<br>
			<input type="submit" name="submit" value="投稿">
			<br><br>
		</form>
	</body>
</html>