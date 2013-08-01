<?php
class Application_Model_SkillmaterialMapper
{
	protected $_dbTable;
	public $_mMaterialid;
	public $_mSkillid;


	public function getMaterialid(){
		return $this->_mMaterialid;
	}
    
	public function setMaterialid($value){
		$this->_mMaterialid = $value;
		return $this;
	}
    
	public function getSkillid(){
		return $this->_mSkillid;
	}
    
	public function setSkillid($value){
		$this->_mSkillid = $value;
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
			$this->setDbTable('Application_Model_DbTable_Skillmaterial');
		return $this->_dbTable;
	}
  
    
	public function save(Application_Model_SkillMapper $old = null){
		$item = $this;
		$data = array();
		if(isset($item->_mMaterialid)) $data['material_id'] = $item->_mMaterialid;
		if(isset($item->_mSkillid)) $data['skill_id'] = $item->_mSkillid;
	
		if($old === null)
			$this->getDbTable()->insert($data);
		else{
			$key = array();
			$old->_mId === null ? $key[] = 'course_id is NULL' : $key['course_id = ?'] =  $old->_mId;

			$this->getDbTable()->update($data, $key);
		}
	}
    
	public function delete(){
		$item = $this;
		$old = $item;
		$key = array();
			$old->_mCourseid === null ? $key[] = 'course_id is NULL' : $key['course_id = ?'] =  $old->_mCourseid;

		$this->getDbTable()->delete($key);
	}
    
	public function fetchAll($where = null, $order = null, $count = null, $offset = null){
		$resultSet = $this->getDbTable()->fetchAll($where, $order, $count, $offset);
		$entries   = array();
		foreach ($resultSet as $row) {
			$item = new Application_Model_SkillmaterialMapper();
			if(isset($row->material_id))$item->_mMaterialid = $row->material_id;
			if(isset($row->skill_id))$item->_mSkillid = $row->skill_id;
            
            $entries[] = $item;
		}
		return $entries;
	}
    
    public function getLastInsert()
    {
        return $this->getDbTable()->getAdapter()->lastInsertId();
    }
    
}
