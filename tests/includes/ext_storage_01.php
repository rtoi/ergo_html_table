<?php

if (!isset($storage)) {
    $storage = new \Sjweh\Html\Table\Storage();
}

$data[0][] = 'Foo';
$data[0][] = 'Bar';
$data[0][] = 'Test';
$data[0][] = 'Test2';
$data[1][] = 'Foo';
$data[1][] = 'Bar';
$data[1][] = 'Test';
$data[1][] = 'Test2';
$data[2][] = 'Foo';
$data[2][] = 'Bar';
$data[2][] = 'Test';
$data[2][] = 'Test2';
$data[3][] = 'Foo';
$data[3][] = 'Bar';
$data[3][] = 'Test';
$data[3][] = 'Test2';


foreach($data as $key => $value) {
    $val = array_map(function ($v) use ($key) {return $v . ' - r' . $key;}, $value); 
    $storage->addRow($val);
}
