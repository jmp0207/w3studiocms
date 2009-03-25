<?php



class W3sSlotAssociationMapBuilder implements MapBuilder {

	
	const CLASS_NAME = 'plugins.sfW3studioCmsPlugin.lib.model.map.W3sSlotAssociationMapBuilder';

	
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
		$this->dbMap = Propel::getDatabaseMap(W3sSlotAssociationPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(W3sSlotAssociationPeer::TABLE_NAME);
		$tMap->setPhpName('W3sSlotAssociation');
		$tMap->setClassname('W3sSlotAssociation');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addColumn('SLOT_ID_SOURCE', 'SlotIdSource', 'INTEGER', true, null);

		$tMap->addColumn('SLOT_ID_DESTINATION', 'SlotIdDestination', 'INTEGER', true, null);

	} 
} 