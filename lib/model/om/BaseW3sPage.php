<?php


abstract class BaseW3sPage extends BaseObject  implements Persistent {


  const PEER = 'W3sPagePeer';

	
	protected static $peer;

	
	protected $id;

	
	protected $group_id;

	
	protected $page_name;

	
	protected $is_home;

	
	protected $to_delete;

	
	protected $aW3sGroup;

	
	protected $collW3sContents;

	
	private $lastW3sContentCriteria = null;

	
	protected $collW3sSearchEngines;

	
	private $lastW3sSearchEngineCriteria = null;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	
	public function applyDefaultValues()
	{
	}

	
	public function getId()
	{
		return $this->id;
	}

	
	public function getGroupId()
	{
		return $this->group_id;
	}

	
	public function getPageName()
	{
		return $this->page_name;
	}

	
	public function getIsHome()
	{
		return $this->is_home;
	}

	
	public function getToDelete()
	{
		return $this->to_delete;
	}

	
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = W3sPagePeer::ID;
		}

		return $this;
	} 
	
	public function setGroupId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->group_id !== $v) {
			$this->group_id = $v;
			$this->modifiedColumns[] = W3sPagePeer::GROUP_ID;
		}

		if ($this->aW3sGroup !== null && $this->aW3sGroup->getId() !== $v) {
			$this->aW3sGroup = null;
		}

		return $this;
	} 
	
	public function setPageName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->page_name !== $v) {
			$this->page_name = $v;
			$this->modifiedColumns[] = W3sPagePeer::PAGE_NAME;
		}

		return $this;
	} 
	
	public function setIsHome($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->is_home !== $v) {
			$this->is_home = $v;
			$this->modifiedColumns[] = W3sPagePeer::IS_HOME;
		}

		return $this;
	} 
	
	public function setToDelete($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->to_delete !== $v) {
			$this->to_delete = $v;
			$this->modifiedColumns[] = W3sPagePeer::TO_DELETE;
		}

		return $this;
	} 
	
	public function hasOnlyDefaultValues()
	{
						if (array_diff($this->modifiedColumns, array())) {
				return false;
			}

				return true;
	} 
	
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->group_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->page_name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->is_home = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->to_delete = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

						return $startcol + 5; 
		} catch (Exception $e) {
			throw new PropelException("Error populating W3sPage object", $e);
		}
	}

	
	public function ensureConsistency()
	{

		if ($this->aW3sGroup !== null && $this->group_id !== $this->aW3sGroup->getId()) {
			$this->aW3sGroup = null;
		}
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
			$con = Propel::getConnection(W3sPagePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

				
		$stmt = W3sPagePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); 
		if ($deep) {  
			$this->aW3sGroup = null;
			$this->collW3sContents = null;
			$this->lastW3sContentCriteria = null;

			$this->collW3sSearchEngines = null;
			$this->lastW3sSearchEngineCriteria = null;

		} 	}

	
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(W3sPagePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			W3sPagePeer::doDelete($this, $con);
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
			$con = Propel::getConnection(W3sPagePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
			W3sPagePeer::addInstanceToPool($this);
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

												
			if ($this->aW3sGroup !== null) {
				if ($this->aW3sGroup->isModified() || $this->aW3sGroup->isNew()) {
					$affectedRows += $this->aW3sGroup->save($con);
				}
				$this->setW3sGroup($this->aW3sGroup);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = W3sPagePeer::ID;
			}

						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = W3sPagePeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += W3sPagePeer::doUpdate($this, $con);
				}

				$this->resetModified(); 			}

			if ($this->collW3sContents !== null) {
				foreach ($this->collW3sContents as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collW3sSearchEngines !== null) {
				foreach ($this->collW3sSearchEngines as $referrerFK) {
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


												
			if ($this->aW3sGroup !== null) {
				if (!$this->aW3sGroup->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aW3sGroup->getValidationFailures());
				}
			}


			if (($retval = W3sPagePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collW3sContents !== null) {
					foreach ($this->collW3sContents as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collW3sSearchEngines !== null) {
					foreach ($this->collW3sSearchEngines as $referrerFK) {
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
		$pos = W3sPagePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getGroupId();
				break;
			case 2:
				return $this->getPageName();
				break;
			case 3:
				return $this->getIsHome();
				break;
			case 4:
				return $this->getToDelete();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true)
	{
		$keys = W3sPagePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getGroupId(),
			$keys[2] => $this->getPageName(),
			$keys[3] => $this->getIsHome(),
			$keys[4] => $this->getToDelete(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = W3sPagePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setGroupId($value);
				break;
			case 2:
				$this->setPageName($value);
				break;
			case 3:
				$this->setIsHome($value);
				break;
			case 4:
				$this->setToDelete($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = W3sPagePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setGroupId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setPageName($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setIsHome($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setToDelete($arr[$keys[4]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(W3sPagePeer::DATABASE_NAME);

		if ($this->isColumnModified(W3sPagePeer::ID)) $criteria->add(W3sPagePeer::ID, $this->id);
		if ($this->isColumnModified(W3sPagePeer::GROUP_ID)) $criteria->add(W3sPagePeer::GROUP_ID, $this->group_id);
		if ($this->isColumnModified(W3sPagePeer::PAGE_NAME)) $criteria->add(W3sPagePeer::PAGE_NAME, $this->page_name);
		if ($this->isColumnModified(W3sPagePeer::IS_HOME)) $criteria->add(W3sPagePeer::IS_HOME, $this->is_home);
		if ($this->isColumnModified(W3sPagePeer::TO_DELETE)) $criteria->add(W3sPagePeer::TO_DELETE, $this->to_delete);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(W3sPagePeer::DATABASE_NAME);

		$criteria->add(W3sPagePeer::ID, $this->id);

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

		$copyObj->setGroupId($this->group_id);

		$copyObj->setPageName($this->page_name);

		$copyObj->setIsHome($this->is_home);

		$copyObj->setToDelete($this->to_delete);


		if ($deepCopy) {
									$copyObj->setNew(false);

			foreach ($this->getW3sContents() as $relObj) {
				if ($relObj !== $this) {  					$copyObj->addW3sContent($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getW3sSearchEngines() as $relObj) {
				if ($relObj !== $this) {  					$copyObj->addW3sSearchEngine($relObj->copy($deepCopy));
				}
			}

		} 

		$copyObj->setNew(true);

		$copyObj->setId(NULL); 
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
			self::$peer = new W3sPagePeer();
		}
		return self::$peer;
	}

	
	public function setW3sGroup(W3sGroup $v = null)
	{
		if ($v === null) {
			$this->setGroupId(NULL);
		} else {
			$this->setGroupId($v->getId());
		}

		$this->aW3sGroup = $v;

						if ($v !== null) {
			$v->addW3sPage($this);
		}

		return $this;
	}


	
	public function getW3sGroup(PropelPDO $con = null)
	{
		if ($this->aW3sGroup === null && ($this->group_id !== null)) {
			$c = new Criteria(W3sGroupPeer::DATABASE_NAME);
			$c->add(W3sGroupPeer::ID, $this->group_id);
			$this->aW3sGroup = W3sGroupPeer::doSelectOne($c, $con);
			
		}
		return $this->aW3sGroup;
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
			$criteria = new Criteria(W3sPagePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
			   $this->collW3sContents = array();
			} else {

				$criteria->add(W3sContentPeer::PAGE_ID, $this->id);

				W3sContentPeer::addSelectColumns($criteria);
				$this->collW3sContents = W3sContentPeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(W3sContentPeer::PAGE_ID, $this->id);

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
			$criteria = new Criteria(W3sPagePeer::DATABASE_NAME);
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

				$criteria->add(W3sContentPeer::PAGE_ID, $this->id);

				$count = W3sContentPeer::doCount($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(W3sContentPeer::PAGE_ID, $this->id);

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
			$l->setW3sPage($this);
		}
	}


	
	public function getW3sContentsJoinW3sGroup($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sPagePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
				$this->collW3sContents = array();
			} else {

				$criteria->add(W3sContentPeer::PAGE_ID, $this->id);

				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sGroup($criteria, $con, $join_behavior);
			}
		} else {
									
			$criteria->add(W3sContentPeer::PAGE_ID, $this->id);

			if (!isset($this->lastW3sContentCriteria) || !$this->lastW3sContentCriteria->equals($criteria)) {
				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sGroup($criteria, $con, $join_behavior);
			}
		}
		$this->lastW3sContentCriteria = $criteria;

		return $this->collW3sContents;
	}


	
	public function getW3sContentsJoinW3sLanguage($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sPagePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
				$this->collW3sContents = array();
			} else {

				$criteria->add(W3sContentPeer::PAGE_ID, $this->id);

				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sLanguage($criteria, $con, $join_behavior);
			}
		} else {
									
			$criteria->add(W3sContentPeer::PAGE_ID, $this->id);

			if (!isset($this->lastW3sContentCriteria) || !$this->lastW3sContentCriteria->equals($criteria)) {
				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sLanguage($criteria, $con, $join_behavior);
			}
		}
		$this->lastW3sContentCriteria = $criteria;

		return $this->collW3sContents;
	}


	
	public function getW3sContentsJoinW3sContentType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sPagePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
				$this->collW3sContents = array();
			} else {

				$criteria->add(W3sContentPeer::PAGE_ID, $this->id);

				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sContentType($criteria, $con, $join_behavior);
			}
		} else {
									
			$criteria->add(W3sContentPeer::PAGE_ID, $this->id);

			if (!isset($this->lastW3sContentCriteria) || !$this->lastW3sContentCriteria->equals($criteria)) {
				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sContentType($criteria, $con, $join_behavior);
			}
		}
		$this->lastW3sContentCriteria = $criteria;

		return $this->collW3sContents;
	}


	
	public function getW3sContentsJoinW3sSlot($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sPagePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
				$this->collW3sContents = array();
			} else {

				$criteria->add(W3sContentPeer::PAGE_ID, $this->id);

				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sSlot($criteria, $con, $join_behavior);
			}
		} else {
									
			$criteria->add(W3sContentPeer::PAGE_ID, $this->id);

			if (!isset($this->lastW3sContentCriteria) || !$this->lastW3sContentCriteria->equals($criteria)) {
				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sSlot($criteria, $con, $join_behavior);
			}
		}
		$this->lastW3sContentCriteria = $criteria;

		return $this->collW3sContents;
	}

	
	public function clearW3sSearchEngines()
	{
		$this->collW3sSearchEngines = null; 	}

	
	public function initW3sSearchEngines()
	{
		$this->collW3sSearchEngines = array();
	}

	
	public function getW3sSearchEngines($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sPagePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sSearchEngines === null) {
			if ($this->isNew()) {
			   $this->collW3sSearchEngines = array();
			} else {

				$criteria->add(W3sSearchEnginePeer::PAGE_ID, $this->id);

				W3sSearchEnginePeer::addSelectColumns($criteria);
				$this->collW3sSearchEngines = W3sSearchEnginePeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(W3sSearchEnginePeer::PAGE_ID, $this->id);

				W3sSearchEnginePeer::addSelectColumns($criteria);
				if (!isset($this->lastW3sSearchEngineCriteria) || !$this->lastW3sSearchEngineCriteria->equals($criteria)) {
					$this->collW3sSearchEngines = W3sSearchEnginePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastW3sSearchEngineCriteria = $criteria;
		return $this->collW3sSearchEngines;
	}

	
	public function countW3sSearchEngines(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sPagePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collW3sSearchEngines === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(W3sSearchEnginePeer::PAGE_ID, $this->id);

				$count = W3sSearchEnginePeer::doCount($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(W3sSearchEnginePeer::PAGE_ID, $this->id);

				if (!isset($this->lastW3sSearchEngineCriteria) || !$this->lastW3sSearchEngineCriteria->equals($criteria)) {
					$count = W3sSearchEnginePeer::doCount($criteria, $con);
				} else {
					$count = count($this->collW3sSearchEngines);
				}
			} else {
				$count = count($this->collW3sSearchEngines);
			}
		}
		$this->lastW3sSearchEngineCriteria = $criteria;
		return $count;
	}

	
	public function addW3sSearchEngine(W3sSearchEngine $l)
	{
		if ($this->collW3sSearchEngines === null) {
			$this->initW3sSearchEngines();
		}
		if (!in_array($l, $this->collW3sSearchEngines, true)) { 			array_push($this->collW3sSearchEngines, $l);
			$l->setW3sPage($this);
		}
	}


	
	public function getW3sSearchEnginesJoinW3sLanguage($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sPagePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sSearchEngines === null) {
			if ($this->isNew()) {
				$this->collW3sSearchEngines = array();
			} else {

				$criteria->add(W3sSearchEnginePeer::PAGE_ID, $this->id);

				$this->collW3sSearchEngines = W3sSearchEnginePeer::doSelectJoinW3sLanguage($criteria, $con, $join_behavior);
			}
		} else {
									
			$criteria->add(W3sSearchEnginePeer::PAGE_ID, $this->id);

			if (!isset($this->lastW3sSearchEngineCriteria) || !$this->lastW3sSearchEngineCriteria->equals($criteria)) {
				$this->collW3sSearchEngines = W3sSearchEnginePeer::doSelectJoinW3sLanguage($criteria, $con, $join_behavior);
			}
		}
		$this->lastW3sSearchEngineCriteria = $criteria;

		return $this->collW3sSearchEngines;
	}

	
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
			if ($this->collW3sContents) {
				foreach ((array) $this->collW3sContents as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collW3sSearchEngines) {
				foreach ((array) $this->collW3sSearchEngines as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} 
		$this->collW3sContents = null;
		$this->collW3sSearchEngines = null;
			$this->aW3sGroup = null;
	}

} 