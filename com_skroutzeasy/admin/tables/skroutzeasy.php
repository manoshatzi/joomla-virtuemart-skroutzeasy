<?php
/**
 * @version	$Id: banner.php 14401 2010-01-26 14:10:00Z louis $
 * @package	Joomla
 * @subpackage	Skroutzeasy
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * @package	Joomla
 * @subpackage	Skroutzeasy
 */
class TableSkroutzeasy extends JTable
{
	/** @var int */
	var $sid                        = null;
	/** @var int */
	var $client_id			= '';
	/** @var string */
	var $client_secret              = '';
	/** @var string */
	var $redirect_uri		= '';

	function __construct( &$_db )
	{
		parent::__construct( '#__skroutzeasy', 'sid', $_db );
	}


	/**
	 * Overloaded check function
	 *
	 * @access public
	 * @return boolean
	 * @see JTable::check
	 * @since 1.5
	 */
	function check()
	{
		// check for valid name
		if(trim($this->client_id) == '') {
			$this->setError(JText::_( 'You must select a name for the Client Id.' ));
			return false;
		}
                
		// check for valid name
		if(trim($this->client_secret) == '') {
			$this->setError(JText::_( 'You must select a name for the Client Secret.' ));
			return false;
		}
                
		// check for valid name
		if(trim($this->redirect_uri) == '') {
			$this->setError(JText::_( 'You must select a name for the Redirect Uri.' ));
			return false;
		}

		return true;
	}
}
?>
