--TEST--
ext_008.phpt: Tests HTML_Table_Storage::mergeColToRight 
--FILE--
<?php
// $Id$
require_once 'vendor/autoload.php';
include_once 'includes/ext_storage_01.php';

$storage->setCellAttributes(0, 0, ['colspan' => 2, 'rowspan' => 2]);
$storage->setCellAttributes(0, 3, ['rowspan' => 3]);
$storage->setCellAttributes(3, 0, ['colspan' => 3]);


$storage->mergeColToRight(0, '|', '-->');
echo $storage->toHTML();
echo "----\n";

$storage->mergeColToRight(2, '|', '-->');
echo $storage->toHTML();
echo "----\n";

$storage->mergeColToRight(1, '|', '-->');
echo $storage->toHTML();

?>
--EXPECT--
	<tr>
		<td colspan="2" rowspan="2">Foo - r0</td>
		<td>Test - r0</td>
		<td rowspan="3">Test2 - r0</td>
	</tr>
	<tr>
		<td>Test - r1</td>
	</tr>
	<tr>
		<td colspan="2">Foo - r2|Bar - r2</td>
		<td>Test - r2</td>
	</tr>
	<tr>
		<td colspan="3">Foo - r3</td>
		<td>Test2 - r3</td>
	</tr>
----
	<tr>
		<td colspan="2" rowspan="2">Foo - r0</td>
		<td colspan="2">Test - r0|Test2 - r0</td>
	</tr>
	<tr>
		<td colspan="2">Test - r1</td>
	</tr>
	<tr>
		<td colspan="2">Foo - r2|Bar - r2</td>
		<td colspan="2">Test - r2</td>
	</tr>
	<tr>
		<td colspan="2">Foo - r3</td>
		<td colspan="2">-->Test2 - r3</td>
	</tr>
----
	<tr>
		<td rowspan="2">Foo - r0</td>
		<td colspan="3">-->Test - r0|Test2 - r0</td>
	</tr>
	<tr>
		<td colspan="3">-->Test - r1</td>
	</tr>
	<tr>
		<td>Foo - r2|Bar - r2</td>
		<td colspan="3">-->Test - r2</td>
	</tr>
	<tr>
		<td>Foo - r3</td>
		<td colspan="3">-->-->Test2 - r3</td>
	</tr>
