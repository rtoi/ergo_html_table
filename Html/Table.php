<?php

namespace Ergo\Html;

use PEAR;

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * PEAR::HTML_Table makes the design of HTML tables easy, flexible, reusable and
 * efficient.
 *
 * The PEAR::HTML_Table package provides methods for easy and efficient design
 * of HTML tables.
 * - Lots of customization options.
 * - Tables can be modified at any time.
 * - The logic is the same as standard HTML editors.
 * - Handles col and rowspans.
 * - PHP code is shorter, easier to read and to maintain.
 * - Tables options can be reused.
 *
 * For auto filling of data and such then check out
 * http://pear.php.net/package/HTML_Table_Matrix
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
 * PEAR::HTML_Table makes the design of HTML tables easy, flexible, reusable and efficient.
 *
 * The PEAR::HTML_Table package provides methods for easy and efficient design
 * of HTML tables.
 * - Lots of customization options.
 * - Tables can be modified at any time.
 * - The logic is the same as standard HTML editors.
 * - Handles col and rowspans.
 * - PHP code is shorter, easier to read and to maintain.
 * - Tables options can be reused.
 *
 * For auto filling of data and such then check out
 * http://pear.php.net/package/HTML_Table_Matrix
 *
 * @category   HTML
 * @package    HTML_Table
 * @author     Adam Daniel <adaniel1@eesus.jnj.com>
 * @author     Bertrand Mansion <bmansion@mamasam.com>
 * @copyright  2005-2006 The PHP Group
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/HTML_Table
 */

class Table extends Common2
{
    /**
     * Value to insert into empty cells. This is used as a default for
     * newly-created tbodies.
     * @var    string
     * @access private
     */
    private string $autoFill = '&nbsp;';

    /**
     * Automatically adds a new row, column, or body if a given row, column, or
     * body index does not exist.
     * This is used as a default for newly-created tbodies.
     * @var    bool
     * @access private
     */
    private bool $autoGrow = true;

    /**
     * Array containing the table caption
     * @var     array
     * @access  private
     */
    private array $caption = [];

    /**
     * Array containing the table column group specifications
     *
     * @var     array
     * @author  Laurent Laville (pear at laurent-laville dot org)
     * @access  private
     */
    private array $colgroup = [];

    /**
     * Table\Storage object for the (t)head of the table
     * @var    object
     * @access private
     */
    private ?Table\Storage $thead = null;

    /**
     * Table\Storage object for the (t)foot of the table
     * @var    object
     * @access private
     */
    private ?Table\Storage $tfoot = null;

    /**
     * Table\Storage object for the (t)body of the table
     * @var    array of Table\Storage objects
     * @access private
     */
    private array $tbodies = [];

    /**
     * Number of bodies in the table
     * @var    int
     * @access private
     */
    private int $tbodyCount = 0;

    /**
     * Whether to use <thead>, <tfoot> and <tbody> or not
     * @var    bool
     * @access private
     */
    private bool $useTGroups = false;

    /**
     * Class constructor
     * @param    mixed    $attributes        Associative array of table tag
     *                                       attributes
     * @param    int      $tabOffset         Tab offset of the table
     * @param    bool     $useTGroups        Whether to use <thead>, <tfoot> and
     *                                       <tbody> or not
     * @access   public
     */
    public function __construct(string|array $attributes = null, ?int $tabOffset = 0, bool $useTGroups = false)
    {
        parent::__construct($attributes);
        $this->setIndentLevel($tabOffset);
        $this->useTGroups = $useTGroups;
        $this->addBody();
        if ($this->useTGroups) {
            $this->thead = new Table\Storage($tabOffset, $this->useTGroups);
            $this->tfoot = new Table\Storage($tabOffset, $this->useTGroups);
        }
    }

    /**
     * Returns the API version
     * @access  public
     * @return  double
     * @deprecated
     */
    public function apiVersion(): float
    {
        return 1.7;
    }

