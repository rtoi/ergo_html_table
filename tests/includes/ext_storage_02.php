<?php

if (!isset($storage)) {
    $storage = new \Sjweh\Html\Table\Storage();
}

$data = [];

for ($i = 0; $i <= 3; $i++) {
    for ($j = 0; $j <= 4; $j++) {
        $data[$j] = "row{$i} - col{$j}";
    }
    $storage->addRow($data);
}
