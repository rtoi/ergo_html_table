<?php

/**
 * This example shows how you can merge cells and split already merged cells.
*/

require_once __DIR__ . '/../vendor/autoload.php';

$table = new Ergo\Html\Table();
$storage = $table->getBody();

// Inject data to $storage and sets $css 
include_once 'example_content1.php';
include_once 'example_css.php';

echo "<style type='text/css'>$css</style>" . PHP_EOL;
echo $table->toHtml();

echo "<h2>Merge 2 cells</h2>" . PHP_EOL;
// It is possible to merge two adjacent cells.
// Cells to be merged have to be either on the same row
// or on the same column.
// Merge horizontally
$storage->mergeCells(1, 0, 1, 1); // By default merge by ' ' 
$storage->mergeCells(2, 1, 2, 2, '<br>'); // Merge by '<hr>'
// Merge vertically
$storage->mergeCells(1, 3, 2, 3); // By default merge by '<br>'
$storage->mergeCells(2, 4, 3, 4, '|'); // Merge by '|'
echo $table->toHtml();

echo "<h2>Merge already merged cells" . PHP_EOL;
echo "<h3>Step 1</h3>" . PHP_EOL;
$storage->mergeCells(1, 1, 1, 2, '|'); 
echo $table->toHtml();
echo "<h3>Step 2</h3>" . PHP_EOL;
$storage->mergeCells(2, 0, 2, 1); 
echo $table->toHtml();
echo "<h3>Step 3</h3>" . PHP_EOL;
$storage->mergeCells(1, 0, 2, 0); 
echo $table->toHtml();

echo "<h2>Split columns and rows</h2>" . PHP_EOL;
// It is also possible to split merged cells either by
// column or row. 
echo "<h3>Split merged columns</h3>" . PHP_EOL;
$storage->splitAtCol(1);
$storage->setCellContents(1, 1, 'New content');
echo $table->toHtml();

echo "<h3>Split merged rows</h3>" . PHP_EOL;
$storage->splitAtRow(2);
$storage->setCellContents(2, 0, 'Also new content here');
echo $table->toHtml();