    /**
     * Returns the Table\Storage object for <thead>
     * @access  public
     * @return  object
     */
    public function getHeader(bool $add = \true): ?Table\Storage
    {
        if (is_null($this->thead) && $add) {
            $this->useTGroups = true;
            $this->thead = new Table\Storage(
                $this->getIndentLevel(),
                $this->useTGroups
            );
            for ($i = 0; $i < $this->tbodyCount; $i++) {
                $this->tbodies[$i]->setUseTGroups(true);
            }
        }
        return $this->thead;
    }

    /**
     * Returns the Table\Storage object for <tfoot>
     * @access  public
     * @return  object
     */
    public function getFooter(bool $add = \true): ?Table\Storage
    {
        if (is_null($this->tfoot) && $add) {
            $this->useTGroups = true;
            $this->tfoot = new Table\Storage(
                $this->getIndentLevel(),
                $this->useTGroups
            );
            for ($i = 0; $i < $this->tbodyCount; $i++) {
                $this->tbodies[$i]->setUseTGroups(true);
            }
        }
        return $this->tfoot;
    }

    /**
     * Returns the Table\Storage object for the specified <tbody>
     * (or the whole table if <t{head|foot|body}> is not used)
     * @param   int       $body              (optional) The index of the body to
     *                                       return.
     * @access  public
     * @return  object
     * @throws  PEAR_Error
     */
    public function getBody($body = 0): Table\Storage
    {
        $ret = $this->adjustTbodyCount($body, 'getBody');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        return $this->tbodies[$body];
    }

    /**
     * Adds a table body and returns the body identifier
     * @param   mixed        $attributes     (optional) Associative array or
     *                                       string of table body attributes
     * @access  public
     * @return  int
     */
    public function addBody(string|array $attributes = null): int
    {
        if (!$this->useTGroups && $this->tbodyCount > 0) {
            for ($i = 0; $i < $this->tbodyCount; $i++) {
                $this->tbodies[$i]->setUseTGroups(true);
            }
            $this->useTGroups = true;
        }

        $body = $this->tbodyCount++;
        $this->tbodies[$body] = new Table\Storage(
            $this->getIndentLevel(),
            $this->useTGroups
        );
        $this->tbodies[$body]->setAutoFill($this->autoFill);
        $this->tbodies[$body]->setAttributes($attributes);
        return $body;
    }

    /**
     * Adjusts the number of bodies
     * @param   int          $body           Body index
     * @param   string       $method         Name of calling method
     * @access  private
     * @throws  PEAR_Error
     */
    private function adjustTbodyCount(int $body, string $method)
    {
        if ($this->autoGrow) {
            while ($this->tbodyCount <= $body) {
                $this->addBody();
            }
        } else {
            return PEAR::raiseError('Invalid body reference[' .
                $body . '] in HTML_Table::' . $method);
        }
    }

    /**
     * Sets the table caption
     * @param   string    $caption
     * @param   mixed     $attributes        Associative array or string of
     *                                       table row attributes
     * @access  public
     */
    public function setCaption(string $caption, string|array $attributes = null): void
    {
        $attributes = self::prepareAttributes($attributes);
        $this->caption = array('attr' => $attributes, 'contents' => $caption);
    }

    /**
     * Sets the table columns group specifications, or removes existing ones.
     *
     * @param   mixed     $colgroup        (optional) Columns attributes
     * @param   mixed     $attributes      (optional) Associative array or string
     *                                                  of table row attributes
     * @author  Laurent Laville (pear at laurent-laville dot org)
     * @access  public
     */
    public function setColGroup(string|array $colgroup = null, string|array $attributes = null): void
    {
        if (isset($colgroup)) {
            $attributes = self::prepareAttributes($attributes);
            $this->colgroup[] = array('attr' => $attributes,
                                       'contents' => $colgroup);
        } else {
            $this->colgroup = array();
        }
    }

