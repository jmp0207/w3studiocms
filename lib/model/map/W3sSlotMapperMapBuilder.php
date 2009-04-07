<?php



class W3sSlotMapperMapBuilder implements MapBuilder {

	
	const CLASS_NAME = 'plugins.sfW3studioCmsPlugin.lib.model.map.W3sSlotMapperMapBuilder';

	
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
		$this->dbMap = Propel::getDatabaseMap(W3sSlotMapperPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(W3sSlotMapperPeer::TABLE_NAME);
		$tMap->setPhpName('W3sSlotMapper');
		$tMap->setClassname('W3sSlotMapper');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addColumn('TEMPLATES', 'Templates', 'VARCHAR', true, 255);

		$tMap->addColumn('SLOT_ID_SOURCE', 'SlotIdSource', 'INTEGER', true, null);

		$tMap->addColumn('SLOT_ID_DESTINATION', 'SlotIdDestination', 'INTEGER', true, null);

	} 
} 