<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Video list create</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-9">
<META HTTP-EQUIV="Content-language" CONTENT="tr">
</head>
<body>
<?php

function seo($url){ 
	$url = trim($url);
	$find = array('<b>', '</b>');
	$url = str_replace ($find, '', $url);
	$url = preg_replace('/<(\/{0,1})img(.*?)(\/{0,1})\>/', 'image', $url);
	$find = array(' ', '&amp;amp;amp;quot;', '&amp;amp;amp;amp;', '&amp;amp;amp;', '\r\n', '\n', '/', '\\', '+', '<', '>');
	$url = str_replace ($find, '-', $url);
	
	$find = array('I', 'İ', 'ı');
	$url = str_replace ($find, 'i', $url);

	$find = array('ö', 'Ö');
	$url = str_replace ($find, 'o', $url);

	$find = array('ü', 'Ü');
	$url = str_replace ($find, 'u', $url);

	$find = array('ç', 'Ç');
	$url = str_replace ($find, 'c', $url);

	$find = array('Ş', 'ş');
	$url = str_replace ($find, 's', $url);

	$find = array('Ğ', 'ğ');
	$url = str_replace ($find, 'g', $url);

	/*$find = array('/[^A-Za-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
	$repl = array('', '-', '');
	$url = preg_replace ($find, $repl, $url);
	$url = str_replace ('--', '-', $url);*/
	$url = strtolower($url);
	return $url;
}
$dir = "video/";
$dh  = opendir($dir);
while (false !== ($filename = readdir($dh))) {
   
    $path_parts = pathinfo('video/'.$filename);
	
	$uzanti=$path_parts['extension'];
	$newName=seo($path_parts['filename']);
	
	#echo $newName.'<br>';
	#echo "Yeni dosya Adı : ".$newName;
	echo $newName."<br>";
	#echo $path_parts['filename'].'<br>';
	#rename('video/'.$filename, 'video/'.$newName.".".$uzanti);
	# echo "video/".$filename." | video/".$newName.".".$uzanti."<br>";
}
?>
</body>
</html>