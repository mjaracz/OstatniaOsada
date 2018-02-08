<?php

	session_start();

	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	}

?>

<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Trirong" rel="stylesheet">
	<title>Ostatnia osada - gra przeglądarkowa</title>
	<meta charset="utf-8"/>
	<style type="text/css">
		body 
		{
			background-color: #222222;
			color: white;
			font-family: 'Trirong', serif;
			font-size: 15px;
		}
	</style>


</head>
<body>

<?php
 
	echo "<div id='gra'><p>Witaj ".$_SESSION['user'].'! <a href="logout.php" style = "color: white; cursor: pointer; float: right;">Wyloguj się!</a>'."</p>";

	if (isset($_SESSION['mission_complite']))
	{
		echo '<div id="error">'.$_SESSION['mission_complite'].'</div><br/>';
	}

	echo "<div id='cytat'><p>Jak długo (…) miłośnicy mądrości nie będą mieli w państwach władzy królewskiej (…), tak długo nie ma sposobu, żeby zło ustało.~ Platon,  Księga V</p></div>";
	echo "<b>Drewno:</b> ".$_SESSION['drewno'];
	echo " | <b>Kamień:</b> ".$_SESSION['kamien'];
	echo " | <b>Zboze:</b> ".$_SESSION['zboze']."</p></br></br></div>";


	echo "<p><b>E-mail:</b> ".$_SESSION['email']."</br>";
	echo "<b>Data wygaśniecia premium</b> ".$_SESSION['dnipremium']."</p>";

	$dataczas = new DateTime();
	
	echo 'Aktualna data i czas serwera: ' . $dataczas->format('Y-m-d H:i:s').'</br>';

	
	$koniec = DateTime::createFromFormat('Y-m-d H:i:s', $_SESSION['dnipremium']);

	$roznica = $dataczas->diff($koniec);


	if($dataczas<$koniec)
	{
		echo 'Pozostało premium: '. $roznica->format('%y lat, %m mies, %d dni, %h godz, %i min, %s sek').
		'<span style="color:#bbdefb;"><br/>Konto premium trwa <br/> kolejny cytat dla ciebie:</span>'.'<p><span style="color:#bbdefb;">Ci, którzy z rozumem i z dzielnością nie mają nic wspólnego, a czas spędzają na ucztach i tym podobnych przyjemnościach, i to jest całe ich doświadczenie, ci, zdaje się, zjeżdżają tylko na dół, a stamtąd z powrotem do połowy drogi i na tej przestrzeni plączą się całe życie, a nie mijają tej granicy nigdy i nigdy się nie wznoszą do tego i nie patrzą na to, co jest naprawdę wysoko, i nigdy się istotnie nie napełniają tym co istnieje; więc pewnej a czystej rozkoszy nie kosztują, tylko tak jak bydło patrzą zawsze w dół i schylają się ku ziemi i ku stołom, pasą się tam i parzą, a że każdy z nich chce mieć tego więcej niż inni, więc kopie jeden drugiego i bodzie żelaznymi rogami i kopytami, i zabijają się nawzajem, bo nie napełnili tym, co istnieje, ani tego, co w nich samych jest, ani tego, co w nich jest szczelne.</span></p>';
	}
	else echo 'Premium nie aktywne od: '. $roznica->format('%y lat, %m mies, %d dni, %h godz, %i min, %s sek');
	


?>

</body>
</html> 