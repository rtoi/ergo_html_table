--TEST--
ext_020.phpt: Tests HTML_Table_Storage::deleteCols
--FILE--
<?php
// $Id$
require_once 'vendor/autoload.php';

$table = new \Sjweh\Html\Table();
$storage = $table->getBody();

include_once 'includes/ext_storage_02.php';

$storage->setCellAttributes(0, 0, ['colspan' => 4, 'align' => 'center']);
$storage->setCellAttributes(1, 3, ['colspan' => 2, 'align' => 'center']);
$storage->setCellAttributes(2, 1, ['colspan' => 2]);
$storage->setCellAttributes(3, 1, ['colspan' => 4, 'align' => 'center']);

echo $storage->toHTML();
echo "--------" . PHP_EOL;

$storage->deleteCols(1, 2);
echo $storage->toHTML();
echo "--------" . PHP_EOL;

$storage->deleteCols(1);
echo $storage->toHTML();
echo "--------" . PHP_EOL;

$storage->deleteCols(0);
echo $storage->toHTML();

?>
--EXPECT--
	<tr>
		<td colspan="4" align="center">row0 - col0</td>
		<td>row0 - col4</td>
	</tr>
	<tr>
		<td>row1 - col0</td>
		<td>row1 - col1</td>
		<td>row1 - col2</td>
		<td colspan="2" align="center">row1 - col3</td>
	</tr>
	<tr>
		<td>row2 - col0</td>
		<td colspan="2">row2 - col1</td>
		<td>row2 - col3</td>
		<td>row2 - col4</td>
	</tr>
	<tr>
		<td>row3 - col0</td>
		<td colspan="4" align="center">row3 - col1</td>
	</tr>
--------
	<tr>
		<td colspan="2" align="center">row0 - col0</td>
		<td>row0 - col4</td>
	</tr>
	<tr>
		<td>row1 - col0</td>
		<td colspan="2" align="center">row1 - col3</td>
	</tr>
	<tr>
		<td>row2 - col0</td>
		<td>row2 - col3</td>
		<td>row2 - col4</td>
	</tr>
	<tr>
		<td>row3 - col0</td>
		<td colspan="2" align="center">&nbsp;</td>
	</tr>
--------
	<tr>
		<td align="center">row0 - col0</td>
		<td>row0 - col4</td>
	</tr>
	<tr>
		<td>row1 - col0</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td>row2 - col0</td>
		<td>row2 - col4</td>
	</tr>
	<tr>
		<td>row3 - col0</td>
		<td align="center">&nbsp;</td>
	</tr>
--------
	<tr>
		<td>row0 - col4</td>
	</tr>
	<tr>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td>row2 - col4</td>
	</tr>
	<tr>
		<td align="center">&nbsp;</td>
	</tr>

