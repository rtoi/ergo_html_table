--TEST--
ext_019.phpt: Tests HTML_Table_Storage::keepAttributes
--FILE--
<?php
// $Id$
require_once 'vendor/autoload.php';

$table = new \Ergo\Html\Table();
$storage = $table->getBody();

$storage->addRow(['A', 'B'], 'style = "background-color:yellow;" align = "right"');
$storage->addRow(['C', 'D'], 'style = "color:blue;" align = "center" valign="bottom"');
$storage->addCellClass(0,0, 'c1');
$storage->addCellClass(0,0, 'c2');
$storage->addCellClass(1,1, 'c2');
$table->updateRowAttributes(0, ['class' => 'row1'], true); 
$table->updateRowAttributes(1, ['class' => 'row2', 'style' => 'background-color:red;'], true); 

$storage->keepAttributes(['align']);

echo $table->toHTML();

?>
--EXPECT--
<table>
	<tr>
		<td align="right">A</td>
		<td align="right">B</td>
	</tr>
	<tr>
		<td align="center">C</td>
		<td align="center">D</td>
	</tr>
</table>
