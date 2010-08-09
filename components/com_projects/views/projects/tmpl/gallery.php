<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Vars
$params =  $this->params;
$pageClass = $this->escape($params->get('pageclass_sfx'));
?>
<div class="projects<?php echo $pageClass;?> blog<?php echo $pageClass;?>">
	<div class="projects-content">
		<h1><?php echo JText::_('COM_PROJECTS_PROJECTS_LIST_HEADER');?></h1>
		<?php foreach ($this->items as $item) : 
			$this->item = $item;
			echo $this->loadTemplate('item');
		endforeach; ?>

		<?php if ($this->params->get('show_pagination', 1) && ($this->pagination->get('pages.total') > 1)) : ?>
		<div class="pagination">
			<?php  if ($this->params->get('show_pagination_results', 1)) : ?>
			<p class="counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
			<?php endif; ?>
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
		<?php  endif; ?>
	</div>

	</div>
</div>