<?php


abstract class BaseW3sProject extends BaseObject  implements Persistent {


  const PEER = 'W3sProjectPeer';

	
	protected static $peer;

	
	protected $id;

	
	protected $project_name;

	
	protected $collW3sTemplates;

	
	private $lastW3sTemplateCriteria = null;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	
	public function applyDefaultValues()
	{
		$this->project_name = 'null';
	}

	
	public function getId()
	{
		return $this->id;
	}

	
	public function getProjectName()
	{
		return $this->project_name;
	}

	
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = W3sProjectPeer::ID;
		}

		return $this;
	} 
	
	public function setProjectName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->project_name !== $v || $v === 'null') {
			$this->project_name = $v;
			$this->modifiedColumns[] = W3sProjectPeer::PROJECT_NAME;
		}

		return $this;
	} 
	
	public function hasOnlyDefaultValues()
	{
						if (array_diff($this->modifiedColumns, array(W3sProjectPeer::PROJECT_NAME))) {
				return false;
			}

			if ($this->project_name !== 'null') {
				return false;
			}

				return true;
	} 
	
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->project_name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

						return $startcol + 2; 
		} catch (Exception $e) {
			throw new PropelException("Error populating W3sProject object", $e);
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
			$con = Propel::getConnection(W3sProjectPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

				
		$stmt = W3sProjectPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); 
		if ($deep) {  
			$this->collW3sTemplates = null;
			$this->lastW3sTemplateCriteria = null;

		} 	}

	
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(W3sProjectPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			W3sProjectPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(W3sProjectPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
			W3sProjectPeer::addInstanceToPool($this);
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

			if ($this->isNew() ) {
				$this->modifiedColumns[] = W3sProjectPeer::ID;
			}

						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = W3sProjectPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += W3sProjectPeer::doUpdate($this, $con);
				}

				$this->resetModified(); 			}

			if ($this->collW3sTemplates !== null) {
				foreach ($this->collW3sTemplates as $referrerFK) {
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


			if (($retval = W3sProjectPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collW3sTemplates !== null) {
					foreach ($this->collW3sTemplates as $referrerFK) {
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
		$pos = W3sProjectPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getProjectName();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true)
	{
		$keys = W3sProjectPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getProjectName(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = W3sProjectPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setProjectName($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = W3sProjectPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setProjectName($arr[$keys[1]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(W3sProjectPeer::DATABASE_NAME);

		if ($this->isColumnModified(W3sProjectPeer::ID)) $criteria->add(W3sProjectPeer::ID, $this->id);
		if ($this->isColumnModified(W3sProjectPeer::PROJECT_NAME)) $criteria->add(W3sProjectPeer::PROJECT_NAME, $this->project_name);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(W3sProjectPeer::DATABASE_NAME);

		$criteria->add(W3sProjectPeer::ID, $this->id);

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

		$copyObj->setProjectName($this->project_name);


		if ($deepCopy) {
									$copyObj->setNew(false);

			foreach ($this->getW3sTemplates() as $relObj) {
				if ($relObj !== $this) {  					$copyObj->addW3sTemplate($relObj->copy($deepCopy));
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
			self::$peer = new W3sProjectPeer();
		}
		return self::$peer;
	}

	
	public function clearW3sTemplates()
	{
		$this->collW3sTemplates = null; 	}

	
	public function initW3sTemplates()
	{
		$this->collW3sTemplates = array();
	}

	
	public function getW3sTemplates($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sProjectPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sTemplates === null) {
			if ($this->isNew()) {
			   $this->collW3sTemplates = array();
			} else {

				$criteria->add(W3sTemplatePeer::PROJECT_ID, $this->id);

				W3sTemplatePeer::addSelectColumns($criteria);
				$this->collW3sTemplates = W3sTemplatePeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(W3sTemplatePeer::PROJECT_ID, $this->id);

				W3sTemplatePeer::addSelectColumns($criteria);
				if (!isset($this->lastW3sTemplateCriteria) || !$this->lastW3sTemplateCriteria->equals($criteria)) {
					$this->collW3sTemplates = W3sTemplatePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastW3sTemplateCriteria = $criteria;
		return $this->collW3sTemplates;
	}

	
	public function countW3sTemplates(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sProjectPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collW3sTemplates === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(W3sTemplatePeer::PROJECT_ID, $this->id);

				$count = W3sTemplatePeer::doCount($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(W3sTemplatePeer::PROJECT_ID, $this->id);

				if (!isset($this->lastW3sTemplateCriteria) || !$this->lastW3sTemplateCriteria->equals($criteria)) {
					$count = W3sTemplatePeer::doCount($criteria, $con);
				} else {
					$count = count($this->collW3sTemplates);
				}
			} else {
				$count = count($this->collW3sTemplates);
			}
		}
		$this->lastW3sTemplateCriteria = $criteria;
		return $count;
	}

	
	public function addW3sTemplate(W3sTemplate $l)
	{
		if ($this->collW3sTemplates === null) {
			$this->initW3sTemplates();
		}
		if (!in_array($l, $this->collW3sTemplates, true)) { 			array_push($this->collW3sTemplates, $l);
			$l->setW3sProject($this);
		}
	}

	
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
			if ($this->collW3sTemplates) {
				foreach ((array) $this->collW3sTemplates as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} 
		$this->collW3sTemplates = null;
	}

} 