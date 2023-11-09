--TEST--
ext_018.phpt: Tests HTML_Table_Storage::addColClass and HTML_Table_Storage::removeColClass
--FILE--
<?php
// $Id$
require_once 'vendor/autoload.php';

$table = new \Sjweh\Html\Table();
$storage = $table->getBody();

$storage->addRow(['A', 'B']);
$storage->addRow(['C', 'D']);

$storage->addCellClass(0,0, 'c1');
$storage->addColClass(0, 'c2 c3');
var_dump($storage->hasCellClass(0,0, 'c1'));
$storage->removeColClass(0, 'c1');
echo "----\n";
echo $table->toHTML();

?>
--EXPECT--
bool(true)
----
<table>
	<tr>
		<td class="c2 c3">A</td>
		<td>B</td>
	</tr>
	<tr>
		<td class="c2 c3">C</td>
		<td>D</td>
	</tr>
</table>

