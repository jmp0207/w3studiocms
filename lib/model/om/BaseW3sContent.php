<?php


abstract class BaseW3sContent extends BaseObject  implements Persistent {


  const PEER = 'W3sContentPeer';

	
	protected static $peer;

	
	protected $id;

	
	protected $group_id;

	
	protected $page_id;

	
	protected $language_id;

	
	protected $content_type_id;

	
	protected $slot_id;

	
	protected $content;

	
	protected $edited;

	
	protected $to_delete;

	
	protected $content_position;

	
	protected $aW3sGroup;

	
	protected $aW3sPage;

	
	protected $aW3sLanguage;

	
	protected $aW3sContentType;

	
	protected $aW3sSlot;

	
	protected $collW3sMenuElements;

	
	private $lastW3sMenuElementCriteria = null;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	
	public function applyDefaultValues()
	{
		$this->content_type_id = 1;
		$this->edited = 0;
		$this->to_delete = 0;
		$this->content_position = 1;
	}

	
	public function getId()
	{
		return $this->id;
	}

	
	public function getGroupId()
	{
		return $this->group_id;
	}

	
	public function getPageId()
	{
		return $this->page_id;
	}

	
	public function getLanguageId()
	{
		return $this->language_id;
	}

	
	public function getContentTypeId()
	{
		return $this->content_type_id;
	}

	
	public function getSlotId()
	{
		return $this->slot_id;
	}

	
	public function getContent()
	{
		return $this->content;
	}

	
	public function getEdited()
	{
		return $this->edited;
	}

	
	public function getToDelete()
	{
		return $this->to_delete;
	}

	
	public function getContentPosition()
	{
		return $this->content_position;
	}

	
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = W3sContentPeer::ID;
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
			$this->modifiedColumns[] = W3sContentPeer::GROUP_ID;
		}

		if ($this->aW3sGroup !== null && $this->aW3sGroup->getId() !== $v) {
			$this->aW3sGroup = null;
		}

		return $this;
	} 
	
	public function setPageId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->page_id !== $v) {
			$this->page_id = $v;
			$this->modifiedColumns[] = W3sContentPeer::PAGE_ID;
		}

		if ($this->aW3sPage !== null && $this->aW3sPage->getId() !== $v) {
			$this->aW3sPage = null;
		}

		return $this;
	} 
	
	public function setLanguageId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->language_id !== $v) {
			$this->language_id = $v;
			$this->modifiedColumns[] = W3sContentPeer::LANGUAGE_ID;
		}

		if ($this->aW3sLanguage !== null && $this->aW3sLanguage->getId() !== $v) {
			$this->aW3sLanguage = null;
		}

		return $this;
	} 
	
	public function setContentTypeId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->content_type_id !== $v || $v === 1) {
			$this->content_type_id = $v;
			$this->modifiedColumns[] = W3sContentPeer::CONTENT_TYPE_ID;
		}

		if ($this->aW3sContentType !== null && $this->aW3sContentType->getId() !== $v) {
			$this->aW3sContentType = null;
		}

		return $this;
	} 
	
	public function setSlotId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->slot_id !== $v) {
			$this->slot_id = $v;
			$this->modifiedColumns[] = W3sContentPeer::SLOT_ID;
		}

		if ($this->aW3sSlot !== null && $this->aW3sSlot->getId() !== $v) {
			$this->aW3sSlot = null;
		}

		return $this;
	} 
	
	public function setContent($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->content !== $v) {
			$this->content = $v;
			$this->modifiedColumns[] = W3sContentPeer::CONTENT;
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
			$this->modifiedColumns[] = W3sContentPeer::EDITED;
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
			$this->modifiedColumns[] = W3sContentPeer::TO_DELETE;
		}

		return $this;
	} 
	
	public function setContentPosition($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->content_position !== $v || $v === 1) {
			$this->content_position = $v;
			$this->modifiedColumns[] = W3sContentPeer::CONTENT_POSITION;
		}

		return $this;
	} 
	
	public function hasOnlyDefaultValues()
	{
						if (array_diff($this->modifiedColumns, array(W3sContentPeer::CONTENT_TYPE_ID,W3sContentPeer::EDITED,W3sContentPeer::TO_DELETE,W3sContentPeer::CONTENT_POSITION))) {
				return false;
			}

			if ($this->content_type_id !== 1) {
				return false;
			}

			if ($this->edited !== 0) {
				return false;
			}

			if ($this->to_delete !== 0) {
				return false;
			}

			if ($this->content_position !== 1) {
				return false;
			}

				return true;
	} 
	
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->group_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->page_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->language_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->content_type_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->slot_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
			$this->content = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->edited = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
			$this->to_delete = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
			$this->content_position = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

						return $startcol + 10; 
		} catch (Exception $e) {
			throw new PropelException("Error populating W3sContent object", $e);
		}
	}

	
	public function ensureConsistency()
	{

		if ($this->aW3sGroup !== null && $this->group_id !== $this->aW3sGroup->getId()) {
			$this->aW3sGroup = null;
		}
		if ($this->aW3sPage !== null && $this->page_id !== $this->aW3sPage->getId()) {
			$this->aW3sPage = null;
		}
		if ($this->aW3sLanguage !== null && $this->language_id !== $this->aW3sLanguage->getId()) {
			$this->aW3sLanguage = null;
		}
		if ($this->aW3sContentType !== null && $this->content_type_id !== $this->aW3sContentType->getId()) {
			$this->aW3sContentType = null;
		}
		if ($this->aW3sSlot !== null && $this->slot_id !== $this->aW3sSlot->getId()) {
			$this->aW3sSlot = null;
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
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

				
		$stmt = W3sContentPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); 
		if ($deep) {  
			$this->aW3sGroup = null;
			$this->aW3sPage = null;
			$this->aW3sLanguage = null;
			$this->aW3sContentType = null;
			$this->aW3sSlot = null;
			$this->collW3sMenuElements = null;
			$this->lastW3sMenuElementCriteria = null;

		} 	}

	
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			W3sContentPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(W3sContentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
			W3sContentPeer::addInstanceToPool($this);
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

			if ($this->aW3sPage !== null) {
				if ($this->aW3sPage->isModified() || $this->aW3sPage->isNew()) {
					$affectedRows += $this->aW3sPage->save($con);
				}
				$this->setW3sPage($this->aW3sPage);
			}

			if ($this->aW3sLanguage !== null) {
				if ($this->aW3sLanguage->isModified() || $this->aW3sLanguage->isNew()) {
					$affectedRows += $this->aW3sLanguage->save($con);
				}
				$this->setW3sLanguage($this->aW3sLanguage);
			}

			if ($this->aW3sContentType !== null) {
				if ($this->aW3sContentType->isModified() || $this->aW3sContentType->isNew()) {
					$affectedRows += $this->aW3sContentType->save($con);
				}
				$this->setW3sContentType($this->aW3sContentType);
			}

			if ($this->aW3sSlot !== null) {
				if ($this->aW3sSlot->isModified() || $this->aW3sSlot->isNew()) {
					$affectedRows += $this->aW3sSlot->save($con);
				}
				$this->setW3sSlot($this->aW3sSlot);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = W3sContentPeer::ID;
			}

						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = W3sContentPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += W3sContentPeer::doUpdate($this, $con);
				}

				$this->resetModified(); 			}

			if ($this->collW3sMenuElements !== null) {
				foreach ($this->collW3sMenuElements as $referrerFK) {
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

			if ($this->aW3sPage !== null) {
				if (!$this->aW3sPage->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aW3sPage->getValidationFailures());
				}
			}

			if ($this->aW3sLanguage !== null) {
				if (!$this->aW3sLanguage->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aW3sLanguage->getValidationFailures());
				}
			}

			if ($this->aW3sContentType !== null) {
				if (!$this->aW3sContentType->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aW3sContentType->getValidationFailures());
				}
			}

			if ($this->aW3sSlot !== null) {
				if (!$this->aW3sSlot->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aW3sSlot->getValidationFailures());
				}
			}


			if (($retval = W3sContentPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collW3sMenuElements !== null) {
					foreach ($this->collW3sMenuElements as $referrerFK) {
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
		$pos = W3sContentPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getPageId();
				break;
			case 3:
				return $this->getLanguageId();
				break;
			case 4:
				return $this->getContentTypeId();
				break;
			case 5:
				return $this->getSlotId();
				break;
			case 6:
				return $this->getContent();
				break;
			case 7:
				return $this->getEdited();
				break;
			case 8:
				return $this->getToDelete();
				break;
			case 9:
				return $this->getContentPosition();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true)
	{
		$keys = W3sContentPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getGroupId(),
			$keys[2] => $this->getPageId(),
			$keys[3] => $this->getLanguageId(),
			$keys[4] => $this->getContentTypeId(),
			$keys[5] => $this->getSlotId(),
			$keys[6] => $this->getContent(),
			$keys[7] => $this->getEdited(),
			$keys[8] => $this->getToDelete(),
			$keys[9] => $this->getContentPosition(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = W3sContentPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setPageId($value);
				break;
			case 3:
				$this->setLanguageId($value);
				break;
			case 4:
				$this->setContentTypeId($value);
				break;
			case 5:
				$this->setSlotId($value);
				break;
			case 6:
				$this->setContent($value);
				break;
			case 7:
				$this->setEdited($value);
				break;
			case 8:
				$this->setToDelete($value);
				break;
			case 9:
				$this->setContentPosition($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = W3sContentPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setGroupId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setPageId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setLanguageId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setContentTypeId($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setSlotId($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setContent($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setEdited($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setToDelete($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setContentPosition($arr[$keys[9]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(W3sContentPeer::DATABASE_NAME);

		if ($this->isColumnModified(W3sContentPeer::ID)) $criteria->add(W3sContentPeer::ID, $this->id);
		if ($this->isColumnModified(W3sContentPeer::GROUP_ID)) $criteria->add(W3sContentPeer::GROUP_ID, $this->group_id);
		if ($this->isColumnModified(W3sContentPeer::PAGE_ID)) $criteria->add(W3sContentPeer::PAGE_ID, $this->page_id);
		if ($this->isColumnModified(W3sContentPeer::LANGUAGE_ID)) $criteria->add(W3sContentPeer::LANGUAGE_ID, $this->language_id);
		if ($this->isColumnModified(W3sContentPeer::CONTENT_TYPE_ID)) $criteria->add(W3sContentPeer::CONTENT_TYPE_ID, $this->content_type_id);
		if ($this->isColumnModified(W3sContentPeer::SLOT_ID)) $criteria->add(W3sContentPeer::SLOT_ID, $this->slot_id);
		if ($this->isColumnModified(W3sContentPeer::CONTENT)) $criteria->add(W3sContentPeer::CONTENT, $this->content);
		if ($this->isColumnModified(W3sContentPeer::EDITED)) $criteria->add(W3sContentPeer::EDITED, $this->edited);
		if ($this->isColumnModified(W3sContentPeer::TO_DELETE)) $criteria->add(W3sContentPeer::TO_DELETE, $this->to_delete);
		if ($this->isColumnModified(W3sContentPeer::CONTENT_POSITION)) $criteria->add(W3sContentPeer::CONTENT_POSITION, $this->content_position);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(W3sContentPeer::DATABASE_NAME);

		$criteria->add(W3sContentPeer::ID, $this->id);

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

		$copyObj->setPageId($this->page_id);

		$copyObj->setLanguageId($this->language_id);

		$copyObj->setContentTypeId($this->content_type_id);

		$copyObj->setSlotId($this->slot_id);

		$copyObj->setContent($this->content);

		$copyObj->setEdited($this->edited);

		$copyObj->setToDelete($this->to_delete);

		$copyObj->setContentPosition($this->content_position);


		if ($deepCopy) {
									$copyObj->setNew(false);

			foreach ($this->getW3sMenuElements() as $relObj) {
				if ($relObj !== $this) {  					$copyObj->addW3sMenuElement($relObj->copy($deepCopy));
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
			self::$peer = new W3sContentPeer();
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
			$v->addW3sContent($this);
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

	
	public function setW3sPage(W3sPage $v = null)
	{
		if ($v === null) {
			$this->setPageId(NULL);
		} else {
			$this->setPageId($v->getId());
		}

		$this->aW3sPage = $v;

						if ($v !== null) {
			$v->addW3sContent($this);
		}

		return $this;
	}


	
	public function getW3sPage(PropelPDO $con = null)
	{
		if ($this->aW3sPage === null && ($this->page_id !== null)) {
			$c = new Criteria(W3sPagePeer::DATABASE_NAME);
			$c->add(W3sPagePeer::ID, $this->page_id);
			$this->aW3sPage = W3sPagePeer::doSelectOne($c, $con);
			
		}
		return $this->aW3sPage;
	}

	
	public function setW3sLanguage(W3sLanguage $v = null)
	{
		if ($v === null) {
			$this->setLanguageId(NULL);
		} else {
			$this->setLanguageId($v->getId());
		}

		$this->aW3sLanguage = $v;

						if ($v !== null) {
			$v->addW3sContent($this);
		}

		return $this;
	}


	
	public function getW3sLanguage(PropelPDO $con = null)
	{
		if ($this->aW3sLanguage === null && ($this->language_id !== null)) {
			$c = new Criteria(W3sLanguagePeer::DATABASE_NAME);
			$c->add(W3sLanguagePeer::ID, $this->language_id);
			$this->aW3sLanguage = W3sLanguagePeer::doSelectOne($c, $con);
			
		}
		return $this->aW3sLanguage;
	}

	
	public function setW3sContentType(W3sContentType $v = null)
	{
		if ($v === null) {
			$this->setContentTypeId(1);
		} else {
			$this->setContentTypeId($v->getId());
		}

		$this->aW3sContentType = $v;

						if ($v !== null) {
			$v->addW3sContent($this);
		}

		return $this;
	}


	
	public function getW3sContentType(PropelPDO $con = null)
	{
		if ($this->aW3sContentType === null && ($this->content_type_id !== null)) {
			$c = new Criteria(W3sContentTypePeer::DATABASE_NAME);
			$c->add(W3sContentTypePeer::ID, $this->content_type_id);
			$this->aW3sContentType = W3sContentTypePeer::doSelectOne($c, $con);
			
		}
		return $this->aW3sContentType;
	}

	
	public function setW3sSlot(W3sSlot $v = null)
	{
		if ($v === null) {
			$this->setSlotId(NULL);
		} else {
			$this->setSlotId($v->getId());
		}

		$this->aW3sSlot = $v;

						if ($v !== null) {
			$v->addW3sContent($this);
		}

		return $this;
	}


	
	public function getW3sSlot(PropelPDO $con = null)
	{
		if ($this->aW3sSlot === null && ($this->slot_id !== null)) {
			$c = new Criteria(W3sSlotPeer::DATABASE_NAME);
			$c->add(W3sSlotPeer::ID, $this->slot_id);
			$this->aW3sSlot = W3sSlotPeer::doSelectOne($c, $con);
			
		}
		return $this->aW3sSlot;
	}

	
	public function clearW3sMenuElements()
	{
		$this->collW3sMenuElements = null; 	}

	
	public function initW3sMenuElements()
	{
		$this->collW3sMenuElements = array();
	}

	
	public function getW3sMenuElements($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sContentPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collW3sMenuElements === null) {
			if ($this->isNew()) {
			   $this->collW3sMenuElements = array();
			} else {

				$criteria->add(W3sMenuElementPeer::CONTENT_ID, $this->id);

				W3sMenuElementPeer::addSelectColumns($criteria);
				$this->collW3sMenuElements = W3sMenuElementPeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(W3sMenuElementPeer::CONTENT_ID, $this->id);

				W3sMenuElementPeer::addSelectColumns($criteria);
				if (!isset($this->lastW3sMenuElementCriteria) || !$this->lastW3sMenuElementCriteria->equals($criteria)) {
					$this->collW3sMenuElements = W3sMenuElementPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastW3sMenuElementCriteria = $criteria;
		return $this->collW3sMenuElements;
	}

	
	public function countW3sMenuElements(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(W3sContentPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collW3sMenuElements === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(W3sMenuElementPeer::CONTENT_ID, $this->id);

				$count = W3sMenuElementPeer::doCount($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(W3sMenuElementPeer::CONTENT_ID, $this->id);

				if (!isset($this->lastW3sMenuElementCriteria) || !$this->lastW3sMenuElementCriteria->equals($criteria)) {
					$count = W3sMenuElementPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collW3sMenuElements);
				}
			} else {
				$count = count($this->collW3sMenuElements);
			}
		}
		$this->lastW3sMenuElementCriteria = $criteria;
		return $count;
	}

	
	public function addW3sMenuElement(W3sMenuElement $l)
	{
		if ($this->collW3sMenuElements === null) {
			$this->initW3sMenuElements();
		}
		if (!in_array($l, $this->collW3sMenuElements, true)) { 			array_push($this->collW3sMenuElements, $l);
			$l->setW3sContent($this);
		}
	}

	
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
			if ($this->collW3sMenuElements) {
				foreach ((array) $this->collW3sMenuElements as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} 
		$this->collW3sMenuElements = null;
			$this->aW3sGroup = null;
			$this->aW3sPage = null;
			$this->aW3sLanguage = null;
			$this->aW3sContentType = null;
			$this->aW3sSlot = null;
	}

} 