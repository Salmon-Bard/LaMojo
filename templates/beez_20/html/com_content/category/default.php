<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$app = JFactory::getApplication();
$templateparams =$app->getTemplate(true)->params;

if (!$templateparams->get('html5', 0))
{
	require(JPATH_BASE.'/components/com_content/views/category/tmpl/default.php');
	//evtl. ersetzen durch JPATH_COMPONENT.'/views/...'
} else {
JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers');

$pageClass = $this->params->get('pageclass_sfx');
?>

<section class="category-list <?php echo $pageClass;?>">
<?php if ($this->params->get('show_page_heading', 1)) : ?>
<?php if ($this->params->get('show_page_heading', 1) AND ($this->params->get('show_category_title') OR $this->params->get('page_subheading'))) : ?>
<hgroup>
<?php endif; ?>
<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>

<?php if ($this->params->get('show_category_title') OR $this->params->get('page_subheading')) : ?>
<h2>
	<?php echo $this->escape($this->params->get('page_subheading')); ?>
	<?php if ($this->params->get('show_category_title'))
	{

		echo '<span class="subheading-category">'.$this->category->title.'</span>';
	}
	?>
</h2>
<?php if ($this->params->get('show_page_heading', 1) AND ($this->params->get('show_category_title', 1) OR $this->params->get('page_subheading'))) : ?>
</hgroup>
<?php endif; ?>
<?php endif; ?>

<?php if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
	<div class="category-desc">
	<?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
		<img src="images/<?php echo $this->category->getParams()->get('image'); ?>"/>
	<?php endif; ?>
	<?php if ($this->params->get('show_description') && $this->category->description) : ?>
		<?php echo JHtml::_('content.prepare', $this->category->description); ?>
	<?php endif; ?>
	<div class="clr"></div>
	</div>
<?php endif; ?>


	<?php if (is_array($this->children) && count($this->children) > 0 && $this->params->get('maxLevel') !=0)  : ?>
		<div class="cat-children">

	 <?php if ($this->params->get('show_category_title') OR $this->params->get('page_subheading'))
	 {  echo '<h3>' ;}
	 else

	{echo '<h2>' ;} ?>

<?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?>
 <?php if ($this->params->get('show_category_title') OR $this->params->get('page_subheading'))
	 {  echo '</h3>' ;}
	 else

	{echo '</h2>' ;} ?>
			<?php echo $this->loadTemplate('children'); ?>
		</div>
	<?php endif; ?>

	<div class="cat-items">
		<?php echo $this->loadTemplate('articles'); ?>
	</div>

</section>
<?php } ?>