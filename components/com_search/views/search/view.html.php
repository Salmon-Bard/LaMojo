<?php
/**
 * @version		$Id$
 * @package		Joomla
 * @subpackage	Weblinks
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
  */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the WebLinks component
 *
 * @static
 * @package		Joomla
 * @subpackage	Weblinks
 * @since 1.0
 */
class SearchViewSearch extends JView
{
	function display($tpl = null)
	{
		global $mainframe;

		require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'search.php';

		// Initialize some variables
		$pathway	=& $mainframe->getPathway();
		$uri		=& JFactory::getURI();

		$error	= '';
		$rows	= null;
		$total	= 0;

		// Get some data from the model
		$areas	  = &$this->get('areas');
		$state 		= &$this->get('state');
		$searchword = $state->get('keyword');

		$params = &$mainframe->getParams();

		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();

		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		if (is_object( $menu )) {
			$menu_params = new JParameter( $menu->params );
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	JText::_( 'Search' ));
			}
		} else {
			$params->set('page_title',	JText::_( 'Search' ));
		}

		$document	= &JFactory::getDocument();
		$document->setTitle( $params->get( 'page_title' ) );

		// Get the parameters of the active menu item
		$params	= &$mainframe->getParams();

		// build select lists
		$orders = array(
			'newest' => JText::_('Newest first'),
			'oldest' => JText::_('Oldest first'),
			'popular' => JText::_('Most popular'),
			'alpha' => JText::_('Alphabetical'),
			'category' => JText::_('Section/Category')
		);

		$lists = array();
		$lists['ordering'] = JHtml::_(
			'select.genericlist',
			$orders,
			'ordering',
			array(
				'list.attr' => 'class="inputbox"',
				'list.select' => $state->get('ordering'),
				'option.key' => null
			)
	   );

		$searchphrases = array();
		$searchphrases[] = JHtml::_('select.option',  'all', JText::_( 'All words' ) );
		$searchphrases[] = JHtml::_('select.option',  'any', JText::_( 'Any words' ) );
		$searchphrases[] = JHtml::_('select.option',  'exact', JText::_( 'Exact phrase' ) );
		$lists['searchphrase' ]= JHtml::_('select.radiolist',  $searchphrases, 'searchphrase', '', 'value', 'text', $state->get('match') );

		// log the search
		SearchHelper::logSearch( $searchword);

		//limit searchword

		if(SearchHelper::limitSearchWord($searchword)) {
			$error = JText::_( 'SEARCH_MESSAGE' );
		}

		//sanatise searchword
		if(SearchHelper::santiseSearchWord($searchword, $state->get('match'))) {
			$error = JText::_( 'IGNOREKEYWORD' );
		}

		if (!$searchword && count( JRequest::get('post') ) ) {
			//$error = JText::_( 'Enter a search keyword' );
		}

		// put the filtered results back into the model
		// for next release, the checks should be done in the model perhaps...
		$state->set('keyword', $searchword);

		if(!$error)
		{
			$results	= &$this->get('data' );
			$total		= &$this->get('total');
			$pagination	= &$this->get('pagination');

			require_once JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php';

			for ($i=0; $i < count($results); $i++)
			{
				$row = &$results[$i]->text;

				if ($state->get('match') == 'exact')
				{
					$searchwords = array($searchword);
					$needle = $searchword;
				}
				else
				{
					$searchwords = preg_split("/\s+/", $searchword);
					$needle = $searchwords[0];
				}

				$row = SearchHelper::prepareSearchContent( $row, 200, $needle );
				$searchwords = array_unique( $searchwords );
				$searchRegex = '#(';
				$x = 0;
				foreach ($searchwords as $k => $hlword)
				{
					$searchRegex .= ($x == 0 ? '' : '|');
					$searchRegex .= preg_quote($hlword, '#');
					$x++;
				}
				$searchRegex .= ')#iu';

				$row = preg_replace($searchRegex, '<span class="highlight">\0</span>', $row );

				$result =& $results[$i];
				if ($result->created) {
					$created = JHtml::Date ( $result->created );
				}
				else {
					$created = '';
				}

				$result->created	= $created;
				$result->count		= $i + 1;
			}
		}

		$this->result	= JText::sprintf( 'TOTALRESULTSFOUND', $total );

		$this->assignRef('pagination',  $pagination);
		$this->assignRef('results',		$results);
		$this->assignRef('lists',		$lists);
		$this->assignRef('params',		$params);

		$this->assign('ordering',		$state->get('ordering'));
		$this->assign('searchword',		$searchword);
		$this->assign('searchphrase',	$state->get('match'));
		$this->assign('searchareas',	$areas);

		$this->assign('total',			$total);
		$this->assign('error',			$error);
		$this->assign('action', 		$uri->toString());

		parent::display($tpl);
	}
}
