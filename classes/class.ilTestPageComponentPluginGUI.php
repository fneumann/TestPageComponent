<?php
/**
 * Copyright (c) 2017 Fred Neumann <fred.neumann@gmx.de>
 * GPLv3, see LICENSE
 */

include_once("./Services/COPage/classes/class.ilPageComponentPluginGUI.php");
 
/**
 * Test Page Component GUI
 *
 * @author Fred Neumann <fred.neumann@gmx.de>
 *
 * @ilCtrl_isCalledBy ilTestPageComponentPluginGUI: ilPCPluggedGUI
 */
class ilTestPageComponentPluginGUI extends ilPageComponentPluginGUI
{
	/** @var  ilLanguage $lng */
	protected $lng;

	/** @var  ilCtrl $ctrl */
	protected $ctrl;

	/** @var  ilTemplate $tpl */
	protected $tpl;

	/**
	 * ilTestPageComponentPluginGUI constructor.
	 */
	public function __construct()
	{
		global $DIC;

		$this->lng = $DIC->language();
		$this->ctrl = $DIC->ctrl();
		$this->tpl = $DIC['tpl'];
	}


	/**
	 * Execute command
	 */
	public function executeCommand()
	{
		$next_class = $this->ctrl->getNextClass();

		switch($next_class)
		{
			default:
				// perform valid commands
				$cmd = $this->ctrl->getCmd();
				if (in_array($cmd, array("create", "save", "edit", "update", "cancel")))
				{
					$this->$cmd();
				}
				break;
		}
	}
	
	
	/**
	 * Create
	 */
	public function insert()
	{
		$form = $this->initForm(true);
		$this->tpl->setContent($form->getHTML());
	}
	
	/**
	 * Save new pc example element
	 */
	public function create()
	{
		$form = $this->initForm(true);
		if ($form->checkInput())
		{
            $properties = array(
                'page_value' => $form->getInput('page_value'),
			);
			if ($this->createElement($properties))
			{
				ilUtil::sendSuccess($this->lng->txt("msg_obj_modified"), true);
				$this->returnToParent();
			}
		}
		$form->setValuesByPost();
		$this->tpl->setContent($form->getHtml());
	}
	
	/**
	 * Edit
	 */
	public function edit()
	{
        $form = $this->initForm();

		$this->tpl->setContent($form->getHTML());
	}
	
	/**
	 * Update
	 */
	public function update()
	{
		$form = $this->initForm(true);
		if ($form->checkInput())
		{
			$properties = array(
				'page_value' => $form->getInput('page_value'),
			);
			if ($this->updateElement($properties))
			{
				ilUtil::sendSuccess($this->lng->txt("msg_obj_modified"), true);
				$this->returnToParent();
			}
		}
		$form->setValuesByPost();
		$this->tpl->setContent($form->getHtml());
	}
	
	
	/**
	 * Init editing form
	 *
	 * @param        int        $a_create        true: create component, false: edit component
	 */
	protected function initForm($a_create = false)
	{
		include_once("Services/Form/classes/class.ilPropertyFormGUI.php");
		$form = new ilPropertyFormGUI();

		// field name
        $page_value = new ilTextInputGUI('page_value', 'page_value');
		$page_value->setMaxLength(40);
		$page_value->setSize(40);
		$page_value->setRequired(true);
		$form->addItem($page_value);

		// page info values
		foreach ($this->getPageInfo() as $key => $value)
		{
			$info = new ilNonEditableValueGUI($key);
			$info->setValue($value);
			$form->addItem($info);
		}

		// save and cancel commands
		if ($a_create)
		{
			$this->addCreationButton($form);
			$form->addCommandButton("cancel", $this->lng->txt("cancel"));
			$form->setTitle($this->plugin->getPluginName());
		}
		else
		{
			$prop = $this->getProperties();
			$page_value->setValue($prop['page_value']);

			$form->addCommandButton("update", $this->lng->txt("save"));
			$form->addCommandButton("cancel", $this->lng->txt("cancel"));
			$form->setTitle($this->plugin->getPluginName());
		}
		
		$form->setFormAction($this->ctrl->getFormAction($this));
		return $form;
	}


	/**
	 * Cancel
	 */
	public function cancel()
	{
		$this->returnToParent();
	}


	/**
	 * Get HTML for element
	 *
	 * @param string    page mode (edit, presentation, print, preview, offline)
	 * @return string   html code
	 */
	public function getElementHTML($a_mode, array $a_properties, $a_plugin_version)
	{
		$display = array_merge($a_properties, $this->getPageInfo());

		return '<pre>' . print_r($display, true) . '</pre>';
	}


	/**
	 * Get information about the page that embeds the component
	 * @return	array	key => value
	 */
	public function getPageInfo()
	{
		return array(
			'page_id' => $this->plugin->getPageId(),
			'parent_id' => $this->plugin->getParentId(),
			'parent_type' => $this->plugin->getParentType()
		);
	}
}

?>
