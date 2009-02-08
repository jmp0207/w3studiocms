<?php



class W3sSearchEngineMapBuilder implements MapBuilder {

	
	const CLASS_NAME = 'plugins.sfW3studioCmsPlugin.lib.model.map.W3sSearchEngineMapBuilder';

	
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
		$this->dbMap = Propel::getDatabaseMap(W3sSearchEnginePeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(W3sSearchEnginePeer::TABLE_NAME);
		$tMap->setPhpName('W3sSearchEngine');
		$tMap->setClassname('W3sSearchEngine');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('PAGE_ID', 'PageId', 'INTEGER', 'w3s_page', 'ID', true, null);

		$tMap->addForeignKey('LANGUAGE_ID', 'LanguageId', 'INTEGER', 'w3s_language', 'ID', true, null);

		$tMap->addColumn('META_TITLE', 'MetaTitle', 'LONGVARCHAR', true, null);

		$tMap->addColumn('META_DESCRIPTION', 'MetaDescription', 'LONGVARCHAR', true, null);

		$tMap->addColumn('META_KEYWORDS', 'MetaKeywords', 'LONGVARCHAR', true, null);

		$tMap->addColumn('SITEMAP_CHANGEFREQ', 'SitemapChangefreq', 'LONGVARCHAR', true, null);

		$tMap->addColumn('SITEMAP_LASTMOD', 'SitemapLastmod', 'LONGVARCHAR', true, null);

		$tMap->addColumn('SITEMAP_PRIORITY', 'SitemapPriority', 'LONGVARCHAR', true, null);

	} 
} 