<?php defined('_JEXEC') or die; ?>
		<tr>
			<td class="imgTotal">
				<a href="<?php echo JRoute::_('index.php?option=com_media&amp;view=mediaList&amp;tmpl=component&amp;folder=' . $this->_tmp_folder->path_relative); ?>" target="folderframe">
					<img src="components/com_media/images/folder_sm.png" width="16" height="16" border="0" alt="<?php echo $this->_tmp_folder->name; ?>" /></a>
			</td>
			<td class="description">
				<a href="<?php echo JRoute::_('index.php?option=com_media&amp;view=mediaList&amp;tmpl=component&amp;folder=' . $this->_tmp_folder->path_relative); ?>" target="folderframe"><?php echo $this->_tmp_folder->name; ?></a>
			</td>
			<td>&nbsp;

			</td>
			<td>&nbsp;

			</td>
			<td>
				<a class="delete-item" href="<?php echo JRoute::_('index.php?option=com_media&amp;task=folder.delete&amp;tmpl=component&amp;folder=' . $this->state->folder . '&amp;rm[]=' . $this->_tmp_folder->name); ?>" rel="<?php echo $this->_tmp_folder->name; ?>' :: <?php echo $this->_tmp_folder->files+$this->_tmp_folder->folders; ?>"><img src="components/com_media/images/remove.png" width="16" height="16" border="0" alt="<?php echo JText::_('Delete'); ?>" /></a>
				<input type="checkbox" name="rm[]" value="<?php echo $this->_tmp_folder->name; ?>" />
			</td>
		</tr>
