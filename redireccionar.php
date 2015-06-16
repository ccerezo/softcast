<?php
	session_start();
	
	$servidor = "http://localhost/softcast";
	
	if(!isset($_SESSION['usuario']))
		header("Location: ".$servidor);
?>