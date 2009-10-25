<?php

/**
 * Brightfame
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category   Brightfame
 * @package    Brightfame_Filter
 * @copyright  Copyright (c) 2009 Rob Morgan. (http://robmorgan.id.au)
 * @license    New BSD License
 */
 
require_once 'Zend/Filter/Interface.php';
require_once 'Zend/Filter.php';
require_once 'Zend/Filter/StripTags.php';
require_once 'Zend/Filter/HtmlEntities.php';
require_once 'Zend/Filter/StringToLower.php';
require_once 'Zend/Filter/Word/SeparatorToDash.php';
require_once 'Zend/Filter/PregReplace.php';
require_once 'Brightfame/Filter/RemoveAccents.php';
 
/**
 * @category   Brightfame
 * @package    Brightfame_Filter
 * @copyright  Copyright (c) 2009 Rob Morgan. (http://robmorgan.id.au)
 * @license    New BSD License
 */
class Brightfame_Filter_SanitizeString implements Zend_Filter_Interface
{
    /**
     * Defined by Zend_Filter_Interface
     *
     * Returns sanitized value suitable for urls filenames etc.
     *
     * Inspired from the sanitize_title function in wordpress.
     *
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        $filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StripTags())
                    ->addFilter(new Zend_Filter_StringToLower())
                    ->addFilter(new Zend_Filter_Word_SeparatorToDash())
                    ->addFilter(new Common_Filter_RemoveAccents())
                    ->addFilter(new Zend_Filter_PregReplace('/&.+?;/', ''))
                    ->addFilter(new Zend_Filter_PregReplace('/[^%a-z0-9 _-]/', ''))
                    ->addFilter(new Zend_Filter_PregReplace('|-+|', '-'));
 
        return $filterChain->filter($value);
    }
}