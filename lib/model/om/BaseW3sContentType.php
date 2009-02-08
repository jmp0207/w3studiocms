<?php


abstract class BaseW3sContentType extends BaseObject  implements Persistent {


  const PEER = 'W3sContentTypePeer';

	
	protected static $peer;

	
	protected $id;

	
	protected $type_description;

	
	protected $collW3sContents;

	
	private $lastW3sContentCriteria = null;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	
	public function applyDefaultValues()
	{
		$this->type_description = 'null';
	}

	
	public function getId()
	{
		return $this->id;
	}

	
	public function getTypeDescription()
	{
		return $this->type_description;
	}

	
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = W3sContentTypePeer::ID;
		}

		return $this;
	} 
	
	public function setTypeDescription($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->type_description !== $v || $v === 'null') {
			$this->type_description = $v;
			$this->modifiedColumns[] = W3sContentTypePeer::TYPE_DESCRIPTION;
		}

		return $this;
	} 
	
	public function hasOnlyDefaultValues()
	{
						if (array_diff($this->modifiedColumns, array(W3sContentTypePeer::TYPE_DESCRIPTION))) {
				return false;
			}

			if ($this->type_description !== 'null') {
				return false;
			}

				return true;
	} 
	
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->type_description = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

						return $startcol + 2; 
		} catch (Exception $e) {
			throw new PropelException("Error populating W3sContentType object", $e);
		}
	}

	
	public function ensureConsistency()
	{

	} 
	
	public function reload($deep = false, PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("Cannot reload a deleted object.");
		}

		if ($this->isNew()) {
			throw new PropelException("Cannot reload an unsaved object.");
		}

		if ($con === null) {
			$con = Propel::getConnection(W3sContentTypePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

				
		$stmt = W3sContentTypePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); 
		if ($deep) {  
			$this->collW3sContents = null;
			$this->lastW3sContentCriteria = null;

		} 	}

	
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(W3sContentTypePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			W3sContentTypePeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	
	public function save(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(W3sContentTypePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
			W3sContentTypePeer::addInstanceToPool($this);
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	
	protected function doSave(PropelPDO $con)
	{
		$affectedRows = 0; 		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = W3sContentTypePeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setNew(false);
				} else {
					$affectedRows += W3sContentTypePeer::doUpdate($this, $con);
				}

				$this->resetModified(); 			}

			if ($this->collW3sContents !== null) {
				foreach ($this->collW3sContents as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			$this->alreadyInSave = false;

		}
		return $affectedRows;
	} 
	
	protected $validationFailures = array();

	
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			if (($retval = W3sContentTypePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collW3sContents !== null) {
					foreach ($this->collW3sContents as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}


			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = W3sContentTypePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		$field = $this->getByPosition($pos);
		return $field;
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getTypeDescription();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true)
	{
		$keys = W3sContentTypePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getTypeDescription(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = W3sContentTypePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setTypeDescription($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = W3sContentTypePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setTypeDescription($arr[$keys[1]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(W3sContentTypePeer::DATABASE_NAME);

		if ($this->isColumnModified(W3sContentTypePeer::ID)) $criteria->add(W3sContentTypePeer::ID, $this->id);
		if ($this->isColumnModified(W3sContentTypePeer::TYPE_DESCRIPTION)) $criteria->add(W3sContentTypePeer::TYPE_DESCRIPTION, $this->type_description);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(W3sContentTypePeer::DATABASE_NAME);

		$criteria->add(W3sContentTypePeer::ID, $this->id);

		return $criteria;
	}

	
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setId($this->id);

		$copyObj->setTypeDescription($this->type_description);


		if ($deepCopy) {
									$copyObj->setNew(false);

			foreach ($this->getW3sContents() as $relObj) {
				if ($relObj !== $this) {  					$copyObj->addW3sContent($relObj->copy($deepCopy));
				}
			}

		} 

		$copyObj->setNew(true);

	}

	
	public function copy($deepCopy = false)
	{
				$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new W3sContentTypePeer();
		}
		return self::$peer;
	}

	
	public function clearW3sContents()
	{
		$this->collW3sContents = null; 	}

	
	public function initW3sContents()
	{
		$this->collW3sContents = array();
	}

	
	public function getW3sContents($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sContentTypePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
			   $this->collW3sContents = array();
			} else {

				$criteria->add(W3sContentPeer::CONTENT_TYPE_ID, $this->id);

				W3sContentPeer::addSelectColumns($criteria);
				$this->collW3sContents = W3sContentPeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(W3sContentPeer::CONTENT_TYPE_ID, $this->id);

				W3sContentPeer::addSelectColumns($criteria);
				if (!isset($this->lastW3sContentCriteria) || !$this->lastW3sContentCriteria->equals($criteria)) {
					$this->collW3sContents = W3sContentPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastW3sContentCriteria = $criteria;
		return $this->collW3sContents;
	}

	
	public function countW3sContents(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sContentTypePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(W3sContentPeer::CONTENT_TYPE_ID, $this->id);

				$count = W3sContentPeer::doCount($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(W3sContentPeer::CONTENT_TYPE_ID, $this->id);

				if (!isset($this->lastW3sContentCriteria) || !$this->lastW3sContentCriteria->equals($criteria)) {
					$count = W3sContentPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collW3sContents);
				}
			} else {
				$count = count($this->collW3sContents);
			}
		}
		$this->lastW3sContentCriteria = $criteria;
		return $count;
	}

	
	public function addW3sContent(W3sContent $l)
	{
		if ($this->collW3sContents === null) {
			$this->initW3sContents();
		}
		if (!in_array($l, $this->collW3sContents, true)) { 			array_push($this->collW3sContents, $l);
			$l->setW3sContentType($this);
		}
	}


	
	public function getW3sContentsJoinW3sGroup($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sContentTypePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
				$this->collW3sContents = array();
			} else {

				$criteria->add(W3sContentPeer::CONTENT_TYPE_ID, $this->id);

				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sGroup($criteria, $con, $join_behavior);
			}
		} else {
									
			$criteria->add(W3sContentPeer::CONTENT_TYPE_ID, $this->id);

			if (!isset($this->lastW3sContentCriteria) || !$this->lastW3sContentCriteria->equals($criteria)) {
				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sGroup($criteria, $con, $join_behavior);
			}
		}
		$this->lastW3sContentCriteria = $criteria;

		return $this->collW3sContents;
	}


	
	public function getW3sContentsJoinW3sPage($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sContentTypePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
				$this->collW3sContents = array();
			} else {

				$criteria->add(W3sContentPeer::CONTENT_TYPE_ID, $this->id);

				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sPage($criteria, $con, $join_behavior);
			}
		} else {
									
			$criteria->add(W3sContentPeer::CONTENT_TYPE_ID, $this->id);

			if (!isset($this->lastW3sContentCriteria) || !$this->lastW3sContentCriteria->equals($criteria)) {
				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sPage($criteria, $con, $join_behavior);
			}
		}
		$this->lastW3sContentCriteria = $criteria;

		return $this->collW3sContents;
	}


	
	public function getW3sContentsJoinW3sLanguage($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sContentTypePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
				$this->collW3sContents = array();
			} else {

				$criteria->add(W3sContentPeer::CONTENT_TYPE_ID, $this->id);

				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sLanguage($criteria, $con, $join_behavior);
			}
		} else {
									
			$criteria->add(W3sContentPeer::CONTENT_TYPE_ID, $this->id);

			if (!isset($this->lastW3sContentCriteria) || !$this->lastW3sContentCriteria->equals($criteria)) {
				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sLanguage($criteria, $con, $join_behavior);
			}
		}
		$this->lastW3sContentCriteria = $criteria;

		return $this->collW3sContents;
	}


	
	public function getW3sContentsJoinW3sSlot($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sContentTypePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
				$this->collW3sContents = array();
			} else {

				$criteria->add(W3sContentPeer::CONTENT_TYPE_ID, $this->id);

				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sSlot($criteria, $con, $join_behavior);
			}
		} else {
									
			$criteria->add(W3sContentPeer::CONTENT_TYPE_ID, $this->id);

			if (!isset($this->lastW3sContentCriteria) || !$this->lastW3sContentCriteria->equals($criteria)) {
				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sSlot($criteria, $con, $join_behavior);
			}
		}
		$this->lastW3sContentCriteria = $criteria;

		return $this->collW3sContents;
	}

	
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
			if ($this->collW3sContents) {
				foreach ((array) $this->collW3sContents as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} 
		$this->collW3sContents = null;
	}

} 