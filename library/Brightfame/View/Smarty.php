<?php

require_once 'Zend/View/Interface.php';

class Brightfame_View_Smarty implements Zend_View_Interface {
 
    protected $_smarty = null;
    /**
     * Sets the template engine object
     *
     * @return smarty object
     */
    public function setEngine($smarty) {
        $this->_smarty = $smarty;
    }
    /**
     * Return the template engine object, if any
     * @return mixed
     */
    public function getEngine() {
        return $this->_smarty;
    }
 
    public function setScriptPath($path) {
        // nothing to do... in smarty...
    }
 
    /**
     * Assign a variable to the view
     *
     * @param string $key The variable name.
     * @param mixed $val The variable value.
     * @return void
     */
    public function __set($key, $val) {
        if ('_' == substr($key, 0, 1)) {
            require_once 'Zend/View/Exception.php';
            throw new Zend_View_Exception(
                'Setting private var is not allowed',
                $this);
        }
        if ($this->_smarty == null) {
            require_once 'Zend/View/Exception.php';
            throw new Zend_View_Exception(
                'Smarty not defined',
                $this);
        }
        $this->_smarty->assign($key,$val);
        return;
    }
 
    public function __get($key) {
        if ('_' == substr($key, 0, 1)) {
            require_once 'Zend/View/Exception.php';
            throw new Zend_View_Exception(
                'Setting private var is not allowed',
                $this);
        }
        if ($this->_smarty == null) {
            require_once 'Zend/View/Exception.php';
            throw new Zend_View_Exception(
                'Smarty not defined',
                $this);
        }
        return $this->_smarty->get_template_vars($key);
    }
 
    /**
     * Allows testing with empty() and
     * isset() to work
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key) {
        $vars = $this->_smarty->get_template_vars();
        return isset($vars[$key]);
    }
 
    /**
     * Allows unset() on object properties to work
     *
     * @param string $key
     * @return void
     */
    public function __unset($key) {
        $this->_smarty->clear_assign($key);
    }
 
    /**
     * Assign variables (other method)
     *
     */
    public function assign($spec, $value = null) {
        if (!is_array($spec)) {
            $spec = array($spec=>$value);
        }
        foreach ($spec as $key=>$val) {
            if ('_' == substr($key, 0, 1)) {
                require_once 'Zend/View/Exception.php';
                throw new Zend_View_Exception(
                    'Setting private var is not allowed',
                    $this);
            }
            if ($this->_smarty == null) {
                require_once 'Zend/View/Exception.php';
                throw new Zend_View_Exception(
                    'Smarty not defined', $this);
            }
            $this->_smarty->assign($key,$val);
        }
        return;
 
    }
 
    /**
     * Clear all assigned variables
     *
     * Clears all variables assigned to
     * Zend_View either via {@link assign()} or
     * property overloading ({@link __get()}/{@link __set()}).
     *
     * @return void
     */
    public function clearVars() {
        $this->_smarty->clear_all_assign();
    }
 
    /**
     * Processes a view script and returns the output.
     *
     * @param string $name The script script name to process.
     * @return string The script output.
     */
    public function render($name) {
        return $this->_smarty->fetch($name);
    }
 
    /**
     * Retrieve all view script paths
     * unused (smarty...)
     * @return array
     */
    public function getScriptPaths() {}
 
    /**
     * Set a base path to all view resources
     * unused (smarty...)
     * @param  string $path
     * @param  string $classPrefix
     * @return void
     */
    public function setBasePath($path, $classPrefix='Zend_View')
    {
    }
 
    /**
     * Add an additional path to view resources
     * unused (smarty...)
     * @param  string $path
     * @param  string $classPrefix
     * @return void
     */
    public function addBasePath($path, $classPrefix='Zend_View')
    {
    }
}