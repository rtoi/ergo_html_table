<?php

if (!isset($storage)) {
    $storage = new \Ergo\Html\Table\Storage();
}

$data = [];

for ($i = 0; $i <= 3; $i++) {
    for ($j = 0; $j <= 4; $j++) {
        $data[$j] = "row{$i} - col{$j}";
    }
    $storage->addRow($data);
}

