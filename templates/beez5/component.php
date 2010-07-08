<?php
/**
 * @version		$Id: component.php 16465 2010-04-26 01:46:24Z eddieajau $
 * @package		Joomla.Site
 * @subpackage	tpl_beez2
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

$color = $this->params->get('templatecolor');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="head" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/beez5/css/template.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/beez5/css/position.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/beez5/css/layout.css" type="text/css" media="screen,projection" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/beez5/css/print.css" type="text/css" media="Print" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/beez5/css/beez5.css" type="text/css" />


<?php if($this->direction == 'rtl') : ?>
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/beez/css/template_rtl.css" type="text/css" />
<?php endif; ?>
</head>
<body class="contentpane">
	<div id="all">
	<div id="main">
		<jdoc:include type="message" />
		<jdoc:include type="component" />
		</div>
	</div>
</body>
</html>
