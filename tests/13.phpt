--TEST--
13.phpt: 2 row 1 column, setCellContents / getCellContents
--FILE--
<?php
// $Id$
require_once 'vendor/autoload.php';
$table = new \Ergo\Html\Table();

$data[0][] = 'Test';
$data[1][] = '';

foreach($data as $key => $value) {
    $table->addRow($value);
}

echo $table->getCellContents(0, 0) . "\n";
$table->setCellContents(0, 0, 'FOOBAR');
echo $table->getCellContents(0, 0) . "\n";
// output
echo $table->toHTML();
?>
--EXPECT--
Test
FOOBAR
<table>
	<tr>
		<td>FOOBAR</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
