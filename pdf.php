<?php
require_once('init.php');
//die(print_r($_SESSION['files']));
require_once 'dompdf/autoload.inc.php';
require_once('fpdf/fpdf.php');
use setasign\Fpdi\Fpdi;
require_once('FPDI2/src/autoload.php');
$out = "";
$extrapdf = array();
$once=0;

if(isset($_POST['namn'])&&isset($_POST['typ'])&&isset($_POST['beskr'])&&isset($_POST['attest'])&&isset($_POST['belopp'])&&isset($_POST['datum'])){
	$typ = $_POST['typ'];
	$namn= htmlspecialchars(clean_input($_POST['namn']));
	$namn_f = safe_input($namn);
	$beskr= htmlspecialchars(clean_input($_POST['beskr']));
	$attest= htmlspecialchars(clean_input($_POST['attest']));
	$datum= htmlspecialchars(clean_input($_POST['datum']));
	$belopp= htmlspecialchars(clean_input($_POST['belopp']));
	
	if($typ==1) $shortFileName= "Claim_".rawurlencode($namn_f);
	else $shortFileName= "Kortkvitto_".rawurlencode($namn_f);
	$_SESSION['shortFileName']=$shortFileName;
	
	
	if($typ==1) $out .= "<h3>Begäran om ersättning</h3>";
	else $out .= "<h3>Kvitto för köp från förbundets kort</h3>";
	$out .= "<p>Utgiften gäller: ".$beskr.".</p>";
	if($typ==1) $out .= "<p>Utbetalas till <b>".$namn."</b> och godkänd av <b>".$attest."</b></p>";
	else $out .= "<p>Inköp gjort av <b>".$namn."</b> och godkänd av <b>".$attest."</b></p>";
	$out .= "<p>Kvittodatum: <b>".$datum."</b> och belopp: <b>".$belopp."</b></p>";
	if(isset($_POST['bank'])&&isset($_POST['clearing'])&&$_POST['nummer']&&$typ==1) $out .= "<p>Utbetalas till ".htmlspecialchars(clean_input($_POST['bank']))." med clearingnummer ".safe_input_numbers($_POST['clearing'])." och banknummer ".safe_input_numbers($_POST['nummer'])."</p>";
	else if($typ==1) $out .= "<p>Betalas ut till känt konto</p>";
	
	$_SESSION['out'] = $out;
	
	foreach($_SESSION['files'] as $value){
		if(strtolower(end(explode('.', $value)))=="pdf"){
			array_push($extrapdf, $mapp_p.$value);		
		}else{
			$file_raw = $mapp_u.$value;
			$file_pc = $mapp_pc.$value;
			$file_res = $mapp_r.$value;
			resize($file_raw, $file_pc, 600);//600 pixlar sparas för att kunna klippa i för att kunna klippa lossless ner till 50%
			resize($file_raw, $file_res, 300);//300 pixlar motsvarar 79mm print, vilket är normal kvittobredd
			$file_size = getimagesize($file_res);
			if($file_size[1]<1000) $cuts =1;
			else $cuts = round((($file_size[1]/$file_size[0])/sqrt(2))/2);// [1]/[0] = höjd/bredd
			if($cuts<=1){
				if($once==0){
					$ims= 'data:image/png;base64,' . base64_encode(file_get_contents(@$url.$file_res));
					$out .= '<br /><img src="'.$ims.'">';
					$once=1;
				}else{
					$out .= '<br /><img src="'.$url.$file_res.'">';
				}
				$out .= '<br />'.$url.$file_res;
			}else{
				for($imgs=0; $imgs<$cuts; $imgs++){
					$im = imagecreatefromjpeg($file_res);
					$h = floor(imagesy($im)/$cuts);
					if($imgs==($cuts-1)) $h += imagesy($im)%$cuts;
					$im2 = imagecrop($im, ['x' => 0, 'y' => $imgs*$h, 'width' => imagesx($im), 'height' => $h]);
					$fnc = $imgs.'_'.$value;
					if ($im2 !== FALSE) {
						imagejpeg($im2, $mapp_r.$fnc);
						if($once==0){
							$ims= 'data:image/png;base64,' . base64_encode(file_get_contents(@$url.$mapp_r.$fnc));
							$out .= '<br /><img src="'.$ims.'">';
							$once=1;
						}else{
							$out .= '<br /><img src="'.$url.$mapp_r.$fnc.'">';
						}
						$out .= '<br />'.$url.$mapp_r.$fnc;
						imagedestroy($im2);
					}
					imagedestroy($im);
				}
			}
		}
	}
	$shortFileName .= "_".$date_str;
	$_SESSION['link']=$url.$mapp.$shortFileName.".pdf";
	$_SESSION['shortFileName']=$shortFileName;
	
}else if(isset($_POST['x'])&&isset($_POST['y'])&&isset($_POST['w'])&&isset($_POST['h'])&&isset($_POST['f'])&&$_SESSION['out']!=""){//crop
	$x=safe_input_numbers($_POST['x']);
	$y=safe_input_numbers($_POST['y']);
	$w=safe_input_numbers($_POST['w']);
	$h=safe_input_numbers($_POST['h']);
	$f=clean_input($_POST['f']);
	$out = $_SESSION['out'];
	
	$im = imagecreatefromjpeg($mapp_pc . $f);
	$im2 = imagecrop($im, ['x' => $x, 'y' => $y, 'width' => $w, 'height' => $h]);
	if ($im2 !== FALSE) {
		imagejpeg($im2, $mapp_c.$f);
		imagedestroy($im2);
	}
	imagedestroy($im);

	
	foreach($_SESSION['files'] as $value){
		if(strtolower(end(explode('.', $value)))=="pdf"){
			array_push($extrapdf, $mapp_p.$value);		
		}else{
			$file_raw = $mapp_u.$value;
			if (file_exists($mapp_c.$value)) $file_raw = $mapp_c.$value;
			$file_res = $mapp_r.$value;
			
			resize($file_raw, $file_res, 300);
			$file_size = getimagesize($file_res);
			if($file_size[1]<1000) $cuts =1;
			else $cuts = round((($file_size[1]/$file_size[0])/sqrt(2))/2);
			if($cuts<=1){
				if($once==0){
					$ims= 'data:image/png;base64,' . base64_encode(file_get_contents(@$url.$file_res));
					$out .= '<br /><img src="'.$ims.'">';
					$once=1;
				}else{
					$out .= '<br /><img src="'.$url.$file_res.'">';
				}
				$out .= '<br />'.$url.$file_res;
			}else{
				for($imgs=0; $imgs<$cuts; $imgs++){
					$im = imagecreatefromjpeg($file_res);
					$h = floor(imagesy($im)/$cuts);
					if($imgs==($cuts-1)) $h += imagesy($im)%$cuts;
					$im2 = imagecrop($im, ['x' => 0, 'y' => $imgs*$h, 'width' => imagesx($im), 'height' => $h]);
					$fnc = $imgs.'_'.$value;
					if ($im2 !== FALSE) {
						imagejpeg($im2, $mapp_r.$fnc);
						if($once==0){
							$ims= 'data:image/png;base64,' . base64_encode(file_get_contents(@$url.$mapp_r.$fnc));
							$out .= '<br /><img src="'.$ims.'">';
							$once=1;
						}else{
							$out .= '<br /><img src="'.$url.$mapp_r.$fnc.'">';
						}
						$out .= '<br />'.$url.$mapp_r.$fnc;
						imagedestroy($im2);
					}
					imagedestroy($im);
				}
			}
		}
	}
	
	$shortFileName=$_SESSION['shortFileName'];
	$shortFileName .= "_".$date_str;
	$_SESSION['link']=$url.$mapp.$shortFileName.".pdf";
	$_SESSION['shortFileName']=$shortFileName;
}else die();

//skapa pdf
use Dompdf\Dompdf;
use Dompdf\Options;
$options = new Options();
$options->set('isRemoteEnabled',true);      
$dompdf = new Dompdf( $options );
$dompdf->loadHtml($out);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$output = $dompdf->output();
file_put_contents($mapp.$shortFileName.".pdf", $output);


//lägg till uppladdade pdf i slutet av det skapade dokumentet.
array_push($extrapdf, $mapp.$shortFileName.".pdf");
$pdf = new Fpdi();
foreach (array_reverse($extrapdf) as $file) {
    $pageCount = $pdf->setSourceFile($file);
    for ($i = 0; $i < $pageCount; $i++) {
        $tpl = $pdf->importPage($i + 1, '/MediaBox');
        $pdf->addPage();
        $pdf->useTemplate($tpl);
    }
}
$pdf->Output('F',$mapp.'/'.$shortFileName.".pdf");

header("Location: result.php");

?>