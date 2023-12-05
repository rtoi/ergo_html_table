<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Sjweh\Html\Table;

use Sjweh\Html\Common2;
use PEAR;

/**
 * Storage class for HTML::Table data
 *
 * This class stores data for tables built with HTML_Table. When having
 * more than one instance, it can be used for grouping the table into the
 * parts <thead>...</thead>, <tfoot>...</tfoot> and <tbody>...</tbody>.
 *
 * PHP versions 4 and 5
 *
 * LICENSE:
 *
 * Copyright (c) 2005-2007, Adam Daniel <adaniel1@eesus.jnj.com>,
 *                          Bertrand Mansion <bmansion@mamasam.com>,
 *                          Mark Wiesemann <wiesemann@php.net>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *    * Redistributions of source code must retain the above copyright
 *      notice, this list of conditions and the following disclaimer.
 *    * Redistributions in binary form must reproduce the above copyright
 *      notice, this list of conditions and the following disclaimer in the
 *      documentation and/or other materials provided with the distribution.
 *    * The names of the authors may not be used to endorse or promote products
 *      derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS
 * IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY
 * OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   HTML
 * @package    HTML_Table
 * @author     Adam Daniel <adaniel1@eesus.jnj.com>
 * @author     Bertrand Mansion <bmansion@mamasam.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/HTML_Table
 */

/**
 * Storage class for HTML::Table data
 *
 * This class stores data for tables built with HTML_Table. When having
 * more than one instance, it can be used for grouping the table into the
 * parts <thead>...</thead>, <tfoot>...</tfoot> and <tbody>...</tbody>.
 *
 * @category   HTML
 * @package    HTML_Table
 * @author     Adam Daniel <adaniel1@eesus.jnj.com>
 * @author     Bertrand Mansion <bmansion@mamasam.com>
 * @author     Mark Wiesemann <wiesemann@php.net>
 * @copyright  2005-2006 The PHP Group
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/HTML_Table
 */
class Storage extends Common2
{
    /**
     * Value to insert into empty cells
     * @var    string
     * @access private
     */
    private string $_autoFill = '&nbsp;';

    /**
     * Automatically adds a new row or column if a given row or column index
     * does not exist
     * @var    bool
     * @access private
     */
    private bool $_autoGrow = true;

    /**
     * Array containing the table structure
     * @var     array
     * @access  private
     */
    private array $_structure = [];

    /**
     * Number of rows composing in the table
     * @var     int
     * @access  private
     */
    private int $_rows = 0;

    /**
     * Number of column composing the table
     * @var     int
     * @access  private
     */
    private int $_cols = 0;

    /**
     * Tracks the level of nested tables
     * @var    int
     * @access private
     */
    private int $_nestLevel = 0;

    /**
     * Whether to use <thead>, <tfoot> and <tbody> or not
     * @var    bool
     * @access private
     */
    private bool $_useTGroups = false;

    /**
     * Class constructor
     *
     * @param    int      $tabOffset
     * @param    bool     $useTGroups        Whether to use <thead>, <tfoot> and
     *                                       <tbody> or not
     * @access   public
     */
    function __construct(int $tabOffset = 0, bool $useTGroups = false)
    {
        parent::__construct();
        $this->setIndentLevel($tabOffset);
        $this->_useTGroups = $useTGroups;
    }

    /**
     * Sets the useTGroups value
     * @param   boolean   $useTGroups
     * @access  public
     */
    public function setUseTGroups(bool $useTGroups): void
    {
        $this->_useTGroups = $useTGroups;
    }

    /**
     * Returns the useTGroups value
     * @access   public
     * @return   boolean
     */
    public function getUseTGroups(): bool
    {
        return $this->_useTGroups;
    }

    /**
     * Sets the autoFill value
     * @param   string   $fill
     * @access  public
     */
    public function setAutoFill(string $fill): void
    {
        $this->_autoFill = $fill;
    }

    /**
     * Returns the autoFill value
     * @access   public
     * @return   string
     */
    public function getAutoFill(): string
    {
        return $this->_autoFill;
    }

    /**
     * Sets the autoGrow value
     * @param    bool   $fill
     * @access   public
     */
    public function setAutoGrow(bool $grow): void
    {
        $this->_autoGrow = $grow;
    }

    /**
     * Returns the autoGrow value
     * @access   public
     * @return   bool
     */
    public function getAutoGrow(): bool
    {
        return $this->_autoGrow;
    }

    /**
     * Sets the number of rows in the table
     * @param    int     $rows
     * @access   public
     */
    public function setRowCount(int $rows): void
    {
        $this->_rows = $rows;
    }

    /**
     * Sets the number of columns in the table
     * @param    int     $cols
     * @access   public
     */
    public function setColCount(int $cols): void
    {
        $this->_cols = $cols;
    }

    /**
     * Returns the number of rows in the table
     * @access   public
     * @return   int
     */
    public function getRowCount(): int
    {
        return $this->_rows;
    }

    /**
     * Gets the number of columns in the table
     *
     * If a row index is specified, the count will not take
     * the spanned cells into account in the return value.
     *
     * @param    int    Row index to serve for cols count
     * @access   public
     * @return   int
     */
    public function getColCount(int $row = null): int
    {
        if (!\is_null($row)) {
            $count = 0;
            foreach ($this->_structure[$row] as $cell) {
                if (\is_array($cell)) {
                    $count++;
                }
            }
            return $count;
        }
        return $this->_cols;
    }

    /**
     * Sets a rows type 'TH' or 'TD'
     * @param    int         $row    Row index
     * @param    string      $type   'TH' or 'TD'
     * @access   public
     */
    public function setRowType(int $row, string $type): void
    {
        for ($counter = 0; $counter < $this->_cols; $counter++) {
            if (!$this->isCellSpanned($row, $counter)) {
                $this->_structure[$row][$counter]['type'] = $type;
            }
        }
    }

    /**
     * Sets a columns type 'TH' or 'TD'
     * @param    int         $col    Column index
     * @param    string      $type   'TH' or 'TD'
     * @access   public
     */
    public function setColType(int $col, string $type): void
    {
        for ($counter = 0; $counter < $this->_rows; $counter++) {
            if (!$this->isCellSpanned($counter, $col)) {
                $this->_structure[$counter][$col]['type'] = $type;
            }
        }
    }

    /**
     * Sets the cell attributes for an existing cell.
     *
     * If the given indices do not exist and autoGrow is true then the given
     * row and/or col is automatically added.  If autoGrow is false then an
     * error is returned.
     * @param    int        $row         Row index
     * @param    int        $col         Column index
     * @param    mixed      $attributes  Associative array or string of table
     *                                   row attributes
     * @access   public
     * @throws   PEAR_Error
     */
    public function setCellAttributes(int $row, int $col, string|array $attributes = null)
    {
        if (
            isset($this->_structure[$row][$col])
            && $this->_structure[$row][$col] == '__SPANNED__'
        ) {
             return;
        }
        $attributes = self::prepareAttributes($attributes);
        $err = $this->_adjustEnds($row, $col, 'setCellAttributes', $attributes);
        if (PEAR::isError($err)) {
            return $err;
        }
        $this->_structure[$row][$col]['attr'] = $attributes;
        $this->_updateSpanGrid($row, $col);
    }

    public function hasCellClass(int $row, int $col, string $class): bool
    {
        $attr = $this->getCellAttributes($row, $col);
        return self::hasClassArray($class, $attr);
    }

