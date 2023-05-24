--TEST--
ext_007.phpt: Tests HTML_Table_Storage::mergeCells
--FILE--
<?php
// $Id$
require_once 'vendor/autoload.php';
include_once 'includes/ext_storage_01.php';

$storage->setCellAttributes(0, 0, ['colspan' => 2, 'rowspan' => 2]);
$storage->setCellAttributes(0, 3, ['rowspan' => 3]);
$storage->setCellAttributes(3, 0, ['colspan' => 3]);

$storage->mergeCells(2, 0, 2, 1, '|');
$storage->mergeCells(0, 2, 1, 2);
$storage->mergeCells(1, 2, 2, 2);
$storage->mergeCells(1, 2, 1, 3, '<br/>');

echo $storage->toHTML();
?>
--EXPECT--
	<tr>
		<td colspan="2" rowspan="2">Foo - r0</td>
		<td rowspan="3" colspan="2">Test - r0<br>Test - r1<br>Test - r2<br/>Test2 - r0</td>
	</tr>
	<tr>
	</tr>
	<tr>
		<td colspan="2">Foo - r2|Bar - r2</td>
	</tr>
	<tr>
		<td colspan="3">Foo - r3</td>
		<td>Test2 - r3</td>
	</tr>
