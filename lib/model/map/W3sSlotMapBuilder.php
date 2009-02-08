<?php



class W3sSlotMapBuilder implements MapBuilder {

	
	const CLASS_NAME = 'plugins.sfW3studioCmsPlugin.lib.model.map.W3sSlotMapBuilder';

	
	private $dbMap;

	
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap(W3sSlotPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(W3sSlotPeer::TABLE_NAME);
		$tMap->setPhpName('W3sSlot');
		$tMap->setClassname('W3sSlot');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('TEMPLATE_ID', 'TemplateId', 'INTEGER', 'w3s_template', 'ID', true, null);

		$tMap->addColumn('SLOT_NAME', 'SlotName', 'VARCHAR', true, 255);

		$tMap->addColumn('REPEATED_CONTENTS', 'RepeatedContents', 'INTEGER', true, null);

		$tMap->addColumn('TO_DELETE', 'ToDelete', 'INTEGER', true, null);

	} 
} 