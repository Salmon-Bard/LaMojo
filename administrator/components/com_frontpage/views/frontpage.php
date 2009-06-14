<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * @package		Joomla.Administrator
 * @subpackage	Content
 */
class FrontpageView
{
	/**
	* Writes a list of the articles
	* @param array An array of content objects
	*/
	function showList(&$rows, $page, $option, $lists)
	{
		$limitstart = JRequest::getVar('limitstart', '0', '', 'int');

		$user 		= &JFactory::getUser();
		$db 		= &JFactory::getDbo();
		$nullDate 	= $db->getNullDate();
		$config		= &JFactory::getConfig();
		$now		= &JFactory::getDate();

		//Ordering allowed ?
		$ordering = (($lists['order'] == 'fpordering'));

		JHtml::_('behavior.tooltip');
		?>
		<form action="index.php?option=com_frontpage" method="post" name="adminForm">

			<table>
				<tr>
					<td width="100%" class="filter">
						<?php echo JText::_('Filter'); ?>:
						<input type="text" name="search" id="search" value="<?php echo $lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
						<button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
						<button onclick="document.getElementById('search').value=''; this.form.getElementById('catid').value='0'; this.form.getElementById('filter_authorid').value='0'; this.form.getElementById('filter_state').value=''; this.form.submit();"><?php echo JText::_('Reset'); ?></button>
					</td>
					<td nowrap="nowrap">
						<?php
						echo $lists['catid'];
						echo $lists['authorid'];
						echo $lists['state'];
						?>
					</td>
				</tr>
			</table>

			<table class="adminlist">
			<thead>
				<tr>
					<th width="5">
						<?php echo JText::_('Num'); ?>
					</th>
					<th width="20">
						<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" />
					</th>
					<th class="title">
						<?php echo JHtml::_('grid.sort',   'Title', 'c.title', @$lists['order_Dir'], @$lists['order']); ?>
					</th>
					<th width="10%" nowrap="nowrap">
						<?php echo JHtml::_('grid.sort',   'Published', 'c.state', @$lists['order_Dir'], @$lists['order']); ?>
					</th>
					<th width="80" nowrap="nowrap">
						<?php echo JHtml::_('grid.sort',   'Order', 'fpordering', @$lists['order_Dir'], @$lists['order']); ?>
		 			</th>
					<th width="1%">
						<?php if ($ordering) echo JHtml::_('grid.order',  $rows); ?>
					</th>
					<th width="8%" nowrap="nowrap">
						<?php echo JHtml::_('grid.sort',   'Access', 'groupname', @$lists['order_Dir'], @$lists['order']); ?>
					</th>
					<th width="2%" class="title" align="center" nowrap="nowrap">
						<?php echo JHtml::_('grid.sort',   'ID', 'c.id', @$lists['order_Dir'], @$lists['order']); ?>
					</th>
					<th width="10%" class="title">
						<?php echo JHtml::_('grid.sort',   'Category', 'cc.name', @$lists['order_Dir'], @$lists['order']); ?>
					</th>
					<th width="10%" class="title">
						<?php echo JHtml::_('grid.sort',   'Author', 'author', @$lists['order_Dir'], @$lists['order']); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
			<tr>
				<td colspan="13">
					<?php echo $page->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
			<tbody>
			<?php
			$k = 0;
			for ($i=0, $n=count($rows); $i < $n; $i++)
			{
				$row = &$rows[$i];

				$link = JRoute::_('index.php?option=com_content&task=edit&cid[]='. $row->id);

				$publish_up = &JFactory::getDate($row->publish_up);
				$publish_down = &JFactory::getDate($row->publish_down);
				$publish_up->setOffset($config->getValue('config.offset'));
				$publish_down->setOffset($config->getValue('config.offset'));
				if ($now->toUnix() <= $publish_up->toUnix() && $row->state == 1) {
					$img = 'publish_y.png';
					$alt = JText::_('Published');
				} else if (($now->toUnix() <= $publish_down->toUnix() || $row->publish_down == $nullDate) && $row->state == 1) {
					$img = 'publish_g.png';
					$alt = JText::_('Published');
				} else if ($now->toUnix() > $publish_down->toUnix() && $row->state == 1) {
					$img = 'publish_r.png';
					$alt = JText::_('Expired');
				} else if ($row->state == 0) {
					$img = 'publish_x.png';
					$alt = JText::_('Unpublished');
				} else if ($row->state == -1) {
					$img = 'disabled.png';
					$alt = JText::_('Archived');
				}
				$times = '';
				if (isset($row->publish_up)) {
					if ($row->publish_up == $nullDate) {
						$times .= JText::_('Start: Always');
					} else {
						$times .= JText::_('Start') .": ". $publish_up->toFormat();
					}
				}
				if (isset($row->publish_down)) {
					if ($row->publish_down == $nullDate) {
						$times .= "<br />". JText::_('Finish: No Expiry');
					} else {
						$times .= "<br />". JText::_('Finish') .": ". $publish_down->toFormat();
					}
				}

				$checked 	= JHtml::_('grid.checkedout',   $row, $i);

				if ($user->authorize('core.users.manage')) {
					if ($row->created_by_alias) {
						$author = $row->created_by_alias;
					} else {
						$linkA 	= JRoute::_('index.php?option=com_users&task=edit&cid[]='. $row->created_by);
						$author='<span class="editlinktip hasTip" title="'.JText::_('Edit User').'::'.$row->author.'">' .
								'<a href="'. $linkA .'">'. $row->author .'</a><span>';
					}
				} else {
					if ($row->created_by_alias) {
						$author = $row->created_by_alias;
					} else {
						$author = $row->author;
					}
				}

				// category handling
				if ($row->catid) {
					$row->cat_link 	= JRoute::_('index.php?option=com_categories&task=edit&cid[]='. $row->catid);
					$title_cat		= JText::_('Edit Category');
				}
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $page->getRowOffset($i); ?>
					</td>
					<td>
						<?php echo $checked; ?>
					</td>
					<td>
						<?php
						if (JTable::isCheckedOut($user->get ('id'), $row->checked_out)) {
							echo $row->title;
						} else {
							?>
							<span class="editlinktip hasTip" title="<?php echo JText::_('Edit Content');?>::<?php echo $row->name; ?>">
							<a href="<?php echo $link; ?>">
								<?php echo $row->title; ?></a></span>
							<?php
						}
						?>
					</td>
					<?php
					if ($times) {
						?>
						<td align="center">
							<span class="editlinktip hasTip" title="<?php echo JText::_('Publish Information');?>::<?php echo $times; ?>">
							<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->state ? 'unpublish' : 'publish' ?>')">
								<img src="images/<?php echo $img;?>" width="16" height="16" border="0" alt="<?php echo $alt;?>" /></a></span>
						</td>
						<?php
					}
					?>
					<td class="order" colspan="2">
						<span><?php echo $page->orderUpIcon($i, true, 'orderup', 'Move Up', $ordering); ?></span>
						<span><?php echo $page->orderDownIcon($i, $n, true, 'orderdown', 'Move Down', $ordering); ?></span>
						<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $row->fpordering;?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
					</td>
					<td align="center">
						<?php echo $row->groupname;?>
					</td>
					<td align="center">
						<?php echo $row->id;?>
					</td>
					<td>
						<?php if ($row->catid) : ?>
						<span class="editlinktip hasTip" title="<?php echo $title_cat; ?>::<?php echo $row->name; ?>">
							<a href="<?php echo $row->cat_link; ?>" title="<?php echo $title_cat; ?>">
								<?php echo $row->name; ?></a></span>
						<?php endif; ?>
					</td>
					<td>
						<?php echo $author; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			</tbody>
			</table>
			<?php JHtml::_('content.legend'); ?>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
		<?php echo JHtml::_('form.token'); ?>
		</form>
		<?php
	}
}
