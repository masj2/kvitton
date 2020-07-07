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
		<form method="post" action="pdf.php" name="sec">
			<p>Markera själva kvittot på bilden och klicka sen på spara för att beskära bilden till rätt storlek.</p>
			<input type="hidden" name="x" id="x" value="">
			<input type="hidden" name="y" id="y" value="">
			<input type="hidden" name="w" id="w" value="">
			<input type="hidden" name="h" id="h" value="">
			<?php
			echo '<input type="hidden" name="f" id="f" value="'.clean_input($_GET["file"]).'">';
			?>
			<input id="saveForm" class="button_text" type="submit" name="submit" value="Spara" />

		</form>
		<?php
		echo '<img src="precut/'.clean_input($_GET["file"]).'" id="croppr"/>';
		?>
		<p id="output"></p>
	</body>
</html>