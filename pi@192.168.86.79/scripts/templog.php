#!/usr/bin/php
<?php
$file = "/home/pi/behovsboboxen/scripts/templog.txt";
$context = stream_context_create(
    array(
        "ftp" => array(
            "overwrite" => true
        )
    )
);

$now = date("Y-m-d");
$src = $now;
$dest = fopen("ftp://ipv6home.se:ljuvlig@ipv6home.se/public_html/application/textfile/rasp/templog2.txt", "w", false, $context);
$src .= file_get_contents($file);

//var_dump($dest);
//var_dump($src);
fwrite($dest, $src, strlen($src));
fclose($dest);
