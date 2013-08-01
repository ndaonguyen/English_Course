<?php

class CourseController extends Zend_Controller_Action
{

    public function init()
	{
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('process', 'html')
	                  ->initContext();
	}

    public function indexAction()
    {
        
    }
    public function createAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
       
        
        $courseName = $this->getRequest()->getParam('courseName');
        $courseMapper = new Application_Model_CourseMapper();
        $lastIdBefore = $courseMapper->getLastInsert();
        $courseMapper->setName($courseName);
        $courseMapper->save();      
        $lastIdAfter = $courseMapper->getLastInsert();
    
        if($lastIdAfter != $lastIdBefore)
            echo Zend_Json::encode(array('result' => 'success','courseName' => $courseName, 'id' => $lastIdAfter));
        else
            echo Zend_Json::encode(array('result' => 'fail', 'id' => ''));
        
    }
    
    public function addAction()
    {
        $skillMapper = new Application_Model_SkillMapper();
        $arrSkills   = $skillMapper->fetchAll();
        $this->view->arrSkills= $arrSkills;
    
    }
}
