--TEST--
ext_013.phpt: HTML_Table_Storage -- Cut rows from table and paste them back. Colspan and rowspan attributes set in some cells.
--FILE--
<?php
// $Id$
require_once 'vendor/autoload.php';
include_once 'includes/ext_storage_01.php';

$storage->setCellAttributes(0, 0, ['colspan' => 2, 'rowspan' => 2]);
$storage->setCellAttributes(0, 3, ['rowspan' => 3]);
$storage->setCellAttributes(3, 0, ['colspan' => 3]);

$a = $storage->cutRows(0);
$storage->pasteRows($a, 1);
$storage->pasteRows($a);
$a = $storage->cutRows(3);
$storage->pasteRows($a, 0);

echo $storage->toHTML();
?>
--EXPECT--
	<tr>
		<td colspan="3">Foo - r3</td>
		<td>Test2 - r3</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
		<td>Test - r1</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2">Foo - r0</td>
		<td>Test - r0</td>
		<td>Test2 - r0</td>
	</tr>
	<tr>
		<td>Foo - r2</td>
		<td>Bar - r2</td>
		<td>Test - r2</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2">Foo - r0</td>
		<td>Test - r0</td>
		<td>Test2 - r0</td>
	</tr>
