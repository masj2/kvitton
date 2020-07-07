<?php
require_once('init.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['files'])) {
        $errors = [];
        $all_files = count($_FILES['files']['tmp_name']);
		$filesession = array();
        for ($i = 0; $i < $all_files; $i++) {
			$file_name = safe_input($_FILES['files']['name'][$i]);
            $file_tmp = $_FILES['files']['tmp_name'][$i];
            $file_type = $_FILES['files']['type'][$i];
            $file_size_bytes = $_FILES['files']['size'][$i];
            $file_ext = strtolower(end(explode('.', $_FILES['files']['name'][$i])));

            if (!in_array($file_ext, $extensions)) $errors[] = 'Extension not allowed: ' . $file_name . ' ' . $file_type;
            if ($file_size_bytes > 20971520) $errors[] = 'File size exceeds limit: ' . $file_name . ' ' . $file_type;
            if (empty($errors)) {
				if($file_ext!="pdf"){
					if(!in_array($file_ext, $extensions_jpg)){
						//convert png to jpg
						$image = imagecreatefrompng($file_tmp);
						$bg = imagecreatetruecolor(imagesx($image), imagesy($image));
						imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
						imagealphablending($bg, TRUE);
						imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
						imagedestroy($image);
						$file_name = str_replace('.'.$file_ext, '', $file_name).".jpg";
						imagejpeg($bg, $mapp_u.$file_name);
						array_push($filesession, rawurlencode($file_name));
						imagedestroy($bg);
					}else{
						//jpg
						move_uploaded_file($file_tmp, $mapp_u . $file_name);
						array_push($filesession, rawurlencode($file_name));
					}
				}else{
					//pdf
					move_uploaded_file($file_tmp, $mapp_p . $file_name);
					array_push($filesession, rawurlencode($file_name));
				}
            }
        }
        if ($errors) print_r($errors);
		$_SESSION['files'] = $filesession;
	}
}
?>