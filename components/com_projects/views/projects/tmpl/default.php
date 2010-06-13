<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die;

// include CSS
$document = &JFactory::getDocument();
$document->addStyleSheet('components/com_projects/assets/css/default.css');
?>

<div class="divProjects">
	<div class="divProjects-left-column">
		<div class="divProjects-content">
			<div class="porfolio-list-frame">
				<div class="componenetheading"><?php echo JText::_('COM_PROJECTS_PORFOLIO_LIST_HEADER');?></div>
				<div class="porfolio-list">
					<?php foreach ($this->items as $item) :
						$this->item = $item;
						echo $this->loadTemplate('item');
				endforeach; ?>
				</div>
				<?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) { ?>
				<div class="pagination">
					<?php  if ($this->params->def('show_pagination_results', 1)) { ?>
					<p class="counter">
						<?php echo $this->pagination->getPagesCounter(); ?>
					</p>
				<?php } ?>
				<?php echo $this->pagination->getPagesLinks(); ?>
				</div>
				<?php  } ?>
			</div>
		</div>
	</div>
	
	<div class="divProjects-right-column">
		<div class="divProjects-content">
			<?php echo JText::_('COM_PROJECTS_PROJECTS_GREETING');?><br /><br />
			<a href="<?php echo JRoute::_('index.php?option=com_projects&view=project&layout=form'); ?>">
			<?php echo JText::_('COM_PROJECTS_PROJECT_ADD');?></a><br />
		</div>
	</div>
</div>