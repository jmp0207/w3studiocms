<?php


abstract class BaseW3sContentPeer {

	
	const DATABASE_NAME = 'propel';

	
	const TABLE_NAME = 'w3s_content';

	
	const CLASS_DEFAULT = 'plugins.sfW3studioCmsPlugin.lib.model.W3sContent';

	
	const NUM_COLUMNS = 10;

	
	const NUM_LAZY_LOAD_COLUMNS = 0;

	
	const ID = 'w3s_content.ID';

	
	const GROUP_ID = 'w3s_content.GROUP_ID';

	
	const PAGE_ID = 'w3s_content.PAGE_ID';

	
	const LANGUAGE_ID = 'w3s_content.LANGUAGE_ID';

	
	const CONTENT_TYPE_ID = 'w3s_content.CONTENT_TYPE_ID';

	
	const SLOT_ID = 'w3s_content.SLOT_ID';

	
	const CONTENT = 'w3s_content.CONTENT';

	
	const EDITED = 'w3s_content.EDITED';

	
	const TO_DELETE = 'w3s_content.TO_DELETE';

	
	const CONTENT_POSITION = 'w3s_content.CONTENT_POSITION';

	
	public static $instances = array();

	
	private static $mapBuilder = null;

	
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'GroupId', 'PageId', 'LanguageId', 'ContentTypeId', 'SlotId', 'Content', 'Edited', 'ToDelete', 'ContentPosition', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'groupId', 'pageId', 'languageId', 'contentTypeId', 'slotId', 'content', 'edited', 'toDelete', 'contentPosition', ),
		BasePeer::TYPE_COLNAME => array (self::ID, self::GROUP_ID, self::PAGE_ID, self::LANGUAGE_ID, self::CONTENT_TYPE_ID, self::SLOT_ID, self::CONTENT, self::EDITED, self::TO_DELETE, self::CONTENT_POSITION, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'group_id', 'page_id', 'language_id', 'content_type_id', 'slot_id', 'content', 'edited', 'to_delete', 'content_position', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
	);

	
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'GroupId' => 1, 'PageId' => 2, 'LanguageId' => 3, 'ContentTypeId' => 4, 'SlotId' => 5, 'Content' => 6, 'Edited' => 7, 'ToDelete' => 8, 'ContentPosition' => 9, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'groupId' => 1, 'pageId' => 2, 'languageId' => 3, 'contentTypeId' => 4, 'slotId' => 5, 'content' => 6, 'edited' => 7, 'toDelete' => 8, 'contentPosition' => 9, ),
		BasePeer::TYPE_COLNAME => array (self::ID => 0, self::GROUP_ID => 1, self::PAGE_ID => 2, self::LANGUAGE_ID => 3, self::CONTENT_TYPE_ID => 4, self::SLOT_ID => 5, self::CONTENT => 6, self::EDITED => 7, self::TO_DELETE => 8, self::CONTENT_POSITION => 9, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'group_id' => 1, 'page_id' => 2, 'language_id' => 3, 'content_type_id' => 4, 'slot_id' => 5, 'content' => 6, 'edited' => 7, 'to_delete' => 8, 'content_position' => 9, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
	);

	
	public static function getMapBuilder()
	{
		if (self::$mapBuilder === null) {
			self::$mapBuilder = new W3sContentMapBuilder();
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
		return str_replace(W3sContentPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(W3sContentPeer::ID);

		$criteria->addSelectColumn(W3sContentPeer::GROUP_ID);

		$criteria->addSelectColumn(W3sContentPeer::PAGE_ID);

		$criteria->addSelectColumn(W3sContentPeer::LANGUAGE_ID);

		$criteria->addSelectColumn(W3sContentPeer::CONTENT_TYPE_ID);

		$criteria->addSelectColumn(W3sContentPeer::SLOT_ID);

		$criteria->addSelectColumn(W3sContentPeer::CONTENT);

		$criteria->addSelectColumn(W3sContentPeer::EDITED);

		$criteria->addSelectColumn(W3sContentPeer::TO_DELETE);

		$criteria->addSelectColumn(W3sContentPeer::CONTENT_POSITION);

	}

	
	public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
	{
				$criteria = clone $criteria;

								$criteria->setPrimaryTableName(W3sContentPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			W3sContentPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 		$criteria->setDbName(self::DATABASE_NAME); 
		if ($con === null) {
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
		$objects = W3sContentPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	
	public static function doSelect(Criteria $criteria, PropelPDO $con = null)
	{
		return W3sContentPeer::populateObjects(W3sContentPeer::doSelectStmt($criteria, $con));
	}
	
	public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			W3sContentPeer::addSelectColumns($criteria);
		}

				$criteria->setDbName(self::DATABASE_NAME);

				return BasePeer::doSelect($criteria, $con);
	}
	
	public static function addInstanceToPool(W3sContent $obj, $key = null)
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
			if (is_object($value) && $value instanceof W3sContent) {
				$key = (string) $value->getId();
			} elseif (is_scalar($value)) {
								$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or W3sContent object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
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
	
				$cls = W3sContentPeer::getOMClass();
		$cls = substr('.'.$cls, strrpos('.'.$cls, '.') + 1);
				while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = W3sContentPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = W3sContentPeer::getInstanceFromPool($key))) {
																$results[] = $obj;
			} else {
		
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				W3sContentPeer::addInstanceToPool($obj, $key);
			} 		}
		$stmt->closeCursor();
		return $results;
	}

	
	public static function doCountJoinW3sGroup(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
				$criteria = clone $criteria;

								$criteria->setPrimaryTableName(W3sContentPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			W3sContentPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 
				$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(W3sContentPeer::GROUP_ID,), array(W3sGroupPeer::ID,), $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; 		}
		$stmt->closeCursor();
		return $count;
	}


	
	public static function doCountJoinW3sPage(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
				$criteria = clone $criteria;

								$criteria->setPrimaryTableName(W3sContentPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			W3sContentPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 
				$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(W3sContentPeer::PAGE_ID,), array(W3sPagePeer::ID,), $join_behavior);

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

								$criteria->setPrimaryTableName(W3sContentPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			W3sContentPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 
				$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(W3sContentPeer::LANGUAGE_ID,), array(W3sLanguagePeer::ID,), $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; 		}
		$stmt->closeCursor();
		return $count;
	}


	
	public static function doCountJoinW3sContentType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
				$criteria = clone $criteria;

								$criteria->setPrimaryTableName(W3sContentPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			W3sContentPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 
				$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(W3sContentPeer::CONTENT_TYPE_ID,), array(W3sContentTypePeer::ID,), $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; 		}
		$stmt->closeCursor();
		return $count;
	}


	
	public static function doCountJoinW3sSlot(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
				$criteria = clone $criteria;

								$criteria->setPrimaryTableName(W3sContentPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			W3sContentPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 
				$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(W3sContentPeer::SLOT_ID,), array(W3sSlotPeer::ID,), $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; 		}
		$stmt->closeCursor();
		return $count;
	}


	
	public static function doSelectJoinW3sGroup(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		W3sContentPeer::addSelectColumns($c);
		$startcol = (W3sContentPeer::NUM_COLUMNS - W3sContentPeer::NUM_LAZY_LOAD_COLUMNS);
		W3sGroupPeer::addSelectColumns($c);

		$c->addJoin(array(W3sContentPeer::GROUP_ID,), array(W3sGroupPeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = W3sContentPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = W3sContentPeer::getInstanceFromPool($key1))) {
															} else {

				$omClass = W3sContentPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				W3sContentPeer::addInstanceToPool($obj1, $key1);
			} 
			$key2 = W3sGroupPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = W3sGroupPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = W3sGroupPeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					W3sGroupPeer::addInstanceToPool($obj2, $key2);
				} 
								$obj2->addW3sContent($obj1);

			} 
			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	
	public static function doSelectJoinW3sPage(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		W3sContentPeer::addSelectColumns($c);
		$startcol = (W3sContentPeer::NUM_COLUMNS - W3sContentPeer::NUM_LAZY_LOAD_COLUMNS);
		W3sPagePeer::addSelectColumns($c);

		$c->addJoin(array(W3sContentPeer::PAGE_ID,), array(W3sPagePeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = W3sContentPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = W3sContentPeer::getInstanceFromPool($key1))) {
															} else {

				$omClass = W3sContentPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				W3sContentPeer::addInstanceToPool($obj1, $key1);
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
								$obj2->addW3sContent($obj1);

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

		W3sContentPeer::addSelectColumns($c);
		$startcol = (W3sContentPeer::NUM_COLUMNS - W3sContentPeer::NUM_LAZY_LOAD_COLUMNS);
		W3sLanguagePeer::addSelectColumns($c);

		$c->addJoin(array(W3sContentPeer::LANGUAGE_ID,), array(W3sLanguagePeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = W3sContentPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = W3sContentPeer::getInstanceFromPool($key1))) {
															} else {

				$omClass = W3sContentPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				W3sContentPeer::addInstanceToPool($obj1, $key1);
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
								$obj2->addW3sContent($obj1);

			} 
			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	
	public static function doSelectJoinW3sContentType(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		W3sContentPeer::addSelectColumns($c);
		$startcol = (W3sContentPeer::NUM_COLUMNS - W3sContentPeer::NUM_LAZY_LOAD_COLUMNS);
		W3sContentTypePeer::addSelectColumns($c);

		$c->addJoin(array(W3sContentPeer::CONTENT_TYPE_ID,), array(W3sContentTypePeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = W3sContentPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = W3sContentPeer::getInstanceFromPool($key1))) {
															} else {

				$omClass = W3sContentPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				W3sContentPeer::addInstanceToPool($obj1, $key1);
			} 
			$key2 = W3sContentTypePeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = W3sContentTypePeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = W3sContentTypePeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					W3sContentTypePeer::addInstanceToPool($obj2, $key2);
				} 
								$obj2->addW3sContent($obj1);

			} 
			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	
	public static function doSelectJoinW3sSlot(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		W3sContentPeer::addSelectColumns($c);
		$startcol = (W3sContentPeer::NUM_COLUMNS - W3sContentPeer::NUM_LAZY_LOAD_COLUMNS);
		W3sSlotPeer::addSelectColumns($c);

		$c->addJoin(array(W3sContentPeer::SLOT_ID,), array(W3sSlotPeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = W3sContentPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = W3sContentPeer::getInstanceFromPool($key1))) {
															} else {

				$omClass = W3sContentPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				W3sContentPeer::addInstanceToPool($obj1, $key1);
			} 
			$key2 = W3sSlotPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = W3sSlotPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = W3sSlotPeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					W3sSlotPeer::addInstanceToPool($obj2, $key2);
				} 
								$obj2->addW3sContent($obj1);

			} 
			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
				$criteria = clone $criteria;

								$criteria->setPrimaryTableName(W3sContentPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			W3sContentPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 
				$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(W3sContentPeer::GROUP_ID,), array(W3sGroupPeer::ID,), $join_behavior);
		$criteria->addJoin(array(W3sContentPeer::PAGE_ID,), array(W3sPagePeer::ID,), $join_behavior);
		$criteria->addJoin(array(W3sContentPeer::LANGUAGE_ID,), array(W3sLanguagePeer::ID,), $join_behavior);
		$criteria->addJoin(array(W3sContentPeer::CONTENT_TYPE_ID,), array(W3sContentTypePeer::ID,), $join_behavior);
		$criteria->addJoin(array(W3sContentPeer::SLOT_ID,), array(W3sSlotPeer::ID,), $join_behavior);
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

		W3sContentPeer::addSelectColumns($c);
		$startcol2 = (W3sContentPeer::NUM_COLUMNS - W3sContentPeer::NUM_LAZY_LOAD_COLUMNS);

		W3sGroupPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (W3sGroupPeer::NUM_COLUMNS - W3sGroupPeer::NUM_LAZY_LOAD_COLUMNS);

		W3sPagePeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (W3sPagePeer::NUM_COLUMNS - W3sPagePeer::NUM_LAZY_LOAD_COLUMNS);

		W3sLanguagePeer::addSelectColumns($c);
		$startcol5 = $startcol4 + (W3sLanguagePeer::NUM_COLUMNS - W3sLanguagePeer::NUM_LAZY_LOAD_COLUMNS);

		W3sContentTypePeer::addSelectColumns($c);
		$startcol6 = $startcol5 + (W3sContentTypePeer::NUM_COLUMNS - W3sContentTypePeer::NUM_LAZY_LOAD_COLUMNS);

		W3sSlotPeer::addSelectColumns($c);
		$startcol7 = $startcol6 + (W3sSlotPeer::NUM_COLUMNS - W3sSlotPeer::NUM_LAZY_LOAD_COLUMNS);

		$c->addJoin(array(W3sContentPeer::GROUP_ID,), array(W3sGroupPeer::ID,), $join_behavior);
		$c->addJoin(array(W3sContentPeer::PAGE_ID,), array(W3sPagePeer::ID,), $join_behavior);
		$c->addJoin(array(W3sContentPeer::LANGUAGE_ID,), array(W3sLanguagePeer::ID,), $join_behavior);
		$c->addJoin(array(W3sContentPeer::CONTENT_TYPE_ID,), array(W3sContentTypePeer::ID,), $join_behavior);
		$c->addJoin(array(W3sContentPeer::SLOT_ID,), array(W3sSlotPeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = W3sContentPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = W3sContentPeer::getInstanceFromPool($key1))) {
															} else {
				$omClass = W3sContentPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				W3sContentPeer::addInstanceToPool($obj1, $key1);
			} 
			
			$key2 = W3sGroupPeer::getPrimaryKeyHashFromRow($row, $startcol2);
			if ($key2 !== null) {
				$obj2 = W3sGroupPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = W3sGroupPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					W3sGroupPeer::addInstanceToPool($obj2, $key2);
				} 
								$obj2->addW3sContent($obj1);
			} 
			
			$key3 = W3sPagePeer::getPrimaryKeyHashFromRow($row, $startcol3);
			if ($key3 !== null) {
				$obj3 = W3sPagePeer::getInstanceFromPool($key3);
				if (!$obj3) {

					$omClass = W3sPagePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					W3sPagePeer::addInstanceToPool($obj3, $key3);
				} 
								$obj3->addW3sContent($obj1);
			} 
			
			$key4 = W3sLanguagePeer::getPrimaryKeyHashFromRow($row, $startcol4);
			if ($key4 !== null) {
				$obj4 = W3sLanguagePeer::getInstanceFromPool($key4);
				if (!$obj4) {

					$omClass = W3sLanguagePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					W3sLanguagePeer::addInstanceToPool($obj4, $key4);
				} 
								$obj4->addW3sContent($obj1);
			} 
			
			$key5 = W3sContentTypePeer::getPrimaryKeyHashFromRow($row, $startcol5);
			if ($key5 !== null) {
				$obj5 = W3sContentTypePeer::getInstanceFromPool($key5);
				if (!$obj5) {

					$omClass = W3sContentTypePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					W3sContentTypePeer::addInstanceToPool($obj5, $key5);
				} 
								$obj5->addW3sContent($obj1);
			} 
			
			$key6 = W3sSlotPeer::getPrimaryKeyHashFromRow($row, $startcol6);
			if ($key6 !== null) {
				$obj6 = W3sSlotPeer::getInstanceFromPool($key6);
				if (!$obj6) {

					$omClass = W3sSlotPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj6 = new $cls();
					$obj6->hydrate($row, $startcol6);
					W3sSlotPeer::addInstanceToPool($obj6, $key6);
				} 
								$obj6->addW3sContent($obj1);
			} 
			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	
	public static function doCountJoinAllExceptW3sGroup(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
				$criteria = clone $criteria;

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			W3sContentPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 
				$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
				$criteria->addJoin(array(W3sContentPeer::PAGE_ID,), array(W3sPagePeer::ID,), $join_behavior);
				$criteria->addJoin(array(W3sContentPeer::LANGUAGE_ID,), array(W3sLanguagePeer::ID,), $join_behavior);
				$criteria->addJoin(array(W3sContentPeer::CONTENT_TYPE_ID,), array(W3sContentTypePeer::ID,), $join_behavior);
				$criteria->addJoin(array(W3sContentPeer::SLOT_ID,), array(W3sSlotPeer::ID,), $join_behavior);
		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; 		}
		$stmt->closeCursor();
		return $count;
	}


	
	public static function doCountJoinAllExceptW3sPage(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
				$criteria = clone $criteria;

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			W3sContentPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 
				$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
				$criteria->addJoin(array(W3sContentPeer::GROUP_ID,), array(W3sGroupPeer::ID,), $join_behavior);
				$criteria->addJoin(array(W3sContentPeer::LANGUAGE_ID,), array(W3sLanguagePeer::ID,), $join_behavior);
				$criteria->addJoin(array(W3sContentPeer::CONTENT_TYPE_ID,), array(W3sContentTypePeer::ID,), $join_behavior);
				$criteria->addJoin(array(W3sContentPeer::SLOT_ID,), array(W3sSlotPeer::ID,), $join_behavior);
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
			W3sContentPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 
				$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
				$criteria->addJoin(array(W3sContentPeer::GROUP_ID,), array(W3sGroupPeer::ID,), $join_behavior);
				$criteria->addJoin(array(W3sContentPeer::PAGE_ID,), array(W3sPagePeer::ID,), $join_behavior);
				$criteria->addJoin(array(W3sContentPeer::CONTENT_TYPE_ID,), array(W3sContentTypePeer::ID,), $join_behavior);
				$criteria->addJoin(array(W3sContentPeer::SLOT_ID,), array(W3sSlotPeer::ID,), $join_behavior);
		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; 		}
		$stmt->closeCursor();
		return $count;
	}


	
	public static function doCountJoinAllExceptW3sContentType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
				$criteria = clone $criteria;

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			W3sContentPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 
				$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
				$criteria->addJoin(array(W3sContentPeer::GROUP_ID,), array(W3sGroupPeer::ID,), $join_behavior);
				$criteria->addJoin(array(W3sContentPeer::PAGE_ID,), array(W3sPagePeer::ID,), $join_behavior);
				$criteria->addJoin(array(W3sContentPeer::LANGUAGE_ID,), array(W3sLanguagePeer::ID,), $join_behavior);
				$criteria->addJoin(array(W3sContentPeer::SLOT_ID,), array(W3sSlotPeer::ID,), $join_behavior);
		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; 		}
		$stmt->closeCursor();
		return $count;
	}


	
	public static function doCountJoinAllExceptW3sSlot(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
				$criteria = clone $criteria;

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			W3sContentPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 
				$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
				$criteria->addJoin(array(W3sContentPeer::GROUP_ID,), array(W3sGroupPeer::ID,), $join_behavior);
				$criteria->addJoin(array(W3sContentPeer::PAGE_ID,), array(W3sPagePeer::ID,), $join_behavior);
				$criteria->addJoin(array(W3sContentPeer::LANGUAGE_ID,), array(W3sLanguagePeer::ID,), $join_behavior);
				$criteria->addJoin(array(W3sContentPeer::CONTENT_TYPE_ID,), array(W3sContentTypePeer::ID,), $join_behavior);
		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; 		}
		$stmt->closeCursor();
		return $count;
	}


	
	public static function doSelectJoinAllExceptW3sGroup(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

								if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		W3sContentPeer::addSelectColumns($c);
		$startcol2 = (W3sContentPeer::NUM_COLUMNS - W3sContentPeer::NUM_LAZY_LOAD_COLUMNS);

		W3sPagePeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (W3sPagePeer::NUM_COLUMNS - W3sPagePeer::NUM_LAZY_LOAD_COLUMNS);

		W3sLanguagePeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (W3sLanguagePeer::NUM_COLUMNS - W3sLanguagePeer::NUM_LAZY_LOAD_COLUMNS);

		W3sContentTypePeer::addSelectColumns($c);
		$startcol5 = $startcol4 + (W3sContentTypePeer::NUM_COLUMNS - W3sContentTypePeer::NUM_LAZY_LOAD_COLUMNS);

		W3sSlotPeer::addSelectColumns($c);
		$startcol6 = $startcol5 + (W3sSlotPeer::NUM_COLUMNS - W3sSlotPeer::NUM_LAZY_LOAD_COLUMNS);

				$c->addJoin(array(W3sContentPeer::PAGE_ID,), array(W3sPagePeer::ID,), $join_behavior);
				$c->addJoin(array(W3sContentPeer::LANGUAGE_ID,), array(W3sLanguagePeer::ID,), $join_behavior);
				$c->addJoin(array(W3sContentPeer::CONTENT_TYPE_ID,), array(W3sContentTypePeer::ID,), $join_behavior);
				$c->addJoin(array(W3sContentPeer::SLOT_ID,), array(W3sSlotPeer::ID,), $join_behavior);

		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = W3sContentPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = W3sContentPeer::getInstanceFromPool($key1))) {
															} else {
				$omClass = W3sContentPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				W3sContentPeer::addInstanceToPool($obj1, $key1);
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
								$obj2->addW3sContent($obj1);

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
								$obj3->addW3sContent($obj1);

			} 
				
				$key4 = W3sContentTypePeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = W3sContentTypePeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$omClass = W3sContentTypePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					W3sContentTypePeer::addInstanceToPool($obj4, $key4);
				} 
								$obj4->addW3sContent($obj1);

			} 
				
				$key5 = W3sSlotPeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = W3sSlotPeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$omClass = W3sSlotPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					W3sSlotPeer::addInstanceToPool($obj5, $key5);
				} 
								$obj5->addW3sContent($obj1);

			} 
			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	
	public static function doSelectJoinAllExceptW3sPage(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

								if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		W3sContentPeer::addSelectColumns($c);
		$startcol2 = (W3sContentPeer::NUM_COLUMNS - W3sContentPeer::NUM_LAZY_LOAD_COLUMNS);

		W3sGroupPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (W3sGroupPeer::NUM_COLUMNS - W3sGroupPeer::NUM_LAZY_LOAD_COLUMNS);

		W3sLanguagePeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (W3sLanguagePeer::NUM_COLUMNS - W3sLanguagePeer::NUM_LAZY_LOAD_COLUMNS);

		W3sContentTypePeer::addSelectColumns($c);
		$startcol5 = $startcol4 + (W3sContentTypePeer::NUM_COLUMNS - W3sContentTypePeer::NUM_LAZY_LOAD_COLUMNS);

		W3sSlotPeer::addSelectColumns($c);
		$startcol6 = $startcol5 + (W3sSlotPeer::NUM_COLUMNS - W3sSlotPeer::NUM_LAZY_LOAD_COLUMNS);

				$c->addJoin(array(W3sContentPeer::GROUP_ID,), array(W3sGroupPeer::ID,), $join_behavior);
				$c->addJoin(array(W3sContentPeer::LANGUAGE_ID,), array(W3sLanguagePeer::ID,), $join_behavior);
				$c->addJoin(array(W3sContentPeer::CONTENT_TYPE_ID,), array(W3sContentTypePeer::ID,), $join_behavior);
				$c->addJoin(array(W3sContentPeer::SLOT_ID,), array(W3sSlotPeer::ID,), $join_behavior);

		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = W3sContentPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = W3sContentPeer::getInstanceFromPool($key1))) {
															} else {
				$omClass = W3sContentPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				W3sContentPeer::addInstanceToPool($obj1, $key1);
			} 
				
				$key2 = W3sGroupPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = W3sGroupPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$omClass = W3sGroupPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					W3sGroupPeer::addInstanceToPool($obj2, $key2);
				} 
								$obj2->addW3sContent($obj1);

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
								$obj3->addW3sContent($obj1);

			} 
				
				$key4 = W3sContentTypePeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = W3sContentTypePeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$omClass = W3sContentTypePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					W3sContentTypePeer::addInstanceToPool($obj4, $key4);
				} 
								$obj4->addW3sContent($obj1);

			} 
				
				$key5 = W3sSlotPeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = W3sSlotPeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$omClass = W3sSlotPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					W3sSlotPeer::addInstanceToPool($obj5, $key5);
				} 
								$obj5->addW3sContent($obj1);

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

		W3sContentPeer::addSelectColumns($c);
		$startcol2 = (W3sContentPeer::NUM_COLUMNS - W3sContentPeer::NUM_LAZY_LOAD_COLUMNS);

		W3sGroupPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (W3sGroupPeer::NUM_COLUMNS - W3sGroupPeer::NUM_LAZY_LOAD_COLUMNS);

		W3sPagePeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (W3sPagePeer::NUM_COLUMNS - W3sPagePeer::NUM_LAZY_LOAD_COLUMNS);

		W3sContentTypePeer::addSelectColumns($c);
		$startcol5 = $startcol4 + (W3sContentTypePeer::NUM_COLUMNS - W3sContentTypePeer::NUM_LAZY_LOAD_COLUMNS);

		W3sSlotPeer::addSelectColumns($c);
		$startcol6 = $startcol5 + (W3sSlotPeer::NUM_COLUMNS - W3sSlotPeer::NUM_LAZY_LOAD_COLUMNS);

				$c->addJoin(array(W3sContentPeer::GROUP_ID,), array(W3sGroupPeer::ID,), $join_behavior);
				$c->addJoin(array(W3sContentPeer::PAGE_ID,), array(W3sPagePeer::ID,), $join_behavior);
				$c->addJoin(array(W3sContentPeer::CONTENT_TYPE_ID,), array(W3sContentTypePeer::ID,), $join_behavior);
				$c->addJoin(array(W3sContentPeer::SLOT_ID,), array(W3sSlotPeer::ID,), $join_behavior);

		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = W3sContentPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = W3sContentPeer::getInstanceFromPool($key1))) {
															} else {
				$omClass = W3sContentPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				W3sContentPeer::addInstanceToPool($obj1, $key1);
			} 
				
				$key2 = W3sGroupPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = W3sGroupPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$omClass = W3sGroupPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					W3sGroupPeer::addInstanceToPool($obj2, $key2);
				} 
								$obj2->addW3sContent($obj1);

			} 
				
				$key3 = W3sPagePeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = W3sPagePeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$omClass = W3sPagePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					W3sPagePeer::addInstanceToPool($obj3, $key3);
				} 
								$obj3->addW3sContent($obj1);

			} 
				
				$key4 = W3sContentTypePeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = W3sContentTypePeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$omClass = W3sContentTypePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					W3sContentTypePeer::addInstanceToPool($obj4, $key4);
				} 
								$obj4->addW3sContent($obj1);

			} 
				
				$key5 = W3sSlotPeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = W3sSlotPeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$omClass = W3sSlotPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					W3sSlotPeer::addInstanceToPool($obj5, $key5);
				} 
								$obj5->addW3sContent($obj1);

			} 
			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	
	public static function doSelectJoinAllExceptW3sContentType(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

								if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		W3sContentPeer::addSelectColumns($c);
		$startcol2 = (W3sContentPeer::NUM_COLUMNS - W3sContentPeer::NUM_LAZY_LOAD_COLUMNS);

		W3sGroupPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (W3sGroupPeer::NUM_COLUMNS - W3sGroupPeer::NUM_LAZY_LOAD_COLUMNS);

		W3sPagePeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (W3sPagePeer::NUM_COLUMNS - W3sPagePeer::NUM_LAZY_LOAD_COLUMNS);

		W3sLanguagePeer::addSelectColumns($c);
		$startcol5 = $startcol4 + (W3sLanguagePeer::NUM_COLUMNS - W3sLanguagePeer::NUM_LAZY_LOAD_COLUMNS);

		W3sSlotPeer::addSelectColumns($c);
		$startcol6 = $startcol5 + (W3sSlotPeer::NUM_COLUMNS - W3sSlotPeer::NUM_LAZY_LOAD_COLUMNS);

				$c->addJoin(array(W3sContentPeer::GROUP_ID,), array(W3sGroupPeer::ID,), $join_behavior);
				$c->addJoin(array(W3sContentPeer::PAGE_ID,), array(W3sPagePeer::ID,), $join_behavior);
				$c->addJoin(array(W3sContentPeer::LANGUAGE_ID,), array(W3sLanguagePeer::ID,), $join_behavior);
				$c->addJoin(array(W3sContentPeer::SLOT_ID,), array(W3sSlotPeer::ID,), $join_behavior);

		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = W3sContentPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = W3sContentPeer::getInstanceFromPool($key1))) {
															} else {
				$omClass = W3sContentPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				W3sContentPeer::addInstanceToPool($obj1, $key1);
			} 
				
				$key2 = W3sGroupPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = W3sGroupPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$omClass = W3sGroupPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					W3sGroupPeer::addInstanceToPool($obj2, $key2);
				} 
								$obj2->addW3sContent($obj1);

			} 
				
				$key3 = W3sPagePeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = W3sPagePeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$omClass = W3sPagePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					W3sPagePeer::addInstanceToPool($obj3, $key3);
				} 
								$obj3->addW3sContent($obj1);

			} 
				
				$key4 = W3sLanguagePeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = W3sLanguagePeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$omClass = W3sLanguagePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					W3sLanguagePeer::addInstanceToPool($obj4, $key4);
				} 
								$obj4->addW3sContent($obj1);

			} 
				
				$key5 = W3sSlotPeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = W3sSlotPeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$omClass = W3sSlotPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					W3sSlotPeer::addInstanceToPool($obj5, $key5);
				} 
								$obj5->addW3sContent($obj1);

			} 
			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	
	public static function doSelectJoinAllExceptW3sSlot(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

								if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		W3sContentPeer::addSelectColumns($c);
		$startcol2 = (W3sContentPeer::NUM_COLUMNS - W3sContentPeer::NUM_LAZY_LOAD_COLUMNS);

		W3sGroupPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (W3sGroupPeer::NUM_COLUMNS - W3sGroupPeer::NUM_LAZY_LOAD_COLUMNS);

		W3sPagePeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (W3sPagePeer::NUM_COLUMNS - W3sPagePeer::NUM_LAZY_LOAD_COLUMNS);

		W3sLanguagePeer::addSelectColumns($c);
		$startcol5 = $startcol4 + (W3sLanguagePeer::NUM_COLUMNS - W3sLanguagePeer::NUM_LAZY_LOAD_COLUMNS);

		W3sContentTypePeer::addSelectColumns($c);
		$startcol6 = $startcol5 + (W3sContentTypePeer::NUM_COLUMNS - W3sContentTypePeer::NUM_LAZY_LOAD_COLUMNS);

				$c->addJoin(array(W3sContentPeer::GROUP_ID,), array(W3sGroupPeer::ID,), $join_behavior);
				$c->addJoin(array(W3sContentPeer::PAGE_ID,), array(W3sPagePeer::ID,), $join_behavior);
				$c->addJoin(array(W3sContentPeer::LANGUAGE_ID,), array(W3sLanguagePeer::ID,), $join_behavior);
				$c->addJoin(array(W3sContentPeer::CONTENT_TYPE_ID,), array(W3sContentTypePeer::ID,), $join_behavior);

		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = W3sContentPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = W3sContentPeer::getInstanceFromPool($key1))) {
															} else {
				$omClass = W3sContentPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				W3sContentPeer::addInstanceToPool($obj1, $key1);
			} 
				
				$key2 = W3sGroupPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = W3sGroupPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$omClass = W3sGroupPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					W3sGroupPeer::addInstanceToPool($obj2, $key2);
				} 
								$obj2->addW3sContent($obj1);

			} 
				
				$key3 = W3sPagePeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = W3sPagePeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$omClass = W3sPagePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					W3sPagePeer::addInstanceToPool($obj3, $key3);
				} 
								$obj3->addW3sContent($obj1);

			} 
				
				$key4 = W3sLanguagePeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = W3sLanguagePeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$omClass = W3sLanguagePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					W3sLanguagePeer::addInstanceToPool($obj4, $key4);
				} 
								$obj4->addW3sContent($obj1);

			} 
				
				$key5 = W3sContentTypePeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = W3sContentTypePeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$omClass = W3sContentTypePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					W3sContentTypePeer::addInstanceToPool($obj5, $key5);
				} 
								$obj5->addW3sContent($obj1);

			} 
			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


  static public function getUniqueColumnNames()
  {
    return array();
  }
	
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	
	public static function getOMClass()
	{
		return W3sContentPeer::CLASS_DEFAULT;
	}

	
	public static function doInsert($values, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} else {
			$criteria = $values->buildCriteria(); 		}

		if ($criteria->containsKey(W3sContentPeer::ID) && $criteria->keyContainsValue(W3sContentPeer::ID) ) {
			throw new PropelException('Cannot insert a value for auto-increment primary key ('.W3sContentPeer::ID.')');
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
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; 
			$comparison = $criteria->getComparison(W3sContentPeer::ID);
			$selectCriteria->add(W3sContentPeer::ID, $criteria->remove(W3sContentPeer::ID), $comparison);

		} else { 			$criteria = $values->buildCriteria(); 			$selectCriteria = $values->buildPkeyCriteria(); 		}

				$criteria->setDbName(self::DATABASE_NAME);

		return BasePeer::doUpdate($selectCriteria, $criteria, $con);
	}

	
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; 		try {
									$con->beginTransaction();
			$affectedRows += BasePeer::doDeleteAll(W3sContentPeer::TABLE_NAME, $con);
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
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
												W3sContentPeer::clearInstancePool();

						$criteria = clone $values;
		} elseif ($values instanceof W3sContent) {
						W3sContentPeer::removeInstanceFromPool($values);
						$criteria = $values->buildPkeyCriteria();
		} else {
			


			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(W3sContentPeer::ID, (array) $values, Criteria::IN);

			foreach ((array) $values as $singleval) {
								W3sContentPeer::removeInstanceFromPool($singleval);
			}
		}

				$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; 
		try {
									$con->beginTransaction();
			
			$affectedRows += BasePeer::doDelete($criteria, $con);

						W3sMenuElementPeer::clearInstancePool();

			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	
	public static function doValidate(W3sContent $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(W3sContentPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(W3sContentPeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(W3sContentPeer::DATABASE_NAME, W3sContentPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = W3sContentPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
        }
    }

    return $res;
	}

	
	public static function retrieveByPK($pk, PropelPDO $con = null)
	{

		if (null !== ($obj = W3sContentPeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(W3sContentPeer::DATABASE_NAME);
		$criteria->add(W3sContentPeer::ID, $pk);

		$v = W3sContentPeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}

	
	public static function retrieveByPKs($pks, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(W3sContentPeer::DATABASE_NAME);
			$criteria->add(W3sContentPeer::ID, $pks, Criteria::IN);
			$objs = W3sContentPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} 

Propel::getDatabaseMap(BaseW3sContentPeer::DATABASE_NAME)->addTableBuilder(BaseW3sContentPeer::TABLE_NAME, BaseW3sContentPeer::getMapBuilder());

