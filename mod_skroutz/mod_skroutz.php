<?php
/**
 * @package    SkroutzEasy module for Joomla 1.5.x and 1.6.x+
 * @copyright  Copyright (c) 2012 Skroutz S.A. - http://www.skroutz.gr
 * @link       http://developers.skroutz.gr/oauth2
 * @license    MIT
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

$type 	= modSkroutzHelper::getType();
$return	= modSkroutzHelper::getReturnURL($params, $type);

$user =& JFactory::getUser();

require(JModuleHelper::getLayoutPath('mod_skroutz'));
