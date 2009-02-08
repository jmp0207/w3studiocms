<?php



class W3sTemplateMapBuilder implements MapBuilder {

	
	const CLASS_NAME = 'plugins.sfW3studioCmsPlugin.lib.model.map.W3sTemplateMapBuilder';

	
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
		$this->dbMap = Propel::getDatabaseMap(W3sTemplatePeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(W3sTemplatePeer::TABLE_NAME);
		$tMap->setPhpName('W3sTemplate');
		$tMap->setClassname('W3sTemplate');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('PROJECT_ID', 'ProjectId', 'INTEGER', 'w3s_project', 'ID', true, null);

		$tMap->addColumn('TEMPLATE_NAME', 'TemplateName', 'VARCHAR', true, 255);

	} 
} 