    /**
     * Inserts a new col in a colgroup.
     *
     * @param int|null  $col        At what index the col will be inserted. If this is not set, the new
     *                              coll will be added at the end of the colgroup.
     * @param array     $contents
     * @param int       $groupId
     * @return void
     * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
     */
    public function insertCol(?int $col = null, array $contents = [''], int $groupId = 0): void
    {
        if (!isset($col)) {
            $this->colgroup[$groupId]['contents'][] = $contents;
            return;
        }
        $arr1 = $col > 0 ? array_slice($this->colgroup[$groupId]['contents'], 0, $col) : [];
        $arr2 = $col < $this->getColCount() ? array_slice($this->colgroup[$groupId]['contents'], $col) : [];
        $this->colgroup[$groupId]['contents'] = array_merge($arr1, $contents, $arr2);
    }

    public function getColGroup(): array
    {
        return $this->colgroup ?? [];
    }

    /**
     * Sets the autoFill value
     * @param   mixed   $fill          Whether autoFill should be enabled or not
     * @param   int     $body          (optional) The index of the body to set.
     *                                 Pass null to set for all bodies.
     * @access  public
     * @throws  PEAR_Error
     */
    public function setAutoFill(string $fill, int $body = null)
    {
        if (!is_null($body)) {
            $ret = $this->adjustTbodyCount($body, 'setAutoFill');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            $this->tbodies[$body]->setAutoFill($fill);
        } else {
            $this->autoFill = $fill;
            for ($i = 0; $i < $this->tbodyCount; $i++) {
                $this->tbodies[$i]->setAutoFill($fill);
            }
        }
    }

    /**
     * Returns the autoFill value
     * @param    int         $body   (optional) The index of the body to get.
     *                               Pass null to get the default for new bodies.
     * @access   public
     * @return   mixed
     * @throws   PEAR_Error
     */
    public function getAutoFill($body = null): string
    {
        if (!is_null($body)) {
            $ret = $this->adjustTbodyCount($body, 'getAutoFill');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            return $this->tbodies[$body]->getAutoFill();
        }
        return $this->autoFill;
    }

    /**
     * Sets the autoGrow value
     * @param    bool     $grow        Whether autoGrow should be enabled or not
     * @param    int      $body        (optional) The index of the body to set.
     *                                 Pass null to set for all bodies.
     * @access   public
     * @throws   PEAR_Error
     */
    public function setAutoGrow(bool $grow, int $body = null)
    {
        if (!is_null($body)) {
            $ret = $this->adjustTbodyCount($body, 'setAutoGrow');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            $this->tbodies[$body]->setAutoGrow($grow);
        } else {
            $this->autoGrow = $grow;
            for ($i = 0; $i < $this->tbodyCount; $i++) {
                $this->tbodies[$i]->setAutoGrow($grow);
            }
        }
    }

    /**
     * Returns the autoGrow value
     * @param    int     $body       (optional) The index of the body to get.
     *                               Pass null to get the default for new bodies.
     * @access   public
     * @return   mixed
     * @throws   PEAR_Error
     */
    public function getAutoGrow(int $body = null): bool
    {
        if (!is_null($body)) {
            $ret = $this->adjustTbodyCount($body, 'getAutoGrow');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            return $this->tbodies[$body]->getAutoGrow();
        }
        return $this->autoGrow;
    }

    /**
     * Sets the number of rows in the table body
     * @param    int       $rows       The number of rows
     * @param    int       $body       (optional) The index of the body to set.
     * @access   public
     * @throws   PEAR_Error
     */
    public function setRowCount(int $rows, int $body = 0)
    {
        $ret = $this->adjustTbodyCount($body, 'setRowCount');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        $this->tbodies[$body]->setRowCount($rows);
    }

    /**
     * Sets the number of columns in the table
     * @param    int         $cols      The number of columns
     * @param    int         $body      (optional) The index of the body to set.
     * @access   public
     * @throws   PEAR_Error
     */
    public function setColCount(int $cols, int $body = 0)
    {
        $ret = $this->adjustTbodyCount($body, 'setColCount');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        $this->tbodies[$body]->setColCount($cols);
    }

