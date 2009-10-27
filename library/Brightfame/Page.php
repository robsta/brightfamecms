<?php

class Brightfame_Page extends Brightfame_Model_Abstract
{
    // The page object stores all of its data in the params array
    protected $_params = array(
        'id'                 => null,
        'uri'                => null,
        'baseUrl'            => null,
        'data'               => null,
        'parents'            => null,
        'metaData'           => null,
        'properties'         => null,
        'language'           => null,
        'availableLanguages' => null,
        'content'            => null,
        'defaultContent'     => null,
        'contentTemplate'    => null,
        'design'             => null,
        'layout'             => null,
    );

    // These parameters are locked
    protected $_protectedParams = array();

    public function __construct($uri, $view = null)
    {
        $this->setParam('uri', $uri);
        $this->setView($view);
    }

    public function __wakeup()
    {
    }

    public function setParams($params)
    {
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                $this->setParam($key, $value);
            }
        }
    }

    public function setParam($key, $value, $protected = false)
    {
        if ($this->_isProtected($key)) {
            require_once 'Brightfame/Page/Exception.php';
            throw new Brightfame_Page_Exception('Unable to set this protected property in Brightfame_Page: ' . $key);
        } else {
            $this->_params[$key] = $value;
            if ($protected == true) {
                $this->_protectedParams[] = $key;
            }
        }
    }

    public function getParams()
    {
        return $this->_params;
    }

    public function getParam($key)
    {
        if (isset($this->_params[$key])) {
            return $this->_params[$key];
        }
    }

    public function setData($data)
    {
        $this->setParam('data', serialize($data));
    }

    public function getData()
    {
        return unserialize($this->getParam('data'));
    }

    public function getLanguage()
    {
        if ($this->_hasProperty('language')) {
            return $this->getParam('language');
        } else {
            return null;
        }

    }

    public function getAvailableLanguages()
    {
        if ($this->_hasProperty('availableLanguages')) {
            return $this->getParam('availableLanguages');
        } else {
            return null;
        }

    }

    public function getContent($key = null, $useDefault = true)
    {
        $content = $this->getParam('content');
        $defaultContent = $this->getParam('defaultContent');
        if ($useDefault && is_array($defaultContent)) {
            foreach ($defaultContent as $k => $v) {
                if (!empty($v) && empty($content[$k])) {
                    $content[$k] = $v;
                }
            }
        }

        if ($key !== null) {
            return $content->$key;
        } else {
            return $content;
        }

    }
}