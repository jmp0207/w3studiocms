<?php


abstract class BaseW3sGroup extends BaseObject  implements Persistent {


  const PEER = 'W3sGroupPeer';

	
	protected static $peer;

	
	protected $id;

	
	protected $template_id;

	
	protected $group_name;

	
	protected $edited;

	
	protected $to_delete;

	
	protected $aW3sTemplate;

	
	protected $collW3sContents;

	
	private $lastW3sContentCriteria = null;

	
	protected $collW3sPages;

	
	private $lastW3sPageCriteria = null;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	
	public function applyDefaultValues()
	{
		$this->edited = 0;
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

	
	public function getGroupName()
	{
		return $this->group_name;
	}

	
	public function getEdited()
	{
		return $this->edited;
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
			$this->modifiedColumns[] = W3sGroupPeer::ID;
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
			$this->modifiedColumns[] = W3sGroupPeer::TEMPLATE_ID;
		}

		if ($this->aW3sTemplate !== null && $this->aW3sTemplate->getId() !== $v) {
			$this->aW3sTemplate = null;
		}

		return $this;
	} 
	
	public function setGroupName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->group_name !== $v) {
			$this->group_name = $v;
			$this->modifiedColumns[] = W3sGroupPeer::GROUP_NAME;
		}

		return $this;
	} 
	
	public function setEdited($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->edited !== $v || $v === 0) {
			$this->edited = $v;
			$this->modifiedColumns[] = W3sGroupPeer::EDITED;
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
			$this->modifiedColumns[] = W3sGroupPeer::TO_DELETE;
		}

		return $this;
	} 
	
	public function hasOnlyDefaultValues()
	{
						if (array_diff($this->modifiedColumns, array(W3sGroupPeer::EDITED,W3sGroupPeer::TO_DELETE))) {
				return false;
			}

			if ($this->edited !== 0) {
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
			$this->group_name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->edited = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->to_delete = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

						return $startcol + 5; 
		} catch (Exception $e) {
			throw new PropelException("Error populating W3sGroup object", $e);
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
			$con = Propel::getConnection(W3sGroupPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

				
		$stmt = W3sGroupPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
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

			$this->collW3sPages = null;
			$this->lastW3sPageCriteria = null;

		} 	}

	
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(W3sGroupPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			W3sGroupPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(W3sGroupPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
			W3sGroupPeer::addInstanceToPool($this);
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
				$this->modifiedColumns[] = W3sGroupPeer::ID;
			}

						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = W3sGroupPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += W3sGroupPeer::doUpdate($this, $con);
				}

				$this->resetModified(); 			}

			if ($this->collW3sContents !== null) {
				foreach ($this->collW3sContents as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collW3sPages !== null) {
				foreach ($this->collW3sPages as $referrerFK) {
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


			if (($retval = W3sGroupPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collW3sContents !== null) {
					foreach ($this->collW3sContents as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collW3sPages !== null) {
					foreach ($this->collW3sPages as $referrerFK) {
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
		$pos = W3sGroupPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getGroupName();
				break;
			case 3:
				return $this->getEdited();
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
		$keys = W3sGroupPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getTemplateId(),
			$keys[2] => $this->getGroupName(),
			$keys[3] => $this->getEdited(),
			$keys[4] => $this->getToDelete(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = W3sGroupPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setGroupName($value);
				break;
			case 3:
				$this->setEdited($value);
				break;
			case 4:
				$this->setToDelete($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = W3sGroupPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setTemplateId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setGroupName($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setEdited($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setToDelete($arr[$keys[4]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(W3sGroupPeer::DATABASE_NAME);

		if ($this->isColumnModified(W3sGroupPeer::ID)) $criteria->add(W3sGroupPeer::ID, $this->id);
		if ($this->isColumnModified(W3sGroupPeer::TEMPLATE_ID)) $criteria->add(W3sGroupPeer::TEMPLATE_ID, $this->template_id);
		if ($this->isColumnModified(W3sGroupPeer::GROUP_NAME)) $criteria->add(W3sGroupPeer::GROUP_NAME, $this->group_name);
		if ($this->isColumnModified(W3sGroupPeer::EDITED)) $criteria->add(W3sGroupPeer::EDITED, $this->edited);
		if ($this->isColumnModified(W3sGroupPeer::TO_DELETE)) $criteria->add(W3sGroupPeer::TO_DELETE, $this->to_delete);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(W3sGroupPeer::DATABASE_NAME);

		$criteria->add(W3sGroupPeer::ID, $this->id);

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

		$copyObj->setGroupName($this->group_name);

		$copyObj->setEdited($this->edited);

		$copyObj->setToDelete($this->to_delete);


		if ($deepCopy) {
									$copyObj->setNew(false);

			foreach ($this->getW3sContents() as $relObj) {
				if ($relObj !== $this) {  					$copyObj->addW3sContent($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getW3sPages() as $relObj) {
				if ($relObj !== $this) {  					$copyObj->addW3sPage($relObj->copy($deepCopy));
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
			self::$peer = new W3sGroupPeer();
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
			$v->addW3sGroup($this);
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
			$criteria = new Criteria(W3sGroupPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
			   $this->collW3sContents = array();
			} else {

				$criteria->add(W3sContentPeer::GROUP_ID, $this->id);

				W3sContentPeer::addSelectColumns($criteria);
				$this->collW3sContents = W3sContentPeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(W3sContentPeer::GROUP_ID, $this->id);

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
			$criteria = new Criteria(W3sGroupPeer::DATABASE_NAME);
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

				$criteria->add(W3sContentPeer::GROUP_ID, $this->id);

				$count = W3sContentPeer::doCount($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(W3sContentPeer::GROUP_ID, $this->id);

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
			$l->setW3sGroup($this);
		}
	}


	
	public function getW3sContentsJoinW3sPage($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sGroupPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
				$this->collW3sContents = array();
			} else {

				$criteria->add(W3sContentPeer::GROUP_ID, $this->id);

				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sPage($criteria, $con, $join_behavior);
			}
		} else {
									
			$criteria->add(W3sContentPeer::GROUP_ID, $this->id);

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
			$criteria = new Criteria(W3sGroupPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
				$this->collW3sContents = array();
			} else {

				$criteria->add(W3sContentPeer::GROUP_ID, $this->id);

				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sLanguage($criteria, $con, $join_behavior);
			}
		} else {
									
			$criteria->add(W3sContentPeer::GROUP_ID, $this->id);

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
			$criteria = new Criteria(W3sGroupPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
				$this->collW3sContents = array();
			} else {

				$criteria->add(W3sContentPeer::GROUP_ID, $this->id);

				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sContentType($criteria, $con, $join_behavior);
			}
		} else {
									
			$criteria->add(W3sContentPeer::GROUP_ID, $this->id);

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
			$criteria = new Criteria(W3sGroupPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sContents === null) {
			if ($this->isNew()) {
				$this->collW3sContents = array();
			} else {

				$criteria->add(W3sContentPeer::GROUP_ID, $this->id);

				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sSlot($criteria, $con, $join_behavior);
			}
		} else {
									
			$criteria->add(W3sContentPeer::GROUP_ID, $this->id);

			if (!isset($this->lastW3sContentCriteria) || !$this->lastW3sContentCriteria->equals($criteria)) {
				$this->collW3sContents = W3sContentPeer::doSelectJoinW3sSlot($criteria, $con, $join_behavior);
			}
		}
		$this->lastW3sContentCriteria = $criteria;

		return $this->collW3sContents;
	}

	
	public function clearW3sPages()
	{
		$this->collW3sPages = null; 	}

	
	public function initW3sPages()
	{
		$this->collW3sPages = array();
	}

	
	public function getW3sPages($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sGroupPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sPages === null) {
			if ($this->isNew()) {
			   $this->collW3sPages = array();
			} else {

				$criteria->add(W3sPagePeer::GROUP_ID, $this->id);

				W3sPagePeer::addSelectColumns($criteria);
				$this->collW3sPages = W3sPagePeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(W3sPagePeer::GROUP_ID, $this->id);

				W3sPagePeer::addSelectColumns($criteria);
				if (!isset($this->lastW3sPageCriteria) || !$this->lastW3sPageCriteria->equals($criteria)) {
					$this->collW3sPages = W3sPagePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastW3sPageCriteria = $criteria;
		return $this->collW3sPages;
	}

	
	public function countW3sPages(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sGroupPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collW3sPages === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(W3sPagePeer::GROUP_ID, $this->id);

				$count = W3sPagePeer::doCount($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(W3sPagePeer::GROUP_ID, $this->id);

				if (!isset($this->lastW3sPageCriteria) || !$this->lastW3sPageCriteria->equals($criteria)) {
					$count = W3sPagePeer::doCount($criteria, $con);
				} else {
					$count = count($this->collW3sPages);
				}
			} else {
				$count = count($this->collW3sPages);
			}
		}
		$this->lastW3sPageCriteria = $criteria;
		return $count;
	}

	
	public function addW3sPage(W3sPage $l)
	{
		if ($this->collW3sPages === null) {
			$this->initW3sPages();
		}
		if (!in_array($l, $this->collW3sPages, true)) { 			array_push($this->collW3sPages, $l);
			$l->setW3sGroup($this);
		}
	}

	
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
			if ($this->collW3sContents) {
				foreach ((array) $this->collW3sContents as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collW3sPages) {
				foreach ((array) $this->collW3sPages as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} 
		$this->collW3sContents = null;
		$this->collW3sPages = null;
			$this->aW3sTemplate = null;
	}

} 