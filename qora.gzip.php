<?php
function htmlFilter($html)
{
	$html1 = $html;
	$html1 = str_replace("<", "&lt;", $html1);
	$html1 = str_replace(">", "&gt;", $html1);
	return $html1;
}  

if(isset($_POST['txt']))
{
	if($_POST['comp'] == "Compress")
	{
		$string = $_POST['txt'];
		$compressed = gzencode($string,9);
		echo "Uncompressed size: ". strlen($string)."\n";
		$base64 = base64_encode($compressed);
		$base64 = "?gz!".$base64;
		echo "Compressed size: ". (strlen($base64))."\n";
	}
	else
	{
		$base64 = substr($_POST['zip'],4);
		$compressed = gzcompress($string);
	    echo "Compressed size: ". (strlen($_POST['zip']))."\n";
		$string = gzdecode(base64_decode($base64));
		echo "Uncompressed size: ". strlen($string)."\n";
	}
}
$string = htmlFilter($string);
echo "
<form method=post>
Uncompressed:<br>
<textarea name=txt rows=20 cols=60>$string</textarea>
<br>
Compressed:
<br>
<textarea name=zip rows=20 cols=60>$base64</textarea>
<br>
<input type=submit value=\"Compress\" name=comp>
<input type=submit value=\"Decompress\" name=comp>
</form>";
?>
