<?php

	session_start();

	if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
	{
		header('Location: gra.php');
		exit();
	}
?>

<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Trirong" rel="stylesheet" />
	<link href="style.css" rel="stylesheet" type="text/css" /> 
	
	<title>Ostatnia Osada</title>
	<meta charset="utf-8"/>


</head>
<body>

	<div id="container">
		<header>
			<h1>
				Tylko martwi ujrzeli koniec wojny ~ Platon
			</h1>
		</header>

		<h2>
			<p>Nie masz jeszcze swojej <i>Osady</i>, <a href="rejestracja.php" style="color: white;">utwórz konto</a></p>
		</h2>


		<div id="form">
			<form action="zaloguj.php" method="post">

				<input type="text" name="login" placeholder="Login: " onfocus="this.placeholder=''" onblur="this.placeholder='Login: '">
				<input type="password" name="haslo" placeholder="Hasło: " onfocus="this.placeholder=''" onblur="this.placeholder='Hasło: '">
				<input type="submit" value="Zaloguj się"/>
				<?php

					if (isset($_SESSION['blad']))	echo $_SESSION['blad'];

					unset($_SESSION['blad']);

				?>

			</form>
		</div>
	</div>

<


</body>
</html>