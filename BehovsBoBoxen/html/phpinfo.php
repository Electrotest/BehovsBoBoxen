<?php
$phpExtContents = "<br /><h1>Laddade extensions:</h1><br />";
// recuperation des extensions PHP
$loaded_extensions = get_loaded_extensions();

//var_dump($loaded_extensions);
foreach ($loaded_extensions as $extension)
	$phpExtContents .= "<li>${extension}</li>";
	
$sqlite3 = "";
if(class_exists('sqlite3')){
	$sqlite3 = "sqlite3 finns.";
}else{
	$sqlite3 = "sqlite3 finns inte.";
}

//affichage du phpinfo
if (isset($_GET['phpinfo']))
{
	phpinfo();
	exit();
}

	
$pageContents = <<< EOPAGE
<!DOCTYPE html >
<body>
<head>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	<meta http-equiv="Content-type: image/png" />
	<style type="text/css">
* {
	margin: 0;
	padding: 0;
}

html {
	background: #ddd;
}
body {
	margin: 1em 10%;
	padding: 1em 3em;
	font: 80%/1.4 tahoma, arial, helvetica, lucida sans, sans-serif;
	border: 1px solid #999;
	background: #eee;
	position: relative;
}
dl {
	margin: 10px;
	padding: 10px;
}
dt {
	font-weight: bold;
	text-align: right;
	width: 11em;
	clear: both;
}
dd {
	margin: -1.35em 0 0 12em;
	padding-bottom: 0.4em;
	overflow: auto;
}
dd ul li {
	float: left;
	display: block;
	width: 16.5%;
	margin: 0;
	padding: 0 0 0 20px;
	background: url(favicon.png) -10px 125% no-repeat;
	line-height: 1.6;
}
</style>
</head>
	<dl class="content">
		<dd>
			<ul>
			${phpExtContents}
			$sqlite3
			</ul>
		</dd>
	</dl>
	<h2>Phpinfo här</h2>
	<a href="?phpinfo=1">phpinfo()</a>
<br /><br />
</body>
</html>
EOPAGE;

echo $pageContents; 

?>