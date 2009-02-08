<?php


abstract class BaseW3sMenuElementPeer {

	
	const DATABASE_NAME = 'propel';

	
	const TABLE_NAME = 'w3s_menu_element';

	
	const CLASS_DEFAULT = 'plugins.sfW3studioCmsPlugin.lib.model.W3sMenuElement';

	
	const NUM_COLUMNS = 8;

	
	const NUM_LAZY_LOAD_COLUMNS = 0;

	
	const ID = 'w3s_menu_element.ID';

	
	const CONTENT_ID = 'w3s_menu_element.CONTENT_ID';

	
	const PAGE_ID = 'w3s_menu_element.PAGE_ID';

	
	const LINK = 'w3s_menu_element.LINK';

	
	const EXTERNAL_LINK = 'w3s_menu_element.EXTERNAL_LINK';

	
	const IMAGE = 'w3s_menu_element.IMAGE';

	
	const ROLLOVER_IMAGE = 'w3s_menu_element.ROLLOVER_IMAGE';

	
	const POSITION = 'w3s_menu_element.POSITION';

	
	public static $instances = array();

	
	private static $mapBuilder = null;

	
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'ContentId', 'PageId', 'Link', 'ExternalLink', 'Image', 'RolloverImage', 'Position', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'contentId', 'pageId', 'link', 'externalLink', 'image', 'rolloverImage', 'position', ),
		BasePeer::TYPE_COLNAME => array (self::ID, self::CONTENT_ID, self::PAGE_ID, self::LINK, self::EXTERNAL_LINK, self::IMAGE, self::ROLLOVER_IMAGE, self::POSITION, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'content_id', 'page_id', 'link', 'external_link', 'image', 'rollover_image', 'position', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, )
	);

	
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'ContentId' => 1, 'PageId' => 2, 'Link' => 3, 'ExternalLink' => 4, 'Image' => 5, 'RolloverImage' => 6, 'Position' => 7, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'contentId' => 1, 'pageId' => 2, 'link' => 3, 'externalLink' => 4, 'image' => 5, 'rolloverImage' => 6, 'position' => 7, ),
		BasePeer::TYPE_COLNAME => array (self::ID => 0, self::CONTENT_ID => 1, self::PAGE_ID => 2, self::LINK => 3, self::EXTERNAL_LINK => 4, self::IMAGE => 5, self::ROLLOVER_IMAGE => 6, self::POSITION => 7, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'content_id' => 1, 'page_id' => 2, 'link' => 3, 'external_link' => 4, 'image' => 5, 'rollover_image' => 6, 'position' => 7, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, )
	);

	
	public static function getMapBuilder()
	{
		if (self::$mapBuilder === null) {
			self::$mapBuilder = new W3sMenuElementMapBuilder();
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
		return str_replace(W3sMenuElementPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(W3sMenuElementPeer::ID);

		$criteria->addSelectColumn(W3sMenuElementPeer::CONTENT_ID);

		$criteria->addSelectColumn(W3sMenuElementPeer::PAGE_ID);

		$criteria->addSelectColumn(W3sMenuElementPeer::LINK);

		$criteria->addSelectColumn(W3sMenuElementPeer::EXTERNAL_LINK);

		$criteria->addSelectColumn(W3sMenuElementPeer::IMAGE);

		$criteria->addSelectColumn(W3sMenuElementPeer::ROLLOVER_IMAGE);

		$criteria->addSelectColumn(W3sMenuElementPeer::POSITION);

	}

	
	public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
	{
				$criteria = clone $criteria;

								$criteria->setPrimaryTableName(W3sMenuElementPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			W3sMenuElementPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 		$criteria->setDbName(self::DATABASE_NAME); 
		if ($con === null) {
			$con = Propel::getConnection(W3sMenuElementPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
		$objects = W3sMenuElementPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	
	public static function doSelect(Criteria $criteria, PropelPDO $con = null)
	{
		return W3sMenuElementPeer::populateObjects(W3sMenuElementPeer::doSelectStmt($criteria, $con));
	}
	
	public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(W3sMenuElementPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			W3sMenuElementPeer::addSelectColumns($criteria);
		}

				$criteria->setDbName(self::DATABASE_NAME);

				return BasePeer::doSelect($criteria, $con);
	}
	
	public static function addInstanceToPool(W3sMenuElement $obj, $key = null)
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
			if (is_object($value) && $value instanceof W3sMenuElement) {
				$key = (string) $value->getId();
			} elseif (is_scalar($value)) {
								$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or W3sMenuElement object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
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
	
				$cls = W3sMenuElementPeer::getOMClass();
		$cls = substr('.'.$cls, strrpos('.'.$cls, '.') + 1);
				while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = W3sMenuElementPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = W3sMenuElementPeer::getInstanceFromPool($key))) {
																$results[] = $obj;
			} else {
		
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				W3sMenuElementPeer::addInstanceToPool($obj, $key);
			} 		}
		$stmt->closeCursor();
		return $results;
	}

	
	public static function doCountJoinW3sContent(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
				$criteria = clone $criteria;

								$criteria->setPrimaryTableName(W3sMenuElementPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			W3sMenuElementPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 
				$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(W3sMenuElementPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(W3sMenuElementPeer::CONTENT_ID,), array(W3sContentPeer::ID,), $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; 		}
		$stmt->closeCursor();
		return $count;
	}


	
	public static function doSelectJoinW3sContent(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		W3sMenuElementPeer::addSelectColumns($c);
		$startcol = (W3sMenuElementPeer::NUM_COLUMNS - W3sMenuElementPeer::NUM_LAZY_LOAD_COLUMNS);
		W3sContentPeer::addSelectColumns($c);

		$c->addJoin(array(W3sMenuElementPeer::CONTENT_ID,), array(W3sContentPeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = W3sMenuElementPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = W3sMenuElementPeer::getInstanceFromPool($key1))) {
															} else {

				$omClass = W3sMenuElementPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				W3sMenuElementPeer::addInstanceToPool($obj1, $key1);
			} 
			$key2 = W3sContentPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = W3sContentPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = W3sContentPeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					W3sContentPeer::addInstanceToPool($obj2, $key2);
				} 
								$obj2->addW3sMenuElement($obj1);

			} 
			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
				$criteria = clone $criteria;

								$criteria->setPrimaryTableName(W3sMenuElementPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			W3sMenuElementPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); 
				$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(W3sMenuElementPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(W3sMenuElementPeer::CONTENT_ID,), array(W3sContentPeer::ID,), $join_behavior);
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

		W3sMenuElementPeer::addSelectColumns($c);
		$startcol2 = (W3sMenuElementPeer::NUM_COLUMNS - W3sMenuElementPeer::NUM_LAZY_LOAD_COLUMNS);

		W3sContentPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (W3sContentPeer::NUM_COLUMNS - W3sContentPeer::NUM_LAZY_LOAD_COLUMNS);

		$c->addJoin(array(W3sMenuElementPeer::CONTENT_ID,), array(W3sContentPeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = W3sMenuElementPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = W3sMenuElementPeer::getInstanceFromPool($key1))) {
															} else {
				$omClass = W3sMenuElementPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				W3sMenuElementPeer::addInstanceToPool($obj1, $key1);
			} 
			
			$key2 = W3sContentPeer::getPrimaryKeyHashFromRow($row, $startcol2);
			if ($key2 !== null) {
				$obj2 = W3sContentPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = W3sContentPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					W3sContentPeer::addInstanceToPool($obj2, $key2);
				} 
								$obj2->addW3sMenuElement($obj1);
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
		return W3sMenuElementPeer::CLASS_DEFAULT;
	}

	
	public static function doInsert($values, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(W3sMenuElementPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} else {
			$criteria = $values->buildCriteria(); 		}

		if ($criteria->containsKey(W3sMenuElementPeer::ID) && $criteria->keyContainsValue(W3sMenuElementPeer::ID) ) {
			throw new PropelException('Cannot insert a value for auto-increment primary key ('.W3sMenuElementPeer::ID.')');
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
			$con = Propel::getConnection(W3sMenuElementPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; 
			$comparison = $criteria->getComparison(W3sMenuElementPeer::ID);
			$selectCriteria->add(W3sMenuElementPeer::ID, $criteria->remove(W3sMenuElementPeer::ID), $comparison);

		} else { 			$criteria = $values->buildCriteria(); 			$selectCriteria = $values->buildPkeyCriteria(); 		}

				$criteria->setDbName(self::DATABASE_NAME);

		return BasePeer::doUpdate($selectCriteria, $criteria, $con);
	}

	
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(W3sMenuElementPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; 		try {
									$con->beginTransaction();
			$affectedRows += BasePeer::doDeleteAll(W3sMenuElementPeer::TABLE_NAME, $con);
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
			$con = Propel::getConnection(W3sMenuElementPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
												W3sMenuElementPeer::clearInstancePool();

						$criteria = clone $values;
		} elseif ($values instanceof W3sMenuElement) {
						W3sMenuElementPeer::removeInstanceFromPool($values);
						$criteria = $values->buildPkeyCriteria();
		} else {
			


			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(W3sMenuElementPeer::ID, (array) $values, Criteria::IN);

			foreach ((array) $values as $singleval) {
								W3sMenuElementPeer::removeInstanceFromPool($singleval);
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

	
	public static function doValidate(W3sMenuElement $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(W3sMenuElementPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(W3sMenuElementPeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(W3sMenuElementPeer::DATABASE_NAME, W3sMenuElementPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = W3sMenuElementPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
        }
    }

    return $res;
	}

	
	public static function retrieveByPK($pk, PropelPDO $con = null)
	{

		if (null !== ($obj = W3sMenuElementPeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(W3sMenuElementPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(W3sMenuElementPeer::DATABASE_NAME);
		$criteria->add(W3sMenuElementPeer::ID, $pk);

		$v = W3sMenuElementPeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}

	
	public static function retrieveByPKs($pks, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(W3sMenuElementPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(W3sMenuElementPeer::DATABASE_NAME);
			$criteria->add(W3sMenuElementPeer::ID, $pks, Criteria::IN);
			$objs = W3sMenuElementPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} 

Propel::getDatabaseMap(BaseW3sMenuElementPeer::DATABASE_NAME)->addTableBuilder(BaseW3sMenuElementPeer::TABLE_NAME, BaseW3sMenuElementPeer::getMapBuilder());

