<?php



class W3sProjectMapBuilder implements MapBuilder {

	
	const CLASS_NAME = 'plugins.sfW3studioCmsPlugin.lib.model.map.W3sProjectMapBuilder';

	
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
		$this->dbMap = Propel::getDatabaseMap(W3sProjectPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(W3sProjectPeer::TABLE_NAME);
		$tMap->setPhpName('W3sProject');
		$tMap->setClassname('W3sProject');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addColumn('PROJECT_NAME', 'ProjectName', 'VARCHAR', true, 255);

	} 
} 