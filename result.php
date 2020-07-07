<?php
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="ie=edge" />
		<link rel="stylesheet" href="style.css" type="text/css" />
		<title>Kvitton</title>
	</head>
	<body>
		<div class="box">
			<?php
			echo '<p>Har du bakgrund med på bilden så kan du klippa om bilden här.</p>';
			foreach($_SESSION['files'] as $value){
				if(strtolower(end(explode('.', $value)))!="pdf") echo '<a href="resize.php?file='.$value.'">Klipp om '.$value.'</a><br />';
			}
			echo '<br /><a href="'.$_SESSION['link'].'" download>Klicka sen här för kolla så dokumentet blev bra.</a><br /><br /><a href="mail.php">Klicka sen här för att skicka in filen till kansliet om du är nöjd med den.</a>';
			?>
		</div>
	</body>
</html>