<?php

/**
 * Brightfame Page Manager
 *
 * @category    Brightfame
 * @package     Brightfame_Page
 * @subpackage  Manager
 * @license     New BSD {@link http://framework.zend.com/license/new-bsd}
 * @version     $Id: $
 */
class Brightfame_Page_Manager
{
    /**
     * Singleton instance
     *
     * @var Brightfame_Page_Manager
     */
    protected static $_instance = null;
    
    /**
     * Singleton pattern implementation makes "new" unavailable
     *
     * @return void
     */
    protected function __construct()
    {}

    /**
     * Singleton pattern implementation makes "clone" unavailable
     *
     * @return void
     */
    protected function __clone()
    {}

    /**
     * Returns an instance of Brightfame_Page_Manager.
     *
     * Singleton pattern implementation
     *
     * @return Brightfame_Page_Manager Provides a fluent interface
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    
    /**
     * Returns the Default Page
     * 
     * @return Brightfame_Page
     */
    public function getDefaultPage()
    {
        
    }
    
    /**
     * Return the Requested Page.
     * If the page doesn't exit then return the default 404 page.
     * 
     * @param string $page
     * @return Brightfame_Page
     */
    public function getPage($page)
    {
        
    }
}