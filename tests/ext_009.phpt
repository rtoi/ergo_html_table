--TEST--
ext_009.phpt: Tests HTML_Table_Storage::insertCol 
--FILE--
<?php
// $Id$
require_once 'vendor/autoload.php';
include_once 'includes/ext_storage_01.php';

$storage->setCellAttributes(0, 0, ['colspan' => 2, 'rowspan' => 2]);
$storage->setCellAttributes(0, 3, ['rowspan' => 3]);
$storage->setCellAttributes(3, 0, ['colspan' => 3]);


$storage->insertCol(3);
echo $storage->toHTML();
echo "----\n";

$storage->insertCol(1);
echo $storage->toHTML();
echo "----\n";

$storage->insertCol(0);
echo $storage->toHTML();
echo "----\n";

$storage->insertCol();
echo $storage->toHTML();

?>
--EXPECT--
	<tr>
		<td colspan="2" rowspan="2">Foo - r0</td>
		<td>Test - r0</td>
		<td>&nbsp;</td>
		<td rowspan="3">Test2 - r0</td>
	</tr>
	<tr>
		<td>Test - r1</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>Foo - r2</td>
		<td>Bar - r2</td>
		<td>Test - r2</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3">Foo - r3</td>
		<td>&nbsp;</td>
		<td>Test2 - r3</td>
	</tr>
----
	<tr>
		<td colspan="3" rowspan="2">Foo - r0</td>
		<td>Test - r0</td>
		<td>&nbsp;</td>
		<td rowspan="3">Test2 - r0</td>
	</tr>
	<tr>
		<td>Test - r1</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>Foo - r2</td>
		<td>&nbsp;</td>
		<td>Bar - r2</td>
		<td>Test - r2</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4">Foo - r3</td>
		<td>&nbsp;</td>
		<td>Test2 - r3</td>
	</tr>
----
	<tr>
		<td>&nbsp;</td>
		<td colspan="3" rowspan="2">Foo - r0</td>
		<td>Test - r0</td>
		<td>&nbsp;</td>
		<td rowspan="3">Test2 - r0</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>Test - r1</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>Foo - r2</td>
		<td>&nbsp;</td>
		<td>Bar - r2</td>
		<td>Test - r2</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td colspan="4">Foo - r3</td>
		<td>&nbsp;</td>
		<td>Test2 - r3</td>
	</tr>
----
	<tr>
		<td>&nbsp;</td>
		<td colspan="3" rowspan="2">Foo - r0</td>
		<td>Test - r0</td>
		<td>&nbsp;</td>
		<td rowspan="3">Test2 - r0</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>Test - r1</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>Foo - r2</td>
		<td>&nbsp;</td>
		<td>Bar - r2</td>
		<td>Test - r2</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td colspan="4">Foo - r3</td>
		<td>&nbsp;</td>
		<td>Test2 - r3</td>
		<td>&nbsp;</td>
	</tr>
