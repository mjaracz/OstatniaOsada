<?php
	session_start();
 

	if (isset($_POST['email']))
	{
		//Uadana walidacja..Bo zoastała zarezerowaowana pamieć na ziemenne $_POST
		$czy_dodamy=true;

		//wszystko co z nickim...
		// sprawdzenie długości nicka, czy ma te minumum 3 a max 20 znaków, wbu. funkcja strlen nazwa od string lenght
		

		$nick = $_POST['Nickname'];

		$dlugosc_nick = strlen($nick);

		if (($dlugosc_nick<3) || ($dlugosc_nick>20))
		{
			$czy_dodamy = false;
			$_SESSION['blad_nick'] ="Twoja nazwa musi posiadać od 3 do 20 znaków.";
		}

		// funkcja ctype_alnum sprawdza czy wszystkie znaki w łancuchu są alfanumeryczne
		if (ctype_alnum($nick)!=true)
		{
			$czy_dodamy=false;
			$_SESSION['blad_nick']="Twoja unikalna nazwa musi zawnierać tylko znaki alfanumeryczne..";

		}

		// zabieramy się za email.. czy ma małpe itd..

		//sanityzacja danych- termin ten oznacza wyczyszczenie kodu z potencialnie groźnych zapisów
		//zmienna email_B rezerwuje miejsce w pamieci dla przefiltraowanego adresu email na pewno bespieczniego
		/* funkcja filter_var(zmienna, filtr) przeszuka, przefiltruje zmienna W poszukiwaniu określnego zbioru znaków (drugi arg.) */
		// FILTER_SANITIZE_EMAIL "usuwa" znakie nie mogące być w mailu np zamienia paweł@gmail.com na pawe@gmail.com, jednocześnie sprawdzając pod kątem alfanumeryczności
		
		$email = $_POST['email'];
		$email_B = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if ((filter_var($email_B, FILTER_VALIDATE_EMAIL)!=true) || ($email_B!=$email))
		{
			$czy_dodamy=false;
			$_SESSION['blad_email']="Podany przez ciebie email, powinnien posiadać coś jeszcze..(małpe tym podobne)... Lub po porstu <i>znaki alfanumeryczne</i>  nie dogadują się z twoim mailem.. one kochają dominować w mailach..";
		}



		// teraz pora na... password..
		$haslo1 = $_POST['haslo1'];	
		$haslo2 = $_POST['haslo2'];
	
		if ((strlen($haslo1)<8) || (strlen($haslo1)>20))
		{
			$czy_dodamy = false;
			$_SESSION['blad_pass'] ="Twoja hasło musi posiadać od 8 do 20 znaków.";
		}

		if ($haslo1!=$haslo2)
		{
			$czy_dodamy = false;
			$_SESSION['blad_pass']="Wpisz.. wiesz co, takie samo hasło w obydwu polach!!!";			

		}

		$haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);

			
		// czy zaakceptowano regulamin
		if (!isset($_POST['regulamin']))
		{
			$czy_dodamy = false;
			$_SESSION['blad_regu']="Konieczne jest zaakceptowanie regulaminu naszej witryny!!";			
		}

		//recaptcha .. sprawdzmy czy urzytkownik nie jest bootem
		$sekret='6LdppCEUAAAAAM2nfwzWfERIBxEPK2tJLMqWZjmt';

		$sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);

		$odzpowiedz= json_decode($sprawdz);

		if (!($odzpowiedz->success))
		{
			$czy_dodamy = false;
			$_SESSION['blad_recaptcha']="Wychodzi na to że.. jesteś bootem, przestań nim być- parszywie udając człowieka, to wtedy pogadamy !!! Lub po prostu zaznacza co trzeba.";

		}


		//zapamiętanie danych rejestracji
		$_SESSION['z_nick'] = $nick;
		$_SESSION['z_email'] = $email;
		$_SESSION['z_haslo'] = $haslo1;
		if (isset($_POST['regulamin'])) $_SESSION['z_regulamin'] = true;

		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);

		try 
		{
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				//czy email istnieje

				$rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'");
				if (!$rezultat) throw new Exception($polaczenie->error);


				$ile_takich_maili = $rezultat->num_rows;
				if ($ile_takich_maili>0)
				{
					$czy_dodamy = false;
					$_SESSION['blad_email']="Do podanego przez ciebie adresu email zostało już przpisane kąto";					
				}

				//czy nick jest rzeczywiscie uniklany^^?

				$rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE user='$nick'");
				if (!$rezultat) throw new Exception($polaczenie->error);


				$ile_takich_nickow = $rezultat->num_rows;
				if ($ile_takich_nickow>0)
				{
					$czy_dodamy = false;
					$_SESSION['blad_nick']="Już istnieje podany przez ciebie nick";					
				}

				if ($czy_dodamy==true)
				{
					//Jupijej udało się.. dodajemy gracza do bazy

					if ($polaczenie->query("INSERT INTO uzytkownicy VALUES (NULL, '$nick', '$haslo_hash', '$email', 100, 100, 100, now() + INTERVAL 14 DAY)"))
					{
						$_SESSION['udana_rej']= true;
						header('Location: witamy.php');
					}
					else
					{ 
						throw new Exception($polaczenie->error);
					}

					$dataczas = new DateTime();
					
				}


				$polaczenie->close();

			}

		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Serwer się zjebał!! Wybaczcie te drobne niedogodności,  zarejestruj się w innym terminie.</span>';
			echo '<br/> Info. dla wtajemniczonych: '.$e;
		}
	}
