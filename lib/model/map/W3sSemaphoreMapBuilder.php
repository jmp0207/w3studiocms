<?php



class W3sSemaphoreMapBuilder implements MapBuilder {

	
	const CLASS_NAME = 'plugins.sfW3studioCmsPlugin.lib.model.map.W3sSemaphoreMapBuilder';

	
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
		$this->dbMap = Propel::getDatabaseMap(W3sSemaphorePeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(W3sSemaphorePeer::TABLE_NAME);
		$tMap->setPhpName('W3sSemaphore');
		$tMap->setClassname('W3sSemaphore');

		$tMap->setUseIdGenerator(false);

		$tMap->addForeignPrimaryKey('SF_GUARD_USER_ID', 'SfGuardUserId', 'INTEGER' , 'sf_guard_user', 'ID', true, null);

		$tMap->addPrimaryKey('OPERATION', 'Operation', 'VARCHAR', true, 255);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null);

	} 
} 