    /**
     * Returns the number of rows in the table
     * @param    int    $body           (optional) The index of the body to get.
     *                                  Pass null to get the total number of
     *                                  rows in all bodies.
     * @access   public
     * @return   int
     * @throws   PEAR_Error
     */
    public function getRowCount(int $body = null): int
    {
        if (!is_null($body)) {
            $ret = $this->adjustTbodyCount($body, 'getRowCount');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            return $this->tbodies[$body]->getRowCount();
        }
        $rowCount = 0;
        for ($i = 0; $i < $this->tbodyCount; $i++) {
            $rowCount += $this->tbodies[$i]->getRowCount();
        }
        return $rowCount;
    }

    /**
     * Gets the number of columns in the table
     *
     * If a row index is specified, the count will not take
     * the spanned cells into account in the return value.
     *
     * @param    int      $row          Row index to serve for cols count
     * @param    int      $body         (optional) The index of the body to get.
     * @access   public
     * @return   int
     * @throws   PEAR_Error
     */
    public function getColCount(int $row = null, int $body = 0): int
    {
        $ret = $this->adjustTbodyCount($body, 'getColCount');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        return $this->tbodies[$body]->getColCount($row);
    }

    /**
     * Sets a rows type 'TH' or 'TD'
     * @param    int         $row    Row index
     * @param    string      $type   'TH' or 'TD'
     * @param    int         $body   (optional) The index of the body to set.
     * @access   public
     * @throws   PEAR_Error
     */
    public function setRowType(int $row, string $type, int $body = 0)
    {
        $ret = $this->adjustTbodyCount($body, 'setRowType');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        $this->tbodies[$body]->setRowType($row, $type);
    }

    /**
     * Sets a columns type 'TH' or 'TD'
     * @param    int         $col    Column index
     * @param    string      $type   'TH' or 'TD'
     * @param    int         $body   (optional) The index of the body to set.
     *                               Pass null to set for all bodies.
     * @access   public
     * @throws   PEAR_Error
     */
    public function setColType(int $col, string $type, int $body = null)
    {
        if (!is_null($body)) {
            $ret = $this->adjustTbodyCount($body, 'setColType');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            $this->tbodies[$body]->setColType($col, $type);
        } else {
            for ($i = 0; $i < $this->tbodyCount; $i++) {
                $this->tbodies[$i]->setColType($col, $type);
            }
        }
    }

    /**
     * Sets the cell attributes for an existing cell.
     *
     * If the given indices do not exist and autoGrow is true then the given
     * row and/or col is automatically added.  If autoGrow is false then an
     * error is returned.
     * @param    int     $row          Row index
     * @param    int     $col          Column index
     * @param    mixed   $attributes   Associative array or string of
     *                                 table row attributes
     * @param    int     $body         (optional) The index of the body to set.
     * @access   public
     * @throws   PEAR_Error
     */
    public function setCellAttributes(int $row, int $col, string|array $attributes = null, int $body = 0)
    {
        $ret = $this->adjustTbodyCount($body, 'setCellAttributes');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        $ret = $this->tbodies[$body]->setCellAttributes($row, $col, $attributes);
        if (PEAR::isError($ret)) {
            return $ret;
        }
    }

    /**
     * Updates the cell attributes passed but leaves other existing attributes
     * intact
     * @param    int      $row          Row index
     * @param    int      $col          Column index
     * @param    mixed    $attributes   Associative array or string of table row
     *                                  attributes
     * @param    int      $body         (optional) The index of the body to set.
     * @access   public
     * @throws   PEAR_Error
     */
    public function updateCellAttributes(int $row, int $col, string|array $attributes = null, int $body = 0)
    {
        $ret = $this->adjustTbodyCount($body, 'updateCellAttributes');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        $ret = $this->tbodies[$body]->updateCellAttributes($row, $col, $attributes);
        if (PEAR::isError($ret)) {
            return $ret;
        }
    }

