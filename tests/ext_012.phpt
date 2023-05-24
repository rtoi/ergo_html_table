--TEST--
ext_012.phpt: HTML_Table_Storage -- Cut columns from table and paste them back. Colspan and rowspan attributes set in some cells.
--FILE--
<?php
// $Id$
require_once 'vendor/autoload.php';
include_once 'includes/ext_storage_01.php';

$storage->setCellAttributes(0, 0, ['colspan' => 2, 'rowspan' => 2]);
$storage->setCellAttributes(0, 3, ['rowspan' => 3]);
$storage->setCellAttributes(3, 0, ['colspan' => 3]);

$a = $storage->cutCols(0);
$storage->pasteCols($a, 1);
$storage->pasteCols($a);
$a = $storage->cutCols(3);
$storage->pasteCols($a, 0);

echo $storage->toHTML();
?>
--EXPECT--
	<tr>
		<td rowspan="3">Test2 - r0</td>
		<td rowspan="2">&nbsp;</td>
		<td rowspan="2">Foo - r0</td>
		<td>Test - r0</td>
		<td rowspan="2">Foo - r0</td>
	</tr>
	<tr>
		<td>Test - r1</td>
	</tr>
	<tr>
		<td>Bar - r2</td>
		<td>Foo - r2</td>
		<td>Test - r2</td>
		<td>Foo - r2</td>
	</tr>
	<tr>
		<td>Test2 - r3</td>
		<td>&nbsp;</td>
		<td>Foo - r3</td>
		<td>&nbsp;</td>
		<td>Foo - r3</td>
	</tr>
