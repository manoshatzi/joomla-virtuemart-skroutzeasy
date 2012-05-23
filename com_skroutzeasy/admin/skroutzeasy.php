<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Set the table directory
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_skroutzeasy'.DS.'tables');

$controllerName = JRequest::getCmd( '', 'skroutzeasy' );

switch ($controllerName)
{
    default:
        $controllerName = 'skroutzeasy';
        // allow fall through

    case 'skroutzeasy' :
    case 'client':
        require_once( JPATH_COMPONENT.DS.'controllers'.DS.$controllerName.'.php' );
	$controllerName = 'SkroutzeasyController'.$controllerName;

        // Create the controller
	$controller = new $controllerName();

        // Perform the Request task
	$controller->execute( JRequest::getCmd('task') );

        // Redirect if set by the controller
	$controller->redirect();
	break;
}