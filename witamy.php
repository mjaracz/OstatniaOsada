<?php
	
	session_start();

	 if (!isset($_SESSION['udana_rej'])) 
	 {
	 	header('Location: index.php');
	 	exit();
	 }
	 else
	 {
	 	unset($_SESSION['udana_rej']);
	 }

	 //usuwanie zapmiętanych danych
	 if (isset($_SESSION['z_nick'])) unset($_SESSION['z_nick']);
	 if (isset($_SESSION['z_email'])) unset($_SESSION['z_email']);
	 if (isset($_SESSION['z_haslo'])) unset($_SESSION['z_haslo']);
	 if (isset($_SESSION['z_regulamin'])) unset($_SESSION['z_regulamin']);

	 //usuwanie informacji o błędach
	 if (isset($_SESSION['blad_nick'])) unset($_SESSION['b_nick']);
	 if (isset($_SESSION['blad_email'])) unset($_SESSION['b_email']);
	 if (isset($_SESSION['blad_pass'])) unset($_SESSION['blad_pass']);
	 if (isset($_SESSION['blad_regu'])) unset($_SESSION['blad_regu']);
	 if (isset($_SESSION['blad_recaptcha'])) unset($_SESSION['blad_recaptcha']);
	 


?>

<!DOCTYPE html>
<html>
<head>
	<title>Nowy osadnik</title>
	<meta charset='utf-8'/>
	<style type="text/css">
		<style type="text/css">
		body
		{
			background-color: #222222;
			color: white;
			font-family: 'Trirong', serif;
			font-size: 15px;
		}

		#rejestracja
		{

			width: 320px;
			float: left;

		}
		.error
		{
			color: #ef9a9a;
			margin-top: 10px;
			margin-bottom: 10px;
			font-size: 12px;
			font-family: 'Trirong', serif;
			
		}
	</style>
</head>
<body>
	<p>Witaj nowy Odkrywco, waleczny mężu!</p>
	<a href="index.php">Wbij do swojej osady! Zaloguj się na swoje kąto!!</a>
	<br/><br/>

</body>
</html>