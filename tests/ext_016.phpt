--TEST--
ext_016.phpt: Tests HTML_Table_Storage::updateColContents
--FILE--
<?php
// $Id$
require_once 'vendor/autoload.php';
include_once 'includes/ext_storage_01.php';

$storage->setCellAttributes(0, 0, ['colspan' => 2, 'rowspan' => 2]);
$storage->setCellAttributes(0, 3, ['rowspan' => 3]);
$storage->setCellAttributes(3, 0, ['colspan' => 3]);

$storage->setTab('    ');

$fn = function (int $rows, int $col) {
    return array_map(function ($val) use ($col) {return "row=$val, col=$col";}, range(0, $rows - 1));
};

$col = 2;
$storage->updateColContents($col, $fn($storage->getRowCount(), $col), 'td');
echo $storage->toHTML();
echo "----\n";

$col = 1;
$storage->updateColContents($col, $fn($storage->getRowCount(), $col), 'td');
echo $storage->toHTML();
echo "----\n";

$col = 0;
$storage->updateColContents($col, $fn($storage->getRowCount(), $col), 'td');
echo $storage->toHTML();

?>
--EXPECT--
    <tr>
        <td colspan="2" rowspan="2">Foo - r0</td>
        <td>row=0, col=2</td>
        <td rowspan="3">Test2 - r0</td>
    </tr>
    <tr>
        <td>row=1, col=2</td>
    </tr>
    <tr>
        <td>Foo - r2</td>
        <td>Bar - r2</td>
        <td>row=2, col=2</td>
    </tr>
    <tr>
        <td colspan="3">Foo - r3</td>
        <td>Test2 - r3</td>
    </tr>
----
    <tr>
        <td colspan="2" rowspan="2">Foo - r0</td>
        <td>row=0, col=2</td>
        <td rowspan="3">Test2 - r0</td>
    </tr>
    <tr>
        <td>row=1, col=2</td>
    </tr>
    <tr>
        <td>Foo - r2</td>
        <td>row=2, col=1</td>
        <td>row=2, col=2</td>
    </tr>
    <tr>
        <td colspan="3">Foo - r3</td>
        <td>Test2 - r3</td>
    </tr>
----
    <tr>
        <td colspan="2" rowspan="2">row=0, col=0</td>
        <td>row=0, col=2</td>
        <td rowspan="3">Test2 - r0</td>
    </tr>
    <tr>
        <td>row=1, col=2</td>
    </tr>
    <tr>
        <td>row=2, col=0</td>
        <td>row=2, col=1</td>
        <td>row=2, col=2</td>
    </tr>
    <tr>
        <td colspan="3">row=3, col=0</td>
        <td>Test2 - r3</td>
    </tr>