    /**
     * Returns the attributes for a given cell
     * @param    int         $row        Row index
     * @param    int         $col        Column index
     * @param    int         $body       (optional) The index of the body to get.
     * @return   array
     * @access   public
     * @throws   PEAR_Error
     */
    public function getCellAttributes(int $row, int $col, int $body = 0)
    {
        $ret = $this->adjustTbodyCount($body, 'getCellAttributes');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        return $this->tbodies[$body]->getCellAttributes($row, $col);
    }

    /**
     * Sets the cell contents for an existing cell
     *
     * If the given indices do not exist and autoGrow is true then the given
     * row and/or col is automatically added.  If autoGrow is false then an
     * error is returned.
     * @param    int      $row         Row index
     * @param    int      $col         Column index
     * @param    mixed    $contents    May contain html or any object with a
     *                                 toHTML() method; it is an array (with
     *                                 strings and/or objects), $col will be
     *                                 used as start offset and the array
     *                                 elements will be set to this and the
     *                                 following columns in $row
     * @param    string   $type        (optional) Cell type either 'TH' or 'TD'
     * @param    int      $body        (optional) The index of the body to set.
     * @access   public
     * @throws   PEAR_Error
     */
    public function setCellContents(int $row, int $col, $contents, string $type = 'TD', int $body = 0)
    {
        $ret = $this->adjustTbodyCount($body, 'setCellContents');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        $ret = $this->tbodies[$body]->setCellContents($row, $col, $contents, $type);
        if (PEAR::isError($ret)) {
            return $ret;
        }
    }

    /**
     * Returns the cell contents for an existing cell
     * @param    int        $row    Row index
     * @param    int        $col    Column index
     * @param    int        $body   (optional) The index of the body to get.
     * @access   public
     * @return   mixed
     * @throws   PEAR_Error
     */
    public function getCellContents(int $row, int $col, int $body = 0)
    {
        $ret = $this->adjustTbodyCount($body, 'getCellContents');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        return $this->tbodies[$body]->getCellContents($row, $col);
    }

    /**
     * Sets the contents of a header cell
     * @param    int      $row
     * @param    int      $col
     * @param    mixed    $contents
     * @param    mixed    $attributes   Associative array or string of
     *                                  table row attributes
     * @param    int      $body         (optional) The index of the body to set.
     * @access   public
     * @throws   PEAR_Error
     */
    public function setHeaderContents(
        int $row,
        int $col,
        $contents,
        string|array $attributes = null,
        int $body = 0
    ) {
        $ret = $this->adjustTbodyCount($body, 'setHeaderContents');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        $this->tbodies[$body]->setHeaderContents($row, $col, $contents, $attributes);
    }

    /**
     * Adds a table row and returns the row identifier
     * @param    array     $contents     (optional) Must be a indexed array of
     *                                   valid cell contents
     * @param    mixed     $attributes   (optional) Associative array or string
     *                                   of table row attributes. This can also
     *                                   be an array of attributes, in which
     *                                   case the attributes will be repeated
     *                                   in a loop.
     * @param    string    $type         (optional) Cell type either 'th' or 'td'
     * @param    bool      $inTR         false if attributes are to be applied
     *                                   in TD tags; true if attributes are to
     *                                  �be applied in TR tag
     * @param    int       $body         (optional) The index of the body to use.
     * @return   int
     * @access   public
     * @throws   PEAR_Error
     */
    public function addRow(
        array $contents = null,
        string|array $attributes = null,
        string $type = 'td',
        bool $inTR = false,
        int $body = 0
    ) {
        $ret = $this->adjustTbodyCount($body, 'addRow');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        $ret = $this->tbodies[$body]->addRow($contents, $attributes, $type, $inTR);
        return $ret;
    }

