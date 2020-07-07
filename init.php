<?php
session_start();
$mapp="kvitton/";
$mapp_u="uploads/";
$mapp_p="pdf/";
$mapp_r="res/";
$mapp_c="cut/";
$mapp_pc="precut/";
//Dessa 6 mappar ovan måste finnas i dokumentrooten för hemsidan.
$url = "https://kvitton.ungpirat.se/"; //Ange exakt länk till hemsidan
$extensions = ['jpg', 'jpeg', 'JPG', 'JPEG', 'png', 'PNG', 'gif', 'pdf'];
$extensions_jpg = ['jpg', 'jpeg', 'JPG', 'JPEG'];
$date_str = date("Y-m-d_H-i-s",time());//Filnamnets datumstämpel, filnamn måste vara unik för att inte skriva över andra filer.

function safe_input($name){
	$name = str_replace(' ', '_', $name);
	$name = str_replace('ö', 'o', $name);
	$name = str_replace('Ö', 'O', $name);
	$name = str_replace('å', 'a', $name);
	$name = str_replace('Å', 'A', $name);
	$name = str_replace('ä', 'a', $name);
	$name = str_replace('Ä', 'A', $name);
	$name = preg_replace('/[^ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789\-\._]/', '', $name);
	return $name;
}
function safe_input_numbers($nr){
	$nr = preg_replace("/[^0-9]/", "", $nr);
	if($nr=="")$nr=0;
	return $nr;
}
function clean_input($name){
	$name = str_replace('<', '', $name);
	$name = str_replace('>', '', $name);
	$name = str_replace('?', '', $name);
	$name = str_replace('$', '', $name);
	$name = str_replace('script', '', $name);
	return $name;
}

function resize($sourceImage, $targetImage, $newWidth){
    list($origWidth, $origHeight) = getimagesize($sourceImage);
	
	$ratio = $newWidth / $origWidth;
    $newHeight = (int)$origHeight * $ratio;
    $newImage = imagecreatetruecolor($newWidth, $newHeight);
	$image = @imagecreatefromjpeg($sourceImage);
	imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
	if($newHeight<$newWidth) $newImage = imagerotate($newImage, 270, 0);
	if(imagejpeg($newImage,$targetImage))
	{
		imagedestroy($image);
		imagedestroy($newImage);
	}   
}