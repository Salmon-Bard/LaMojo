<?php
/**
* @version $Id$
* @package Joomla
* @subpackage Config
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* @package Joomla
* @subpackage Config
*/
class HTML_config {

	function showconfig( &$row, &$lists, $option) {
		$tabs = new mosTabs(1);
		?>
		<form action="index2.php" method="post" name="adminForm">

		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>

		<table cellpadding="1" cellspacing="1" border="0" width="100%">
		<tr>
			<td width="270">
				<span class="componentheading"><?php echo JText::_( 'configuration.php is' ); ?> :
				<?php echo is_writable( '../configuration.php' ) ? '<b><font color="green"> '. JText::_( 'Writeable' ) .'</font></b>' : '<b><font color="red"> '. JText::_( 'Unwriteable' ) .'</font></b>' ?>
				</span>
			</td>
			<?php
			if (JPath::canCHMOD('../configuration.php')) {
				if (is_writable('../configuration.php')) {
					?>
					<td>
						<input type="checkbox" id="disable_write" name="disable_write" value="1"/>
						<label for="disable_write"><?php echo JText::_( 'Make unwriteable after saving' ); ?></label>
					</td>
					<?php
				} else {
					?>
					<td>
						<input type="checkbox" id="enable_write" name="enable_write" value="1"/>
						<label for="enable_write"><?php echo JText::_( 'Override write protection while saving' ); ?></label>
					</td>
				<?php
				} // if
			} // if
			?>
		</tr>
		</table>

			<?php
		$title = JText::_( 'Site' );
		$tabs->startPane("configPane");
		$tabs->startTab( $title, "site-page" );
			?>

			<table class="adminform">
			<tr>
				<td width="185"><?php echo JText::_( 'Site Offline' ); ?>:</td>
				<td><?php echo $lists['offline']; ?></td>
			</tr>
			<tr>
				<td valign="top"><?php echo JText::_( 'Offline Message' ); ?>:</td>
				<td><textarea class="text_area" cols="60" rows="2" style="width:500px; height:40px" name="config_offline_message"><?php echo htmlspecialchars( stripslashes( $row->config_offline_message ), ENT_QUOTES); ?></textarea><?php
					$tip = JText::_( 'TIPIFYOURSITEISOFFLINE' );
					echo mosToolTip( $tip );
				?></td>
			</tr>
			<tr>
				<td valign="top"><?php echo JText::_( 'System Error Message' ); ?>:</td>
				<td><textarea class="text_area" cols="60" rows="2" style="width:500px; height:40px" name="config_error_message"><?php echo htmlspecialchars( stripslashes( $row->config_error_message ), ENT_QUOTES); ?></textarea><?php
					$tip = JText::_( 'TIPCOULDNOTCONNECTDB' );
					echo mosToolTip( $tip );
				?></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Site Name' ); ?>:</td>
				<td><input class="text_area" type="text" name="config_sitename" size="50" value="<?php echo $row->config_sitename; ?>"/></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Default WYSIWYG Editor' ); ?>:</td>
				<td><?php echo $lists['editor']; ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'List Length' ); ?>:</td>
				<td><?php echo $lists['list_limit']; ?><?php
					$tip = JText::_( 'TIPSETSDEFAULTLENGTHLISTS' );
					echo mosToolTip( $tip );
				?>
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Help Server' ); ?>:</td>
				<td><?php echo $lists['helpsites']; ?></td>
			</tr>
			</table>
			
					<?php
		$title = JText::_( 'Debug' );
		$tabs->endTab();
		$tabs->startTab( $title, "Debug-page" );
			?>
			<table class="adminform">
			<tr>
				<td><?php echo JText::_( 'Enable Debuging' ); ?>:</td>
				<td><?php echo $lists['debug']; ?><?php
					$tip = JText::_( 'TIPDEBUGGINGINFO' );
					echo mosToolTip( $tip );
				?></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Debug Database' ); ?>:</td>
				<td><?php echo $lists['debug_db']; ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Enable Logging' ); ?>:</td>
				<td><?php echo $lists['log']; ?><?php
					$tip = JText::_( 'TIPLOGGINGINFO' );
					echo mosToolTip( $tip );
				?></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Log Database' ); ?>:</td>
				<td><?php echo $lists['log_db']; ?></td>
			</tr>
			</table>
		