    /**
     * Sets the row attributes for an existing row
     * @param    int      $row          Row index
     * @param    mixed    $attributes   Associative array or string of table row
     *                                  attributes. This can also be an array of
     *                                  attributes, in which case the attributes
     *                                  will be repeated in a loop.
     * @param    bool     $inTR         false if attributes are to be applied in
     *                                  TD tags; true if attributes are to be
     *                                  applied in TR tag
     * @param    int      $body         (optional) The index of the body to set.
     * @access   public
     * @throws   PEAR_Error
     */
    public function setRowAttributes(int $row, string|array $attributes = null, bool $inTR = false, int $body = 0)
    {
        $ret = $this->adjustTbodyCount($body, 'setRowAttributes');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        $ret = $this->tbodies[$body]->setRowAttributes($row, $attributes, $inTR);
        if (PEAR::isError($ret)) {
            return $ret;
        }
    }

    /**
     * Updates the row attributes for an existing row
     * @param    int      $row          Row index
     * @param    mixed    $attributes   Associative array or string of table row
     *                                  attributes
     * @param    bool     $inTR         false if attributes are to be applied in
     *                                  TD tags; true if attributes are to be
     *                                  applied in TR tag
     * @param    int      $body         (optional) The index of the body to set.
     * @access   public
     * @throws   PEAR_Error
     */
    public function updateRowAttributes(
        int $row,
        string|array $attributes = null,
        bool $inTR = false,
        int $body = 0
    ) {
        $ret = $this->adjustTbodyCount($body, 'updateRowAttributes');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        $ret = $this->tbodies[$body]->updateRowAttributes($row, $attributes, $inTR);
        if (PEAR::isError($ret)) {
            return $ret;
        }
    }

    /**
     * Returns the attributes for a given row as contained in the TR tag
     * @param    int      $row       Row index
     * @param    int      $body      (optional) The index of the body to get.
     * @return   array
     * @access   public
     * @throws   PEAR_Error
     */
    public function getRowAttributes(int $row, int $body = 0): array
    {
        $ret = $this->adjustTbodyCount($body, 'getRowAttributes');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        return $this->tbodies[$body]->getRowAttributes($row);
    }

    /**
     * Alternates the row attributes starting at $start
     * @param   int     $start            Row index of row in which alternating
     *                                    begins
     * @param   mixed   $attributes1      Associative array or string of table
     *                                    row attributes
     * @param   mixed   $attributes2      Associative array or string of table
     *                                    row attributes
     * @param   bool    $inTR             false if attributes are to be applied
     *                                    in TD tags; true if attributes are to
     *                                    be applied in TR tag
     * @param   int     $firstAttributes  (optional) Which attributes should be
     *                                    applied to the first row, 1 or 2.
     * @param   int     $body             (optional) The index of the body to set.
     *                                    Pass null to set for all bodies.
     * @access  public
     * @throws  PEAR_Error
     */
    public function altRowAttributes(
        int $start,
        string|array $attributes1,
        string|array $attributes2,
        bool $inTR = false,
        int $firstAttributes = 1,
        int $body = null
    ) {
        if (!is_null($body)) {
            $ret = $this->adjustTbodyCount($body, 'altRowAttributes');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            $this->tbodies[$body]->altRowAttributes(
                $start,
                $attributes1,
                $attributes2,
                $inTR,
                $firstAttributes
            );
        } else {
            for ($i = 0; $i < $this->tbodyCount; $i++) {
                $this->tbodies[$i]->altRowAttributes(
                    $start,
                    $attributes1,
                    $attributes2,
                    $inTR,
                    $firstAttributes
                );
                // if the tbody's row count is odd, toggle $firstAttributes to
                // prevent the next tbody's first row from having the same
                // attributes as this tbody's last row.
                if ($this->tbodies[$i]->getRowCount() % 2) {
                    $firstAttributes ^= 3;
                }
            }
        }
    }

    /**
     * Adds a table column and returns the column identifier
     * @param    array     $contents     (optional) Must be a indexed array of
     *                                   valid cell contents
     * @param    mixed     $attributes   (optional) Associative array or string
     *                                   of table row attributes
     * @param    string    $type         (optional) Cell type either 'th' or 'td'
     * @param    int       $body         (optional) The index of the body to use.
     * @return   int
     * @access   public
     * @throws   PEAR_Error
     */
    public function addCol(
        array $contents = null,
        string|array $attributes = null,
        string $type = 'td',
        int $body = 0
    ) {
        $ret = $this->adjustTbodyCount($body, 'addCol');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        return $this->tbodies[$body]->addCol($contents, $attributes, $type);
    }

