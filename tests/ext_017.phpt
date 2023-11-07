--TEST--
ext_017.phpt: Tests HTML_Table_Storage::addCellClass, HTML_Table_Storage::hasCellClass, HTML_Table_Storage::removeCellClass
--FILE--
<?php
// $Id$
require_once 'vendor/autoload.php';

$table = new \Sjweh\Html\Table();
$storage = $table->getBody();

$storage->addRow(['A', 'B']);
$storage->addRow(['C', 'D']);

var_dump($storage->hasCellClass(0,0, 'c1'));
echo "----\n";
$storage->addCellClass(0,0, 'c1');
var_dump($storage->hasCellClass(0,0, 'c1'));
$storage->addCellClass(0,0, 'c2 c3');
var_dump($storage->hasCellClass(0,0, 'c3'));
$storage->removeCellClass(0,0, 'c2');
echo "----\n";
echo $table->toHTML();

?>
--EXPECT--
bool(false)
----
bool(true)
bool(true)
----
<table>
	<tr>
		<td class="c1 c3">A</td>
		<td>B</td>
	</tr>
	<tr>
		<td>C</td>
		<td>D</td>
	</tr>
</table>
