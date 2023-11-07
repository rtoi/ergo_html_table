--TEST--
ext_015.phpt: Tests HTML_Table_Storage::insertRow 
--FILE--
<?php
// $Id$
require_once 'vendor/autoload.php';
include_once 'includes/ext_storage_01.php';

$storage->setCellAttributes(0, 0, ['colspan' => 2, 'rowspan' => 2]);
$storage->setCellAttributes(0, 3, ['rowspan' => 3]);
$storage->setCellAttributes(3, 0, ['colspan' => 3]);

$storage::setOption(HTML_Common2::OPTION_INDENT, '    ');

$storage->insertRow(1);
echo $storage->toHTML();
echo "----\n";

$storage->insertRow(3);
echo $storage->toHTML();
echo "----\n";

$storage->insertRow(0);
$storage->insertRow();
echo $storage->toHTML();

?>
--EXPECT--
    <tr>
        <td colspan="2" rowspan="3">Foo - r0</td>
        <td>Test - r0</td>
        <td rowspan="4">Test2 - r0</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Test - r1</td>
    </tr>
    <tr>
        <td>Foo - r2</td>
        <td>Bar - r2</td>
        <td>Test - r2</td>
    </tr>
    <tr>
        <td colspan="3">Foo - r3</td>
        <td>Test2 - r3</td>
    </tr>
----
    <tr>
        <td colspan="2" rowspan="3">Foo - r0</td>
        <td>Test - r0</td>
        <td rowspan="5">Test2 - r0</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Test - r1</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Foo - r2</td>
        <td>Bar - r2</td>
        <td>Test - r2</td>
    </tr>
    <tr>
        <td colspan="3">Foo - r3</td>
        <td>Test2 - r3</td>
    </tr>
----
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" rowspan="3">Foo - r0</td>
        <td>Test - r0</td>
        <td rowspan="5">Test2 - r0</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Test - r1</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Foo - r2</td>
        <td>Bar - r2</td>
        <td>Test - r2</td>
    </tr>
    <tr>
        <td colspan="3">Foo - r3</td>
        <td>Test2 - r3</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