    /**
     * Sets the column attributes for an existing column
     * @param    int       $col          Column index
     * @param    mixed     $attributes   (optional) Associative array or string
     *                                   of table row attributes
     * @param    int       $body         (optional) The index of the body to set.
     *                                   Pass null to set for all bodies.
     * @access   public
     * @throws   PEAR_Error
     */
    public function setColAttributes(int $col, string|array $attributes = null, int $body = null)
    {
        if (!is_null($body)) {
            $ret = $this->adjustTbodyCount($body, 'setColAttributes');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            $this->tbodies[$body]->setColAttributes($col, $attributes);
        } else {
            for ($i = 0; $i < $this->tbodyCount; $i++) {
                $this->tbodies[$i]->setColAttributes($col, $attributes);
            }
        }
    }

    /**
     * Updates the column attributes for an existing column
     * @param    int       $col          Column index
     * @param    mixed     $attributes   (optional) Associative array or
     *                                   string of table row attributes
     * @param    int       $body         (optional) The index of the body to set.
     *                                   Pass null to set for all bodies.
     * @access   public
     * @throws   PEAR_Error
     */
    public function updateColAttributes(int $col, string|array $attributes = null, int $body = null)
    {
        if (!is_null($body)) {
            $ret = $this->adjustTbodyCount($body, 'updateColAttributes');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            $this->tbodies[$body]->updateColAttributes($col, $attributes);
        } else {
            for ($i = 0; $i < $this->tbodyCount; $i++) {
                $this->tbodies[$i]->updateColAttributes($col, $attributes);
            }
        }
    }

    /**
     * Sets the attributes for all cells
     * @param    mixed    $attributes    (optional) Associative array or
     *                                   string of table row attributes
     * @param    int      $body          (optional) The index of the body to set.
     *                                   Pass null to set for all bodies.
     * @access   public
     * @throws   PEAR_Error
     */
    public function setAllAttributes(string|array $attributes = null, int $body = null)
    {
        if (!is_null($body)) {
            $ret = $this->adjustTbodyCount($body, 'setAllAttributes');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            $this->tbodies[$body]->setAllAttributes($attributes);
        } else {
            for ($i = 0; $i < $this->tbodyCount; $i++) {
                $this->tbodies[$i]->setAllAttributes($attributes);
            }
        }
    }

    /**
     * Updates the attributes for all cells
     * @param    mixed    $attributes   (optional) Associative array or string
     *                                  of table row attributes
     * @param    int      $body         (optional) The index of the body to set.
     *                                  Pass null to set for all bodies.
     * @access   public
     * @throws   PEAR_Error
     */
    public function updateAllAttributes(string|array $attributes = null, int $body = null)
    {
        if (!is_null($body)) {
            $ret = $this->adjustTbodyCount($body, 'updateAllAttributes');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            $this->tbodies[$body]->updateAllAttributes($attributes);
        } else {
            for ($i = 0; $i < $this->tbodyCount; $i++) {
                $this->tbodies[$i]->updateAllAttributes($attributes);
            }
        }
    }

