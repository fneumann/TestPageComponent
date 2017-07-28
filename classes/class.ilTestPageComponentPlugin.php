<?php
/**
 * Copyright (c) 2017 Fred Neumann <fred.neumann@gmx.de>
 * GPLv3, see LICENSE
 */
include_once("./Services/COPage/classes/class.ilPageComponentPlugin.php");
 
/**
 * Test Page Component plugin
 *
 * @author Fred Neumann <fred.neumann@gmx.de>
 */
class ilTestPageComponentPlugin extends ilPageComponentPlugin
{
	/**
	 * Get plugin name 
	 *
	 * @return string
	 */
	function getPluginName()
	{
		return "TestPageComponent";
	}


	/**
	 * Check if parent type is valid
	 *
	 * @return string
	 */
	function isValidParentType($a_parent_type)
	{
		// test with all parent types
		return true;
	}
}

?>
