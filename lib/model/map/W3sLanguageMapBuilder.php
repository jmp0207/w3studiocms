<?php



class W3sLanguageMapBuilder implements MapBuilder {

	
	const CLASS_NAME = 'plugins.sfW3studioCmsPlugin.lib.model.map.W3sLanguageMapBuilder';

	
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
		$this->dbMap = Propel::getDatabaseMap(W3sLanguagePeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(W3sLanguagePeer::TABLE_NAME);
		$tMap->setPhpName('W3sLanguage');
		$tMap->setClassname('W3sLanguage');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addColumn('LANGUAGE', 'Language', 'VARCHAR', true, 50);

		$tMap->addColumn('MAIN_LANGUAGE', 'MainLanguage', 'CHAR', true, null);

		$tMap->addColumn('TO_DELETE', 'ToDelete', 'INTEGER', true, null);

	} 
} 