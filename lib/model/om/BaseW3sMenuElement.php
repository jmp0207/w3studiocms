<?php


abstract class BaseW3sMenuElement extends BaseObject  implements Persistent {


  const PEER = 'W3sMenuElementPeer';

	
	protected static $peer;

	
	protected $id;

	
	protected $content_id;

	
	protected $page_id;

	
	protected $link;

	
	protected $external_link;

	
	protected $image;

	
	protected $rollover_image;

	
	protected $position;

	
	protected $aW3sContent;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	
	public function applyDefaultValues()
	{
		$this->content_id = 0;
		$this->page_id = 0;
		$this->position = 0;
	}

	
	public function getId()
	{
		return $this->id;
	}

	
	public function getContentId()
	{
		return $this->content_id;
	}

	
	public function getPageId()
	{
		return $this->page_id;
	}

	
	public function getLink()
	{
		return $this->link;
	}

	
	public function getExternalLink()
	{
		return $this->external_link;
	}

	
	public function getImage()
	{
		return $this->image;
	}

	
	public function getRolloverImage()
	{
		return $this->rollover_image;
	}

	
	public function getPosition()
	{
		return $this->position;
	}

	
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = W3sMenuElementPeer::ID;
		}

		return $this;
	} 
	
	public function setContentId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->content_id !== $v || $v === 0) {
			$this->content_id = $v;
			$this->modifiedColumns[] = W3sMenuElementPeer::CONTENT_ID;
		}

		if ($this->aW3sContent !== null && $this->aW3sContent->getId() !== $v) {
			$this->aW3sContent = null;
		}

		return $this;
	} 
	
	public function setPageId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->page_id !== $v || $v === 0) {
			$this->page_id = $v;
			$this->modifiedColumns[] = W3sMenuElementPeer::PAGE_ID;
		}

		return $this;
	} 
	
	public function setLink($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->link !== $v) {
			$this->link = $v;
			$this->modifiedColumns[] = W3sMenuElementPeer::LINK;
		}

		return $this;
	} 
	
	public function setExternalLink($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->external_link !== $v) {
			$this->external_link = $v;
			$this->modifiedColumns[] = W3sMenuElementPeer::EXTERNAL_LINK;
		}

		return $this;
	} 
	
	public function setImage($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->image !== $v) {
			$this->image = $v;
			$this->modifiedColumns[] = W3sMenuElementPeer::IMAGE;
		}

		return $this;
	} 
	
	public function setRolloverImage($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->rollover_image !== $v) {
			$this->rollover_image = $v;
			$this->modifiedColumns[] = W3sMenuElementPeer::ROLLOVER_IMAGE;
		}

		return $this;
	} 
	
	public function setPosition($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->position !== $v || $v === 0) {
			$this->position = $v;
			$this->modifiedColumns[] = W3sMenuElementPeer::POSITION;
		}

		return $this;
	} 
	
	public function hasOnlyDefaultValues()
	{
						if (array_diff($this->modifiedColumns, array(W3sMenuElementPeer::CONTENT_ID,W3sMenuElementPeer::PAGE_ID,W3sMenuElementPeer::POSITION))) {
				return false;
			}

			if ($this->content_id !== 0) {
				return false;
			}

			if ($this->page_id !== 0) {
				return false;
			}

			if ($this->position !== 0) {
				return false;
			}

				return true;
	} 
	
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->content_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->page_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->link = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->external_link = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->image = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->rollover_image = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->position = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

						return $startcol + 8; 
		} catch (Exception $e) {
			throw new PropelException("Error populating W3sMenuElement object", $e);
		}
	}

	
	public function ensureConsistency()
	{

		if ($this->aW3sContent !== null && $this->content_id !== $this->aW3sContent->getId()) {
			$this->aW3sContent = null;
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
			$con = Propel::getConnection(W3sMenuElementPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

				
		$stmt = W3sMenuElementPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); 
		if ($deep) {  
			$this->aW3sContent = null;
		} 	}

	
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(W3sMenuElementPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			W3sMenuElementPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(W3sMenuElementPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
			W3sMenuElementPeer::addInstanceToPool($this);
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

												
			if ($this->aW3sContent !== null) {
				if ($this->aW3sContent->isModified() || $this->aW3sContent->isNew()) {
					$affectedRows += $this->aW3sContent->save($con);
				}
				$this->setW3sContent($this->aW3sContent);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = W3sMenuElementPeer::ID;
			}

						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = W3sMenuElementPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += W3sMenuElementPeer::doUpdate($this, $con);
				}

				$this->resetModified(); 			}

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


												
			if ($this->aW3sContent !== null) {
				if (!$this->aW3sContent->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aW3sContent->getValidationFailures());
				}
			}


			if (($retval = W3sMenuElementPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = W3sMenuElementPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getContentId();
				break;
			case 2:
				return $this->getPageId();
				break;
			case 3:
				return $this->getLink();
				break;
			case 4:
				return $this->getExternalLink();
				break;
			case 5:
				return $this->getImage();
				break;
			case 6:
				return $this->getRolloverImage();
				break;
			case 7:
				return $this->getPosition();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true)
	{
		$keys = W3sMenuElementPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getContentId(),
			$keys[2] => $this->getPageId(),
			$keys[3] => $this->getLink(),
			$keys[4] => $this->getExternalLink(),
			$keys[5] => $this->getImage(),
			$keys[6] => $this->getRolloverImage(),
			$keys[7] => $this->getPosition(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = W3sMenuElementPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setContentId($value);
				break;
			case 2:
				$this->setPageId($value);
				break;
			case 3:
				$this->setLink($value);
				break;
			case 4:
				$this->setExternalLink($value);
				break;
			case 5:
				$this->setImage($value);
				break;
			case 6:
				$this->setRolloverImage($value);
				break;
			case 7:
				$this->setPosition($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = W3sMenuElementPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setContentId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setPageId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setLink($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setExternalLink($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setImage($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setRolloverImage($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setPosition($arr[$keys[7]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(W3sMenuElementPeer::DATABASE_NAME);

		if ($this->isColumnModified(W3sMenuElementPeer::ID)) $criteria->add(W3sMenuElementPeer::ID, $this->id);
		if ($this->isColumnModified(W3sMenuElementPeer::CONTENT_ID)) $criteria->add(W3sMenuElementPeer::CONTENT_ID, $this->content_id);
		if ($this->isColumnModified(W3sMenuElementPeer::PAGE_ID)) $criteria->add(W3sMenuElementPeer::PAGE_ID, $this->page_id);
		if ($this->isColumnModified(W3sMenuElementPeer::LINK)) $criteria->add(W3sMenuElementPeer::LINK, $this->link);
		if ($this->isColumnModified(W3sMenuElementPeer::EXTERNAL_LINK)) $criteria->add(W3sMenuElementPeer::EXTERNAL_LINK, $this->external_link);
		if ($this->isColumnModified(W3sMenuElementPeer::IMAGE)) $criteria->add(W3sMenuElementPeer::IMAGE, $this->image);
		if ($this->isColumnModified(W3sMenuElementPeer::ROLLOVER_IMAGE)) $criteria->add(W3sMenuElementPeer::ROLLOVER_IMAGE, $this->rollover_image);
		if ($this->isColumnModified(W3sMenuElementPeer::POSITION)) $criteria->add(W3sMenuElementPeer::POSITION, $this->position);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(W3sMenuElementPeer::DATABASE_NAME);

		$criteria->add(W3sMenuElementPeer::ID, $this->id);

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

		$copyObj->setContentId($this->content_id);

		$copyObj->setPageId($this->page_id);

		$copyObj->setLink($this->link);

		$copyObj->setExternalLink($this->external_link);

		$copyObj->setImage($this->image);

		$copyObj->setRolloverImage($this->rollover_image);

		$copyObj->setPosition($this->position);


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
			self::$peer = new W3sMenuElementPeer();
		}
		return self::$peer;
	}

	
	public function setW3sContent(W3sContent $v = null)
	{
		if ($v === null) {
			$this->setContentId(0);
		} else {
			$this->setContentId($v->getId());
		}

		$this->aW3sContent = $v;

						if ($v !== null) {
			$v->addW3sMenuElement($this);
		}

		return $this;
	}


	
	public function getW3sContent(PropelPDO $con = null)
	{
		if ($this->aW3sContent === null && ($this->content_id !== null)) {
			$c = new Criteria(W3sContentPeer::DATABASE_NAME);
			$c->add(W3sContentPeer::ID, $this->content_id);
			$this->aW3sContent = W3sContentPeer::doSelectOne($c, $con);
			
		}
		return $this->aW3sContent;
	}

	
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
		} 
			$this->aW3sContent = null;
	}

} 