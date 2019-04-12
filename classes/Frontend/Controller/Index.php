<?php
/* Copyright (c) 1998-2017 ILIAS open source, Extended GPL, see docs/LICENSE */

namespace ILIAS\Plugin\TodoLists\Frontend\Controller;

use ILIAS\Plugin\TodoLists\Company\Access\Handler;
use ILIAS\Plugin\TodoLists\Company\Access\Settings;
use ILIAS\Plugin\TodoLists\Frontend\Export;
use ILIAS\Plugin\TodoLists\Types\Explorer;

/**
 * Class Index
 * @package ILIAS\Plugin\TodoLists\Frontend\Controller
 * @author Michael Jansen <mjansen@databay.de>          
 */
class Index extends Base
{

	/**
	 * @inheritdoc
	 */
	protected function init()
	{
		parent::init();
	}

	/**
	 * @inheritdoc
	 */
	public function getDefaultCommand()
	{
		return 'showCmd';
	}

	/**
	 * @return string
	 * @throws \ilTemplateException
	 */
	public function showCmd()
	{
		$exp = new Explorer();
		$tpl = \ilTodoListsPlugin::getInstance()->getTemplate('tpl.my_todos.html', false, false);
		global $DIC;
		$DIC->ui()->mainTemplate()->addCss('Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/TodoLists/templates/todo.css');

		foreach($exp->getResults() as $key => $res) {
			if(is_array($res) && count($res) > 1){
				foreach ($res as $sub_key => $resource ) {
					$tpl->setVariable('DATA_ROW', $resource['type']. '=>' . $resource['title'] . '[' .  $resource['ref_id'] . ']');
					$tpl->parse();
				}
			}

		}
		return $tpl->get();
	}
}