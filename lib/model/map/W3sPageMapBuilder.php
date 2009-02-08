<?php



class W3sPageMapBuilder implements MapBuilder {

	
	const CLASS_NAME = 'plugins.sfW3studioCmsPlugin.lib.model.map.W3sPageMapBuilder';

	
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
		$this->dbMap = Propel::getDatabaseMap(W3sPagePeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(W3sPagePeer::TABLE_NAME);
		$tMap->setPhpName('W3sPage');
		$tMap->setClassname('W3sPage');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('GROUP_ID', 'GroupId', 'INTEGER', 'w3s_group', 'ID', true, null);

		$tMap->addColumn('PAGE_NAME', 'PageName', 'VARCHAR', true, 255);

		$tMap->addColumn('IS_HOME', 'IsHome', 'INTEGER', true, null);

		$tMap->addColumn('TO_DELETE', 'ToDelete', 'INTEGER', true, null);

	} 
} 