<?php


abstract class BaseW3sSearchEngine extends BaseObject  implements Persistent {


  const PEER = 'W3sSearchEnginePeer';

	
	protected static $peer;

	
	protected $id;

	
	protected $page_id;

	
	protected $language_id;

	
	protected $meta_title;

	
	protected $meta_description;

	
	protected $meta_keywords;

	
	protected $sitemap_changefreq;

	
	protected $sitemap_lastmod;

	
	protected $sitemap_priority;

	
	protected $aW3sPage;

	
	protected $aW3sLanguage;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	
	public function applyDefaultValues()
	{
		$this->page_id = 0;
		$this->language_id = 0;
	}

	
	public function getId()
	{
		return $this->id;
	}

	
	public function getPageId()
	{
		return $this->page_id;
	}

	
	public function getLanguageId()
	{
		return $this->language_id;
	}

	
	public function getMetaTitle()
	{
		return $this->meta_title;
	}

	
	public function getMetaDescription()
	{
		return $this->meta_description;
	}

	
	public function getMetaKeywords()
	{
		return $this->meta_keywords;
	}

	
	public function getSitemapChangefreq()
	{
		return $this->sitemap_changefreq;
	}

	
	public function getSitemapLastmod()
	{
		return $this->sitemap_lastmod;
	}

	
	public function getSitemapPriority()
	{
		return $this->sitemap_priority;
	}

	
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = W3sSearchEnginePeer::ID;
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
			$this->modifiedColumns[] = W3sSearchEnginePeer::PAGE_ID;
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

		if ($this->language_id !== $v || $v === 0) {
			$this->language_id = $v;
			$this->modifiedColumns[] = W3sSearchEnginePeer::LANGUAGE_ID;
		}

		if ($this->aW3sLanguage !== null && $this->aW3sLanguage->getId() !== $v) {
			$this->aW3sLanguage = null;
		}

