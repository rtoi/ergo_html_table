--TEST--
ext_001.phpt: Tests HTML_Table_Storage::isCellSpanned
--FILE--
<?php
// $Id$
require_once 'vendor/autoload.php';
include_once 'includes/ext_storage_01.php';

$storage->setCellAttributes(0, 0, ['colspan' => 2, 'rowspan' => 2]);
$storage->setCellAttributes(0, 3, ['rowspan' => 3]);
$storage->setCellAttributes(3, 0, ['colspan' => 3]);

var_dump($storage->isCellSpanned(0,0));
var_dump($storage->isCellSpanned(0,1));
var_dump($storage->isCellSpanned(1,1));
var_dump($storage->isCellSpanned(2,3));
var_dump($storage->isCellSpanned(3,3));
var_dump($storage->isCellSpanned(10,10));

?>
--EXPECT--
bool(false)
bool(true)
bool(true)
bool(true)
bool(false)
NULL
