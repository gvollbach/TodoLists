<?php
/* Copyright (c) 1998-2017 ILIAS open source, Extended GPL, see docs/LICENSE */

use ILIAS\Plugin\TodoLists\Frontend;

require_once 'Services/UIComponent/classes/class.ilUIHookPluginGUI.php';

/**
 * Class ilTodoListsUIHookGUI
 * @ilCtrl_isCalledBy ilTodoListsUIHookGUI: ilUIPluginRouterGUI
 */
class ilTodoListsUIHookGUI extends \ilUIHookPluginGUI
{
	/**
	 * @var \ILIAS\DI\Container
	 */
	protected $dic;

	/**
	 * ilTodoListsUIHookGUI constructor.
	 */
	public function __construct()
	{
		global $DIC;

		$this->dic = $DIC;
	}

	/**
	 *
	 */
	public function executeCommand()
	{
		$this->setPluginObject(ilTodoListsPlugin::getInstance());

		$this->dic->ui()->mainTemplate()->getStandardTemplate();

		$next_class = $this->dic->ctrl()->getNextClass();
		switch(strtolower($next_class))
		{
			default:
				$dispatcher = Frontend\Dispatcher::getInstance($this);
				$dispatcher->setDic($this->dic);

				$response = $dispatcher->dispatch($this->dic->ctrl()->getCmd());
				break;
		}

		$this->dic->ui()->mainTemplate()->setContent($response);
		$this->dic->ui()->mainTemplate()->show();
	}

	/**
	 * @inheritdoc
	 */
	public function getHTML($a_comp, $a_part, $a_par = array())
	{

	}

	/**
	 * @inheritdoc
	 */
	public function modifyGUI($a_comp, $a_part, $a_par = array())
	{
		global $DIC;
		$isAdminContext = !isset($_GET['baseClass']) || strtolower($_GET['baseClass']) === 'iladministrationgui';

		if (!$isAdminContext && !isset($_GET['pluginCmd']) && 'tabs' == $a_part && isset($_GET['ref_id'])) {
			$ilCtrl = $DIC->ctrl();
			$ilAccess = $DIC->access();
			$ilUser = $DIC->user();

			$this->getPluginObject()->loadLanguageModule();

			$ref_id = (int)$_GET['ref_id'];
			$obj = ilObjectFactory::getInstanceByRefId($ref_id, false);
			if ($obj instanceof ilObjCourse &&
				$ilAccess->checkAccess('read', '', $obj->getRefId()))
			{
				$ilCtrl->setParameterByClass(__CLASS__, 'ref_id', $obj->getRefId());
				$DIC->tabs()->addTab(
					'ecr_tab_title',
					'todo', 
					$ilCtrl->getLinkTargetByClass(['ilUIPluginRouterGUI', __CLASS__],'Index.showCmd')
				);
			}
		}
	}
} 