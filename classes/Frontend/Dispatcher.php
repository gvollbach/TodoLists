<?php
/* Copyright (c) 1998-2017 ILIAS open source, Extended GPL, see docs/LICENSE */

namespace ILIAS\Plugin\TodoLists\Frontend;

use ILIAS\DI\Container;

/**
 * Class Dispatcher
 * @package ILIAS\Plugin\TodoLists\Frontend
 * @author Michael Jansen <mjansen@databay.de>
 */
class Dispatcher
{
	/**
	 * @var self
	 */
	private static $instance = null;

	/**
	 * @var \ilTodoListsUIHookGUI
	 */
	protected $coreController;

	/**
	 * @var string
	 */
	protected $defaultController = '';

	/**
	 * @var Container
	 */
	protected $dic;

	/**
	 *
	 */
	private function __clone()
	{
	}

	/**
	 * Dispatcher constructor.
	 * @param \ilTodoListsUIHookGUI $baseController
	 * @param string                              $defaultController
	 */
	private function __construct(\ilTodoListsUIHookGUI $baseController, $defaultController = '')
	{
		$this->coreController    = $baseController;
		$this->defaultController = $defaultController;
	}

	/**
	 * @param Container $dic
	 */
	public function setDic(Container $dic)
	{
		$this->dic = $dic;
	}

	/**
	 * @param  \ilTodoListsUIHookGUI $base_controller
	 * @return self
	 */
	public static function getInstance(\ilTodoListsUIHookGUI $base_controller)
	{
		if(self::$instance === null)
		{
			self::$instance = new self($base_controller);
		}

		return self::$instance;
	}

	/**
	 * @param string $cmd
	 * @return string
	 */
	public function dispatch($cmd)
	{
		$controller = $this->getController($cmd);
		$command    = $this->getCommand($cmd);
		$controller = $this->instantiateController($controller);

		return $controller->$command();
	}

	/**
	 * @param string $cmd
	 * @return string
	 */
	protected function getController($cmd)
	{
		$parts = \explode('.', $cmd);

		if(\count($parts) == 2)
		{
			return $parts[0];
		}

		return $this->defaultController ? $this->defaultController : 'Error';
	}

	/**
	 * @param string $cmd
	 * @return string
	 */
	protected function getCommand($cmd)
	{
		$parts = \explode('.', $cmd);

		if(\count($parts) == 2)
		{
			$cmd = $parts[1];

			return $cmd . 'Cmd';
		}

		return '';
	}

	/**
	 * @param string $controller
	 * @return mixed
	 */
	protected function instantiateController($controller)
	{
		$class = "ILIAS\\Plugin\\TodoLists\\Frontend\\Controller\\$controller";

		return new $class($this->getCoreController(), $this->dic);
	}

	/**
	 * @return string
	 */
	protected function getControllerPath()
	{
		$path = $this->getCoreController()->getPluginObject()->getDirectory() .
			DIRECTORY_SEPARATOR .
			'classes' .
			DIRECTORY_SEPARATOR .
			'Frontend' .
			DIRECTORY_SEPARATOR .
			'Controller' .
			DIRECTORY_SEPARATOR;

		return $path;
	}

	/**
	 * @param string $controller
	 */
	protected function requireController($controller)
	{
		require_once $this->getControllerPath() . $controller . '.php';
	}

	/**
	 * @return \ilTodoListsUIHookGUI
	 */
	public function getCoreController()
	{
		return $this->coreController;
	}

	/**
	 * @param \ilTodoListsUIHookGUI $coreController
	 */
	public function setCoreController(\ilTodoListsUIHookGUI $coreController)
	{
		$this->coreController = $coreController;
	}
}