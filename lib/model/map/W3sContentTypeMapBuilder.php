<?php



class W3sContentTypeMapBuilder implements MapBuilder {

	
	const CLASS_NAME = 'plugins.sfW3studioCmsPlugin.lib.model.map.W3sContentTypeMapBuilder';

	
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
		$this->dbMap = Propel::getDatabaseMap(W3sContentTypePeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(W3sContentTypePeer::TABLE_NAME);
		$tMap->setPhpName('W3sContentType');
		$tMap->setClassname('W3sContentType');

		$tMap->setUseIdGenerator(false);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addColumn('TYPE_DESCRIPTION', 'TypeDescription', 'VARCHAR', true, 50);

	} 
} 