				<?php
		$title = JText::_( 'Users' );
		$tabs->endTab();
		$tabs->startTab( $title, "Users-page" );
			?>
			<table class="adminform">
			<tr>
				<td><?php echo JText::_( 'Allow User Registration' ); ?>:</td>
				<td><?php echo $lists['allowUserRegistration']; ?><?php
					$tip = JText::_( 'If yes, allows users to self-register' );
					echo mosToolTip( $tip );
				?></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Use New Account Activation' ); ?>:</td>
				<td><?php echo $lists['useractivation']; ?>
				<?php
					$tip = JText::_( 'TIPIFYESUSERMAILEDLINK' );
					echo mosToolTip( $tip );
				?></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Require Unique Email' ); ?>:</td>
				<td><?php echo $lists['uniquemail']; ?><?php
					$tip = JText::_( 'TIPIFYESUSERSCANNOTSHAREEMAIL' );
					echo mosToolTip( $tip );
				?></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Show UnAuthorized Links' ); ?>:</td>
				<td><?php echo $lists['shownoauth']; ?><?php
					$tip = JText::_( 'TIPLINKS' );
					echo mosToolTip( $tip );
				?></td>
			</tr>
			</table>
			
				<?php
		$title = JText::_( 'Metadata' );
		$tabs->endTab();
		$tabs->startTab( $title, "metadata-page" );
			?>

			<table class="adminform">
			<tr>
				<td width="185" valign="top"><?php echo JText::_( 'Global Site Meta Description' ); ?>:</td>
				<td><textarea class="text_area" cols="50" rows="3" style="width:500px; height:50px" name="config_MetaDesc"><?php echo htmlspecialchars($row->config_MetaDesc, ENT_QUOTES); ?></textarea></td>
			</tr>
			<tr>
				<td valign="top"><?php echo JText::_( 'Global Site Meta Keywords' ); ?>:</td>
				<td><textarea class="text_area" cols="50" rows="3" style="width:500px; height:50px" name="config_MetaKeys"><?php echo htmlspecialchars($row->config_MetaKeys, ENT_QUOTES); ?></textarea></td>
			</tr>
			<tr>
				<td valign="top"><?php echo JText::_( 'Show Title Meta Tag' ); ?>:</td>
				<td>
				<?php echo $lists['MetaTitle']; ?>
				&nbsp;&nbsp;&nbsp;
				<?php
                    $tip = JText::_( 'TIPSHOWTITLEMETATAGITEMS' );
                    echo mosToolTip( $tip ); ?>
				</td>
			  	</tr>
			<tr>
				<td valign="top"><?php echo JText::_( 'Show Author Meta Tag' ); ?>:</td>
				<td>
				<?php echo $lists['MetaAuthor']; ?>
				&nbsp;&nbsp;&nbsp;
				<?php
                    $tip = JText::_( 'TIPSHOWAUTHORMETATAGITEMS' );
                    echo mosToolTip( $tip ); ?>
				</td>
			</tr>
			</table>
			
				<?php
		$title = JText::_( 'Statistics' );
		$tabs->endTab();
		$tabs->startTab( $title, "stats-page" );
			?>

			<table class="adminform">
			<tr>
				<td width="185"><?php echo JText::_( 'Statistics' ); ?>:</td>
				<td width="100"><?php echo $lists['enable_stats']; ?></td>
				<td><?php
                    $tip = JText::_( 'TIPENABLEDISABLESTATS' );
                    echo mosToolTip( $tip ); ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Log Content Hits by Date' ); ?>:</td>
				<td><?php echo $lists['log_items']; ?></td>
				<td><span class="error"><?php
                    $warn = JText::_( 'TIPLARGEAMOUNTSOFDATA' );
                    echo JWarning( $warn ); ?></span></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Log Search Strings' ); ?>:</td>
				<td><?php echo $lists['log_searches']; ?></td>
				<td>&nbsp;</td>
			</tr>
			</table>			
			
			<?php
		$title = JText::_( 'SEO' );
		$tabs->endTab();
		$tabs->startTab( $title, "seo-page" );
			?>

