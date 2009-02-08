<?php


abstract class BaseW3sSearchEnginePeer {

	
	const DATABASE_NAME = 'propel';

	
	const TABLE_NAME = 'w3s_search_engine';

	
	const CLASS_DEFAULT = 'plugins.sfW3studioCmsPlugin.lib.model.W3sSearchEngine';

	
	const NUM_COLUMNS = 9;

	
	const NUM_LAZY_LOAD_COLUMNS = 0;

	
	const ID = 'w3s_search_engine.ID';

	
	const PAGE_ID = 'w3s_search_engine.PAGE_ID';

	
	const LANGUAGE_ID = 'w3s_search_engine.LANGUAGE_ID';

	
	const META_TITLE = 'w3s_search_engine.META_TITLE';

	
	const META_DESCRIPTION = 'w3s_search_engine.META_DESCRIPTION';

	
	const META_KEYWORDS = 'w3s_search_engine.META_KEYWORDS';

	
	const SITEMAP_CHANGEFREQ = 'w3s_search_engine.SITEMAP_CHANGEFREQ';

	
	const SITEMAP_LASTMOD = 'w3s_search_engine.SITEMAP_LASTMOD';

	
	const SITEMAP_PRIORITY = 'w3s_search_engine.SITEMAP_PRIORITY';

	
	public static $instances = array();

	
	private static $mapBuilder = null;

	
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'PageId', 'LanguageId', 'MetaTitle', 'MetaDescription', 'MetaKeywords', 'SitemapChangefreq', 'SitemapLastmod', 'SitemapPriority', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'pageId', 'languageId', 'metaTitle', 'metaDescription', 'metaKeywords', 'sitemapChangefreq', 'sitemapLastmod', 'sitemapPriority', ),
		BasePeer::TYPE_COLNAME => array (self::ID, self::PAGE_ID, self::LANGUAGE_ID, self::META_TITLE, self::META_DESCRIPTION, self::META_KEYWORDS, self::SITEMAP_CHANGEFREQ, self::SITEMAP_LASTMOD, self::SITEMAP_PRIORITY, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'page_id', 'language_id', 'meta_title', 'meta_description', 'meta_keywords', 'sitemap_changefreq', 'sitemap_lastmod', 'sitemap_priority', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, )
	);

	
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'PageId' => 1, 'LanguageId' => 2, 'MetaTitle' => 3, 'MetaDescription' => 4, 'MetaKeywords' => 5, 'SitemapChangefreq' => 6, 'SitemapLastmod' => 7, 'SitemapPriority' => 8, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'pageId' => 1, 'languageId' => 2, 'metaTitle' => 3, 'metaDescription' => 4, 'metaKeywords' => 5, 'sitemapChangefreq' => 6, 'sitemapLastmod' => 7, 'sitemapPriority' => 8, ),
		BasePeer::TYPE_COLNAME => array (self::ID => 0, self::PAGE_ID => 1, self::LANGUAGE_ID => 2, self::META_TITLE => 3, self::META_DESCRIPTION => 4, self::META_KEYWORDS => 5, self::SITEMAP_CHANGEFREQ => 6, self::SITEMAP_LASTMOD => 7, self::SITEMAP_PRIORITY => 8, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'page_id' => 1, 'language_id' => 2, 'meta_title' => 3, 'meta_description' => 4, 'meta_keywords' => 5, 'sitemap_changefreq' => 6, 'sitemap_lastmod' => 7, 'sitemap_priority' => 8, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, )
	);

	
	public static function getMapBuilder()
	{
		if (self::$mapBuilder === null) {
			self::$mapBuilder = new W3sSearchEngineMapBuilder();
		}
		return self::$mapBuilder;
	}
	
	static public function translateFieldName($name, $fromType, $toType)
	{
		$toNames = self::getFieldNames($toType);
		$key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
		if ($key === null) {
			throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(self::$fieldKeys[$fromType], true));
		}
		return $toNames[$key];
	}

	

	static public function getFieldNames($type = BasePeer::TYPE_PHPNAME)
	{
		if (!array_key_exists($type, self::$fieldNames)) {
			throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
		}
		return self::$fieldNames[$type];
	}

	
	public static function alias($alias, $column)
	{
		return str_replace(W3sSearchEnginePeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(W3sSearchEnginePeer::ID);

		$criteria->addSelectColumn(W3sSearchEnginePeer::PAGE_ID);

		$criteria->addSelectColumn(W3sSearchEnginePeer::LANGUAGE_ID);

		$criteria->addSelectColumn(W3sSearchEnginePeer::META_TITLE);

		$criteria->addSelectColumn(W3sSearchEnginePeer::META_DESCRIPTION);

		$criteria->addSelectColumn(W3sSearchEnginePeer::META_KEYWORDS);

		$criteria->addSelectColumn(W3sSearchEnginePeer::SITEMAP_CHANGEFREQ);

		$criteria->addSelectColumn(W3sSearchEnginePeer::SITEMAP_LASTMOD);

		$criteria->addSelectColumn(W3sSearchEnginePeer::SITEMAP_PRIORITY);

	}

	
	public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
	{
				$criteria = clone $criteria;

								$criteria->setPrimaryTableName(W3sSearchEnginePeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			W3sSearchEnginePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 		$criteria->setDbName(self::DATABASE_NAME); 
		if ($con === null) {
			$con = Propel::getConnection(W3sSearchEnginePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

				$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; 		}
		$stmt->closeCursor();
		return $count;
	}
	
	public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = W3sSearchEnginePeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	
	public static function doSelect(Criteria $criteria, PropelPDO $con = null)
	{
		return W3sSearchEnginePeer::populateObjects(W3sSearchEnginePeer::doSelectStmt($criteria, $con));
	}
	
	public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(W3sSearchEnginePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			W3sSearchEnginePeer::addSelectColumns($criteria);
		}

				$criteria->setDbName(self::DATABASE_NAME);

				return BasePeer::doSelect($criteria, $con);
	}
	
	public static function addInstanceToPool(W3sSearchEngine $obj, $key = null)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if ($key === null) {
				$key = (string) $obj->getId();
			} 			self::$instances[$key] = $obj;
		}
	}

	
	public static function removeInstanceFromPool($value)
	{
		if (Propel::isInstancePoolingEnabled() && $value !== null) {
			if (is_object($value) && $value instanceof W3sSearchEngine) {
				$key = (string) $value->getId();
			} elseif (is_scalar($value)) {
								$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or W3sSearchEngine object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
				throw $e;
			}

			unset(self::$instances[$key]);
		}
	} 
	
	public static function getInstanceFromPool($key)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if (isset(self::$instances[$key])) {
				return self::$instances[$key];
			}
		}
		return null; 	}
	
	
	public static function clearInstancePool()
	{
		self::$instances = array();
	}
	
	
	public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
	{
				if ($row[$startcol + 0] === null) {
			return null;
		}
		return (string) $row[$startcol + 0];
	}

	
	public static function populateObjects(PDOStatement $stmt)
	{
		$results = array();
	
				$cls = W3sSearchEnginePeer::getOMClass();
		$cls = substr('.'.$cls, strrpos('.'.$cls, '.') + 1);
				while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = W3sSearchEnginePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = W3sSearchEnginePeer::getInstanceFromPool($key))) {
																$results[] = $obj;
			} else {
		
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				W3sSearchEnginePeer::addInstanceToPool($obj, $key);
			} 		}
		$stmt->closeCursor();
		return $results;
	}

	
	public static function doCountJoinW3sPage(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
				$criteria = clone $criteria;

								$criteria->setPrimaryTableName(W3sSearchEnginePeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			W3sSearchEnginePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 
				$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(W3sSearchEnginePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(W3sSearchEnginePeer::PAGE_ID,), array(W3sPagePeer::ID,), $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; 		}
		$stmt->closeCursor();
		return $count;
	}


	
	public static function doCountJoinW3sLanguage(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
				$criteria = clone $criteria;

								$criteria->setPrimaryTableName(W3sSearchEnginePeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			W3sSearchEnginePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 
				$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(W3sSearchEnginePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(W3sSearchEnginePeer::LANGUAGE_ID,), array(W3sLanguagePeer::ID,), $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; 		}
		$stmt->closeCursor();
		return $count;
	}


	
	public static function doSelectJoinW3sPage(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		W3sSearchEnginePeer::addSelectColumns($c);
		$startcol = (W3sSearchEnginePeer::NUM_COLUMNS - W3sSearchEnginePeer::NUM_LAZY_LOAD_COLUMNS);
		W3sPagePeer::addSelectColumns($c);

		$c->addJoin(array(W3sSearchEnginePeer::PAGE_ID,), array(W3sPagePeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = W3sSearchEnginePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = W3sSearchEnginePeer::getInstanceFromPool($key1))) {
															} else {

				$omClass = W3sSearchEnginePeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				W3sSearchEnginePeer::addInstanceToPool($obj1, $key1);
			} 
			$key2 = W3sPagePeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = W3sPagePeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = W3sPagePeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					W3sPagePeer::addInstanceToPool($obj2, $key2);
				} 
								$obj2->addW3sSearchEngine($obj1);

			} 
			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	
	public static function doSelectJoinW3sLanguage(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		W3sSearchEnginePeer::addSelectColumns($c);
		$startcol = (W3sSearchEnginePeer::NUM_COLUMNS - W3sSearchEnginePeer::NUM_LAZY_LOAD_COLUMNS);
		W3sLanguagePeer::addSelectColumns($c);

		$c->addJoin(array(W3sSearchEnginePeer::LANGUAGE_ID,), array(W3sLanguagePeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = W3sSearchEnginePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = W3sSearchEnginePeer::getInstanceFromPool($key1))) {
															} else {

				$omClass = W3sSearchEnginePeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				W3sSearchEnginePeer::addInstanceToPool($obj1, $key1);
			} 
			$key2 = W3sLanguagePeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = W3sLanguagePeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = W3sLanguagePeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					W3sLanguagePeer::addInstanceToPool($obj2, $key2);
				} 
								$obj2->addW3sSearchEngine($obj1);

			} 
			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
				$criteria = clone $criteria;

								$criteria->setPrimaryTableName(W3sSearchEnginePeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			W3sSearchEnginePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 
				$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(W3sSearchEnginePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(W3sSearchEnginePeer::PAGE_ID,), array(W3sPagePeer::ID,), $join_behavior);
		$criteria->addJoin(array(W3sSearchEnginePeer::LANGUAGE_ID,), array(W3sLanguagePeer::ID,), $join_behavior);
		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; 		}
		$stmt->closeCursor();
		return $count;
	}

	
	public static function doSelectJoinAll(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		W3sSearchEnginePeer::addSelectColumns($c);
		$startcol2 = (W3sSearchEnginePeer::NUM_COLUMNS - W3sSearchEnginePeer::NUM_LAZY_LOAD_COLUMNS);

		W3sPagePeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (W3sPagePeer::NUM_COLUMNS - W3sPagePeer::NUM_LAZY_LOAD_COLUMNS);

		W3sLanguagePeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (W3sLanguagePeer::NUM_COLUMNS - W3sLanguagePeer::NUM_LAZY_LOAD_COLUMNS);

		$c->addJoin(array(W3sSearchEnginePeer::PAGE_ID,), array(W3sPagePeer::ID,), $join_behavior);
		$c->addJoin(array(W3sSearchEnginePeer::LANGUAGE_ID,), array(W3sLanguagePeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = W3sSearchEnginePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = W3sSearchEnginePeer::getInstanceFromPool($key1))) {
															} else {
				$omClass = W3sSearchEnginePeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				W3sSearchEnginePeer::addInstanceToPool($obj1, $key1);
			} 
			
			$key2 = W3sPagePeer::getPrimaryKeyHashFromRow($row, $startcol2);
			if ($key2 !== null) {
				$obj2 = W3sPagePeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = W3sPagePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					W3sPagePeer::addInstanceToPool($obj2, $key2);
				} 
								$obj2->addW3sSearchEngine($obj1);
			} 
			
			$key3 = W3sLanguagePeer::getPrimaryKeyHashFromRow($row, $startcol3);
			if ($key3 !== null) {
				$obj3 = W3sLanguagePeer::getInstanceFromPool($key3);
				if (!$obj3) {

					$omClass = W3sLanguagePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					W3sLanguagePeer::addInstanceToPool($obj3, $key3);
				} 
								$obj3->addW3sSearchEngine($obj1);
			} 
			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	
	public static function doCountJoinAllExceptW3sPage(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
				$criteria = clone $criteria;

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			W3sSearchEnginePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 
				$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(W3sSearchEnginePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
				$criteria->addJoin(array(W3sSearchEnginePeer::LANGUAGE_ID,), array(W3sLanguagePeer::ID,), $join_behavior);
		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; 		}
		$stmt->closeCursor();
		return $count;
	}


	
	public static function doCountJoinAllExceptW3sLanguage(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
				$criteria = clone $criteria;

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			W3sSearchEnginePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 
				$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(W3sSearchEnginePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
				$criteria->addJoin(array(W3sSearchEnginePeer::PAGE_ID,), array(W3sPagePeer::ID,), $join_behavior);
		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; 		}
		$stmt->closeCursor();
		return $count;
	}


	
	public static function doSelectJoinAllExceptW3sPage(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

								if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		W3sSearchEnginePeer::addSelectColumns($c);
		$startcol2 = (W3sSearchEnginePeer::NUM_COLUMNS - W3sSearchEnginePeer::NUM_LAZY_LOAD_COLUMNS);

		W3sLanguagePeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (W3sLanguagePeer::NUM_COLUMNS - W3sLanguagePeer::NUM_LAZY_LOAD_COLUMNS);

				$c->addJoin(array(W3sSearchEnginePeer::LANGUAGE_ID,), array(W3sLanguagePeer::ID,), $join_behavior);

		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = W3sSearchEnginePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = W3sSearchEnginePeer::getInstanceFromPool($key1))) {
															} else {
				$omClass = W3sSearchEnginePeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				W3sSearchEnginePeer::addInstanceToPool($obj1, $key1);
			} 
				
				$key2 = W3sLanguagePeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = W3sLanguagePeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$omClass = W3sLanguagePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					W3sLanguagePeer::addInstanceToPool($obj2, $key2);
				} 
								$obj2->addW3sSearchEngine($obj1);

			} 
			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	
	public static function doSelectJoinAllExceptW3sLanguage(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

								if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		W3sSearchEnginePeer::addSelectColumns($c);
		$startcol2 = (W3sSearchEnginePeer::NUM_COLUMNS - W3sSearchEnginePeer::NUM_LAZY_LOAD_COLUMNS);

		W3sPagePeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (W3sPagePeer::NUM_COLUMNS - W3sPagePeer::NUM_LAZY_LOAD_COLUMNS);

				$c->addJoin(array(W3sSearchEnginePeer::PAGE_ID,), array(W3sPagePeer::ID,), $join_behavior);

		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = W3sSearchEnginePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = W3sSearchEnginePeer::getInstanceFromPool($key1))) {
															} else {
				$omClass = W3sSearchEnginePeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				W3sSearchEnginePeer::addInstanceToPool($obj1, $key1);
			} 
				
				$key2 = W3sPagePeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = W3sPagePeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$omClass = W3sPagePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					W3sPagePeer::addInstanceToPool($obj2, $key2);
				} 
								$obj2->addW3sSearchEngine($obj1);

			} 
			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


  static public function getUniqueColumnNames()
  {
    return array(array('id'));
  }
	
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	
	public static function getOMClass()
	{
		return W3sSearchEnginePeer::CLASS_DEFAULT;
	}

	
	public static function doInsert($values, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(W3sSearchEnginePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} else {
			$criteria = $values->buildCriteria(); 		}

		if ($criteria->containsKey(W3sSearchEnginePeer::ID) && $criteria->keyContainsValue(W3sSearchEnginePeer::ID) ) {
			throw new PropelException('Cannot insert a value for auto-increment primary key ('.W3sSearchEnginePeer::ID.')');
		}


				$criteria->setDbName(self::DATABASE_NAME);

		try {
									$con->beginTransaction();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollBack();
			throw $e;
		}

		return $pk;
	}

	
	public static function doUpdate($values, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(W3sSearchEnginePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; 
			$comparison = $criteria->getComparison(W3sSearchEnginePeer::ID);
			$selectCriteria->add(W3sSearchEnginePeer::ID, $criteria->remove(W3sSearchEnginePeer::ID), $comparison);

		} else { 			$criteria = $values->buildCriteria(); 			$selectCriteria = $values->buildPkeyCriteria(); 		}

				$criteria->setDbName(self::DATABASE_NAME);

		return BasePeer::doUpdate($selectCriteria, $criteria, $con);
	}

	
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(W3sSearchEnginePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; 		try {
									$con->beginTransaction();
			$affectedRows += BasePeer::doDeleteAll(W3sSearchEnginePeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	
	 public static function doDelete($values, PropelPDO $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(W3sSearchEnginePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
												W3sSearchEnginePeer::clearInstancePool();

						$criteria = clone $values;
		} elseif ($values instanceof W3sSearchEngine) {
						W3sSearchEnginePeer::removeInstanceFromPool($values);
						$criteria = $values->buildPkeyCriteria();
		} else {
			


			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(W3sSearchEnginePeer::ID, (array) $values, Criteria::IN);

			foreach ((array) $values as $singleval) {
								W3sSearchEnginePeer::removeInstanceFromPool($singleval);
			}
		}

				$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; 
		try {
									$con->beginTransaction();
			
			$affectedRows += BasePeer::doDelete($criteria, $con);

			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	
	public static function doValidate(W3sSearchEngine $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(W3sSearchEnginePeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(W3sSearchEnginePeer::TABLE_NAME);

			if (! is_array($cols)) {
				$cols = array($cols);
			}

			foreach ($cols as $colName) {
				if ($tableMap->containsColumn($colName)) {
					$get = 'get' . $tableMap->getColumn($colName)->getPhpName();
					$columns[$colName] = $obj->$get();
				}
			}
		} else {

		}

		$res =  BasePeer::doValidate(W3sSearchEnginePeer::DATABASE_NAME, W3sSearchEnginePeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = W3sSearchEnginePeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
        }
    }

    return $res;
	}

	
	public static function retrieveByPK($pk, PropelPDO $con = null)
	{

		if (null !== ($obj = W3sSearchEnginePeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(W3sSearchEnginePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(W3sSearchEnginePeer::DATABASE_NAME);
		$criteria->add(W3sSearchEnginePeer::ID, $pk);

		$v = W3sSearchEnginePeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}

	
	public static function retrieveByPKs($pks, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(W3sSearchEnginePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(W3sSearchEnginePeer::DATABASE_NAME);
			$criteria->add(W3sSearchEnginePeer::ID, $pks, Criteria::IN);
			$objs = W3sSearchEnginePeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} 

Propel::getDatabaseMap(BaseW3sSearchEnginePeer::DATABASE_NAME)->addTableBuilder(BaseW3sSearchEnginePeer::TABLE_NAME, BaseW3sSearchEnginePeer::getMapBuilder());

