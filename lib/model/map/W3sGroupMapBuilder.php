<?php



class W3sGroupMapBuilder implements MapBuilder {

	
	const CLASS_NAME = 'plugins.sfW3studioCmsPlugin.lib.model.map.W3sGroupMapBuilder';

	
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
		$this->dbMap = Propel::getDatabaseMap(W3sGroupPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(W3sGroupPeer::TABLE_NAME);
		$tMap->setPhpName('W3sGroup');
		$tMap->setClassname('W3sGroup');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('TEMPLATE_ID', 'TemplateId', 'INTEGER', 'w3s_template', 'ID', true, null);

		$tMap->addColumn('GROUP_NAME', 'GroupName', 'VARCHAR', true, 255);

		$tMap->addColumn('EDITED', 'Edited', 'INTEGER', true, null);

		$tMap->addColumn('TO_DELETE', 'ToDelete', 'INTEGER', true, null);

	} 
} 