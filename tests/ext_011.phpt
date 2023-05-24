--TEST--
ext_011.phpt: HTML_Table_Storage -- Cut rows from table and paste them back.
--FILE--
<?php
// $Id$
require_once 'vendor/autoload.php';
include_once 'includes/ext_storage_01.php';

$a = $storage->cutRows(0, 1);
$storage->pasteRows($a, 1);
$a = $storage->cutRows(1);
$storage->pasteRows($a);
$a = $storage->cutRows(2);
$storage->pasteRows($a, 0);

echo $storage->toHTML();
?>
--EXPECT--
	<tr>
		<td>Foo - r3</td>
		<td>Bar - r3</td>
		<td>Test - r3</td>
		<td>Test2 - r3</td>
	</tr>
	<tr>
		<td>Foo - r2</td>
		<td>Bar - r2</td>
		<td>Test - r2</td>
		<td>Test2 - r2</td>
	</tr>
	<tr>
		<td>Foo - r1</td>
		<td>Bar - r1</td>
		<td>Test - r1</td>
		<td>Test2 - r1</td>
	</tr>
	<tr>
		<td>Foo - r0</td>
		<td>Bar - r0</td>
		<td>Test - r0</td>
		<td>Test2 - r0</td>
	</tr>
