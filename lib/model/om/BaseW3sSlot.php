<?php


abstract class BaseW3sSlot extends BaseObject  implements Persistent {


  const PEER = 'W3sSlotPeer';

	
	protected static $peer;

	
	protected $id;

	
	protected $template_id;

	
	protected $slot_name;

	
	protected $repeated_contents;

	
	protected $to_delete;

	
	protected $aW3sTemplate;

	
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
		$this->slot_name = 'null';
		$this->to_delete = 0;
	}

	
	public function getId()
	{
		return $this->id;
	}

	
	public function getTemplateId()
	{
		return $this->template_id;
	}

	
	public function getSlotName()
	{
		return $this->slot_name;
	}

	
	public function getRepeatedContents()
	{
		return $this->repeated_contents;
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
			$this->modifiedColumns[] = W3sSlotPeer::ID;
		}

		return $this;
	} 
	
	public function setTemplateId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->template_id !== $v) {
			$this->template_id = $v;
			$this->modifiedColumns[] = W3sSlotPeer::TEMPLATE_ID;
		}

		if ($this->aW3sTemplate !== null && $this->aW3sTemplate->getId() !== $v) {
			$this->aW3sTemplate = null;
		}

		return $this;
	} 
	
	public function setSlotName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->slot_name !== $v || $v === 'null') {
			$this->slot_name = $v;
			$this->modifiedColumns[] = W3sSlotPeer::SLOT_NAME;
		}

		return $this;
	} 
	
	public function setRepeatedContents($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->repeated_contents !== $v) {
			$this->repeated_contents = $v;
			$this->modifiedColumns[] = W3sSlotPeer::REPEATED_CONTENTS;
		}

		return $this;
	} 
	
	public function setToDelete($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->to_delete !== $v || $v === 0) {
			$this->to_delete = $v;
			$this->modifiedColumns[] = W3sSlotPeer::TO_DELETE;
		}

		return $this;
	} 
	
	public function hasOnlyDefaultValues()
	{
						if (array_diff($this->modifiedColumns, array(W3sSlotPeer::SLOT_NAME,W3sSlotPeer::TO_DELETE))) {
				return false;
			}

			if ($this->slot_name !== 'null') {
				return false;
			}

			if ($this->to_delete !== 0) {
				return false;
			}

				return true;
	} 
	
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->template_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->slot_name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->repeated_contents = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->to_delete = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

						return $startcol + 5; 
		} catch (Exception $e) {
			throw new PropelException("Error populating W3sSlot object", $e);
		}
	}

	
	public function ensureConsistency()
	{

		if ($this->aW3sTemplate !== null && $this->template_id !== $this->aW3sTemplate->getId()) {
			$this->aW3sTemplate = null;
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
			$con = Propel::getConnection(W3sSlotPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

				
		$stmt = W3sSlotPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); 
		if ($deep) {  
			$this->aW3sTemplate = null;
			$this->collW3sContents = null;
			$this->lastW3sContentCriteria = null;

		} 	}

	
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(W3sSlotPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			W3sSlotPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(W3sSlotPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
			W3sSlotPeer::addInstanceToPool($this);
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

												
			if ($this->aW3sTemplate !== null) {
				if ($this->aW3sTemplate->isModified() || $this->aW3sTemplate->isNew()) {
					$affectedRows += $this->aW3sTemplate->save($con);
				}
				$this->setW3sTemplate($this->aW3sTemplate);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = W3sSlotPeer::ID;
			}

						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = W3sSlotPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += W3sSlotPeer::doUpdate($this, $con);
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


												
			if ($this->aW3sTemplate !== null) {
				if (!$this->aW3sTemplate->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aW3sTemplate->getValidationFailures());
				}
			}


			if (($retval = W3sSlotPeer::doValidate($this, $columns)) !== true) {
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
		$pos = W3sSlotPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getTemplateId();
				break;
			case 2:
				return $this->getSlotName();
				break;
			case 3:
				return $this->getRepeatedContents();
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
		$keys = W3sSlotPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getTemplateId(),
			$keys[2] => $this->getSlotName(),
			$keys[3] => $this->getRepeatedContents(),
			$keys[4] => $this->getToDelete(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = W3sSlotPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setTemplateId($value);
				break;
			case 2:
				$this->setSlotName($value);
				break;
			case 3:
				$this->setRepeatedContents($value);
				break;
			case 4:
				$this->setToDelete($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = W3sSlotPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setTemplateId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setSlotName($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setRepeatedContents($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setToDelete($arr[$keys[4]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(W3sSlotPeer::DATABASE_NAME);

		if ($this->isColumnModified(W3sSlotPeer::ID)) $criteria->add(W3sSlotPeer::ID, $this->id);
		if ($this->isColumnModified(W3sSlotPeer::TEMPLATE_ID)) $criteria->add(W3sSlotPeer::TEMPLATE_ID, $this->template_id);
		if ($this->isColumnModified(W3sSlotPeer::SLOT_NAME)) $criteria->add(W3sSlotPeer::SLOT_NAME, $this->slot_name);
		if ($this->isColumnModified(W3sSlotPeer::REPEATED_CONTENTS)) $criteria->add(W3sSlotPeer::REPEATED_CONTENTS, $this->repeated_contents);
		if ($this->isColumnModified(W3sSlotPeer::TO_DELETE)) $criteria->add(W3sSlotPeer::TO_DELETE, $this->to_delete);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(W3sSlotPeer::DATABASE_NAME);

		$criteria->add(W3sSlotPeer::ID, $this->id);

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

		$copyObj->setTemplateId($this->template_id);

		$copyObj->setSlotName($this->slot_name);

		$copyObj->setRepeatedContents($this->repeated_contents);

		$copyObj->setToDelete($this->to_delete);


		if ($deepCopy) {
									$copyObj->setNew(false);

			foreach ($this->getW3sContents() as $relObj) {
				if ($relObj !== $this) {  					$copyObj->addW3sContent($relObj->copy($deepCopy));
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
			self::$peer = new W3sSlotPeer();
		}
		return self::$peer;
	}

	
	public function setW3sTemplate(W3sTemplate $v = null)
	{
		if ($v === null) {
			$this->setTemplateId(NULL);
		} else {
			$this->setTemplateId($v->getId());
		}

		$this->aW3sTemplate = $v;

						if ($v !== null) {
			$v->addW3sSlot($this);
		}

		return $this;
	}


	
	public function getW3sTemplate(PropelPDO $con = null)
	{
		if ($this->aW3sTemplate === null && ($this->template_id !== null)) {
			$c = new Criteria(W3sTemplatePeer::DATABASE_NAME);
			$c->add(W3sTemplatePeer::ID, $this->template_id);
			$this->aW3sTemplate = W3sTemplatePeer::doSelectOne($c, $con);
			
		}
		return $this->aW3sTemplate;
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
			$criteria = new Criteria(W3sSlotPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
			   $this->collW3sContents = array();
			} else {

				$criteria->add(W3sContentPeer::SLOT_ID, $this->id);

				W3sContentPeer::addSelectColumns($criteria);
				$this->collW3sContents = W3sContentPeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(W3sContentPeer::SLOT_ID, $this->id);

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
			$criteria = new Criteria(W3sSlotPeer::DATABASE_NAME);
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

				$criteria->add(W3sContentPeer::SLOT_ID, $this->id);

				$count = W3sContentPeer::doCount($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(W3sContentPeer::SLOT_ID, $this->id);

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
			$l->setW3sSlot($this);
		}
	}


	
	public function getW3sContentsJoinW3sGroup($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sSlotPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
				$this->collW3sContents = array();
			} else {

				$criteria->add(W3sContentPeer::SLOT_ID, $this->id);

				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sGroup($criteria, $con, $join_behavior);
			}
		} else {
									
			$criteria->add(W3sContentPeer::SLOT_ID, $this->id);

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
			$criteria = new Criteria(W3sSlotPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
				$this->collW3sContents = array();
			} else {

				$criteria->add(W3sContentPeer::SLOT_ID, $this->id);

				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sPage($criteria, $con, $join_behavior);
			}
		} else {
									
			$criteria->add(W3sContentPeer::SLOT_ID, $this->id);

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
			$criteria = new Criteria(W3sSlotPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
				$this->collW3sContents = array();
			} else {

				$criteria->add(W3sContentPeer::SLOT_ID, $this->id);

				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sLanguage($criteria, $con, $join_behavior);
			}
		} else {
									
			$criteria->add(W3sContentPeer::SLOT_ID, $this->id);

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
			$criteria = new Criteria(W3sSlotPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
				$this->collW3sContents = array();
			} else {

				$criteria->add(W3sContentPeer::SLOT_ID, $this->id);

				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sContentType($criteria, $con, $join_behavior);
			}
		} else {
									
			$criteria->add(W3sContentPeer::SLOT_ID, $this->id);

			if (!isset($this->lastW3sContentCriteria) || !$this->lastW3sContentCriteria->equals($criteria)) {
				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sContentType($criteria, $con, $join_behavior);
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
			$this->aW3sTemplate = null;
	}

} 