    /**
     * Returns the table structure as HTML
     * @access  public
     * @return  string
     */
    public function toHtml(): string
    {
        $strHtml = '';
        $tabs = $this->getIndent();
        $tab = self::getOption(Common2::OPTION_INDENT);
        $lnEnd = self::getOption(Common2::OPTION_LINEBREAK);
        $tBodyColCounts = array();
        for ($i = 0; $i < $this->tbodyCount; $i++) {
            $tBodyColCounts[] = $this->tbodies[$i]->getColCount();
        }
        $tBodyMaxColCount = 0;
        if (count($tBodyColCounts) > 0) {
            $tBodyMaxColCount = max($tBodyColCounts);
        }
        if ($this->getComment()) {
            $strHtml .= $tabs . "<!-- {$this->getComment()} -->" . $lnEnd;
        }
        if ($this->getRowCount() > 0 && $tBodyMaxColCount > 0) {
            $strHtml .=
                $tabs . '<table' . $this->getAttributes(true) . '>' . $lnEnd;
            if (!empty($this->caption)) {
                $attr = $this->caption['attr'];
                $contents = $this->caption['contents'];
                $strHtml .= $tabs . $tab . '<caption' . self::getAttributesString($attr) . '>';
                if (is_array($contents)) {
                    $contents = implode(', ', $contents);
                }
                $strHtml .= $contents;
                $strHtml .= '</caption>' . $lnEnd;
            }
            if (!empty($this->colgroup)) {
                foreach ($this->colgroup as $g => $col) {
                    $attr = $this->colgroup[$g]['attr'];
                    $contents = $this->colgroup[$g]['contents'];
                    $strHtml .= $tabs . $tab . '<colgroup' . self::getAttributesString($attr) . '>';
                    if (!empty($contents)) {
                        $strHtml .= $lnEnd;
                        if (!is_array($contents)) {
                            $contents = array($contents);
                        }
                        foreach ($contents as $a => $colAttr) {
                            $attr = self::prepareAttributes($colAttr);
                            $strHtml .= $tabs . $tab . $tab . '<col' . self::getAttributesString($attr) . ' />' . $lnEnd;
                        }
                        $strHtml .= $tabs . $tab;
                    }
                    $strHtml .= '</colgroup>' . $lnEnd;
                }
            }
            if ($this->useTGroups) {
                $tHeadColCount = 0;
                if ($this->thead !== null) {
                    $tHeadColCount = $this->thead->getColCount();
                }
                $tFootColCount = 0;
                if ($this->tfoot !== null) {
                    $tFootColCount = $this->tfoot->getColCount();
                }
                $maxColCount = max($tHeadColCount, $tFootColCount, $tBodyMaxColCount);
                if ($this->thead !== null) {
                    $this->thead->setColCount($maxColCount);
                    if ($this->thead->getRowCount() > 0) {
                        $strHtml .= $tabs . $tab . '<thead' .
                                    $this->thead->getAttributes(true) .
                                    '>' . $lnEnd;
                        $strHtml .= $this->thead->toHtml($tabs, $tab);
                        $strHtml .= $tabs . $tab . '</thead>' . $lnEnd;
                    }
                }
                if ($this->tfoot !== null) {
                    $this->tfoot->setColCount($maxColCount);
                    if ($this->tfoot->getRowCount() > 0) {
                        $strHtml .= $tabs . $tab . '<tfoot' .
                                    $this->tfoot->getAttributes(true) .
                                    '>' . $lnEnd;
                        $strHtml .= $this->tfoot->toHtml($tabs, $tab);
                        $strHtml .= $tabs . $tab . '</tfoot>' . $lnEnd;
                    }
                }
                for ($i = 0; $i < $this->tbodyCount; $i++) {
                    $this->tbodies[$i]->setColCount($maxColCount);
                    if ($this->tbodies[$i]->getRowCount() > 0) {
                        $strHtml .= $tabs . $tab . '<tbody' .
                                    $this->tbodies[$i]->getAttributes(true) .
                                    '>' . $lnEnd;
                        $strHtml .= $this->tbodies[$i]->toHtml($tabs, $tab);
                        $strHtml .= $tabs . $tab . '</tbody>' . $lnEnd;
                    }
                }
            } else {
                for ($i = 0; $i < $this->tbodyCount; $i++) {
                    $strHtml .= $this->tbodies[$i]->toHtml($tabs, $tab);
                }
            }
            $strHtml .= $tabs . '</table>' . $lnEnd;
        }
        return $strHtml;
    }

    /**
     * Returns the table structure as HTML
     * @access  public
     * @return  string
     */
    public function __toString(): string
    {
        return $this->toHtml();
    }
}
