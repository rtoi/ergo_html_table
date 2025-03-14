--TEST--
16.phpt: 2 row 2 column, setCaption
--FILE--
<?php
// $Id$
require_once 'vendor/autoload.php';
$table = new \Ergo\Html\Table('width="400"');

$data[0][] = 'Test';
$data[0][] = 'Test';
$data[1][] = 'Test';
$data[1][] = 'Test';

foreach($data as $key => $value) {
    $table->addRow($value, 'bgcolor = "yellow" align = "right"');
}

$table->setCaption('Foobar', 'bgcolor="green"');

// output
echo $table->toHTML();
?>
--EXPECT--
<table width="400">
	<caption bgcolor="green">Foobar</caption>
	<tr>
		<td bgcolor="yellow" align="right">Test</td>
		<td bgcolor="yellow" align="right">Test</td>
	</tr>
	<tr>
		<td bgcolor="yellow" align="right">Test</td>
		<td bgcolor="yellow" align="right">Test</td>
	</tr>
</table>
