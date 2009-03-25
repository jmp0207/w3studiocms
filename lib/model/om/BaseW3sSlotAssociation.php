<?php


abstract class BaseW3sSlotAssociation extends BaseObject  implements Persistent {


  const PEER = 'W3sSlotAssociationPeer';

	
	protected static $peer;

	
	protected $id;

	
	protected $slot_id_source;

	
	protected $slot_id_destination;

	
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

	
	public function getSlotIdSource()
	{
		return $this->slot_id_source;
	}

	
	public function getSlotIdDestination()
	{
		return $this->slot_id_destination;
	}

	
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = W3sSlotAssociationPeer::ID;
		}

		return $this;
	} 
	
	public function setSlotIdSource($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->slot_id_source !== $v) {
			$this->slot_id_source = $v;
			$this->modifiedColumns[] = W3sSlotAssociationPeer::SLOT_ID_SOURCE;
		}

		return $this;
	} 
	
	public function setSlotIdDestination($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->slot_id_destination !== $v) {
			$this->slot_id_destination = $v;
			$this->modifiedColumns[] = W3sSlotAssociationPeer::SLOT_ID_DESTINATION;
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
			$this->slot_id_source = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->slot_id_destination = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

						return $startcol + 3; 
		} catch (Exception $e) {
			throw new PropelException("Error populating W3sSlotAssociation object", $e);
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
			$con = Propel::getConnection(W3sSlotAssociationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

				
		$stmt = W3sSlotAssociationPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); 
		if ($deep) {  
		} 	}

	
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(W3sSlotAssociationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			W3sSlotAssociationPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(W3sSlotAssociationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
			W3sSlotAssociationPeer::addInstanceToPool($this);
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
				$this->modifiedColumns[] = W3sSlotAssociationPeer::ID;
			}

						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = W3sSlotAssociationPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += W3sSlotAssociationPeer::doUpdate($this, $con);
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


			if (($retval = W3sSlotAssociationPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = W3sSlotAssociationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getSlotIdSource();
				break;
			case 2:
				return $this->getSlotIdDestination();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true)
	{
		$keys = W3sSlotAssociationPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getSlotIdSource(),
			$keys[2] => $this->getSlotIdDestination(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = W3sSlotAssociationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setSlotIdSource($value);
				break;
			case 2:
				$this->setSlotIdDestination($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = W3sSlotAssociationPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setSlotIdSource($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setSlotIdDestination($arr[$keys[2]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(W3sSlotAssociationPeer::DATABASE_NAME);

		if ($this->isColumnModified(W3sSlotAssociationPeer::ID)) $criteria->add(W3sSlotAssociationPeer::ID, $this->id);
		if ($this->isColumnModified(W3sSlotAssociationPeer::SLOT_ID_SOURCE)) $criteria->add(W3sSlotAssociationPeer::SLOT_ID_SOURCE, $this->slot_id_source);
		if ($this->isColumnModified(W3sSlotAssociationPeer::SLOT_ID_DESTINATION)) $criteria->add(W3sSlotAssociationPeer::SLOT_ID_DESTINATION, $this->slot_id_destination);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(W3sSlotAssociationPeer::DATABASE_NAME);

		$criteria->add(W3sSlotAssociationPeer::ID, $this->id);

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

		$copyObj->setSlotIdSource($this->slot_id_source);

		$copyObj->setSlotIdDestination($this->slot_id_destination);


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
			self::$peer = new W3sSlotAssociationPeer();
		}
		return self::$peer;
	}

	
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
		} 
	}

} 