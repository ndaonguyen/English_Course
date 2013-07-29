<?php
class Application_Model_CourseMapper
{
	protected $_dbTable;
	public $_mId;
	public $_mName;


	public function getId(){
		return $this->_mId;
	}
	public function setId($value){
		$this->_mId = $value;
		return $this;
	}
	public function getName(){
		return $this->_mName;
	}
	public function setName($value){
		$this->_mName = $value;
		return $this;
	}

	public function setDbTable($dbTable){
		if (is_string($dbTable))
			$dbTable = new $dbTable();
		if (!$dbTable instanceof Zend_Db_Table_Abstract)
			throw new Exception('Invalid table data gateway provided');
		$this->_dbTable = $dbTable;
		return $this;
	}
	public function getDbTable(){
		if (null === $this->_dbTable)
			$this->setDbTable('Application_Model_DbTable_Course');
		return $this->_dbTable;
	}
	public function save(Application_Model_CourseMapper $old = null){
		$item = $this;
		$data = array();
		if(isset($item->_mId)) $data['id'] = $item->_mId;
		if(isset($item->_mName)) $data['name'] = $item->_mName;
	
		if($old === null)
			$this->getDbTable()->insert($data);
		else{
			$key = array();
			$old->_mId === null ? $key[] = 'id is NULL' : $key['id = ?'] =  $old->_mId;

			$this->getDbTable()->update($data, $key);
		}
	}
	public function delete(){
		$item = $this;
		$old = $item;
		$key = array();
			$old->_mId === null ? $key[] = 'id is NULL' : $key['id = ?'] =  $old->_mId;

		$this->getDbTable()->delete($key);
	}
	public function fetchAll($where = null, $order = null, $count = null, $offset = null){
		$resultSet = $this->getDbTable()->fetchAll($where, $order, $count, $offset);
		$entries   = array();
		foreach ($resultSet as $row) {
			$item = new Application_Model_CourseMapper();
			if(isset($row->id))$item->_mId = $row->id;
			if(isset($row->name))$item->_mName = $row->name;
            
            $entries[] = $item;
		}
		return $entries;
	}
}
