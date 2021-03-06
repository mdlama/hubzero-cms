<?php
/**
 * @package    hubzero-cms
 * @copyright  Copyright (c) 2005-2020 The Regents of the University of California.
 * @license    http://opensource.org/licenses/MIT MIT
 */
namespace Components\Content\Site\Helpers;

require_once \Component::path('com_categories') . '/helpers/categories.php';

/**
 * Content Component Category Tree
 */
class Category extends \Components\Categories\Helpers\Categories
{
	/**
	 * Class constructor
	 *
	 * @param   array  $options  Array of options
	 * @return  void
	 */
	public function __construct($options = array())
	{
		$options['table'] = '#__content';
		$options['extension'] = 'com_content';

		parent::__construct($options);
	}
}
