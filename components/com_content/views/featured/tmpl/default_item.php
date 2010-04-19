<?php
/**
 * @version		$Id: default_item.php 15845 2010-04-05 12:35:44Z hackwar $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Create shorcuts.
$params = $this->item->params;
$route	= ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid);
?>

<?php if ($this->item->state == 0) : ?>
<div class="system-unpublished">
<?php endif; ?>
<?php if ($params->get('show_title')) : ?>
	<h2>
		<?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
			<a href="<?php echo JRoute::_($route); ?>">
			<?php echo $this->escape($this->item->title); ?></a>
		<?php else : ?>
			<?php echo $this->escape($this->item->title); ?>
		<?php endif; ?>
	</h2>
<?php endif; ?>

<?php if ($params->get('show_print_icon') || $params->get('show_email_icon') || $params->get('access-edit')) : ?>
	<ul class="actions">
		<?php if ($params->get('show_print_icon')) : ?>
		<li class="print-icon">
			<?php echo JHtml::_('icon.print_popup', $this->item, $params); ?>
		</li>
		<?php endif; ?>
		<?php if ($params->get('show_email_icon')) : ?>
		<li class="email-icon">
			<?php echo JHtml::_('icon.email', $this->item, $params); ?>
		</li>
		<?php endif; ?>

		<?php if ($this->user->authorise('core.edit', 'com_content.frontpage.'.$this->item->id)) : ?>
		<li class="edit-icon">
			<?php echo JHtml::_('icon.edit', $this->item, $params); ?>
		</li>
		<?php endif; ?>
	</ul>
<?php endif; ?>

<?php if (!$params->get('show_intro')) : ?>
	<?php echo $this->item->event->afterDisplayTitle; ?>
<?php endif; ?>

<?php echo $this->item->event->beforeDisplayContent; ?>

<?php // to do not that elegant would be nice to group the params ?>

<?php if (($params->get('show_author')) or ($params->get('show_category')) or ($params->get('show_create_date')) or ($params->get('show_modify_date')) or ($params->get('show_publish_date'))) : ?>
 <dl class="article-info">
 <dt class="article-info-term"><?php  echo JText::_('CONTENT_ARTICLE_INFO'); ?></dt>
<?php endif; ?>
<?php if ($params->get('show_parent_category')) : ?>
		<dd class="parent-category-name">
			<?php $title = $this->escape($this->item->parent_title);
				$title = ($title) ? $title : JText::_('JGLOBAL_UNCATEGORISED');
				$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)) . '">' . $title . '</a>'; ?>
			<?php if ($params->get('link_parent_category') AND $this->item->parent_slug) : ?>
				<?php echo JText::sprintf('CONTENT_PARENT', $url); ?>
				<?php else : ?>
				<?php echo JText::sprintf('CONTENT_PARENT', $title); ?>
			<?php endif; ?>
		</dd>
<?php endif; ?>
<?php if ($params->get('show_category')) : ?>
		<dd class="category-name">
			<?php 	$title = $this->escape($this->item->category_title);
					$title = ($title) ? $title : JText::_('JGLOBAL_UNCATEGORISED');
					$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)).'">'.$title.'</a>';?>
			<?php if ($params->get('link_category') AND $this->item->catslug) : ?>
				<?php echo JText::sprintf('CONTENT_CATEGORY', $url); ?>
				<?php else : ?>
				<?php echo JText::sprintf('CONTENT_CATEGORY', $title); ?>
			<?php endif; ?>
		</dd>
<?php endif; ?>
<?php if ($params->get('show_create_date')) : ?>
		<dd class="create">
		<?php echo JText::sprintf('CONTENT_CREATED_DATE', JHTML::_('date',$this->item->created, JText::_('DATE_FORMAT_LC2'))); ?>
		</dd>
<?php endif; ?>
<?php if ($params->get('show_modify_date')) : ?>
		<dd class="modified">
		<?php echo JText::sprintf('LAST_UPDATED2', JHTML::_('date',$this->item->modified, JText::_('DATE_FORMAT_LC2'))); ?>
		</dd>
<?php endif; ?>
<?php if ($params->get('show_publish_date')) : ?>
		<dd class="published">
		<?php echo JText::sprintf('PUBLISHED_DATE', JHTML::_('date',$this->item->publish_up, JText::_('DATE_FORMAT_LC2'))); ?>
		</dd>
<?php endif; ?>
<?php if ($params->get('show_author') && !empty($this->item->author_name)) : ?>
	<dd class="createdby">
		<?php $author = $params->get('link_author', 0) ? JHTML::_('link',JRoute::_('index.php?option=com_users&view=profile&member_id='.$this->item->created_by),$this->item->author_name) : $this->item->author_name; ?>
		<?php $author=($this->item->created_by_alias ? $this->item->created_by_alias : $author);?>
	<?php echo JText::sprintf('Written_by', $author); ?>
		</dd>
	<?php endif; ?>
<?php if ($params->get('show_hits')) : ?>
		<dd class="hits">
		<?php echo JText::sprintf('CONTENT_ARTICLE_HITS', $this->item->hits); ?>
		</dd>
<?php endif; ?>
	<?php if (($params->get('show_author')) or ($params->get('show_category')) or ($params->get('show_create_date')) or ($params->get('show_modify_date')) or ($params->get('show_publish_date'))) : ?>
 </dl>
<?php endif; ?>

<?php echo $this->item->introtext; ?>

<?php if ($params->get('show_readmore') && $this->item->readmore) :
	if (!$params->get('access-view')) :
		$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
	else :
		$menu = JSite::getMenu();
		$active = $menu->getActive();
		$itemId = $active->id;
		$link1 = JRoute::_('index.php?option=com_users&view=login&&Itemid=' . $itemId);
		$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
		$link = new JURI($link1);
		$link->setVar('return', base64_encode($returnURL));
	endif;
?>
		<p class="readmore">
				<a href="<?php echo $link; ?>">
						<?php if ($params->get('access-view')) :
								echo JText::_('REGISTER_TO_READ_MORE');
						elseif ($readmore = $params->get('alternative_readmore')) :
								echo $readmore;
						else :
								echo JText::sprintf('READ_MORE', $this->escape($this->item->title));
						endif; ?></a>
		</p>
<?php endif; ?>

<?php if ($this->item->state == 0) : ?>
</div>
<?php endif; ?>

<?php
	if ($this->item->params->get('article-allow_comments')) :
		JHtml::addIncludePath(JPATH_SITE.'/components/com_social/helpers/html');
		echo JHtml::_(
			'comments.summary',
			'com_content',
			$this->item->id,
			'index.php?option=com_content&view=article&id='.$this->item->id,
			$route,
			$this->item->title
		);
	endif;
?>

<div class="item-separator"></div>
<?php echo $this->item->event->afterDisplayContent; ?>
