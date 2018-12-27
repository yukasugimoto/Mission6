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
		'jpeg' => 'image/jpeg',
		'mp4' => 'video/mp4'
		);
	
//データベース接続
$dsn  =  '';
$user  =  '';
$password  =  '';
try{
	$pdo = new PDO($dsn,$user,$password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	array(PDO::ATTR_EMULATE_PREPARES => false);
	

	$sql = "SELECT * FROM Review WHERE image = :target;";
	$stmt = $pdo->prepare($sql);
    $stmt -> bindValue(":target", $target, PDO::PARAM_STR);
	$stmt -> execute();
	$row = $stmt -> fetch(PDO::FETCH_ASSOC);
	header("Content-Type: ".$mime[$row["ext"]] );
	echo ($row["raw_data"]);
	
}
catch (PDOException $e) {
		echo("<p>Error</p>");
		exit($e->getMessage());
}

?>
