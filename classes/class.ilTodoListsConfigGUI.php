<?php
/* Copyright (c) 1998-2017 ILIAS open source, Extended GPL, see docs/LICENSE */

use ILIAS\Plugin\TodoLists\UI\Table;

require_once \dirname(__FILE__) . '/class.ilTodoListsPlugin.php';
\ilTodoListsPlugin::getInstance()->registerAutoloader();

require_once 'Services/Component/classes/class.ilPluginConfigGUI.php';

/**
 * Class ilTodoListsConfigGUI
 */
class ilTodoListsConfigGUI extends \ilPluginConfigGUI
{
	/**
	 * @var \ilTodoListsPlugin
	 */
	public $pluginObj = null;

	/**
	 * @var \ILIAS\DI\Container
	 */
	protected $dic;

	/**
	 * @param string $cmd
	 */
	public function performCommand($cmd)
	{
		global $DIC;

		$this->dic       = $DIC;
		$this->pluginObj = \ilTodoListsPlugin::getInstance();

		switch($cmd)
		{
			default:
				$this->$cmd();
				break;
		}
	}

	/**
	 * @param $command string
	 * @return Table\Example
	 */
	protected function getTable($command)
	{
		$table = new Table\Example($this, $command);
		$table->setProvider(new Table\ExampleProvider($this->dic->database()));

		return $table;
	}
	

	/**
	 *
	 */
	protected function configure()
	{
		$table = $this->getTable(__FUNCTION__);
		$table->populate();

		$this->dic->ui()->mainTemplate()->setContent(
			$table->getHTML()
		);
	}
} 