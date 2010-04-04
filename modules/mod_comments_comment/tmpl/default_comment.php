<?php
/**
 * @version		$Id$
 * @package		JXtended.Comments
 * @subpackage	mod_comments_comment
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://jxtended.com
 */

defined('_JEXEC') or die('Invalid Request.');

// Setup the display name for the comment.
$_name = $params->get('show_name_as', 0) ? 'user_login_name' : 'user_name';
?>
	<div class="metadata">
		<p class="author">
<?php if ($params->get('enable_gravatar', 0)) : ?>
			<img class="avatar" src="http://www.gravatar.com/avatar/<?php echo md5(strtolower($item->email)); ?>.jpg?s=30&amp;d=http%3A%2F%2Fwww.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D30" align="left" alt="avatar" />
<?php
	endif;
	if ($item->url) :
?>
			<a href="<?php echo $item->url;?>" target="_blank" rel="nofollow"><?php echo (!empty($item->$_name)) ? $item->$_name : $item->name; ?></a>
<?php else : ?>
			<?php echo (!empty($item->$_name)) ? $item->$_name : $item->name; ?>
<?php endif;?>
		<?php echo JText::sprintf('Comments_Makes_This_Comment', JRoute::_('#comment-'.$item->id)); ?>
		</p>
		<p class="date">
			<?php echo JHtml::_('date', $item->created_date, $params->get('date_format')); ?>
		</p>
	</div>
	<div class="comment">
<?php
	if ($params->get('enable_bbcode')) {
		echo modCommentsCommentHelper::renderBBCode($item->body);
	} else {
		echo $item->body;
	}
?>
	</div>
