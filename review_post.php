<?php
//投稿ページ

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
$star = (isset($_POST['star'] ) === true ) ?$_POST['star']: "";
$errmsgn="";
$errmsgp="";
$errmsgo="";
$errmsga="";
$errmsgs="";
$er1="";
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



//各項目エラーチェック→投稿完了
if( isset($_POST['submit']) === true) {
	if( $name === "") $errmsgn="名前を入力してください";
	if( $place === "") $errmsgp="場所を入力してください";
	if( $post === "") $errmsgo="内容を入力してください";
	if( $pass === "") $errmsga="パスワードを入力してください";
	if( $star === "") $errmsgs="おすすめ度を入力してください";
	if( $errmsgn === "" && $errmsgp === "" && $errmsgo === ""  && $errmsga === "" && $errmsgs === ""  ){
		if ( isset($image)){
			$image=$_FILES["image"]["name"];
			if(!empty ($image)){
				//拡張子えらー
				$ext=substr($image,-3);	
				strtolower('jpg') == strtolower('JPG');
				strtolower('png') == strtolower	('PNG');
				strtolower('mp4') == strtolower('MP4');
				if($ext!="jpg" && $ext!="png" && $ext!="JPG" && $ext!="PNG" &&  $ext!="mp4" && $ext!="MP4"){
					$er1="対応ファイルではありません。";
				}
			if( $er1 === ""){
				//画像をバイナリデータに
				$raw_data = file_get_contents($_FILES["image"]["tmp_name"]);
				
				//DBに格納するファイル名	
				$imname = $_FILES["image"]["tmp_name"].date("Ymd-His");

		
			//DBに格納
			$sql = $pdo -> prepare("INSERT INTO Review (id, name, date, place, post, image, ext, raw_data, star, pass) VALUES(:id, :name, :date, :place, :post, :image, :ext, :raw_data, :star, :pass)" );
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
			$sql -> bindParam(':star', $star, PDO::PARAM_STR);
			$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
			$sql -> execute();
			$message="投稿完了したよ！"; 
			
		}
		else{
			$err_msg4= "登録に失敗しました";
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
				padding-bottom: 60px;
				text-align: center;
				border: 1px solid #cccccc;
				margin: 30px auto;
			}
			.mes{
				font-size: 18pt;
				color: #ff0000;
			}
			.text{
				display: inline-block;
				text-align: left;
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
		<br>
		<div class="main">
		<h2>旅行の写真をシェアしよう</h2>
		<br>
		<p class="mes"><?php echo $message; ?> </p><br>
		<a href="review_top.php">投稿一覧へ</a>
		<?php echo $err_msg4; ?>
		<br><br>

		<form action="" method="post" enctype="multipart/form-data">
		<p class="text">
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
			<textarea name="post" rows="20" cols="60"><?php echo $post; ?></textarea>
			<br>
			<?php echo $errmsgo; ?>
			<br>
			おすすめ度
			<select name="star">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			</select>
			<br>
			<?php echo $errmsgs; ?>
			<br>
			パスワード（編集、削除時に使用します）<br>
			<input type="text" name="pass" size="20">
			<?php echo $errmsga; ?>
			<br><br>
			<input type="submit" name="submit" value="投稿">
			<br><br>
			</p>
		</form>
		</div>
		<footer>
			Copyright 2018 YS
		</footer>
	</body>
</html>
