<?php

/* Copyright (c) 1998-2017 ILIAS open source, Extended GPL, see docs/LICENSE */

include_once("./Services/COPage/classes/class.ilPageComponentPluginImporter.php");

/**
 * Exporter class for the TestPageComponent Plugin
 *
 * @author Fred Neumann <fred.neumann@gmx.de>
 * @version $Id$
 *
 * @ingroup ServicesCOPage
 */
class ilTestPageComponentExporter extends ilPageComponentPluginImporter
{
	public function init()
	{
	}


	/**
	 * Import xml representation
	 *
	 * @param	string			$a_entity
	 * @param	string			$a_id
	 * @param	string			$a_xml
	 * @param	ilImportMapping	$a_mapping
	 */
	public function importXmlRepresentation($a_entity, $a_id, $a_xml, $a_mapping)
	{
		/** @var ilTestPageComponentPlugin $plugin */
		$plugin = ilPluginAdmin::getPluginObject(IL_COMP_SERVICE, 'COPage', 'pgcp', 'TestPageComponent');

		$properties = self::getPCProperties($a_id);
		$version = self::getPCVersion($a_id);

		// todo: analyze properties

		$new_id = self::getPCMapping($a_id, $a_mapping);
		self::setPCProperties($new_id, $properties);
		self::setPCVersion($new_id, $version);
	}
}