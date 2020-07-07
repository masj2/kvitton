<?php
require_once('init.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="ie=edge" />
		<link rel="stylesheet" href="style.css" type="text/css" />
		<title>Kvitton</title>
		<script src="cropper.js"></script>
		<script src="resize.js"></script>
	</head>
	<body>
		<div class="box">
			<p>Vi sparar endast Sessionscookies för att webbplatsen ska fungera. Vi gillar öppenhet och därför listas här nedan exakt all information som finns från denna webbplats på din dator.</p>
			<?php
			htmlentities(print_r($_SESSION));
			?>
		</div>
	</body>
</html>