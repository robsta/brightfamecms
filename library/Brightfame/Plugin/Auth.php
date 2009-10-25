<?php

/** Zend_Controller_Plugin_Abstract */
require_once 'Zend/Controller/Plugin/Abstract.php';
 
/**
 * Plugin to initialize application state
 *
 * @category    Brightfame
 * @package     Brightfame_Auth
 * @license     New BSD {@link http://framework.zend.com/license/new-bsd}
 * @version     $Id: $
 */
class Brightfame_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    protected $_acl;
 
    /**
	 * Dispatch loop startup plugin: get identity and acls
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 * @return void
	 */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
    	$view = Zend_Registry::get('view');
        $auth = $this->getAuth();
        $values = array(
            'user_id' => null,
            'user_name' => null,
            'user_email' => null,
        );
        if ($auth->hasIdentity()) {
            $identity = $auth->getIdentity();
            $values = array(
                'user_id' => $identity->id,
                'user_name' => $identity->username,
                'user_email' => $identity->email,
            );
            $role = empty($identity->role) ? 'user' : $identity->role;
        } else {
            $role = 'guest';
        }
 
        Zend_Registry::set('acl', $this->getAcl());
        Zend_Registry::set('role', $role);
        Zend_Registry::set('user', $values);
    }
    
    /**
     * Get Auth
     * 
     * @return Zend_Auth
     */
    public function getAuth()
    {
		$auth = Zend_Auth::getInstance();
		$config = Zend_Registry::get('config');

		// Use 'someNamespace' instead of 'Zend_Auth'
		$auth->setStorage(new Zend_Auth_Storage_Session($config->app->namespace));
		
		return $auth;
    }
 
    /**
	 * Get ACL lists
	 *
	 * @return Zend_Acl
	 */
    public function getAcl()
    {
        if (null === $this->_acl) {
            $acl = new Zend_Acl();
            $this->_loadAclClasses();
            $acl->add(new Zend_Acl_Resource('page'))
                ->addRole(new Brightfame_Acl_Role_Guest)
                ->addRole(new Brightfame_Acl_Role_Member, 'guest')
                ->addRole(new Brightfame_Acl_Role_Administrator, 'member')
                ->deny()
                ->allow('guest', 'page', array('view'))
                ->allow('member', 'page', array('comment'))
                ->allow('administrator', 'page', array('add', 'edit', 'delete', 'buildindex'));
            $this->_acl = $acl;
        }
        return $this->_acl;
    }
 
    /**
	 * Load ACL classes from Brightfame Framework
	 *
	 * @return void
	 */
    protected function _loadAclClasses()
    {
        $loader = new Zend_Loader_PluginLoader(array(
            'Brightfame_Acl_Role' => APPLICATION_PATH . '/../library/Brightfame/Acl/Role/'
        ));
        foreach (array('Guest', 'Member', 'Administrator') as $role) {
            $loader->load($role);
        }
    }
}