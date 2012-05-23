<?php
/**
 * @version		$Id: banner.php 19343 2010-11-03 18:12:02Z ian $
 * @package		Joomla
 * @subpackage	Banners
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

jimport( 'joomla.application.component.controller' );

/**
 * @package	Joomla
 * @subpackage	Skroutzeasy
 */
class SkroutzeasyControllerSkroutzeasy extends JController
{
	/**
	 * Constructor
	 */
	function __construct( $config = array() )
	{
		parent::__construct( $config );
		// Register Extra tasks
		$this->registerTask( 'apply', 'save' );
	}

	/**
	 * Display the Skroutz Easy params
	 */
	function display()
	{
		global $mainframe;

		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__skroutzeasy';
		$db->setQuery( $query);
		$rows = $db->loadObjectList();

		require_once(JPATH_COMPONENT.DS.'views'.DS.'skroutzeasy.php');
		SkroutzeasyViewSkroutzeasy::skroutzeasy( $rows);
	}

	/**
	 * Save method
	 */
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_skroutzeasy' );

		// Initialize variables
		$post   = JRequest::get( 'post' );
		$row    =& JTable::getInstance('skroutzeasy', 'Table');

		if (!$row->bind( $post )) {
			return JError::raiseWarning( 500, $row->getError() );
		}

		if (!$row->check()) {
			return JError::raiseWarning( 500, $row->getError() );
		}

		if (!$row->store()) {
			return JError::raiseWarning( 500, $row->getError() );
		}
		$row->checkin();

                $link = 'index.php?option=com_skroutzeasy';

		$this->setRedirect( $link, JText::_( 'Your data have been saved.' ) );
	}

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
                $this->setRedirect( 'index.php' );
        }
}
