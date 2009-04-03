<?php

class W3sPageFinder extends DbFinder
{
  protected $class = 'W3sPage';

  /**
   * Exclude pages by id
   *
   * @param int $pageId page id
   * 
   * @return DbFinder a finder object
   */
  public function whereIdNot($pageId)
  {
    return $this->where('Id', '!=', $pageId);
  }
}
