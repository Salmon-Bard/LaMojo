<?php
/**
 * @version		$Id: default.php 14353 2010-01-22 17:50:50Z hackwar $
 * @package		Joomla.Administrator
 * @subpackage	com_templates
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');

$user = JFactory::getUser();
?>
<form action="<?php echo JRoute::_('index.php?option=com_templates&view=styles'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
	<legend class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></legend>
		<div class="filter-search">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" title="<?php echo JText::_('COM_TEMPLATES_STYLES_FILTER_SEARCH_DESC'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select">
			<label class="selectlabel" for="filter_client_id">
				<?php echo JText::_('COM_TEMPLATES_FILTER_TYPE'); ?>
			</label>
			<select name="filter_client_id" id="filter_client_id" class="inputbox">
				<option value="*"><?php echo JText::_('COM_TEMPLATES_FILTER_TYPE'); ?></option>
				<?php echo JHtml::_('select.options', TemplatesHelper::getClientOptions(), 'value', 'text', $this->state->get('filter.client_id'));?>
			</select>
		
			<label class="selectlabel" for="filter_template">
				<?php echo JText::_('COM_TEMPLATES_FILTER_TEMPLATE'); ?>
			</label>
			<select name="filter_template" id="filter_template" class="inputbox">
				<option value="0"><?php echo JText::_('COM_TEMPLATES_FILTER_TEMPLATE'); ?></option>
				<?php echo JHtml::_('select.options', TemplatesHelper::getTemplateOptions($this->state->get('filter.template')), 'value', 'text', $this->state->get('filter.template'));?>
			</select>
			
			<button type="button" id="filter-go" onclick="this.form.submit();">
				<?php echo JText::_('Go'); ?></button>
			
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th class="checkmark-col">
					&nbsp;
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_TEMPLATES_HEADING_STYLE', 'a.title', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_TEMPLATES_HEADING_TEMPLATE', 'a.template', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="width-10">
					<?php echo JHtml::_('grid.sort', 'COM_TEMPLATES_HEADING_TYPE', 'a.client_id', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="width-5">
					<?php echo JHtml::_('grid.sort', 'COM_TEMPLATES_HEADING_DEFAULT', 'a.home', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="width-5">
					<?php echo JText::_('COM_TEMPLATES_HEADING_ASSIGNED'); ?>
				</th>
				<th class="nowrap id-col">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach ($this->items as $i => $item) :
				$canCreate	= $user->authorise('core.create',		'com_templates');
				$canEdit	= $user->authorise('core.edit',			'com_templates');
				$canChange	= $user->authorise('core.edit.state',	'com_templates');
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<input type="radio" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo (int) $item->id; ?>" onclick="isChecked(this.checked);" title="<?php $i+1;?>"/>
				</td>
				<td>
					<?php if ($canCreate || $canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_templates&task=style.edit&id='.(int) $item->id); ?>">
						<?php echo $this->escape($item->title);?></a>
					<?php else : ?>
						<?php echo $this->escape($item->title);?>
					<?php endif; ?>
				</td>
				<td>
					<label for="cb<?php echo $i;?>">
						<?php echo $this->escape($item->template);?>
					</label>
				</td>
				<td class="center">
					<?php echo $item->client_id == 0 ? JText::_('JSITE') : JText::_('JADMINISTRATOR'); ?>
				</td>
				<td class="center">
					<?php if ($item->home == 1) : ?>
							<?php echo JHTML::_('image','menu/icon-16-default.png', JText::_('COM_TEMPLATES_HEADING_DEFAULT'), NULL, true); ?>
					<?php else  : ?>
							&nbsp;
					<?php endif; ?>
				</td>
				<td class="center">
					<?php if ($item->assigned == 1) : ?>
							<?php echo JHTML::_('image','admin/tick.png', JText::_('COM_TEMPLATES_HEADING_ASSIGNED'), NULL, true); ?>
					<?php else : ?>
							&nbsp;
					<?php endif; ?>
				</td>
				<td class="center">
					<?php echo (int) $item->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	
	<?php echo $this->pagination->getListFooter(); ?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->state->get('list.ordering'); ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->get('list.direction'); ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
