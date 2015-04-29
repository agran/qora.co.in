<?php

function httpRequest($host, $port, $method, $path, $params) {

	$paramStr = "";

	foreach ($params as $name => $val) {
		$paramStr .= $name . "=";
		$paramStr .= urlencode($val);
		$paramStr .= "&";
	}

	if (empty($method)) {
		$method = 'GET';
	}
	$method = strtoupper($method);
	if (empty($port)) {
		$port = 80; 
	}

	$sock = fsockopen($host, $port);
	if($sock)
	{
		if ($method == "GET") {
			$path .= "?" . $paramStr;
		}
		fputs($sock, "$method $path HTTP/1.1\r\n");
		fputs($sock, "Host: $host\r\n");
		fputs($sock, "Content-type: " .
		"application/x-www-form-urlencoded\r\n");
		if ($method == "POST") {
			fputs($sock, "Content-length: " . 
			strlen($paramStr) . "\r\n");
		}
		fputs($sock, "Connection: close\r\n\r\n");
		if ($method == "POST") {
			fputs($sock, $paramStr);
		}

		$result = "";
		while (!feof($sock)) {
			$result .= fgets($sock,1024);
		}

		$result2 = strstr($result, "\r\n\r\n");

		fclose($sock);
		$result3 = json_decode($result2 , true);
	}
	return $result3;
}

$ar =  array();
$blockcount = httpRequest("127.0.0.1",
	9085, "GET", "/blocks/height", $ar);  

echo "<br>my explorer - $blockcount<br>";

$ar =  array();
$blockcount2 = httpRequest("193.242.149.63",
	9085, "GET", "/blocks/height", $ar);  

echo "<br>my workserver - $blockcount2<br>";

$buf = httpRequest("127.0.0.1",
	9085, "GET", "/blocks/last", $ar);  

echo "<br>Last block of explorer:<br>";

print_r ( $buf );
echo "<br><br>";

echo "<br><br>";

$buf = httpRequest("127.0.0.1",
	9085, "GET", "/peers/height", $ar);  
	
echo "<br>my explorer peers: ".count($buf)."<br>";
print_r ( $buf );
echo "<br><br>";

$buf = httpRequest("193.242.149.63",
	9085, "GET", "/peers/height", $ar);  

echo "<br>my workserver peers: ".count($buf)."<br>"; 
print_r ( $buf );

?>