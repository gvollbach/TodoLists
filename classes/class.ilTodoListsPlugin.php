<?php
/* Copyright (c) 1998-2017 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once 'Services/UIComponent/classes/class.ilUserInterfaceHookPlugin.php';

/**
 * Class ilTodoListsPlugin
 */
class ilTodoListsPlugin extends \ilUserInterfaceHookPlugin
{
	/**
	 * @var string
	 */
	const CTYPE   = 'Services';

	/**
	 * @var string
	 */
	const CNAME   = 'UIComponent';

	/**
	 * @var string
	 */
	const SLOT_ID = 'uihk';

	/**
	 * @var string
	 */
	const PNAME   = 'TodoLists';

	/**
	 * @var self|\ilPlugin|\ilUserInterfaceHookPlugin
	 */
	private static $instance;

	/**
	 * @return self|\ilPlugin|\ilUserInterfaceHookPlugin
	 */
	public static function getInstance()
	{
		if(null !== self::$instance)
		{
			return self::$instance;
		}

		return (self::$instance = \ilPluginAdmin::getPluginObject(
			self::CTYPE,
			self::CNAME,
			self::SLOT_ID,
			self::PNAME
		));
	}

	/**
	 * Register the plugin autoloader
	 */
	public function registerAutoloader()
	{
		require_once realpath(dirname(__FILE__) . '/../autoload.php');
	}

	/**
	 * @inheritdoc
	 */
	protected function init()
	{
		parent::init();
		$this->registerAutoloader();
	}

	/**
	 * @return string
	 */
	final public function getPluginName()
	{
		return self::PNAME;
	}
} 