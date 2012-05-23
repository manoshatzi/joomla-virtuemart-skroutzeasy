<?php
/**
 * @package    SkroutzEasy module for Joomla 1.5.x and 1.6.x+
 * @copyright  Copyright (c) 2012 Skroutz S.A. - http://www.skroutz.gr
 * @link       http://developers.skroutz.gr/oauth2
 * @license    MIT
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class modSkroutzHelper
{
	function getReturnURL($params, $type)
	{
		if($itemid =  $params->get($type))
		{
			$menu =& JSite::getMenu();
			$item = $menu->getItem($itemid); //var_dump($menu);die;
			if ($item)
			{
				$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
			}
			else
			{
				// stay on the same page
				$uri = JFactory::getURI();
				$url = $uri->toString(array('path', 'query', 'fragment'));
			}

		}
		else
		{
			// stay on the same page
			$uri = JFactory::getURI();
			$url = $uri->toString(array('path', 'query', 'fragment'));
		}

		return base64_encode($url);
	}

	function getType()
	{
		$user = & JFactory::getUser();
		return (!$user->get('guest')) ? 'logout' : 'login';
	}
}