?>


<!DOCTYPE html>
<html>
<head>

	<script src='https://www.google.com/recaptcha/api.js'></script>
	<link href="https://fonts.googleapis.com/css?family=Trirong" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
	
	<title>Utwórz kąto</title>
	<meta charset="utf-8"/>

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

	<header>
	<h1>Najwanijeszy w każdym działaniu jest początek ~ Platon</h1>
	</header>

	<form method="post">

	<div id="form">
		<br/><input type="text" name="Nickname" placeholder="nickname: " value="<?php

		if (isset($_SESSION['z_regulamin']))
		{
			echo $_SESSION['z_nick'];
		}

		?>"/><br/>
		
			<?php
				if (isset($_SESSION['blad_nick']))
				{
					echo '<div class="error">'.$_SESSION['blad_nick'].'</div>';
					unset($_SESSION['blad_nick']);
				} 
			?>

		<input type="text" name="email" placeholder="email: " value="<?php

		if (isset($_SESSION['z_regulamin']))
		{
			echo $_SESSION['z_email'];
		}

		?>" />

			<?php 
				if (isset($_SESSION['blad_email']))
					{
						echo '<div class="error">'.$_SESSION['blad_email'].'</div>';
						unset($_SESSION['blad_email']);
					}
			?>

		<input type="password" name="haslo1" placeholder="hasło: " value="<?php

		if (isset($_SESSION['z_regulamin']))
		{
			echo $_SESSION['z_haslo'];
		}

		?>" />

			<?php 
				if (isset($_SESSION['blad_pass']))
					{
						echo '<div class="error">'.$_SESSION['blad_pass'].'</div>';
						unset($_SESSION['blad_pass']);
					}
			?>

		<input type="password" name="haslo2" placeholder="powtórz! " value="<?php

		if (isset($_SESSION['z_regulamin']))
		{
			echo $_SESSION['z_haslo'];
		}

		?>"/><br/>

		<label class="regulamin">
		<input type="checkbox" name="regulamin"/> Akceptuje regulamin
		</label>

			<?php 
				if (isset($_SESSION['blad_regu']))
					{
						echo '<div class="error">'.$_SESSION['blad_regu'].'</div>';
						unset($_SESSION['blad_regu']);
					}
			?>

		<div class="g-recaptcha" data-sitekey="6LdppCEUAAAAANYZf19Sw8eNaMZSORj4J0ZfZ5bS"></div>
		
			<?php 
				if (isset($_SESSION['blad_recaptcha']))
				{
					echo '<div class="error">'.$_SESSION['blad_recaptcha'].'</div>';
					unset($_SESSION['blad_recaptcha']);
				}
			?>		

		<input type="submit" value="Zarejestruj się"/>
	</div>
	</form>

</body>
</html>