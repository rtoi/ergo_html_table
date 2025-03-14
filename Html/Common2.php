<?php

namespace Ergo\Html;

use HTML_Common2;

/**
 * Extends pear\Html_Common2
 *
 * PHP version
 *
 * @author Risto Toivonen <risto@ergonomiapalvelu.fi>
 * @copyright 2023 Risto Toivonen <risto@ergonomiapalvelu.fi>
 * @license http://URL BSD-2-Clause
 */
abstract class Common2 extends HTML_Common2
{
    /**
     * Updates the attributes in $attr1 with the values in $attr2 without changing the other existing attributes
     * Copied from HTML_Common
     *
     * @param    array   $attr1      Original attributes array
     * @param    array   $attr2      New attributes array
     * @return void
     */
    protected function updateAttrArray(array &$attr1, array $attr2): void
    {
        foreach ($attr2 as $key => $value) {
            $attr1[$key] = $value;
        }
    }

    /**
     * Keep only names attributes in an array.
     * 
     * @param array $attr1 
     * @param array $attr2 Array of keys to keep in array $attr1.
     * @return void
     */
    public static function keepAttributesArray(array &$attr1, array $attr2): void
    {
        $dropAttr = array_diff(array_keys($attr1), $attr2);
        foreach ($dropAttr as  $key) {
            unset($attr1[$key]);
        }
    }

    /**
     * Removes attributes from an array.
     * 
     * @param array $attr1
     * @param array $attr2 Array of keys to remove from array $attr1.
     * @return void
     */
    public static function removeAttributesArray(array &$attr1, array $attr2): void
    {
        // $attr1 = array_diff($attr1, $attr2);
        foreach ($attr2 as $val) {
            if (isset($attr1[$val])) {
                unset($attr1[$val]);
            }
        }
    }

    public static function styleStrToArray(string $style): array
    {
        $arr = \explode(';', $style);
        $out = [];
        foreach ($arr as $val) {
            $tmp = explode(':', $val);
            if (count($tmp) !== 2) {
                continue;
            }
            array_walk($tmp, function (&$val) {$val = trim($val);});
            $out[$tmp[0]] = $tmp[1];
        }
        return $out;
    }
    
    public static function styleArrayToStr(array $style): string
    {
        $str = '';
        foreach ($style as $key => $val) {
            $str .= $key;
            $str .= str_ends_with($key, ':') ? ' ' : ': ';
            $str .= $val;
            $str .= str_ends_with($val, ';') ? '' : ';';
        }
        return $str;
    }


    /**
     * Checks whether the element has given CSS class
     * Method content copied from HTML_Common2
     *
     * @param string $class
     * @param array $attr
     * @return bool
     */
    public static function hasClassArray(string $class, array $attr = []): bool
    {
        $regex = '/(^|\s)' . preg_quote($class, '/') . '(\s|$)/';
        return (bool) \preg_match($regex, $attr['class'] ??= '');
    }

    /**
     * Adds new class(es) to array of attributes.
     *
     * @param string $class
     * @param array $attr
     * @return array
     */
    public static function addClassArray(array|string $class, array $attr = []): array
    {
        $tmp = new static();
        $tmp->mergeAttributes($attr);
        $tmp->addClass($class);
        return $tmp->getAttributes();
    }

    /**
     * Removes a class from an array of attributes.
     *
     * @param string $class
     * @param array $attr
     * @return array
     */
    public static function removeClassArray(array|string $class, array $attr = []): array
    {
        $tmp = new static();
        $tmp->mergeAttributes($attr);
        $tmp->removeClass($class);
        return $tmp->getAttributes();
    }
}