    public function addCellClass(int $row, int $col, array|string $class): void
    {
        $attr = $this->getCellAttributes($row, $col);
        $newAttr = self::addClassArray($class, $attr);
        $this->setCellAttributes($row, $col, $newAttr);
    }

    public function removeCellClass(int $row, int $col, array|string $class): void
    {
        $attr = $this->getCellAttributes($row, $col);
        $newAttr = self::removeClassArray($class, $attr);
        $this->setCellAttributes($row, $col, $newAttr);
    }

    /**
     * Searches for a "span base" for a cell.
     *
     *
     * @param \HTML_Table_storage $section
     * @param int $row
     * @param int $col
     * @param string $insert Set if there are added cells above the row. i.e. We are
     *                       in a middle of the insertCol process.
     * @return array Formatted like ['row' => i, 'col' => j]
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function spanBase(int $row, int $col, bool $insert = false): array
    {
        if (!$this->isCellSpanned($row, $col)) {
            return [$row, $col];
        }
        $rowOrig = $row;
        $colOrig = $col;
        while ($row >= 0) {
            $col = $colOrig + ($row < $rowOrig && $insert) * 1;
            while ($col >= 0) {
                if (!$this->isCellSpanned($row, $col)) {
                    $colspan = $this->_structure[$row][$col]['attr']['colspan'] ?? 1;
                    $rowspan = $this->_structure[$row][$col]['attr']['rowspan'] ?? 1;
                    if (($row + $rowspan - 1) >= $rowOrig && ($col + $colspan - 1) >= $colOrig) {
                        return [$row, $col - ($row < $rowOrig && $insert) * 1];
                    }
                    break;
                }
                $col--;
            }
            $row--;
        }
        trigger_error('Malformatted table. Spanned cell does not have a base.');
    }

    /**
     * Splits merged cell horizontally.
     *
     * This affects cells, which are under the influence of a rowspan attribute.
     *
     * @param int $row
     * @param int $col
     * @return void
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function splitSpanHorizontal(int $row, int $col): void
    {
        [$rowS, $colS] = $this->spanBase($row, $col);
        if ($row === $rowS) {
            return;
        }
        $rowSpanBase = $this->_structure[$rowS][$colS]['attr']['rowspan'] ?? 1;
        $rowSpanNew = ($rowS + $rowSpanBase - 1) - $row + 1;
        $rowSpanUpdated = $row - $rowS;
        $this->updateAttrArray($this->_structure[$rowS][$colS]['attr'], ['rowspan' => $rowSpanUpdated]);
        $this->tidyAttr($this->_structure[$rowS][$colS]);
        $cellBase = $this->_structure[$rowS][$colS];
        $this->_structure[$row][$colS] = ['type' => '', 'contents' => '', 'attr' => []];
        $this->_structure[$row][$colS]['type'] = $cellBase['type'];
        $this->_structure[$row][$colS]['attr'] = $cellBase['attr'];
        $this->updateAttrArray($this->_structure[$row][$colS]['attr'], ['rowspan' => $rowSpanNew]);
        $this->tidyAttr($this->_structure[$row][$colS]);
        $this->_updateSpanGrid($row, $colS);
    }

    /**
     * Splits merged cell vertically.
     *
     * This affects cells, which are under the influence of a colspan attribute.
     *
     * @param int $row
     * @param int $col
     * @return void
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function splitSpanVertical(int $row, int $col): void
    {
        [$rowS, $colS] = $this->spanBase($row, $col);
        if ($col === $colS) {
            return;
        }
        $colSpanBase = $this->_structure[$rowS][$colS]['attr']['colspan'] ?? 1;
        $colSpanNew = ($colS + $colSpanBase - 1) - $col + 1;
        $colSpanUpdated = $col - $colS;
        $this->updateAttrArray($this->_structure[$rowS][$colS]['attr'], ['colspan' => $colSpanUpdated]);
        $this->tidyAttr($this->_structure[$rowS][$colS]);
        $cellBase = $this->_structure[$rowS][$colS];
        $this->_structure[$rowS][$col] = ['type' => '', 'contents' => '', 'attr' => []];
        $this->_structure[$rowS][$col]['type'] = $cellBase['type'];
        $this->_structure[$rowS][$col]['attr'] = $cellBase['attr'];
        $this->updateAttrArray($this->_structure[$rowS][$col]['attr'], ['colspan' => $colSpanNew]);
        $this->tidyAttr($this->_structure[$rowS][$col]);
        $this->_updateSpanGrid($row, $colS);
    }

    /**
     * Updates the cell attributes passed but leaves other existing attributes
     * intact
     * @param    int     $row         Row index
     * @param    int     $col         Column index
     * @param    mixed   $attributes  Associative array or string of table row
     *                                attributes
     * @access   public
     */
    public function updateCellAttributes(int $row, int $col, string|array $attributes = null)
    {
        if (
            isset($this->_structure[$row][$col])
            && $this->_structure[$row][$col] == '__SPANNED__'
        ) {
            return;
        }
        $attributes = self::prepareAttributes($attributes);
        $err = $this->_adjustEnds($row, $col, 'updateCellAttributes', $attributes);
        if (PEAR::isError($err)) {
            return $err;
        }
        $this->_structure[$row][$col]['attr'] ??= [];
        $this->updateAttrArray($this->_structure[$row][$col]['attr'], $attributes);
        $this->_updateSpanGrid($row, $col);
    }

    /**
     * Returns the attributes for a given cell
     * @param    int     $row         Row index
     * @param    int     $col         Column index
     * @return   array
     * @access   public
     */
    public function getCellAttributes(int $row, int $col): array
    {
        if (
            isset($this->_structure[$row][$col])
            && $this->_structure[$row][$col] != '__SPANNED__'
        ) {
            if (isset($this->_structure[$row][$col]['attr'])) {
                return $this->_structure[$row][$col]['attr'];
            } else {
                return [];
            }
        } elseif (!isset($this->_structure[$row][$col])) {
            return PEAR::raiseError('Invalid table cell reference[' .
                $row . '][' . $col . '] in HTML_Table::getCellAttributes');
        }
        return [];
    }

    /**
     * Sets the cell contents for an existing cell
     *
     * If the given indices do not exist and autoGrow is true then the given
     * row and/or col is automatically added.  If autoGrow is false then an
     * error is returned.
     * @param    int      $row        Row index
     * @param    int      $col        Column index
     * @param    mixed    $contents   May contain html or any object with a
     *                                toHTML() method; if it is an array (with
     *                                strings and/or objects), $col will be used
     *                                as start offset and the array elements will
     *                                be set to this and the following columns
     *                                in $row
     * @param    string   $type       (optional) Cell type either 'TH' or 'TD'
     * @access   public
     * @throws   PEAR_Error
     */
    public function setCellContents(int $row, int $col, $contents, string $type = 'TD')
    {
        if (\is_array($contents)) {
            foreach ($contents as $singleContent) {
                $ret = $this->_setSingleCellContents(
                    $row,
                    $col,
                    $singleContent,
                    $type
                );
                if (PEAR::isError($ret)) {
                    return $ret;
                }
                $col++;
            }
        } else {
            $ret = $this->_setSingleCellContents($row, $col, $contents, $type);
            if (PEAR::isError($ret)) {
                return $ret;
            }
        }
    }

