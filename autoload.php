<?php
spl_autoload_register(function($class) {
	$path = str_replace("\\", '/', str_replace("ILIAS\\Plugin\\TodoLists\\", '', $class)) . '.php';

	if(file_exists(\ilTodoListsPlugin::getInstance()->getDirectory() . '/classes/' . $path))
	{
		\ilTodoListsPlugin::getInstance()->includeClass($path);
	}
}, true, true);