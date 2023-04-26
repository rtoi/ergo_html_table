<?php
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
class HTML_Table extends HTML_Common {

    /**
     * Value to insert into empty cells. This is used as a default for
     * newly-created tbodies.
     * @var    string
     * @access private
     */
    private string $_autoFill = '&nbsp;';

    /**
     * Automatically adds a new row, column, or body if a given row, column, or
     * body index does not exist.
     * This is used as a default for newly-created tbodies.
     * @var    bool
     * @access private
     */
    private bool $_autoGrow = true;

    /**
     * Array containing the table caption
     * @var     array
     * @access  private
     */
    private array $_caption = [];

    /**
     * Array containing the table column group specifications
     *
     * @var     array
     * @author  Laurent Laville (pear at laurent-laville dot org)
     * @access  private
     */
    private array $_colgroup = [];

    /**
     * HTML_Table_Storage object for the (t)head of the table
     * @var    object
     * @access private
     */
    private ?HTML_Table_Storage $_thead = null;

    /**
     * HTML_Table_Storage object for the (t)foot of the table
     * @var    object
     * @access private
     */
    private ?HTML_Table_Storage $_tfoot = null;

    /**
     * HTML_Table_Storage object for the (t)body of the table
     * @var    array of HTML_Table_Storage objects
     * @access private
     */
    private array $_tbodies = [];

    /**
     * Number of bodies in the table
     * @var    int
     * @access private
     */
    private int $_tbodyCount = 0;

