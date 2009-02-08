<?php



class W3sContentMapBuilder implements MapBuilder {

	
	const CLASS_NAME = 'plugins.sfW3studioCmsPlugin.lib.model.map.W3sContentMapBuilder';

	
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
		$this->dbMap = Propel::getDatabaseMap(W3sContentPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(W3sContentPeer::TABLE_NAME);
		$tMap->setPhpName('W3sContent');
		$tMap->setClassname('W3sContent');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('GROUP_ID', 'GroupId', 'INTEGER', 'w3s_group', 'ID', true, null);

		$tMap->addForeignKey('PAGE_ID', 'PageId', 'INTEGER', 'w3s_page', 'ID', true, null);

		$tMap->addForeignKey('LANGUAGE_ID', 'LanguageId', 'INTEGER', 'w3s_language', 'ID', true, null);

		$tMap->addForeignKey('CONTENT_TYPE_ID', 'ContentTypeId', 'INTEGER', 'w3s_content_type', 'ID', true, null);

		$tMap->addForeignKey('SLOT_ID', 'SlotId', 'INTEGER', 'w3s_slot', 'ID', true, null);

		$tMap->addColumn('CONTENT', 'Content', 'LONGVARCHAR', true, null);

		$tMap->addColumn('EDITED', 'Edited', 'INTEGER', true, null);

		$tMap->addColumn('TO_DELETE', 'ToDelete', 'INTEGER', true, null);

		$tMap->addColumn('CONTENT_POSITION', 'ContentPosition', 'INTEGER', true, null);

	} 
} 