    /**
     * Sets the cell contents for a single existing cell
     *
     * If the given indices do not exist and autoGrow is true then the given
     * row and/or col is automatically added.  If autoGrow is false then an
     * error is returned.
     * @param    int      $row        Row index
     * @param    int      $col        Column index
     * @param    mixed    $contents   May contain html or any object with a
     *                                toHTML() method; if it is an array (with
     *                                strings and/or objects), $col will be used
     *                                as start offset and the array elements will
     *                                be set to this and the following columns
     *                                in $row
     * @param    string   $type       (optional) Cell type either 'TH' or 'TD'
     * @access   private
     * @throws   PEAR_Error
     */
    private function _setSingleCellContents(int $row, int $col, $contents, string $type = 'TD')
    {
        if (
            isset($this->_structure[$row][$col])
            && $this->_structure[$row][$col] == '__SPANNED__'
        ) {
            return;
        }
        $err = $this->_adjustEnds($row, $col, 'setCellContents');
        if (PEAR::isError($err)) {
            return $err;
        }
        $this->_structure[$row][$col]['contents'] = $contents;
        $this->_structure[$row][$col]['type'] = $type;
    }

    /**
     * Updates the contents of cells in a column.
     *
     * @param int $col
     * @param array $contents   Array of strings
     * @param array|string $type
     * @return void
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function updateColContents(int $col, array $contents = null, array|string $type = 'td'): void
    {
        if ($col < 0 || $col >= $this->getColCount()) {
            return;
        }
        $cellTypes = \array_map('strtolower', \is_array($type) ? $type : \array_fill(0, \count($contents), $type));
        foreach ($contents as $row => $content) {
            if ($cellTypes[$row] === 'td') {
                $this->setCellContents($row, $col, $content);
            } elseif ($cellTypes[$row] === 'th') {
                $this->setHeaderContents($row, $col, $content);
            }
        }
    }

    /**
     * Returns the cell contents for an existing cell.
     * For __SPANNED__ cell returns null.
     *
     * @param    int        $row    Row index
     * @param    int        $col    Column index
     * @access   public
     * @return   mixed
     */
    public function getCellContents(int $row, int $col)
    {
        if (
            isset($this->_structure[$row][$col])
            && $this->_structure[$row][$col] == '__SPANNED__'
        ) {
            return null;
        }
        if (!isset($this->_structure[$row][$col])) {
            return PEAR::raiseError('Invalid table cell reference[' .
                $row . '][' . $col . '] in HTML_Table::getCellContents');
        }
        return $this->_structure[$row][$col]['contents'];
    }

