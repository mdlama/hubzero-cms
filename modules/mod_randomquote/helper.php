<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2011 Purdue University. All rights reserved.
 * All rights reserved.
 *
 * This file is part of: The HUBzero(R) Platform for Scientific Collaboration
 *
 * The HUBzero(R) Platform for Scientific Collaboration (HUBzero) is free
 * software: you can redistribute it and/or modify it under the terms of
 * the GNU Lesser General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * HUBzero is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @author    Shawn Rice <zooley@purdue.edu>
 * @copyright Copyright 2005-2011 Purdue University. All rights reserved.
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * Module class for displaying a random quote
 */
class modRandomQuote extends Hubzero_Module
{
	/**
	 * Get module contents
	 * 
	 * @return     void
	 */
	public function run()
	{
		require_once(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_feedback' . DS . 'tables' . DS . 'selectedquotes.php');
		ximport('Hubzero_View_Helper_Html');

		$database =& JFactory::getDBO();

		//Get the admin configured settings
		$filters = array();
		$filters['limit'] = 1;
		$this->charlimit = $this->params->get('charlimit', 150);
		$this->showauthor = $this->params->get('show_author', 1);
		$this->showall = $this->params->get('show_all_link', 1);
		$quotesrc = $this->params->get('quotesrc', 'miniquote');

		$pool = trim($this->params->get('quotepool'));
		$filters['notable_quotes'] = ($pool == 'notable_quotes') ?  1 : 0;
		$filters['flash_rotation'] = ($pool == 'flash_rotation') ?  1 : 0;
		$filters['miniquote'] = ($quotesrc == 'miniquote') ?  1 : 0;
		$filters['sortby'] = 'RAND()';

		$this->filters = $filters;

		// Get quotes
		$sq = new SelectedQuotes($database);
		$quotes = $sq->getResults($filters);
		$quote = ($quotes) ? $quotes[0] : '';

		if ($quote) 
		{
			$this->quote_to_show = ($quotesrc == 'miniquote') ? stripslashes($quote->miniquote) : stripslashes($quote->short_quote);
		} 
		else 
		{
			$this->quote_to_show = '';
		}
		$this->quote = $quote;

		require(JModuleHelper::getLayoutPath($this->module->module));
	}

	/**
	 * Display module content
	 * 
	 * @return     void
	 */
	public function display()
	{
		// Push some CSS to the template
		ximport('Hubzero_Document');
		Hubzero_Document::addModuleStylesheet($this->module->module);

		$debug = (defined('JDEBUG') && JDEBUG ? true : false);

		if (!$debug && intval($this->params->get('cache', 0)))
		{
			$cache =& JFactory::getCache('callback');
			$cache->setCaching(1);
			$cache->setLifeTime(intval($this->params->get('cache_time', 15)));
			$cache->call(array($this, 'run'));
			echo '<!-- cached ' . JFactory::getDate() . ' -->';
			return;
		}

		$this->run();
	}
}