			<table class="adminform">
			<tr>
				<td width="200"><strong><?php echo JText::_( 'Search Engine Optimization Settings' ); ?></strong></td>
				<td width="100">&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Search Engine Friendly URLs' ); ?>:</td>
				<td><?php echo $lists['sef']; ?>&nbsp;</td>
				<td><span class="error"><?php
                $tip = JText::_( 'WARNAPACHEONLY' );
                echo JWarning( $tip ); ?></span></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Dynamic Page Titles' ); ?>:</td>
				<td><?php echo $lists['pagetitles']; ?></td>
				<td><?php echo
                    $tip = JText::_( 'TIPDYNAMICALLYCHANGESPAGETITLE' );
                    mosToolTip( $tip ); ?></td>
			</tr>
			</table>

			<?php
		$title = JText::_( 'Content' );
		$tabs->endTab();
		$tabs->startTab( $title, "content-page" );
			?>

			<table class="adminform">
			<tr>
				<td colspan="3"><?php echo JText::_( 'DESCCONTROLOUTPUTELEMENTS' ); ?><br /><br /></td>
			</tr>
			<tr>
				<td width="200"><?php echo JText::_( 'Linked Titles' ); ?>:</td>
				<td width="100"><?php echo $lists['link_titles']; ?></td>
				<td><?php
					$tip = JText::_( 'TIPIFYESTITLECONTENTITEMS' );
					echo mosToolTip( $tip );
				?></td>
			</tr>
			<tr>
				<td width="200"><?php echo JText::_( 'Read More Link' ); ?>:</td>
				<td width="100"><?php echo $lists['readmore']; ?></td>
				<td><?php
					$tip = JText::_( 'TIPIFSETTOSHOWREADMORELINK' );
					echo mosToolTip( $tip );
				?></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Item Rating/Voting' ); ?>:</td>
				<td><?php echo $lists['vote']; ?></td>
				<td><?php
					$tip = JText::_( 'TIPIFSETTOSHOWVOTING' );
					echo mosToolTip( $tip );
				?></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Author Names' ); ?>:</td>
				<td><?php echo $lists['hideAuthor']; ?></td>
				<td><?php
					$tip = JText::_( 'TIPIFSETTOSHOWAUTHOR' );
					echo mosToolTip( $tip );
				?></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Created Date and Time' ); ?>:</td>
				<td><?php echo $lists['hideCreateDate']; ?></td>
				<td><?php
					$tip = JText::_( 'TIPIFSETTOSHOWDATETIMECREATED' );
					echo mosToolTip( $tip );
				?></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Modified Date and Time' ); ?>:</td>
				<td><?php echo $lists['hideModifyDate']; ?></td>
				<td><?php
					$tip = JText::_( 'TIPIFSETTOSHOWDATETIMEMODIFIED' );
					echo mosToolTip( $tip );
				?></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Hits' ); ?>:</td>
				<td><?php echo $lists['hits']; ?></td>
				<td><?php
					$tip = JText::_( 'TIPIFSETTOSHOWHITS' );
					echo mosToolTip( $tip );
				?></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'PDF Icon' ); ?>:</td>
				<td><?php echo $lists['hidePdf']; ?></td>
				<?php
				if (!is_writable( JPATH_SITE . '/media/' )) {
                    $tip = JText::_( 'TIPOPTIONMEDIA' );
					echo "<td align=\"left\">";
					echo mosToolTip( $tip );
					echo "</td>";
				} else {
					?>
					<td>&nbsp;</td>
					<?php
				}
				?>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Print Icon' ); ?>:</td>
				<td><?php echo $lists['hidePrint']; ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Email Icon' ); ?>:</td>
				<td><?php echo $lists['hideEmail']; ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Icons' ); ?>:</td>
				<td><?php echo $lists['icons']; ?></td>
				<td><?php
                    $tip = JText::_( 'TIPPRINTPDFEMAIL' );
                    echo mosToolTip( $tip ); ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Table of Contents on multi-page items' ); ?>:</td>
				<td><?php echo $lists['multipage_toc']; ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Back Button' ); ?>:</td>
				<td><?php echo $lists['back_button']; ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Content Item Navigation' ); ?>:</td>
				<td><?php echo $lists['item_navigation']; ?></td>
				<td>&nbsp;</td>
			</tr>
			</table>

			<?php
		$title = JText::_( 'Server' );
		$tabs->endTab();
		$tabs->startTab( $title, "server-page" );
			?>

			<table class="adminform">
			<tr>
				<td width="185"><?php echo JText::_( 'Absolute Path' ); ?>:</td>
				<td width="450"><strong><?php echo $row->config_absolute_path; ?></strong></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Live Site' ); ?>:</td>
				<td><strong><?php echo $row->config_live_site; ?></strong></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Secure Site' ); ?>:</td>
				<td>
				<input class="text_area" type="text" name="config_secure_site" size="50" value="<?php echo $row->config_secure_site; ?>"/>
				<?php
                    $tip = JText::_( 'TIPSECURESITE' );
                    echo mosToolTip( $tip ); ?>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Secret Word' ); ?>:</td>
				<td><strong><?php echo $row->config_secret; ?></strong></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'GZIP Page Compression' ); ?>:</td>
				<td>
				<?php echo $lists['gzip']; ?>
				<?php
                    $tip = JText::_( 'Compress buffered output if supported' );
                    echo mosToolTip( $tip ); ?>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Login Session Lifetime' ); ?>:</td>
				<td>
				<input class="text_area" type="text" name="config_lifetime" size="10" value="<?php echo $row->config_lifetime; ?>"/>
				&nbsp;<?php echo JText::_('seconds'); ?>&nbsp;
				<?php
                    $tip = JText::_( 'TIPAUTOLOGOUTTIMEOF' );
                    echo mosToolTip( $tip ); ?>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Error Reporting' ); ?>:</td>
				<td><?php echo $lists['error_reporting']; ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Enable XML-PRC' ); ?>:</td>
				<td><?php echo $lists['xmlrpc_server']; ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'FTP Root' ); ?>:</td>
				<td><input class="text_area" type="text" name="config_ftp_root" size="50" value="<?php echo $row->config_ftp_root; ?>"/></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'FTP Username' ); ?>:</td>
				<td><input class="text_area" type="text" name="config_ftp_user" size="25" value="<?php echo $row->config_ftp_user; ?>"/></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'FTP Password' ); ?>:</td>
				<td><input class="text_area" type="password" name="config_ftp_pass" size="25" value="<?php echo $row->config_ftp_pass; ?>"/></td>
			</tr>
			</table>
			
				<?php
		$title = JText::_( 'Database' );
		$tabs->endTab();
		$tabs->startTab( $title, "db-page" );
			?>

			<table class="adminform">
			<tr>
				<td width="185"><?php echo JText::_( 'Database type' ); ?>:</td>
				<td><input class="text_area" type="text" name="config_dbtype" size="25" value="<?php echo $row->config_dbtype; ?>"/></td>
			</tr>
			<tr>
				<td width="185"><?php echo JText::_( 'Hostname' ); ?>:</td>
				<td><input class="text_area" type="text" name="config_host" size="25" value="<?php echo $row->config_host; ?>"/></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Username' ); ?>:</td>
				<td><input class="text_area" type="text" name="config_user" size="25" value="<?php echo $row->config_user; ?>"/></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Password' ); ?>:</td>
				<td><input class="text_area" type="password" name="config_password" size="25" value="<?php echo $row->config_password; ?>"/></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Database' ); ?>:</td>
				<td><input class="text_area" type="text" name="config_db" size="25" value="<?php echo $row->config_db; ?>"/></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Database Prefix' ); ?>:</td>
				<td>
				<input class="text_area" type="text" name="config_dbprefix" size="10" value="<?php echo $row->config_dbprefix; ?>"/>
				&nbsp;<?php
                $warn = JText::_( 'WARNDONOTCHANGEDATABASETABLESPREFIX' );
                echo JWarning( $warn ); ?>
				</td>
			</tr>
			</table>

			<?php
		$title = JText::_( 'Mail' );
		$tabs->endTab();
		$tabs->startTab( $title, "mail-page" );
			?>

			<table class="adminform">
			<tr>
				<td width="185"><?php echo JText::_( 'Mailer' ); ?>:</td>
				<td><?php echo $lists['mailer']; ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Mail From' ); ?>:</td>
				<td><input class="text_area" type="text" name="config_mailfrom" size="50" value="<?php echo $row->config_mailfrom; ?>"/></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'From Name' ); ?>:</td>
				<td><input class="text_area" type="text" name="config_fromname" size="50" value="<?php echo $row->config_fromname; ?>"/></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Sendmail Path' ); ?>:</td>
				<td><input class="text_area" type="text" name="config_sendmail" size="50" value="<?php echo $row->config_sendmail; ?>"/></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'SMTP Auth' ); ?>:</td>
				<td><?php echo $lists['smtpauth']; ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'SMTP User' ); ?>:</td>
				<td><input class="text_area" type="text" name="config_smtpuser" size="50" value="<?php echo $row->config_smtpuser; ?>"/></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'SMTP Pass' ); ?>:</td>
				<td><input class="text_area" type="text" name="config_smtppass" size="50" value="<?php echo $row->config_smtppass; ?>"/></td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'SMTP Host' ); ?>:</td>
				<td><input class="text_area" type="text" name="config_smtphost" size="50" value="<?php echo $row->config_smtphost; ?>"/></td>
			</tr>
			</table>
			
			<?php
		$title = JText::_( 'Locale' );
		$tabs->endTab();
		$tabs->startTab( $title, "Locale-page" );
			?>

			<table class="adminform">
			<tr>
				<td width="185"><?php echo JText::_( 'Time Offset' ); ?>:</td>
				<td>
				<?php echo $lists['offset']; ?>
				<?php
				$tip = JText::_( 'Current date/time configured to display' ) .': '. mosCurrentDate( JText::_( '_DATE_FORMAT_LC2' ) );
				echo mosToolTip( $tip );
				?>
				</td>
			</tr>
			<tr>
				<td width="185"><?php echo JText::_( 'Server Offset' ); ?>:</td>
				<td>
				<input class="text_area" type="text" name="config_offset" size="15" value="<?php echo $row->config_offset; ?>" disabled="true"/>
				</td>
			</tr>
			</table>

			<?php
		$title = JText::_( 'Cache' );
		$tabs->endTab();
		$tabs->startTab( $title, "cache-page" );
			?>

			<table class="adminform" border="0">
			<?php
			if (is_writeable($row->config_cachepath)) {
				?>
				<tr>
					<td width="185"><?php echo JText::_( 'Caching' ); ?>:</td>
					<td width="500"><?php echo $lists['caching']; ?></td>
					<td>&nbsp;</td>
				</tr>
				<?php
			}
			?>
			<tr>
				<td><?php echo JText::_( 'Cache Folder' ); ?>:</td>
				<td>
				<input class="text_area" type="text" name="config_cachepath" size="50" value="<?php echo $row->config_cachepath; ?>"/>
				<?php
				if (is_writeable($row->config_cachepath)) {
                    $tip = JText::_( 'TIPDIRWRITEABLE' );
					echo mosToolTip( $tip );
				} else {
                    $warn = JText::_( 'TIPCACHEDIRISUNWRITEABLE' );
					echo JWarning( $warn );
				}
				?>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?php echo JText::_( 'Cache Time' ); ?>:</td>
				<td><input class="text_area" type="text" name="config_cachetime" size="5" value="<?php echo $row->config_cachetime; ?>"/> <?php echo JText::_( 'seconds' ); ?></td>
				<td>&nbsp;</td>
			</tr>
			</table>

		<?php
		$tabs->endTab();
		$tabs->endPane();
		?>

		<input type="hidden" name="option" value="<?php echo $option; ?>"/>
		<input type="hidden" name="config_admin_path" value="<?php echo $row->config_admin_path; ?>"/>
		<input type="hidden" name="config_absolute_path" value="<?php echo $row->config_absolute_path; ?>"/>
		<input type="hidden" name="config_live_site" value="<?php echo $row->config_live_site; ?>"/>
		<input type="hidden" name="config_secret" value="<?php echo $row->config_secret; ?>"/>
		<input type="hidden" name="config_multilingual_support" value="<?php echo $row->config_multilingual_support; ?>"/>
	  	<input type="hidden" name="config_lang" value="<?php echo $row->config_lang; ?>"/>
	  	<input type="hidden" name="config_lang_administrator" value="<?php echo $row->config_lang_administrator; ?>"/>
	  	<input type="hidden" name="task" value=""/>
		</form>

		<?php
		echo mosHTML::Script('/includes/js/overlib_mini.js');
	}
}
?>