    /**
     * Whether to use <thead>, <tfoot> and <tbody> or not
     * @var    bool
     * @access private
     */
    private bool $_useTGroups = false;

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
        parent::__construct($attributes, (int)$tabOffset);
        $this->_useTGroups = $useTGroups;
        $this->addBody();
        if ($this->_useTGroups) {
            $this->_thead = new HTML_Table_Storage($tabOffset, $this->_useTGroups);
            $this->_tfoot = new HTML_Table_Storage($tabOffset, $this->_useTGroups);
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
     * Returns the HTML_Table_Storage object for <thead>
     * @access  public
     * @return  object
     */
    public function getHeader(): HTML_Table_Storage
    {
        if (is_null($this->_thead)) {
            $this->_useTGroups = true;
            $this->_thead = new HTML_Table_Storage($this->_tabOffset,
                                                    $this->_useTGroups);
            for ($i = 0; $i < $this->_tbodyCount; $i++) {
                $this->_tbodies[$i]->setUseTGroups(true);
            }
        }
        return $this->_thead;
    }

    /**
     * Returns the HTML_Table_Storage object for <tfoot>
     * @access  public
     * @return  object
     */
    public function getFooter(): HTML_Table_Storage
    {
        if (is_null($this->_tfoot)) {
            $this->_useTGroups = true;
            $this->_tfoot = new HTML_Table_Storage($this->_tabOffset,
                                                    $this->_useTGroups);
            for ($i = 0; $i < $this->_tbodyCount; $i++) {
                $this->_tbodies[$i]->setUseTGroups(true);
            }
        }
        return $this->_tfoot;
    }

    /**
     * Returns the HTML_Table_Storage object for the specified <tbody>
     * (or the whole table if <t{head|foot|body}> is not used)
     * @param   int       $body              (optional) The index of the body to
     *                                       return.
     * @access  public
     * @return  object
     * @throws  PEAR_Error
     */
    public function getBody($body = 0): HTML_Table_Storage
    {
        $ret = $this->_adjustTbodyCount($body, 'getBody');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        return $this->_tbodies[$body];
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
        if (!$this->_useTGroups && $this->_tbodyCount > 0) {
            for ($i = 0; $i < $this->_tbodyCount; $i++) {
                $this->_tbodies[$i]->setUseTGroups(true);
            }
            $this->_useTGroups = true;
        }

        $body = $this->_tbodyCount++;
        $this->_tbodies[$body] = new HTML_Table_Storage($this->_tabOffset,
                                                         $this->_useTGroups);
        $this->_tbodies[$body]->setAutoFill($this->_autoFill);
        $this->_tbodies[$body]->setAttributes($attributes);
        return $body;
    }

    /**
     * Adjusts the number of bodies
     * @param   int          $body           Body index
     * @param   string       $method         Name of calling method
     * @access  private
     * @throws  PEAR_Error
     */
    private function _adjustTbodyCount(int $body, string $method)
    {
        if ($this->_autoGrow) {
            while ($this->_tbodyCount <= $body) {
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
        $attributes = $this->_parseAttributes($attributes);
        $this->_caption = array('attr' => $attributes, 'contents' => $caption);
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
            $attributes = $this->_parseAttributes($attributes);
            $this->_colgroup[] = array('attr' => $attributes,
                                       'contents' => $colgroup);
        } else {
            $this->_colgroup = array();
        }
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
            $ret = $this->_adjustTbodyCount($body, 'setAutoFill');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            $this->_tbodies[$body]->setAutoFill($fill);
        } else {
            $this->_autoFill = $fill;
            for ($i = 0; $i < $this->_tbodyCount; $i++) {
                $this->_tbodies[$i]->setAutoFill($fill);
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
            $ret = $this->_adjustTbodyCount($body, 'getAutoFill');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            return $this->_tbodies[$body]->getAutoFill();
        }
        return $this->_autoFill;
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
            $ret = $this->_adjustTbodyCount($body, 'setAutoGrow');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            $this->_tbodies[$body]->setAutoGrow($grow);
        } else {
            $this->_autoGrow = $grow;
            for ($i = 0; $i < $this->_tbodyCount; $i++) {
                $this->_tbodies[$i]->setAutoGrow($grow);
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
            $ret = $this->_adjustTbodyCount($body, 'getAutoGrow');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            return $this->_tbodies[$body]->getAutoGrow();
        } 
        return $this->_autoGrow;
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
        $ret = $this->_adjustTbodyCount($body, 'setRowCount');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        $this->_tbodies[$body]->setRowCount($rows);
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
        $ret = $this->_adjustTbodyCount($body, 'setColCount');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        $this->_tbodies[$body]->setColCount($cols);
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
            $ret = $this->_adjustTbodyCount($body, 'getRowCount');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            return $this->_tbodies[$body]->getRowCount();
        } 
        $rowCount = 0;
        for ($i = 0; $i < $this->_tbodyCount; $i++) {
            $rowCount += $this->_tbodies[$i]->getRowCount();
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
        $ret = $this->_adjustTbodyCount($body, 'getColCount');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        return $this->_tbodies[$body]->getColCount($row);
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
        $ret = $this->_adjustTbodyCount($body, 'setRowType');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        $this->_tbodies[$body]->setRowType($row, $type);
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
            $ret = $this->_adjustTbodyCount($body, 'setColType');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            $this->_tbodies[$body]->setColType($col, $type);
        } else {
            for ($i = 0; $i < $this->_tbodyCount; $i++) {
                $this->_tbodies[$i]->setColType($col, $type);
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
        $ret = $this->_adjustTbodyCount($body, 'setCellAttributes');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        $ret = $this->_tbodies[$body]->setCellAttributes($row, $col, $attributes);
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
        $ret = $this->_adjustTbodyCount($body, 'updateCellAttributes');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        $ret = $this->_tbodies[$body]->updateCellAttributes($row, $col, $attributes);
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
        $ret = $this->_adjustTbodyCount($body, 'getCellAttributes');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        return $this->_tbodies[$body]->getCellAttributes($row, $col);
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
        $ret = $this->_adjustTbodyCount($body, 'setCellContents');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        $ret = $this->_tbodies[$body]->setCellContents($row, $col, $contents, $type);
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
        $ret = $this->_adjustTbodyCount($body, 'getCellContents');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        return $this->_tbodies[$body]->getCellContents($row, $col);
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
    public function setHeaderContents(int $row, int $col, $contents, string|array $attributes = null,
        int $body = 0)
    {
        $ret = $this->_adjustTbodyCount($body, 'setHeaderContents');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        $this->_tbodies[$body]->setHeaderContents($row, $col, $contents, $attributes);
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
    public function addRow(array $contents = null, string|array $attributes = null, string $type = 'td',
        bool $inTR = false, int $body = 0)
    {
        $ret = $this->_adjustTbodyCount($body, 'addRow');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        $ret = $this->_tbodies[$body]->addRow($contents, $attributes, $type, $inTR);
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
        $ret = $this->_adjustTbodyCount($body, 'setRowAttributes');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        $ret = $this->_tbodies[$body]->setRowAttributes($row, $attributes, $inTR);
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
    public function updateRowAttributes(int $row, string|array $attributes = null, bool $inTR = false,
        int $body = 0)
    {
        $ret = $this->_adjustTbodyCount($body, 'updateRowAttributes');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        $ret = $this->_tbodies[$body]->updateRowAttributes($row, $attributes, $inTR);
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
        $ret = $this->_adjustTbodyCount($body, 'getRowAttributes');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        return $this->_tbodies[$body]->getRowAttributes($row);
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
    public function altRowAttributes(int $start, string|array $attributes1, 
        string|array $attributes2, bool $inTR = false, int $firstAttributes = 1, 
        int $body = null)
    {
        if (!is_null($body)) {
            $ret = $this->_adjustTbodyCount($body, 'altRowAttributes');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            $this->_tbodies[$body]->altRowAttributes($start, $attributes1,
                $attributes2, $inTR, $firstAttributes);
        } else {
            for ($i = 0; $i < $this->_tbodyCount; $i++) {
                $this->_tbodies[$i]->altRowAttributes($start, $attributes1,
                    $attributes2, $inTR, $firstAttributes);
                // if the tbody's row count is odd, toggle $firstAttributes to
                // prevent the next tbody's first row from having the same
                // attributes as this tbody's last row.
                if ($this->_tbodies[$i]->getRowCount() % 2) {
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
    public function addCol(array $contents = null, string|array $attributes = null, 
        string $type = 'td', int $body = 0)
    {
        $ret = $this->_adjustTbodyCount($body, 'addCol');
        if (PEAR::isError($ret)) {
            return $ret;
        }
        return $this->_tbodies[$body]->addCol($contents, $attributes, $type);
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
            $ret = $this->_adjustTbodyCount($body, 'setColAttributes');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            $this->_tbodies[$body]->setColAttributes($col, $attributes);
        } else {
            for ($i = 0; $i < $this->_tbodyCount; $i++) {
                $this->_tbodies[$i]->setColAttributes($col, $attributes);
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
            $ret = $this->_adjustTbodyCount($body, 'updateColAttributes');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            $this->_tbodies[$body]->updateColAttributes($col, $attributes);
        } else {
            for ($i = 0; $i < $this->_tbodyCount; $i++) {
                $this->_tbodies[$i]->updateColAttributes($col, $attributes);
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
            $ret = $this->_adjustTbodyCount($body, 'setAllAttributes');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            $this->_tbodies[$body]->setAllAttributes($attributes);
        } else {
            for ($i = 0; $i < $this->_tbodyCount; $i++) {
                $this->_tbodies[$i]->setAllAttributes($attributes);
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
            $ret = $this->_adjustTbodyCount($body, 'updateAllAttributes');
            if (PEAR::isError($ret)) {
                return $ret;
            }
            $this->_tbodies[$body]->updateAllAttributes($attributes);
        } else {
            for ($i = 0; $i < $this->_tbodyCount; $i++) {
                $this->_tbodies[$i]->updateAllAttributes($attributes);
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
        $tabs = $this->_getTabs();
        $tab = $this->_getTab();
        $lnEnd = $this->_getLineEnd();
        $tBodyColCounts = array();
        for ($i = 0; $i < $this->_tbodyCount; $i++) {
            $tBodyColCounts[] = $this->_tbodies[$i]->getColCount();
        }
        $tBodyMaxColCount = 0;
        if (count($tBodyColCounts) > 0) {
            $tBodyMaxColCount = max($tBodyColCounts);
        }
        if ($this->_comment) {
            $strHtml .= $tabs . "<!-- {$this->_comment} -->" . $lnEnd;
        }
        if ($this->getRowCount() > 0 && $tBodyMaxColCount > 0) {
            $strHtml .=
                $tabs . '<table' . $this->_getAttrString($this->_attributes) . '>' . $lnEnd;
            if (!empty($this->_caption)) {
                $attr = $this->_caption['attr'];
                $contents = $this->_caption['contents'];
                $strHtml .= $tabs . $tab . '<caption' . $this->_getAttrString($attr) . '>';
                if (is_array($contents)) {
                    $contents = implode(', ', $contents);
                }
                $strHtml .= $contents;
                $strHtml .= '</caption>' . $lnEnd;
            }
            if (!empty($this->_colgroup)) {
                foreach ($this->_colgroup as $g => $col) {
                    $attr = $this->_colgroup[$g]['attr'];
                    $contents = $this->_colgroup[$g]['contents'];
                    $strHtml .= $tabs . $tab . '<colgroup' . $this->_getAttrString($attr) . '>';
                    if (!empty($contents)) {
                        $strHtml .= $lnEnd;
                        if (!is_array($contents)) {
                            $contents = array($contents);
                        }
                        foreach ($contents as $a => $colAttr) {
                            $attr = $this->_parseAttributes($colAttr);
                            $strHtml .= $tabs . $tab . $tab . '<col' . $this->_getAttrString($attr) . ' />' . $lnEnd;
                        }
                        $strHtml .= $tabs . $tab;
                    }
                    $strHtml .= '</colgroup>' . $lnEnd;
                }
            }
            if ($this->_useTGroups) {
                $tHeadColCount = 0;
                if ($this->_thead !== null) {
                    $tHeadColCount = $this->_thead->getColCount();
                }
                $tFootColCount = 0;
                if ($this->_tfoot !== null) {
                    $tFootColCount = $this->_tfoot->getColCount();
                }
                $maxColCount = max($tHeadColCount, $tFootColCount, $tBodyMaxColCount);
                if ($this->_thead !== null) {
                    $this->_thead->setColCount($maxColCount);
                    if ($this->_thead->getRowCount() > 0) {
                        $strHtml .= $tabs . $tab . '<thead' .
                                    $this->_getAttrString($this->_thead->_attributes) .
                                    '>' . $lnEnd;
                        $strHtml .= $this->_thead->toHtml($tabs, $tab);
                        $strHtml .= $tabs . $tab . '</thead>' . $lnEnd;
                    }
                }
                if ($this->_tfoot !== null) {
                    $this->_tfoot->setColCount($maxColCount);
                    if ($this->_tfoot->getRowCount() > 0) {
                        $strHtml .= $tabs . $tab . '<tfoot' .
                                    $this->_getAttrString($this->_tfoot->_attributes) .
                                    '>' . $lnEnd;
                        $strHtml .= $this->_tfoot->toHtml($tabs, $tab);
                        $strHtml .= $tabs . $tab . '</tfoot>' . $lnEnd;
                    }
                }
                for ($i = 0; $i < $this->_tbodyCount; $i++) {
                    $this->_tbodies[$i]->setColCount($maxColCount);
                    if ($this->_tbodies[$i]->getRowCount() > 0) {
                        $strHtml .= $tabs . $tab . '<tbody' .
                                    $this->_getAttrString($this->_tbodies[$i]->_attributes) .
                                    '>' . $lnEnd;
                        $strHtml .= $this->_tbodies[$i]->toHtml($tabs, $tab);
                        $strHtml .= $tabs . $tab . '</tbody>' . $lnEnd;
                    }
                }
            } else {
                for ($i = 0; $i < $this->_tbodyCount; $i++) {
                    $strHtml .= $this->_tbodies[$i]->toHtml($tabs, $tab);
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
