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

	/**
	 * This function is called when the page content is cloned
	 * @param array 	$a_properties		properties saved in the page, (should be modified if neccessary)
	 * @param string	$a_plugin_version	plugin version of the properties
	 */
	public function onClone(&$a_properties, $a_plugin_version)
	{
		if ($file_id = $a_properties['page_file'])
		{
			try
			{
				include_once("./Modules/File/classes/class.ilObjFile.php");
				$fileObj = new ilObjFile($file_id, false);
				$newObj = clone($fileObj);
				$newObj->setId(null);
				$newObj->create();
				$newObj->createDirectory();
				$this->rCopy($fileObj->getDirectory(), $newObj->getDirectory());
				$a_properties['page_file'] = $newObj->getId();

				ilUtil::sendInfo("File Object $file_id cloned.", true);
			}
			catch (Exception $e)
			{
				ilUtil::sendFailure($e->getMessage(), true);
			}
		}
	}


	/**
	 * This function is called before the page content is deleted
	 * @param array 	$a_properties		properties saved in the page (will be deleted afterwards)
	 * @param string	$a_plugin_version	plugin version of the properties
	 */
	public function onDelete($a_properties, $a_plugin_version)
	{
		if ($file_id = $a_properties['page_file'])
		{
			try
			{
				include_once("./Modules/File/classes/class.ilObjFile.php");
				$fileObj = new ilObjFile($file_id, false);
				$fileObj->delete();

				ilUtil::sendInfo("File Object $file_id deleted.", true);
			}
			catch (Exception $e)
			{
				ilUtil::sendFailure($e->getMessage(), true);
			}
		}
	}


	/**
	 * Recursively copy directory (taken from php manual)
	 * @param string $src
	 * @param string $dst
	 */
	private function rCopy($src, $dst)
	{
		$dir = opendir($src);
		@mkdir($dst);
		while (false !== ($file = readdir($dir)))
		{
			if (($file != '.') && ($file != '..'))
			{
				if (is_dir($src . '/' . $file))
				{
					$this->rCopy($src . '/' . $file, $dst . '/' . $file);
				}
				else
				{
					copy($src . '/' . $file, $dst . '/' . $file);
				}
			}
		}
		closedir($dir);
	}
}

?>
