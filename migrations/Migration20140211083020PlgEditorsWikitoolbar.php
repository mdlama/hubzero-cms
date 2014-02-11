<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * Migration script for ...
 **/
class Migration20140211083020PlgEditorsWikitoolbar extends Hubzero_Migration
{
	/**
	 * Up
	 **/
	protected static function up($db)
	{
		self::addPluginEntry('editors','wikitoolbar');
	}

	/**
	 * Down
	 **/
	protected static function down($db)
	{
		self::deletePluginEntry('editors','wikitoolbar');
	}
}