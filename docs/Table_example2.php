<?php

/*
* This example will output html websafe colors in a table
* using HTML_Table.
*/

require_once __DIR__ . '/../vendor/autoload.php';

$table = new Ergo\Html\Table('width = "100%"');

$table->setCaption('256 colors table');
$i = $j = 0;
for ($R = 0; $R <= 255; $R += 51) {
	for ($G = 0; $G <= 255; $G += 51) {
		for($B = 0; $B <= 255; $B += 51) {
			$table->setCellAttributes($i, $j, 'bgcolor = "#'.sprintf('%02X%02X%02X', $R, $G, $B).'"');
			$j++;
		}
	}
	$i++;
	$j = 0;
}
echo $table->toHtml();
