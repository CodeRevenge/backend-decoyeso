<?php
	include("./functions.php");
	try {
		// Recibiendo valores
		$token = $_SERVER['HTTP_TOKEN'] ?? "";

		echo json_encode(verifyRole($token));
	}catch(Exception $e) {
		$json = new stdClass;
		$json->status = "TOKEN_ERROR";
		$json->eMessage = $e->getMessage();
		echo json_encode($json);
	}
	
?>