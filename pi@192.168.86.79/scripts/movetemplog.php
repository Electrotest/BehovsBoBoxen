<?php

$latestdata = file_get_contents("/home/pi/behovsboboxen/scripts/templog.txt");

$savefile = "/home/pi/behovsboboxen/scripts/savedlog.txt";

file_put_contents($savefile, $latestdata);

$clear = "";

$todaysfile = "/home/pi/behovsboboxen/scripts/templog.txt";

file_put_contents($todaysfile, $clear);
