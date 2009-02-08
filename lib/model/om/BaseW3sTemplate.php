<?php


abstract class BaseW3sTemplate extends BaseObject  implements Persistent {


  const PEER = 'W3sTemplatePeer';

	
	protected static $peer;

	
	protected $id;

	
	protected $project_id;

	
	protected $template_name;

	
	protected $aW3sProject;

	
	protected $collW3sGroups;

	
	private $lastW3sGroupCriteria = null;

	
	protected $collW3sSlots;

	
	private $lastW3sSlotCriteria = null;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	
	public function applyDefaultValues()
	{
		$this->project_id = 0;
		$this->template_name = 'null';
	}

	
	public function getId()
	{
		return $this->id;
	}

	
	public function getProjectId()
	{
		return $this->project_id;
	}

	
	public function getTemplateName()
	{
		return $this->template_name;
	}

	
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = W3sTemplatePeer::ID;
		}

		return $this;
	} 
	
	public function setProjectId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->project_id !== $v || $v === 0) {
			$this->project_id = $v;
			$this->modifiedColumns[] = W3sTemplatePeer::PROJECT_ID;
		}

		if ($this->aW3sProject !== null && $this->aW3sProject->getId() !== $v) {
			$this->aW3sProject = null;
		}

		return $this;
	} 
	
	public function setTemplateName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->template_name !== $v || $v === 'null') {
			$this->template_name = $v;
			$this->modifiedColumns[] = W3sTemplatePeer::TEMPLATE_NAME;
		}

		return $this;
	} 
	
	public function hasOnlyDefaultValues()
	{
						if (array_diff($this->modifiedColumns, array(W3sTemplatePeer::PROJECT_ID,W3sTemplatePeer::TEMPLATE_NAME))) {
				return false;
			}

			if ($this->project_id !== 0) {
				return false;
			}

			if ($this->template_name !== 'null') {
				return false;
			}

				return true;
	} 
	
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->project_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->template_name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

						return $startcol + 3; 
		} catch (Exception $e) {
			throw new PropelException("Error populating W3sTemplate object", $e);
		}
	}

	
	public function ensureConsistency()
	{

		if ($this->aW3sProject !== null && $this->project_id !== $this->aW3sProject->getId()) {
			$this->aW3sProject = null;
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
			$con = Propel::getConnection(W3sTemplatePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

				
		$stmt = W3sTemplatePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); 
		if ($deep) {  
			$this->aW3sProject = null;
			$this->collW3sGroups = null;
			$this->lastW3sGroupCriteria = null;

			$this->collW3sSlots = null;
			$this->lastW3sSlotCriteria = null;

		} 	}

	
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(W3sTemplatePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			W3sTemplatePeer::doDelete($this, $con);
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
			$con = Propel::getConnection(W3sTemplatePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
			W3sTemplatePeer::addInstanceToPool($this);
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

												
			if ($this->aW3sProject !== null) {
				if ($this->aW3sProject->isModified() || $this->aW3sProject->isNew()) {
					$affectedRows += $this->aW3sProject->save($con);
				}
				$this->setW3sProject($this->aW3sProject);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = W3sTemplatePeer::ID;
			}

						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = W3sTemplatePeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += W3sTemplatePeer::doUpdate($this, $con);
				}

				$this->resetModified(); 			}

			if ($this->collW3sGroups !== null) {
				foreach ($this->collW3sGroups as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collW3sSlots !== null) {
				foreach ($this->collW3sSlots as $referrerFK) {
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


												
			if ($this->aW3sProject !== null) {
				if (!$this->aW3sProject->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aW3sProject->getValidationFailures());
				}
			}


			if (($retval = W3sTemplatePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collW3sGroups !== null) {
					foreach ($this->collW3sGroups as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collW3sSlots !== null) {
					foreach ($this->collW3sSlots as $referrerFK) {
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
		$pos = W3sTemplatePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getProjectId();
				break;
			case 2:
				return $this->getTemplateName();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true)
	{
		$keys = W3sTemplatePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getProjectId(),
			$keys[2] => $this->getTemplateName(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = W3sTemplatePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setProjectId($value);
				break;
			case 2:
				$this->setTemplateName($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = W3sTemplatePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setProjectId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setTemplateName($arr[$keys[2]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(W3sTemplatePeer::DATABASE_NAME);

		if ($this->isColumnModified(W3sTemplatePeer::ID)) $criteria->add(W3sTemplatePeer::ID, $this->id);
		if ($this->isColumnModified(W3sTemplatePeer::PROJECT_ID)) $criteria->add(W3sTemplatePeer::PROJECT_ID, $this->project_id);
		if ($this->isColumnModified(W3sTemplatePeer::TEMPLATE_NAME)) $criteria->add(W3sTemplatePeer::TEMPLATE_NAME, $this->template_name);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(W3sTemplatePeer::DATABASE_NAME);

		$criteria->add(W3sTemplatePeer::ID, $this->id);

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

		$copyObj->setProjectId($this->project_id);

		$copyObj->setTemplateName($this->template_name);


		if ($deepCopy) {
									$copyObj->setNew(false);

			foreach ($this->getW3sGroups() as $relObj) {
				if ($relObj !== $this) {  					$copyObj->addW3sGroup($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getW3sSlots() as $relObj) {
				if ($relObj !== $this) {  					$copyObj->addW3sSlot($relObj->copy($deepCopy));
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
			self::$peer = new W3sTemplatePeer();
		}
		return self::$peer;
	}

	
	public function setW3sProject(W3sProject $v = null)
	{
		if ($v === null) {
			$this->setProjectId(0);
		} else {
			$this->setProjectId($v->getId());
		}

		$this->aW3sProject = $v;

						if ($v !== null) {
			$v->addW3sTemplate($this);
		}

		return $this;
	}


	
	public function getW3sProject(PropelPDO $con = null)
	{
		if ($this->aW3sProject === null && ($this->project_id !== null)) {
			$c = new Criteria(W3sProjectPeer::DATABASE_NAME);
			$c->add(W3sProjectPeer::ID, $this->project_id);
			$this->aW3sProject = W3sProjectPeer::doSelectOne($c, $con);
			
		}
		return $this->aW3sProject;
	}

	
	public function clearW3sGroups()
	{
		$this->collW3sGroups = null; 	}

	
	public function initW3sGroups()
	{
		$this->collW3sGroups = array();
	}

	
	public function getW3sGroups($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sTemplatePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sGroups === null) {
			if ($this->isNew()) {
			   $this->collW3sGroups = array();
			} else {

				$criteria->add(W3sGroupPeer::TEMPLATE_ID, $this->id);

				W3sGroupPeer::addSelectColumns($criteria);
				$this->collW3sGroups = W3sGroupPeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(W3sGroupPeer::TEMPLATE_ID, $this->id);

				W3sGroupPeer::addSelectColumns($criteria);
				if (!isset($this->lastW3sGroupCriteria) || !$this->lastW3sGroupCriteria->equals($criteria)) {
					$this->collW3sGroups = W3sGroupPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastW3sGroupCriteria = $criteria;
		return $this->collW3sGroups;
	}

	
	public function countW3sGroups(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sTemplatePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collW3sGroups === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(W3sGroupPeer::TEMPLATE_ID, $this->id);

				$count = W3sGroupPeer::doCount($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(W3sGroupPeer::TEMPLATE_ID, $this->id);

				if (!isset($this->lastW3sGroupCriteria) || !$this->lastW3sGroupCriteria->equals($criteria)) {
					$count = W3sGroupPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collW3sGroups);
				}
			} else {
				$count = count($this->collW3sGroups);
			}
		}
		$this->lastW3sGroupCriteria = $criteria;
		return $count;
	}

	
	public function addW3sGroup(W3sGroup $l)
	{
		if ($this->collW3sGroups === null) {
			$this->initW3sGroups();
		}
		if (!in_array($l, $this->collW3sGroups, true)) { 			array_push($this->collW3sGroups, $l);
			$l->setW3sTemplate($this);
		}
	}

	
	public function clearW3sSlots()
	{
		$this->collW3sSlots = null; 	}

	
	public function initW3sSlots()
	{
		$this->collW3sSlots = array();
	}

	
	public function getW3sSlots($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sTemplatePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sSlots === null) {
			if ($this->isNew()) {
			   $this->collW3sSlots = array();
			} else {

				$criteria->add(W3sSlotPeer::TEMPLATE_ID, $this->id);

				W3sSlotPeer::addSelectColumns($criteria);
				$this->collW3sSlots = W3sSlotPeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(W3sSlotPeer::TEMPLATE_ID, $this->id);

				W3sSlotPeer::addSelectColumns($criteria);
				if (!isset($this->lastW3sSlotCriteria) || !$this->lastW3sSlotCriteria->equals($criteria)) {
					$this->collW3sSlots = W3sSlotPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastW3sSlotCriteria = $criteria;
		return $this->collW3sSlots;
	}

	
	public function countW3sSlots(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sTemplatePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collW3sSlots === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(W3sSlotPeer::TEMPLATE_ID, $this->id);

				$count = W3sSlotPeer::doCount($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(W3sSlotPeer::TEMPLATE_ID, $this->id);

				if (!isset($this->lastW3sSlotCriteria) || !$this->lastW3sSlotCriteria->equals($criteria)) {
					$count = W3sSlotPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collW3sSlots);
				}
			} else {
				$count = count($this->collW3sSlots);
			}
		}
		$this->lastW3sSlotCriteria = $criteria;
		return $count;
	}

	
	public function addW3sSlot(W3sSlot $l)
	{
		if ($this->collW3sSlots === null) {
			$this->initW3sSlots();
		}
		if (!in_array($l, $this->collW3sSlots, true)) { 			array_push($this->collW3sSlots, $l);
			$l->setW3sTemplate($this);
		}
	}

	
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
			if ($this->collW3sGroups) {
				foreach ((array) $this->collW3sGroups as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collW3sSlots) {
				foreach ((array) $this->collW3sSlots as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} 
		$this->collW3sGroups = null;
		$this->collW3sSlots = null;
			$this->aW3sProject = null;
	}

} 