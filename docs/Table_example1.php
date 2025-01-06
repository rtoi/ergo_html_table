<?php

/*
* This example shows how you can set row and col attributes
* with HTML_Table.
*/

require_once __DIR__ . '/../vendor/autoload.php';

$table = new Ergo\Html\Table('width = "400"');

$data[0][] = 'i am';
$data[0][] = 'i think';
$data[0][] = 'therefore';
$data[0][] = 'therefore';

$data[1][] = 'i think';
$data[1][] = 'i am';
$data[1][] = 'therefore';
$data[1][] = 'therefore';

$data[2][] = 'i am';
$data[2][] = 'therefore';
$data[2][] = 'i think';
$data[2][] = 'i think';

foreach ($data as $key => $value) {
    $table->addRow(
        $data[$key],
        [
            ['bgcolor' => 'lightblue', 'align' => 'center' ],
            ['bgcolor' => 'green' ],
            ['bgcolor' => 'red']
        ]
    );
}

foreach ($data as $key => $value) {
    $table->addRow(
        $data[$key],
        ['bgcolor = "blue"','bgcolor = "green"','bgcolor = "red"']
    );
}

foreach ($data as $key => $value) {
    $table->addRow(
        $data[$key], 
        'bgcolor = "yellow" align = "right"', 'TD', 
        true
    );
}

foreach ($data as $key => $value) {
    $table->addRow(
        $data[$key], 
        ['bgcolor' => 'pink', 'align' => 'center']
    );
}

$table->setColAttributes(1, 'bgcolor = "teal"');
$table->updateColAttributes(
    2, 
    ['bgcolor = "blue"','bgcolor = "green"','bgcolor = "red"']
);

echo '<pre>';
var_dump($table->getCellAttributes(2, 2));
var_dump($table->getRowAttributes(8));
echo '</pre>';
echo $table->toHTML();
