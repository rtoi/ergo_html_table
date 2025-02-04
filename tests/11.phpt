--TEST--
11.phpt: 2 row 1 column, setAutoFill / getAutoFill
--FILE--
<?php
// $Id$
require_once 'vendor/autoload.php';
$table = new \Ergo\Html\Table();

$table->setAutoFill('N/A');

$data[0][] = 'Test';
$data[1][] = '';

foreach($data as $key => $value) {
    $table->addRow($value);
}

echo $table->getAutoFill() . "\n";

// output
echo $table->toHTML();
?>
--EXPECT--
N/A
<table>
	<tr>
		<td>Test</td>
	</tr>
	<tr>
		<td>N/A</td>
	</tr>
</table>