    /**
     * Returns cell's type.
     *
     * If the referenced cell is not defined or the cell is spanned, the method
     * returns null.
     *
     * @param int $row
     * @param int $col
     * @return string|null
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function getCellType(int $row, int $col): ?string
    {
        if (!isset($this->_structure[$row][$col]) || $this->isCellSpanned($row, $col)) {
            return null;
        }
        return \strtolower($this->_structure[$row][$col]['type'] ?? 'td');
    }

    public function setCellType(int $row, int $col, string $type = 'td'): void
    {
        $type = \strtolower($type);
        if (!isset($this->_structure[$row][$col]) || $this->isCellSpanned($row, $col)) {
            return;
        }
        $this->_structure[$row][$col]['type'] = $type;
    }



    /**
     * Tests if the cell is spanned, i.e. it equals to '__SPANNED__'.
     *
     * @param   int $row
     * @param   int $col
     * @return  bool|null   Returns null if cell specified by $row & $col arguments
     *                      does not exist.
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function isCellSpanned(int $row, int $col): ?bool
    {
        if (!isset($this->_structure[$row][$col])) {
            return null;
        }
        return $this->_structure[$row][$col] === '__SPANNED__';
    }

    /**
     * Splits all rowspan cells on a row.
     *
     * @param int $row
     * @return void
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function splitAtRow(int $row): void
    {
        if ($row < 0 || ($row + 1) > $this->getRowCount()) {
            return;
        }
        $col = 0;
        while ($col < $this->getColCount()) {
            [$rowS, $colS] = $this->spanBase($row, $col);
            if ($rowS < $row) {
                $this->splitSpanHorizontal($row, $col);
            }
            $col++;
        }
    }

    /**
     * Splits all colspan cells on a column.
     *
     * @param int $col
     * @return void
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function splitAtCol(int $col): void
    {
        if ($col < 0 || ($col + 1) > $this->getColCount()) {
            return;
        }
        $row = 0;
        while ($row < $this->getRowCount()) {
            [$rowS, $colS] = $this->spanBase($row, $col);
            if ($colS < $col) {
                $this->splitSpanVertical($row, $col);
            }
            $row++;
        }
    }

    /**
     * Removes rows from table and retuns them in an array.
     *
     * @param int $rowStart
     * @param int|null $rowEnd
     * @return array
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function cutRows(int $rowStart, ?int $rowEnd = null): array
    {
        $rowEnd = $rowEnd ?? $rowStart;
        if ($rowStart < 0 || ($rowEnd + 1) > $this->getRowCount()) {
            trigger_error('row arguments out of bounds.', E_USER_ERROR);
        }
        $length = $rowEnd - $rowStart + 1;
        $this->splitAtRow($rowEnd + 1);
        $this->splitAtRow($rowStart);
        $this->setRowCount($this->getRowCount() - $length);
        return \array_splice($this->_structure, $rowStart, $length);
    }

    /**
     * Pastes rows cut with cutRows() into table.
     *
     * @param array $arr
     * @param int|null $atRow
     * @param string|null   $type   If set, the cell types of the pasted rows are set explicitly.
     * @return void
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function pasteRows(array $arr, ?int $atRow = null, ?string $type = null): void
    {
        $rowCountOrig = $this->getRowCount();
        $atRow = $atRow ?? $rowCountOrig;
        $atRow = $atRow < 0 ? 0 : $atRow;
        if ($atRow < 0 || $atRow > $rowCountOrig) {
            trigger_error("atRow argument (val = $atRow) out of bounds.", E_USER_ERROR);
        }
        $this->splitAtRow($atRow);
        $tmp = [];
        if ($atRow <= $rowCountOrig - 1) {
            $tmp = \array_splice($this->_structure, $atRow);
        }
        $this->_structure = \array_merge($this->_structure, $arr);
        if (count($tmp)) {
            $this->_structure = \array_merge($this->_structure, $tmp);
        }
        $this->setRowCount($rowCountOrig + count($arr));
        if ($rowCountOrig === 0) {
            $noCols = array_key_exists('attr', $arr[0]) ? count($arr[0]) - 1 : count($arr[0]);
            $this->setColCount($noCols);
        }
        if (isset($type)) {
            $row = 0;
            while ($row < \count($arr)) {
                $this->setRowType($row + $atRow, $type);
                $row++;
            }
        }
    }

    /**
     * Removes one or more columns from table and returns removed columns
     * as an array.
     *
     * NOTE: Be careful with this method. Value of $length parameter is not
     * tested. This should be called only after calling splitAtCol method first.
     * Calling splitAtCol first ensures colspan attributes are set right.
     *
     * @param int       $col    First column to be removed.
     * @param int|null  $length How many columns to remove. If null, removes
     *                          all columns from $col to end of the table.
     * @return array            Removed columns.
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    private function sliceAtCol(int $col, ?int $length = null): array
    {
        if ($col < 0 || $col >= $this->getColCount()) {
            trigger_error("col argument (val = $col) is out of bounds.", E_USER_ERROR);
        }
        $length = $length ?? $this->getColCount() - $col;
        $row = 0;
        $out = [];
        while ($row < $this->getRowCount()) {
            $attr = $this->_structure[$row]['attr'] ?? [];
            unset($this->_structure[$row]['attr']);
            $out[] = \array_splice($this->_structure[$row], $col, $length);
            if (\count($attr)) {
                $this->_structure[$row]['attr'] = $attr;
            }
            $row++;
        }
        return $out;
    }

    /**
     * Cuts one or more columns from table and return them in an array.
     *
     * Columns cut from table can later be pasted back to Storage object with
     * pasteCols method.
     *
     * @param int       $colStart   First column to be cut from table
     * @param int|null  $colEnd     Last column to be cut from table. If this is not
     *                              set, only one column is cut.
     * @return array                Columns cut from table in an array.
     * @see HTML_Table_Storage::pasteCols
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function cutCols(int $colStart, ?int $colEnd = null): array
    {
        $colEnd = $colEnd ?? $colStart;
        if ($colStart < 0 || $colEnd  >= $this->getColCount()) {
            trigger_error('Col arguments out of bounds.', E_USER_ERROR);
        }
        $noCols = $this->getColCount();
        $length = $colEnd - $colStart + 1;
        $this->splitAtCol($colEnd + 1);
        $this->splitAtCol($colStart);
        $out = $this->sliceAtCol($colStart, $length);
        $this->setColCount($noCols - $length);
        return $out;
    }

    /**
     * Cuts one or more columns from table and return them in an array.
     *
     * Columns cut from table can later be pasted back to Storage object with
     * pasteCols method.
     *
     * This differs from cutCols in the way how spanned cols are handled.
     *
     * @param int       $colStart   First column to be cut from table
     * @param int|null  $colEnd     Last column to be cut from table. If this is not
     *                              set, only one column is cut.
     * @return array                Columns cut from table in an array.
     * @see HTML_Table_Storage::pasteCols
     * @see HTML_Table_Storage::cutCols
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function deleteCols(int $colStart, ?int $colEnd = \null): array
    {
        $colEnd = $colEnd ?? $colStart;
        if ($colStart < 0 || $colEnd  >= $this->getColCount()) {
            trigger_error('Col arguments out of bounds.', E_USER_ERROR);
        }
        $noCols = $this->getColCount();
        $length = $colEnd - $colStart + 1;

        $row = 0;
        while ($row < $this->getRowCount()) {
            [$rowS, $colS1] = $this->spanBase($row, $colStart);
            [$rowS, $colS2] = $this->spanBase($row, $colStart + $length);
            $colspan = $this->_structure[$row][$colS1]['attr']['colspan'] ?? 1;
            $rowspan = $this->_structure[$row][$colS1]['attr']['rowspan'] ?? 1;

            if ($colS1 === $colS2 && $colS1 < $colStart && $colspan > ($colStart + $length)) {
                $this->_structure[$rowS][$colS1]['attr']['colspan'] = $colspan - $length;
                $row += $rowspan;
                continue;
            }
            $this->splitSpanVertical($row, $colStart + $length);
            $this->splitSpanVertical($row, $colStart);
            $row++;
        }
        $out = $this->sliceAtCol($colStart, $length);
        $this->setColCount($noCols - $length);
        return $out;
    }


    /**
     * Adds one or more columns onto the end of table.
     *
     * @param array $arr Array of columns to be added to table.
     *                   Array is expected to have right number of rows.
     * @return void
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    private function appendCols(array $arr): void
    {
        $row = 0;
        while ($row < $this->getRowCount()) {
            $attr = $this->_structure[$row]['attr'] ?? [];
            unset($this->_structure[$row]['attr']);
            $this->_structure[$row] = \array_merge($this->_structure[$row], $arr[$row]);
            if (\count($attr)) {
                $this->_structure[$row]['attr'] = $attr;
            }
            $row++;
        }
    }

    /**
     * Removes unnecessary attributes from a table cell.
     *
     * @param array $cell
     * @return array        Cleaned table cell.
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    private function tidyAttr(array &$cell): void
    {
        if (in_array('__SPANNED__', $cell)) {
            return;
        }
        if (isset($cell['attr']) && count($cell['attr']) === 0) {
            unset($cell['attr']);
            return;
        }
        $rowSpan = $cell['attr']['rowspan'] ?? 1;
        $colSpan = $cell['attr']['colspan'] ?? 1;
        if ($rowSpan === 1) {
            unset($cell['attr']['rowspan']);
        }
        if ($colSpan === 1) {
            unset($cell['attr']['colspan']);
        }
    }

    /**
     *
     * @param string|array $cell
     * @param int $row
     * @param int|null $col
     * @return void
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    private function pushToRow(string|array $cell, int $row, ?int $col = null): void
    {
        $col = $col ?? $this->getColCount();
        if ($row < 0 || $row > $this->getRowCount()) {
            return;
        }
        if (is_string($cell)) {
            $cell = $cell !== '__SPANNED__' ? ['contents' => $cell] : ['__SPANNED__'];
        } elseif (array_key_exists('contents', $cell) || array_key_exists('attr', $cell)) {
            $this->tidyAttr($cell);
            $cell = [$cell];
        }
        $attr = $this->_structure[$row]['attr'] ?? [];
        unset($this->_structure[$row]['attr']);
        $arr1 = $col > 0 ? array_slice($this->_structure[$row], 0, $col) : [];
        $arr2 = $col < $this->getColCount() ? array_slice($this->_structure[$row], $col) : [];
        $this->_structure[$row] = \array_merge($arr1, $cell, $arr2);
        if (\count($attr)) {
            $this->_structure[$row]['attr'] = $attr;
        }
    }

    /**
     * Paste columns to table at specific location.
     *
     * @param array     $arr    One or more columns to pasted. Most likely,
     *                          the array is obtained from the cutCols method..
     * @param int|null  $atCol  Starting from which column the new columns are to
     *                          pasted. Old columns are moved right. If atCol is not
     *                          set, new columns are appended to the end of table.
     * @return void
     * @see HTML_Table_storage::cutCols()
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function pasteCols(array $arr, ?int $atCol = null): void
    {
        $atCol = $atCol ?? $this->getColCount();
        if ($atCol < 0 || $atCol > $this->getColCount()) {
            trigger_error("atCol argument (val = $atCol) out of bounds.", E_USER_ERROR);
        }
        if (\count($arr) !== $this->getRowCount()) {
            trigger_error("Row count of arr argument is wrong.", E_USER_ERROR);
        }
        $this->splitAtCol($atCol);
        $tmp = [];
        if ($atCol <= $this->getColCount() - 1) {
            $tmp = $this->sliceAtCol($atCol);
        }
        $this->appendCols($arr);
        if (\count($tmp)) {
            $this->appendCols($tmp);
        }
        $this->setColCount($this->getColCount() + \count($arr[0]));
    }

    /**
     * Merges two adjacent cells.
     *
     * Cells to be merged have to be either on the same row
     * or on the same column. If rows are on the same column,
     * they have to have the same colspan value. Correspondingly
     * cells on the same row have to have the same rowspan value.
     *
     * @param   int         $row1
     * @param   int         $col1
     * @param   int         $row2
     * @param   int         $col2
     * @param   string|null $glue   String used to combine the strings from the merged
     *                              cells. Defaults to '<br>' or ' '.
     * @return  void
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function mergeCells(int $row1, int $col1, int $row2, int $col2, ?string $glue = null)
    {
        [$rowS1, $colS1] = $this->spanBase($row1, $col1);
        [$rowS2, $colS2] = $this->spanBase($row2, $col2);
        $vertical = ($colS1 === $colS2);
        $horizontal = ($rowS1 === $rowS2);
        if (($vertical && $horizontal) || (!$vertical && !$horizontal)) {
            return;
        }
        $colSpan1 = (int) ($this->_structure[$rowS1][$colS1]['attr']['colspan'] ?? 1);
        $rowSpan1 = (int) ($this->_structure[$rowS1][$colS1]['attr']['rowspan'] ?? 1);
        $colSpan2 = (int) ($this->_structure[$rowS2][$colS2]['attr']['colspan'] ?? 1);
        $rowSpan2 = (int) ($this->_structure[$rowS2][$colS2]['attr']['rowspan'] ?? 1);
        if (
            ($vertical && ($colSpan1 !== $colSpan2)) ||
            ($horizontal && ($rowSpan1 !== $rowSpan2))
        ) {
            return;
        }
        $firstCell =  ($horizontal) ? ($colS2 > $colS1) : ($rowS2 > $rowS1);
        $c1 = $firstCell ? $colS1 : $colS2;
        $r1 = $firstCell ? $rowS1 : $rowS2;
        $c2 = $firstCell ? $colS2 : $colS1;
        $r2 = $firstCell ? $rowS2 : $rowS1;
        $cSpan1 = $firstCell ? $colSpan1 : $colSpan2;
        $rSpan1 = $firstCell ? $rowSpan1 : $rowSpan2;
        if (
            ($vertical && ($rowS1 + $rSpan1 !== $rowS2)) ||
            ($horizontal && ($colS1 + $cSpan1 !== $colS2))
        ) {
            return;
        }
        $glue = $glue ?? ($horizontal ? ' ' : '<br>');
        $glue = \strlen($this->_structure[$r1][$c1]['contents']) ? $glue : '';
        if (strlen($this->_structure[$r2][$c2]['contents'])) {
            $this->_structure[$r1][$c1]['contents'] .= $glue . $this->_structure[$r2][$c2]['contents'];
        }
        if ($horizontal) {
            $this->_structure[$r1][$c1]['attr']['colspan'] = $colSpan1 + $colSpan2;
        } else {
            $this->_structure[$r1][$c1]['attr']['rowspan'] = $rowSpan1 + $rowSpan2;
        }
        $this->tidyAttr($this->_structure[$r1][$c1]);
        $this->tidyAttr($this->_structure[$r2][$c2]);
        $this->_structure[$r2][$c2] = '__SPANNED__';
    }

    /**
     * Combines one column with the one on its right side.
     *
     * @param int       $col    The column to be merged.
     * @param string    $glue   String used as glue when merging cells. If the cell
     *                          of the first column is empty, glue is not used.
     * @param string    $indent This string is used as glue when the first cell is empty.
     * @return void
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function mergeColToRight(int $col, string $glue = ' ', string $indent = ''): void
    {
        if ($col < 0 || ($col + 1) >= $this->getColCount()) {
            return;
        }
        $this->splitAtCol($col);
        $row = 0;
        while ($row < $this->getRowCount()) {
            [$sR1, $sC1] = $this->spanBase($row, $col);
            [$sR2, $sC2] = $this->spanBase($row, $col + 1);
            if ([$sR1, $sC1] === [$sR2, $sC2]) {
                $row++;
                continue;
            }
            if (($row + 1) <= $this->getRowCount()) {
                $this->splitSpanHorizontal($row + 1, $col);
                $this->splitSpanHorizontal($row + 1, $col + 1);
            }
            if (
                \strlen($indent) &&
                !$this->isCellSpanned($row, $col) &&
                \strlen(\trim($this->_structure[$row][$col]['contents'])) === 0
            ) {
                $this->_structure[$row][$col]['contents'] = $indent;
                $glue = '';
            }
            $this->mergeCells($row, $col, $row, $col + 1, $glue);
            $row++;
        }
    }

    /**
     * Inserts an empty column into the table.
     *
     * @param null|int $col New empty column is inserted to this column id. If
     *                      null, then column is inserted at the end of the table.
     * @return void
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function insertCol(?int $col = null, string $type = 'TD'): void
    {
        $col = $col ?? $this->getColCount();
        if ($col < 0 || $col > $this->getColCount()) {
            return;
        }
        $row = 0;
        while ($row < $this->getRowCount()) {
            if ($col === 0 || $col === $this->getColCount()) {
                $this->pushToRow(['contents' => '', 'type' => $type], $row, $col);
                $row++;
                continue;
            }

            [$sR, $sC] = $this->spanBase($row, $col, true);
            $sColSpan = $this->_structure[$sR][$sC]['attr']['colspan'] ?? 1;
            $attr = $this->getCellAttributes($row, $col);
            unset($attr['rowspan']);
            unset($attr['colspan']);
            if ($sC === $col) {
                $cell = ['contents' => '', 'attr' => $attr, 'type' => $type];
                $this->pushToRow($cell, $row, $col);
                $row++;
                continue;
            }
            $cell = '__SPANNED__';
            if ($sR === $row) {
                $this->_structure[$row][$sC]['attr']['colspan'] = $sColSpan + 1;
            }
            $this->pushToRow($cell, $row, $col);
            $row++;
        }
        $this->setColCount($this->getColCount() + 1);
    }

   /**
     * Inserts an empty row into the table.
     *
     * @param null|int $row New empty row is inserted to this column id. If
     *                      null, then row is inserted at the end of the table.
     * @return void
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function insertRow(?int $row = null, string $type = 'td'): void
    {
        $row = $row ?? $this->getRowCount();
        if ($row < 0 || $row > $this->getRowCount()) {
            return;
        }
        $newRow = [];
        if ($row === 0 || $row === $this->getRowCount()) {
            $newRow = array_fill(0, $this->getColCount(), ['contents' => '', 'type' => $type]);
        }
        $col = 0;
        while ($col < $this->getColCount() && !($row === 0 || $row === $this->getRowCount())) {
            [$sR, $sC] = $this->spanBase($row, $col);
            $sColSpan = $this->_structure[$sR][$sC]['attr']['colspan'] ?? 1;
            $sRowSpan = $this->_structure[$sR][$sC]['attr']['rowspan'] ?? 1;
            $attr = $this->getCellAttributes($row, $col);
            unset($attr['rowspan']);
            unset($attr['colspan']);
            if ($sR === $row) {
                $cell = ['contents' => '', 'attr' => $attr, 'type' => $type];
                $newRow[$col] = $cell;
                $col++;
                continue;
            }
            $cell = '__SPANNED__';
            if ($sC === $col) {
                $this->_structure[$sR][$col]['attr']['rowspan'] = $sRowSpan + 1;
            }
            $newRow[$col] = $cell;
            $col++;
        }
        $arr1 = $row > 0 ? \array_slice($this->_structure, 0, $row) : [];
        $arr2 = $row < $this->getRowCount() ? \array_slice($this->_structure, $row) : [];
        $this->_structure = \array_merge($arr1, [$newRow], $arr2);
        $this->setRowCount($this->getRowCount() + 1);
    }

    /**
     * Inserts a new column and merges it to the previous column.
     *
     * @param   int     $col    Column id to which the new column is appended. I.e. this
     *                          IS NOT the id of the new column.
     * @return  void
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function appendSpannedCol(int $col): void
    {
        if ($col < 0 || $col >= $this->getColCount() - 1) {
            return;
        }
        $row = 0;
        while ($row < $this->getRowCount()) {
            [$sR, $sC] = $this->spanBase($row, $col, true);
            $sColSpan = $this->_structure[$sR][$sC]['attr']['colspan'] ?? 1;
            $attr = $this->getCellAttributes($row, $col);
            unset($attr['rowspan']);
            unset($attr['colspan']);
            $cell = '__SPANNED__';
            $this->pushToRow($cell, $row, $col + 1);
            if ($sR === $row) {
                $this->_structure[$row][$sC]['attr']['colspan'] = $sColSpan + 1;
            }
            $row++;
        }
        $this->setColCount($this->getColCount() + 1);
    }

    /**
     * Tests if a cell is withing a specified area.
     *
     * @param int $rowTop
     * @param int $colLeft
     * @param int $rowBottom
     * @param int $colRight
     * @param int $row
     * @param int $col
     * @return bool
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    private static function isCellInArea(int $rowTop, int $colLeft, int $rowBottom, int $colRight, int $row, int $col): bool
    {
        return $row >= $rowTop && $row <= $rowBottom && $col >= $colLeft && $col <= $colRight;
    }

    /**
     * Copies cells from a storage structure.
     *
     * @param int $row1
     * @param int $col1
     * @param int $row2
     * @param int $col2
     * @param bool $keepKeys
     * @return array
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function copyCells(int $row1, int $col1, int $row2, int $col2, bool $keepKeys = false): array
    {
        if (
            (\min($row1, $row2, $col1, $col2) < 0) || \max($row1, $row2)  >= $this->getRowCount() ||
                \max($col1, $col2) >= $this->getColCount()
        ) {
            trigger_error("Argument out of bounds.", E_USER_ERROR);
        }
        $rowS = $row1 < $row2 ? $row1 : $row2;
        $colS = $col1 < $col2 ? $col1 : $col2;
        $rowE = $row2 > $row1 ? $row2 : $row1;
        $colE = $col2 > $col1 ? $col2 : $col1;
        if ($keepKeys) {
            $arr = \array_fill($rowS, $rowE - $rowS + 1, \array_fill($colS, $colE - $colS + 1, ''));
        } else {
            $arr = \array_fill(0, $rowE - $rowS + 1, \array_fill(0, $colE - $colS + 1, ''));
        }
        $row = $rowS;
        $i = 0;
        $cellData = [];
        while ($row <= $rowE) {
            $col = $colS;
            $j = 0;
            while ($col <= $colE) {
                // Checks if the copied cell has a colspan/rowspan setting.
                // If the setting is found, it is defined so that the span ends
                // at the border of the area to be copied.
                // If a spanned cell is copied, and the spanbase is not inside the area
                // to be copied, the value of the cell is set to null.
                // This is handled in the pasteCells method so that the copied cell is
                // not attached to the target.
                if ($this->isCellSpanned($row, $col)) {
                    [$rowSpanBase, $colSpanBase] = $this->spanBase($row, $col);
                    $cellData = self::isCellInArea($rowS, $colS, $rowE, $colE, $rowSpanBase, $colSpanBase) ?
                        '__SPANNED__' : null;
                } else {
                    $rowSpan = $this->_structure[$row][$col]['attr']['rowspan'] ?? 1;
                    $rowSpan = ($row + $rowSpan - 1 > $rowE) ? $rowE - $row + 1 : $rowSpan;
                    $colSpan = $this->_structure[$row][$col]['attr']['colspan'] ?? 1;
                    $colSpan = ($col + $colSpan - 1 > $colE) ? $colE - $col + 1 : $colSpan;
                    $cellData = $this->_structure[$row][$col];
                    if (!isset($cellData['attr'])) {
                        $cellData['attr'] = [];
                    }
                    $cellData['attr']['rowspan'] = $rowSpan;
                    $cellData['attr']['colspan'] = $colSpan;
                    $this->tidyAttr($cellData);
                }
                if ($keepKeys) {
                    $arr[$row][$col] = $cellData;
                } else {
                    $arr[$i][$j] = $cellData;
                }
                $col++;
                $j++;
            }

            $row++;
            $i++;
        }
        return $arr;
    }

    /**
     * Pastes cells copied with copyCells method to a storage.
     *
     * NOTE: We expect, that $cells array is returned by copyCells method.
     * Spanned cells won't be overwritten by this method. To overwrite spanned
     * cells, use splitSpanHorizontal and splitSpanVertical methods.
     *
     * @param array $cells
     * @param int $row
     * @param int $col
     * @param string $type
     * @return void
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function pasteCells(array $cells, int $row, int $col, string $type = 'td'): void
    {
        if ($row < 0 || $row >= $this->getRowCount() || $col < 0 || $col >= $this->getColCount()) {
            return;
        }
        // The cells to be pasted must fit inside the storage!
        if ($row + \count($cells) - 1  >= $this->getRowCount() || $col + \count($cells[0]) - 1 >= $this->getColCount()) {
            return;
        }
        // $i & $j: indexes of _structure
        // $iCell & $jCell: indexes of $cells array
        $iCellMax = \count($cells) - 1;
        $iMax = $row + $iCellMax;
        $i = $iMax;
        $iCell = $iCellMax;
        while ($i >= $row) {
            $jCellMax = \count($cells[$iCell]) - 1;
            $jMax = $col + $jCellMax;
            $j = $jMax;
            $jCell = $jCellMax;
            while ($j >= $col) {
                // We must be able to paste a spanned cell, because, for example,
                // adjacent cells to be connected can have the setting colspan=2
                // in the first cell.
                // However, have to make sure that the span does not exceed the cells to be pasted.
                // + This is ensured when copying cells in the copyCells method.
                // We also need to make sure that if we paste to a cell with a
                // colspan/rowspan setting, there will be no "orphaned" spanned cells outside the
                // pasted cells.
                // + This can be ensured by using splitSpan methods before joining.
                if (\is_null($cells[$iCell][$jCell])) {
                    $j--;
                    $jCell--;
                    continue;
                }
                if ($j === $jMax && $this->isCellSpanned($i, $j + 1) === true) {
                    $this->splitSpanVertical($i, $j + 1);
                }
                if ($j === $col && $this->isCellSpanned($i, $j) === true) {
                    $this->splitSpanVertical($i, $j);
                }
                if ($i === $iMax && $this->isCellSpanned($i + 1, $j) === true) {
                    $this->splitSpanHorizontal($i + 1, $j);
                }
                if ($i === $row && $this->isCellSpanned($i, $j) === true) {
                    $this->splitSpanHorizontal($i, $j);
                }
                //~ if (!is_null($cells[$iCell][$jCell])) {
                    $this->_structure[$i][$j] = isset($cells[$iCell][$jCell]['contents']) ?
                        $cells[$iCell][$jCell] : '__SPANNED__';
                //~ }
                $j--;
                $jCell--;
            }
            $i--;
            $iCell--;
        }
    }

    /**
     * Sets the contents of a header cell
     * @param    int     $row
     * @param    int     $col
     * @param    mixed   $contents
     * @param    mixed   $attributes  Associative array or string of table row
     *                                attributes
     * @access   public
     */
    public function setHeaderContents(int $row, int $col, $contents, string|array $attributes = null): void
    {
        $this->setCellContents($row, $col, $contents, 'TH');
        if (!\is_null($attributes)) {
            $this->updateCellAttributes($row, $col, $attributes);
        }
    }

