<?php
//セッション
session_start();
if(!isset($_SESSION["name"])) {
	$no_login_url = "login.php";
	header("Location: {$no_login_url}");
	exit;
}
	//review_top.phpから
	if(isset($_GET["target"]) && $_GET["target"] !== ""){
		$target = $_GET["target"];
	}
	else{
		header("Location: review_top.php");
	}
	
	//mimetypes
	$mime = array(
		'png' => 'image/png',
		'jpeg' => 'image/jpeg'
		);
	
//データベース接続
$dsn  =  'mysql:dbname=tt_463_99sv_coco_com;host=localhost;charset=utf8';
$user  =  'tt-463.99sv-coco';
$password  =  'Rk8FEYJA';
try{
	$pdo = new PDO($dsn,$user,$password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	array(PDO::ATTR_EMULATE_PREPARES => false);
	
//	$id = $_GET['id'];
	$sql = "SELECT * FROM Review WHERE image = :target;";
	$stmt = $pdo->prepare($sql);
    $stmt -> bindValue(":target", $target, PDO::PARAM_STR);
	$stmt -> execute();
	$row = $stmt -> fetch(PDO::FETCH_ASSOC);
	header("Content-Type: ".$mime[$row["ext"]] );
	echo ($row["raw_data"]);
	
	
//$ext = $row["ext"];
//$orifile = $row["raw_data"];
//list($oriwidth, $oriheight) = getimagesize($orifile);
//$thuwidth = 200;
//$thuheight = round( $oriheight * $thuwidth / $oriwidth);
//$oriimage = imagecreatefrompng($orifile);
//$thuimage = imagecreatetruecolor($thuwidth, $thuheight);
//imagecopyresized($thuimage, $oriimage, 0, 0, 0, 0,
//$thuwidth, $thuheight,
//$oriwidth, $oriheight);
//imagepng($thuimage);
//imagedestroy($oriimage);
//imagedestroy($thumimage);

}
catch (PDOException $e) {
		echo("<p>Error</p>");
		exit($e->getMessage());
}



?>
