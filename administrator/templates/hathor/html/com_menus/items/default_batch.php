<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	templates.hathor
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.6
 */

// no direct access
defined('_JEXEC') or die;

$options = array(
	JHtml::_('select.option', 'c', JText::_('JGLOBAL_BATCH_COPY')),
	JHtml::_('select.option', 'm', JText::_('JGLOBAL_BATCH_MOVE'))
);
$published = $this->state->get('filter.published');
?>
<fieldset class="batch">
	<legend><?php echo JText::_('COM_MENUS_BATCH_OPTIONS');?></legend>
	<label id="batch-access-lbl" for="batch-access" class="hasTip" title="<?php echo JText::_('JGLOBAL_BATCH_ACCESS_LABEL').'::'.JText::_('JGLOBAL_BATCH_ACCESS_LABEL_DESC'); ?>">
		<?php echo JText::_('JGLOBAL_BATCH_ACCESS_LABEL') ?>
	</label>
	<?php echo JHtml::_('access.assetgrouplist', 'batch[assetgroup_id]', '', 'class="inputbox"', array('title' => JText::_('JGLOBAL_BATCH_NOCHANGE'), 'id' => 'batch-access'));?>

	<?php if ($published >= 0) : ?>
		<label id="batch-choose-action-lbl" for="batch-menu-id">
			<?php echo JText::_('COM_MENUS_BATCH_MENU_LABEL'); ?>
		</label>
		<fieldset id="batch-choose-action" class="combo">
			<select name="batch[menu_id]" class="inputbox" id="batch-menu-id">
				<option value=""><?php echo JText::_('JSELECT') ?></option>
				<?php echo JHtml::_('select.options', JHtml::_('menu.menuitems', array('published' => $published)));?>
			</select>
			<?php echo JHTML::_( 'select.radiolist', $options, 'batch[move_copy]', '', 'value', 'text', 'm'); ?>
		</fieldset>
	<?php endif; ?>
	<button type="submit" onclick="Joomla.submitbutton('item.batch');">
		<?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
	</button>
	<button type="button" onclick="document.id('batch-menu-id').value='';document.id('batch-access').value=''">
		<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
	</button>
</fieldset>