    /**
     * Adds a table row and returns the row identifier
     * @param    array    $contents   (optional) Must be a indexed array of valid
     *                                           cell contents
     * @param    mixed    $attributes (optional) Associative array or string of
     *                                           table row attributes. This can
     *                                           also be an array of attributes,
     *                                           in which case the attributes
     *                                           will be repeated in a loop.
     * @param    string   $type       (optional) Cell type either 'th' or 'td'
     * @param    bool     $inTR                  false if attributes are to be
     *                                           applied in TD tags; true if
     *                                           attributes are to be applied in
     *                                            TR tag
     * @return   int
     * @access   public
     */
    public function addRow(
        array $contents = null,
        string|array $attributes = null,
        array|string $type = 'td',
        bool $inTR = false
    ): int {
        if (isset($contents) && !is_array($contents)) {
            return PEAR::raiseError('First parameter to HTML_Table::addRow ' .
                                    'must be an array');
        }
        if (is_null($contents)) {
            $contents = array();
        }

        $row = $this->_rows++;
        $type = is_array($type) ? array_map('strtolower', $type) : strtolower($type);
        foreach ($contents as $col => $content) {
            if ((is_string($type) && $type === 'td') || (is_array($type) && $type[$col] === 'td')) {
                $this->setCellContents($row, $col, $content);
            } elseif ((is_string($type) && $type === 'th') || (is_array($type) && $type[$col] === 'th')) {
                $this->setHeaderContents($row, $col, $content);
            }
        }
        $this->setRowAttributes($row, $attributes, $inTR);
        return $row;
    }

