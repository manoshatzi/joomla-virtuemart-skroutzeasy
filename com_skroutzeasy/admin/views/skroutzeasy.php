<?php
/**
 * @version		$Id: banner.php 18162 2010-07-16 07:00:47Z ian $
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

/**
* @package		Joomla
* @subpackage	Banners
*/
class SkroutzeasyViewSkroutzeasy
{
	function setToolbar()
	{
		JToolBarHelper::title( JText::_( 'Skroutz Easy' ), 'generic.png' );
		JToolBarHelper::save( 'save' );
		JToolBarHelper::apply('apply');
		JToolBarHelper::cancel( 'cancel' );
		JToolBarHelper::help( 'screen.banners' );
	}

	function skroutzeasy( &$row )
	{
		SkroutzeasyViewSkroutzeasy::setToolbar();

		?>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			if (form.client_id.value == "") {
				alert( "<?php echo JText::_( 'You must provide a Client Id.', true ); ?>" );
			} else if (form.client_secret.value == "") {
				alert( "<?php echo JText::_( 'You must provide a Client Secret.', true ); ?>" );
			} else if (form.redirect_uri.value == "") {
				alert( "<?php echo JText::_( 'You must provide a Redirect Uri.', true ); ?>" );
			} else {
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		<form action="index.php" method="post" name="adminForm">

		<div class="col100">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Details' ); ?></legend>

				<table class="admintable">
				<tbody>
					<tr>
						<td width="20%" class="key">
							<label for="client_id">
								<?php echo JText::_( 'Client Id' ); ?>:
							</label>
						</td>
						<td width="80%">
                                                        <input class="inputbox" type="text" name="client_id" id="client_id" size="100" value="<?php echo $row[0]->client_id;?>" />
						</td>
					</tr>
					<tr>
						<td width="20%" class="key">
							<label for="client_secret">
								<?php echo JText::_( 'Client Secret' ); ?>:
							</label>
						</td>
						<td width="80%">
                                                        <input class="inputbox" type="text" name="client_secret" id="client_secret" size="100" value="<?php echo $row[0]->client_secret;?>" />
						</td>
					</tr>
					<tr>
						<td width="20%" class="key">
							<label for="redirect_uri">
								<?php echo JText::_( 'Redirect Uri' ); ?>:
							</label>
						</td>
						<td width="80%">
                                                        <input class="inputbox" type="text" name="redirect_uri" id="redirect_uri" size="100" value="<?php echo $row[0]->redirect_uri;?>" />
						</td>
					</tr>
					<tr>
						<td colspan="3">
						</td>
					</tr>
				</tbody>
				</table>
			</fieldset>
		</div>
		<div class="clr"></div>

		<input type="hidden" name="option" value="com_skroutzeasy" />
                <input type="hidden" name="sid" value="<?php echo $row[0]->sid; ?>" />
		<input type="hidden" name="task" value="" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php
	}

}
