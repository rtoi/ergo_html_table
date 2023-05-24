--TEST--
ext_010.phpt: HTML_Table_Storage -- Cut columns from table and paste them back.
--FILE--
<?php
// $Id$
require_once 'vendor/autoload.php';
include_once 'includes/ext_storage_01.php';

$a = $storage->cutCols(0, 1);
$storage->pasteCols($a, 1);
$a = $storage->cutCols(1);
$storage->pasteCols($a);

echo $storage->toHTML();
?>
--EXPECT--
	<tr>
		<td>Test - r0</td>
		<td>Bar - r0</td>
		<td>Test2 - r0</td>
		<td>Foo - r0</td>
	</tr>
	<tr>
		<td>Test - r1</td>
		<td>Bar - r1</td>
		<td>Test2 - r1</td>
		<td>Foo - r1</td>
	</tr>
	<tr>
		<td>Test - r2</td>
		<td>Bar - r2</td>
		<td>Test2 - r2</td>
		<td>Foo - r2</td>
	</tr>
	<tr>
		<td>Test - r3</td>
		<td>Bar - r3</td>
		<td>Test2 - r3</td>
		<td>Foo - r3</td>
	</tr>