    /**
     * Sets the row attributes for an existing row
     * @param    int      $row            Row index
     * @param    mixed    $attributes     Associative array or string of table
     *                                    row attributes. This can also be an
     *                                    array of attributes, in which case the
     *                                    attributes will be repeated in a loop.
     * @param    bool     $inTR           false if attributes are to be applied
     *                                    in TD tags; true if attributes are to
     *                                    be applied in TR tag
     * @access   public
     * @throws   PEAR_Error
     */
    public function setRowAttributes(int $row, string|array $attributes = null, bool $inTR = false)
    {
        if (!$inTR) {
            $multiAttr = $this->_isAttributesArray($attributes);
            for ($i = 0; $i < $this->_cols; $i++) {
                if ($multiAttr) {
                    $this->setCellAttributes(
                        $row,
                        $i,
                        $attributes[$i - ((\ceil(($i + 1) / \count($attributes))) - 1) * \count($attributes)]
                    );
                } else {
                    $this->setCellAttributes($row, $i, $attributes);
                }
            }
        } else {
            $attributes = self::prepareAttributes($attributes);
            $err = $this->_adjustEnds($row, 0, 'setRowAttributes', $attributes);
            if (PEAR::isError($err)) {
                return $err;
            }
            $this->_structure[$row]['attr'] = $attributes;
        }
    }

