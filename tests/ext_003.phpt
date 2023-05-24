--TEST--
ext_003.phpt: Tests HTML_Table_Storage::splitSpanVertical 
--FILE--
<?php
// $Id$
require_once 'vendor/autoload.php';
include_once 'includes/ext_storage_01.php';

$storage->setCellAttributes(0, 0, ['colspan' => 2, 'rowspan' => 2]);
$storage->setCellAttributes(0, 3, ['rowspan' => 3]);
$storage->setCellAttributes(3, 0, ['colspan' => 3]);

$storage->splitSpanVertical(0,1);
$storage->splitSpanVertical(3,2);
$storage->splitSpanVertical(2,3); // This should have no effect
$storage->splitSpanVertical(3,1);

echo $storage->toHTML();

?>
--EXPECT--
	<tr>
		<td rowspan="2">Foo - r0</td>
		<td rowspan="2">&nbsp;</td>
		<td>Test - r0</td>
		<td rowspan="3">Test2 - r0</td>
	</tr>
	<tr>
		<td>Test - r1</td>
	</tr>
	<tr>
		<td>Foo - r2</td>
		<td>Bar - r2</td>
		<td>Test - r2</td>
	</tr>
	<tr>
		<td>Foo - r3</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>Test2 - r3</td>
	</tr>
