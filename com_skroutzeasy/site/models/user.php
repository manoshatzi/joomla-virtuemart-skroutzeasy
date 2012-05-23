<?php
/**
 * @version		$Id: user.php 14401 2010-01-26 14:10:00Z louis $
 * @package		Joomla
 * @subpackage	User
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * User Component User Model
 *
 * @package		Joomla
 * @subpackage	User
 * @since 1.5
 */
class SkroutzeasyModelUser extends JModel
{
	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Method to store the user data
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function storeBT($data,$user,$type)
	{
            // Get a database object
            $db =& JFactory::getDBO();
            if($type == 1){
                $query = 'SELECT `id`,`username` FROM #__users WHERE email=' . $db->quote( $user->get('email') );
                $db->setQuery($query);
                $usr = $db->loadObject();
                
                $data['virtuemart_user_id'] = $usr->id;
                $data['address_type'] = 'BT';
                $userinfo   = $this->getTable('userinfos');
                if (!$userinfo->bindChecknStore($data)) {
                    vmError($userinfo->getError());
                }

                $data['virtuemart_user_id'] = $usr->id;
                $data['virtuemart_vendor_id'] = 1;
                $data['user_is_vendor'] = 0;
                $data['customer_number'] = md5($usr->username);
                $data['perms'] = 'shopper';
                $usertable = $this->getTable('vmusers');
                $usertable -> bindChecknStore($data);
                $errors = $usertable->getErrors();
		foreach($errors as $error){
			$this->setError($error);
			vmError('storing user adress data'.$error);
			$noError = false;
		}
                return $noError;
                
            }elseif($type == 2){
                $userinfo   = $this->getTable('userinfos');
                $data['virtuemart_user_id'] = $user;
                $data['address_type'] = 'ST';
                $data['address_type_name'] = 'shipping';
                if (!$userinfo->bindChecknStore($data)) {
                    vmError($userinfo->getError());
                }
            }

	}
        
	/**
	 * Method to store the user data
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function storeST($data,$user,$type)
	{
            // Get a database object
            $db =& JFactory::getDBO();
            if($type == 1){
                $query = 'SELECT `id` FROM #__users WHERE email=' . $db->quote( $user->get('email') );
                $db->setQuery($query);
                $usr = $db->loadObject();
                
                $data['virtuemart_user_id'] = $usr->id;
                $data['address_type'] = 'ST';
                $data['address_type_name'] = 'shipping';
                $userinfo   = $this->getTable('userinfos');                
                if (!$userinfo->bindChecknStore($data)) {
                    vmError($userinfo->getError());
                }
                
            }elseif($type == 2){
                $userinfo   = $this->getTable('userinfos');
                $data['virtuemart_user_id'] = $user;
                $data['address_type'] = 'ST';
                $data['address_type_name'] = 'shipping';
                if (!$userinfo->bindChecknStore($data)) {
                    vmError($userinfo->getError());
                }
            }

	}
        
	/**
	 * Method to store the user data
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function updateBT($data,$user)
	{
            $data['virtuemart_user_id'] = $user;
            $data['address_type'] = 'BT';
            $userinfo   = $this->getTable('userinfos');                
            if (!$userinfo->bindChecknStore($data)) {
                vmError($userinfo->getError());
            }
	}
        
	/**
	 * Method to store the user data
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function updateST($data,$user)
	{
            $data['virtuemart_user_id'] = $user;
            $data['address_type'] = 'ST';
            $data['address_type_name'] = 'shipping';
            $userinfo   = $this->getTable('userinfos');                
            if (!$userinfo->bindChecknStore($data)) {
                vmError($userinfo->getError());
            }

	}

}
?>
