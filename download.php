<?php
$file = "kvitton/".$_GET['f'].".pdf";
if (strpos($file, '..') !== false) die();
header('Content-type: application/pdf');
header('Content-Disposition: attachment; filename="' . basename($file) . '"');
header('Content-Transfer-Encoding: binary');
readfile($file);
?>