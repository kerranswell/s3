<?php

$f = file_get_contents("version.txt");

if (preg_match("|([0-9]+)|", $f, $matches))
{
    $num = (int)$matches[1] + 1;
} else {
    $num = time();
}

file_put_contents("version.txt", $num);

echo $num;