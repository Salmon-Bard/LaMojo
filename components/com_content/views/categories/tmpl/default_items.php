<?php 
/**
 * @version		$Id: default_items.php 15048 2010-02-25 17:24:37Z hackwar $
 * @package		Joomla.Site
 * @subpackage	com_weblinks
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

if(count($this->itemsLevel[$this->parent->level + 1]) > 0 && (($this->parent->level + 1) > $this->maxLevel || $this->maxLevel == 0)) : 
?>
	<ul>
	<?php foreach($this->itemsLevel[$this->parent->level + 1] as $item) : ?>
		<li>
		<span class="jitem-title"><a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($item->id.':'.$item->alias));?>">
			<?php echo $this->escape($item->title); ?></a>
		</span>
		<?php if ($item->description) : ?>
			<div class="jdescription">
				<?php echo JHtml::_('content.prepare', $item->description); ?>
			</div>
		<?php endif; ?>
		<?php 
		if(count($item->getChildren()) > 0) : 
			$this->itemsLevel[$item->level + 1] = $item->getChildren();
			$this->parent = $item;
			echo $this->loadTemplate('items');
			$this->parent = $item->getParent();
		endif;
		?>
		</li>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>