    /**
     * Updates the row attributes for an existing row
     * @param    int      $row            Row index
     * @param    mixed    $attributes     Associative array or string of table
     *                                    row attributes
     * @param    bool     $inTR           false if attributes are to be applied
     *                                    in TD tags; true if attributes are to
     *                                    be applied in TR tag
     * @access   public
     * @throws   PEAR_Error
     */
    public function updateRowAttributes(int $row, string|array $attributes = null, bool $inTR = false)
    {
        if (!$inTR) {
            $multiAttr = $this->_isAttributesArray($attributes);
            for ($i = 0; $i < $this->_cols; $i++) {
                if ($multiAttr) {
                    $this->updateCellAttributes(
                        $row,
                        $i,
                        $attributes[$i - ((\ceil(($i + 1) / \count($attributes))) - 1) * \count($attributes)]
                    );
                } else {
                    $this->updateCellAttributes($row, $i, $attributes);
                }
            }
        } else {
            $attributes = self::prepareAttributes($attributes);
            $err = $this->_adjustEnds($row, 0, 'updateRowAttributes', $attributes);
            if (PEAR::isError($err)) {
                return $err;
            }
            $this->_structure[$row]['attr'] ??= [];
            $this->updateAttrArray($this->_structure[$row]['attr'], $attributes);
        }
    }

    /**
     * Returns the attributes for a given row as contained in the TR tag
     * @param    int     $row         Row index
     * @return   array
     * @access   public
     */
    public function getRowAttributes(int $row): array
    {
        if (isset($this->_structure[$row]['attr'])) {
            return $this->_structure[$row]['attr'];
        }
        return [];
    }

    /**
     * Alternates the row attributes starting at $start
     * @param    int      $start            Row index of row in which alternating
     *                                      begins
     * @param    mixed    $attributes1      Associative array or string of table
     *                                      row attributes
     * @param    mixed    $attributes2      Associative array or string of table
     *                                      row attributes
     * @param    bool     $inTR             false if attributes are to be applied
     *                                      in TD tags; true if attributes are to
     *                                      be applied in TR tag
     * @param    int      $firstAttributes  (optional) Which attributes should be
     *                                      applied to the first row, 1 or 2.
     * @access   public
     */
    public function altRowAttributes(
        int $start,
        string|array $attributes1,
        string|array $attributes2,
        bool $inTR = false,
        int $firstAttributes = 1
    ): void {
        for ($row = $start; $row < $this->_rows; $row++) {
            if (($row + $start + ($firstAttributes - 1)) % 2 == 0) {
                $attributes = $attributes1;
            } else {
                $attributes = $attributes2;
            }
            $this->updateRowAttributes($row, $attributes, $inTR);
        }
    }

    /**
     * Adds a table column and returns the column identifier
     * @param    array    $contents   (optional) Must be a indexed array of valid
     *                                cell contents
     * @param    mixed    $attributes (optional) Associative array or string of
     *                                table row attributes
     * @param    string   $type       (optional) Cell type either 'th' or 'td'
     * @return   int
     * @access   public
     */
    public function addCol(array $contents = null, string|array $attributes = null, string $type = 'td'): int
    {
        if (isset($contents) && !\is_array($contents)) {
            return PEAR::raiseError('First parameter to HTML_Table::addCol ' .
                                    'must be an array');
        }
        if (is_null($contents)) {
            $contents = array();
        }

        $type = \strtolower($type);
        $col = $this->_cols++;
        foreach ($contents as $row => $content) {
            if ($type == 'td') {
                $this->setCellContents($row, $col, $content);
            } elseif ($type == 'th') {
                $this->setHeaderContents($row, $col, $content);
            }
        }
        $this->setColAttributes($col, $attributes);
        return $col;
    }

    /**
     * Sets the column attributes for an existing column
     * @param    int      $col            Column index
     * @param    mixed    $attributes     (optional) Associative array or string
     *                                    of table row attributes
     * @access   public
     */
    public function setColAttributes(int $col, string|array $attributes = null): void
    {
        $multiAttr = $this->_isAttributesArray($attributes);
        for ($i = 0; $i < $this->_rows; $i++) {
            if ($multiAttr) {
                $this->setCellAttributes(
                    $i,
                    $col,
                    $attributes[$i - ((\ceil(($i + 1) / \count($attributes))) - 1) * \count($attributes)]
                );
            } else {
                $this->setCellAttributes($i, $col, $attributes);
            }
        }
    }

    public function addColClass(int $col, array|string $class): void
    {
        for ($i = 0; $i < $this->_rows; $i++) {
            $this->addCellClass($i, $col, $class);
        }
    }

    public function removeColClass(int $col, array|string $class): void
    {
        for ($i = 0; $i < $this->_rows; $i++) {
            $this->removeCellClass($i, $col, $class);
        }
    }

    /**
     * Updates the column attributes for an existing column
     * @param    int      $col            Column index
     * @param    mixed    $attributes     (optional) Associative array or string
     *                                    of table row attributes
     * @access   public
     */
    public function updateColAttributes(int $col, string|array $attributes = null): void
    {
        $multiAttr = $this->_isAttributesArray($attributes);
        for ($i = 0; $i < $this->_rows; $i++) {
            if ($multiAttr) {
                $this->updateCellAttributes(
                    $i,
                    $col,
                    $attributes[$i - ((ceil(($i + 1) / count($attributes))) - 1) * count($attributes)]
                );
            } else {
                $this->updateCellAttributes($i, $col, $attributes);
            }
        }
    }