		return $this;
	} 
	
	public function setMetaTitle($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->meta_title !== $v) {
			$this->meta_title = $v;
			$this->modifiedColumns[] = W3sSearchEnginePeer::META_TITLE;
		}

		return $this;
	} 
	
	public function setMetaDescription($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->meta_description !== $v) {
			$this->meta_description = $v;
			$this->modifiedColumns[] = W3sSearchEnginePeer::META_DESCRIPTION;
		}

		return $this;
	} 
	
	public function setMetaKeywords($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->meta_keywords !== $v) {
			$this->meta_keywords = $v;
			$this->modifiedColumns[] = W3sSearchEnginePeer::META_KEYWORDS;
		}

		return $this;
	} 
	
	public function setSitemapChangefreq($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->sitemap_changefreq !== $v) {
			$this->sitemap_changefreq = $v;
			$this->modifiedColumns[] = W3sSearchEnginePeer::SITEMAP_CHANGEFREQ;
		}

		return $this;
	} 
	
	public function setSitemapLastmod($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->sitemap_lastmod !== $v) {
			$this->sitemap_lastmod = $v;
			$this->modifiedColumns[] = W3sSearchEnginePeer::SITEMAP_LASTMOD;
		}

		return $this;
	} 
	
	public function setSitemapPriority($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->sitemap_priority !== $v) {
			$this->sitemap_priority = $v;
			$this->modifiedColumns[] = W3sSearchEnginePeer::SITEMAP_PRIORITY;
		}

		return $this;
	} 
	
	public function hasOnlyDefaultValues()
	{
						if (array_diff($this->modifiedColumns, array(W3sSearchEnginePeer::PAGE_ID,W3sSearchEnginePeer::LANGUAGE_ID))) {
				return false;
			}

			if ($this->page_id !== 0) {
				return false;
			}

			if ($this->language_id !== 0) {
				return false;
			}

				return true;
	} 
	
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->page_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->language_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->meta_title = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->meta_description = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->meta_keywords = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->sitemap_changefreq = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->sitemap_lastmod = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->sitemap_priority = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

						return $startcol + 9; 
		} catch (Exception $e) {
			throw new PropelException("Error populating W3sSearchEngine object", $e);
		}
	}

	
	public function ensureConsistency()
	{

		if ($this->aW3sPage !== null && $this->page_id !== $this->aW3sPage->getId()) {
			$this->aW3sPage = null;
		}
		if ($this->aW3sLanguage !== null && $this->language_id !== $this->aW3sLanguage->getId()) {
			$this->aW3sLanguage = null;
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
			$con = Propel::getConnection(W3sSearchEnginePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

				
		$stmt = W3sSearchEnginePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); 
		if ($deep) {  
			$this->aW3sPage = null;
			$this->aW3sLanguage = null;
		} 	}

	
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(W3sSearchEnginePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			W3sSearchEnginePeer::doDelete($this, $con);
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
			$con = Propel::getConnection(W3sSearchEnginePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
			W3sSearchEnginePeer::addInstanceToPool($this);
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

			if ($this->isNew() ) {
				$this->modifiedColumns[] = W3sSearchEnginePeer::ID;
			}

						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = W3sSearchEnginePeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += W3sSearchEnginePeer::doUpdate($this, $con);
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


			if (($retval = W3sSearchEnginePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = W3sSearchEnginePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getPageId();
				break;
			case 2:
				return $this->getLanguageId();
				break;
			case 3:
				return $this->getMetaTitle();
				break;
			case 4:
				return $this->getMetaDescription();
				break;
			case 5:
				return $this->getMetaKeywords();
				break;
			case 6:
				return $this->getSitemapChangefreq();
				break;
			case 7:
				return $this->getSitemapLastmod();
				break;
			case 8:
				return $this->getSitemapPriority();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true)
	{
		$keys = W3sSearchEnginePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getPageId(),
			$keys[2] => $this->getLanguageId(),
			$keys[3] => $this->getMetaTitle(),
			$keys[4] => $this->getMetaDescription(),
			$keys[5] => $this->getMetaKeywords(),
			$keys[6] => $this->getSitemapChangefreq(),
			$keys[7] => $this->getSitemapLastmod(),
			$keys[8] => $this->getSitemapPriority(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = W3sSearchEnginePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setPageId($value);
				break;
			case 2:
				$this->setLanguageId($value);
				break;
			case 3:
				$this->setMetaTitle($value);
				break;
			case 4:
				$this->setMetaDescription($value);
				break;
			case 5:
				$this->setMetaKeywords($value);
				break;
			case 6:
				$this->setSitemapChangefreq($value);
				break;
			case 7:
				$this->setSitemapLastmod($value);
				break;
			case 8:
				$this->setSitemapPriority($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = W3sSearchEnginePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setPageId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setLanguageId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setMetaTitle($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setMetaDescription($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setMetaKeywords($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setSitemapChangefreq($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setSitemapLastmod($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setSitemapPriority($arr[$keys[8]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(W3sSearchEnginePeer::DATABASE_NAME);

		if ($this->isColumnModified(W3sSearchEnginePeer::ID)) $criteria->add(W3sSearchEnginePeer::ID, $this->id);
		if ($this->isColumnModified(W3sSearchEnginePeer::PAGE_ID)) $criteria->add(W3sSearchEnginePeer::PAGE_ID, $this->page_id);
		if ($this->isColumnModified(W3sSearchEnginePeer::LANGUAGE_ID)) $criteria->add(W3sSearchEnginePeer::LANGUAGE_ID, $this->language_id);
		if ($this->isColumnModified(W3sSearchEnginePeer::META_TITLE)) $criteria->add(W3sSearchEnginePeer::META_TITLE, $this->meta_title);
		if ($this->isColumnModified(W3sSearchEnginePeer::META_DESCRIPTION)) $criteria->add(W3sSearchEnginePeer::META_DESCRIPTION, $this->meta_description);
		if ($this->isColumnModified(W3sSearchEnginePeer::META_KEYWORDS)) $criteria->add(W3sSearchEnginePeer::META_KEYWORDS, $this->meta_keywords);
		if ($this->isColumnModified(W3sSearchEnginePeer::SITEMAP_CHANGEFREQ)) $criteria->add(W3sSearchEnginePeer::SITEMAP_CHANGEFREQ, $this->sitemap_changefreq);
		if ($this->isColumnModified(W3sSearchEnginePeer::SITEMAP_LASTMOD)) $criteria->add(W3sSearchEnginePeer::SITEMAP_LASTMOD, $this->sitemap_lastmod);
		if ($this->isColumnModified(W3sSearchEnginePeer::SITEMAP_PRIORITY)) $criteria->add(W3sSearchEnginePeer::SITEMAP_PRIORITY, $this->sitemap_priority);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(W3sSearchEnginePeer::DATABASE_NAME);

		$criteria->add(W3sSearchEnginePeer::ID, $this->id);

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

		$copyObj->setPageId($this->page_id);

		$copyObj->setLanguageId($this->language_id);

		$copyObj->setMetaTitle($this->meta_title);

		$copyObj->setMetaDescription($this->meta_description);

		$copyObj->setMetaKeywords($this->meta_keywords);

		$copyObj->setSitemapChangefreq($this->sitemap_changefreq);

		$copyObj->setSitemapLastmod($this->sitemap_lastmod);

		$copyObj->setSitemapPriority($this->sitemap_priority);


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
			self::$peer = new W3sSearchEnginePeer();
		}
		return self::$peer;
	}

	
	public function setW3sPage(W3sPage $v = null)
	{
		if ($v === null) {
			$this->setPageId(0);
		} else {
			$this->setPageId($v->getId());
		}

		$this->aW3sPage = $v;

						if ($v !== null) {
			$v->addW3sSearchEngine($this);
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
			$this->setLanguageId(0);
		} else {
			$this->setLanguageId($v->getId());
		}

		$this->aW3sLanguage = $v;

						if ($v !== null) {
			$v->addW3sSearchEngine($this);
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

	
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
		} 
			$this->aW3sPage = null;
			$this->aW3sLanguage = null;
	}

} 