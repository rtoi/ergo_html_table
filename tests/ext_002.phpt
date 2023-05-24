--TEST--
ext_002.phpt: Tests HTML_Table_Storage::spanBase 
--FILE--
<?php
// $Id$
require_once 'vendor/autoload.php';
include_once 'includes/ext_storage_01.php';

$storage->setCellAttributes(0, 0, ['colspan' => 2, 'rowspan' => 2]);
$storage->setCellAttributes(0, 3, ['rowspan' => 3]);
$storage->setCellAttributes(3, 0, ['colspan' => 3]);

var_dump($storage->spanBase(0,0));
var_dump($storage->spanBase(1,1));
var_dump($storage->spanBase(3,2));
var_dump($storage->spanBase(1,3));

?>
--EXPECT--
array(2) {
  [0]=>
  int(0)
  [1]=>
  int(0)
}
array(2) {
  [0]=>
  int(0)
  [1]=>
  int(0)
}
array(2) {
  [0]=>
  int(3)
  [1]=>
  int(0)
}
array(2) {
  [0]=>
  int(0)
  [1]=>
  int(3)
}