    /**
     * Sets the attributes for all cells
     * @param    mixed    $attributes        (optional) Associative array or
     *                                       string of table row attributes
     * @access   public
     */
    public function setAllAttributes(string|array $attributes = null): void
    {
        for ($i = 0; $i < $this->_rows; $i++) {
            $this->setRowAttributes($i, $attributes);
        }
    }

    /**
     * Updates the attributes for all cells
     * @param    mixed    $attributes        (optional) Associative array or
     *                                       string of table row attributes
     * @access   public
     */
    public function updateAllAttributes(string|array $attributes = null): void
    {
        for ($i = 0; $i < $this->_rows; $i++) {
            $this->updateRowAttributes($i, $attributes);
        }
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }

    /**
     * Returns the table rows as HTML
     * @access   public
     * @return   string
     */
    public function toHtml($tabs = null, $tab = null): string
    {
        $strHtml = '';
        if (is_null($tabs)) {
            $tabs = $this->getIndent();
        }
        if (is_null($tab)) {
            $tab = self::getOption(Common2::OPTION_INDENT);
        }
        $lnEnd = self::getOption(Common2::OPTION_LINEBREAK);
        if ($this->_useTGroups) {
            $extraTab = $tab;
        } else {
            $extraTab = '';
        }
        if ($this->_cols > 0) {
            for ($i = 0; $i < $this->_rows; $i++) {
                $attr = '';
                if (isset($this->_structure[$i]['attr'])) {
                    $attr = self::getAttributesString($this->_structure[$i]['attr']);
                }
                $strHtml .= $tabs . $tab . $extraTab . '<tr' . $attr . '>' . $lnEnd;
                for ($j = 0; $j < $this->_cols; $j++) {
                    $attr     = [];
                    $contents = '';
                    $type     = 'td';
                    if (isset($this->_structure[$i][$j]) && $this->_structure[$i][$j] == '__SPANNED__') {
                        continue;
                    }
                    if (isset($this->_structure[$i][$j]['type'])) {
                        $type = (\strtolower($this->_structure[$i][$j]['type']) == 'th' ? 'th' : 'td');
                    }
                    if (isset($this->_structure[$i][$j]['attr'])) {
                        $attr = $this->_structure[$i][$j]['attr'];
                    }
                    if (isset($this->_structure[$i][$j]['contents'])) {
                        $contents = $this->_structure[$i][$j]['contents'];
                    }
                    $strHtml .= $tabs . $tab . $tab . $extraTab . "<$type" . self::getAttributesString($attr) . '>';
                    if (is_object($contents)) {
                        // changes indent and line end settings on nested tables
                        if (is_subclass_of($contents, 'HtmlCommon2')) {
                            self::setOption(Common2::OPTION_INDENT, $tab . $extraTab);
                            $contents->setIndentLevel($this->getIndentLevel() + 3);
                            $contents->_nestLevel = $this->_nestLevel + 1;
                            $contents->setLineEnd(self::getOption(Common2::OPTION_LINEBREAK));
                        }
                        if (method_exists($contents, 'toHtml')) {
                            $contents = $contents->toHtml();
                        } elseif (method_exists($contents, 'toString')) {
                            $contents = $contents->toString();
                        }
                    }
                    if (is_array($contents)) {
                        $contents = \implode(', ', $contents);
                    }
                    if (isset($this->_autoFill) && $contents === '') {
                        $contents = $this->_autoFill;
                    }
                    $strHtml .= $contents;
                    $strHtml .= "</$type>" . $lnEnd;
                }
                $strHtml .= $tabs . $tab . $extraTab . '</tr>' . $lnEnd;
            }
        }
        return $strHtml;
    }

    /**
     * Checks if rows or columns are spanned
     * @param    int        $row            Row index
     * @param    int        $col            Column index
     * @access   private
     */
    private function _updateSpanGrid(int $row, int $col): void
    {
        if (isset($this->_structure[$row][$col]['attr']['colspan'])) {
            $colspan = $this->_structure[$row][$col]['attr']['colspan'];
        }

        if (isset($this->_structure[$row][$col]['attr']['rowspan'])) {
            $rowspan = $this->_structure[$row][$col]['attr']['rowspan'];
        }

        if (isset($colspan)) {
            for ($j = $col + 1; (($j < $this->_cols) && ($j <= ($col + $colspan - 1))); $j++) {
                $this->_structure[$row][$j] = '__SPANNED__';
            }
        }

        if (isset($rowspan)) {
            for ($i = $row + 1; (($i < $this->_rows) && ($i <= ($row + $rowspan - 1))); $i++) {
                $this->_structure[$i][$col] = '__SPANNED__';
            }
        }

        if (isset($colspan) && isset($rowspan)) {
            for ($i = $row + 1; (($i < $this->_rows) && ($i <= ($row + $rowspan - 1))); $i++) {
                for ($j = $col + 1; (($j <= $this->_cols) && ($j <= ($col + $colspan - 1))); $j++) {
                    $this->_structure[$i][$j] = '__SPANNED__';
                }
            }
        }
    }

    /**
    * Adjusts ends (total number of rows and columns)
    * @param    int     $row        Row index
    * @param    int     $col        Column index
    * @param    string  $method     Method name of caller
    *                               Used to populate PEAR_Error if thrown.
    * @param    array   $attributes Assoc array of attributes
    *                               Default is an empty array.
    * @access   private
    * @throws   PEAR_Error
    */
    private function _adjustEnds(int $row, int $col, string $method, array $attributes = array())
    {
        $colspan = isset($attributes['colspan']) ? $attributes['colspan'] : 1;
        $rowspan = isset($attributes['rowspan']) ? $attributes['rowspan'] : 1;
        if (($row + $rowspan - 1) >= $this->_rows) {
            if ($this->_autoGrow) {
                $this->_rows = $row + $rowspan;
            } else {
                return PEAR::raiseError('Invalid table row reference[' .
                    $row . '] in HTML_Table::' . $method);
            }
        }

        if (($col + $colspan - 1) >= $this->_cols) {
            if ($this->_autoGrow) {
                $this->_cols = $col + $colspan;
            } else {
                return PEAR::raiseError('Invalid table column reference[' .
                    $col . '] in HTML_Table::' . $method);
            }
        }
    }

    /**
    * Tells if the parameter is an array of attribute arrays/strings
    * @param    mixed   $attributes Variable to test
    * @access   private
    * @return   bool
    */
    private function _isAttributesArray(string|array $attributes = null): bool
    {
        if (is_array($attributes) && isset($attributes[0])) {
            if (\is_array($attributes[0]) || (\is_string($attributes[0]) && \count($attributes) > 1)) {
                return true;
            }
        }
        return false;
    }

    public function keepAttributes(array $keep): void
    {
        for ($row = 0; $row < $this->getRowCount(); $row++) {
            if (isset($this->_structure[$row]['attr'])) {
                self::keepAttributesArray($this->_structure[$row]['attr'], $keep);
            }

            for ($col = 0; $col < $this->getColCount(); $col++) {
                if (is_array($this->_structure[$row][$col])) {
                    self::keepAttributesArray($this->_structure[$row][$col]['attr'], $keep);
                }
            }
        }
    }
}
