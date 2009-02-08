<?php



class W3sMenuElementMapBuilder implements MapBuilder {

	
	const CLASS_NAME = 'plugins.sfW3studioCmsPlugin.lib.model.map.W3sMenuElementMapBuilder';

	
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
		$this->dbMap = Propel::getDatabaseMap(W3sMenuElementPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(W3sMenuElementPeer::TABLE_NAME);
		$tMap->setPhpName('W3sMenuElement');
		$tMap->setClassname('W3sMenuElement');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('CONTENT_ID', 'ContentId', 'INTEGER', 'w3s_content', 'ID', true, null);

		$tMap->addColumn('PAGE_ID', 'PageId', 'INTEGER', true, null);

		$tMap->addColumn('LINK', 'Link', 'LONGVARCHAR', true, null);

		$tMap->addColumn('EXTERNAL_LINK', 'ExternalLink', 'LONGVARCHAR', true, null);

		$tMap->addColumn('IMAGE', 'Image', 'LONGVARCHAR', true, null);

		$tMap->addColumn('ROLLOVER_IMAGE', 'RolloverImage', 'LONGVARCHAR', true, null);

		$tMap->addColumn('POSITION', 'Position', 'SMALLINT', true, null);

	} 
} 