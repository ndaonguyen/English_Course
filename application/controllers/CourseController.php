<?php

class CourseController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
        
    }
    
    public function addAction()
    {
        $skillMapper = new Application_Model_SkillMapper();
        $arrSkills   = $skillMapper->fetchAll();
        $this->view->arrSkills= $arrSkills;
     //   print_r($arrCourses);
    //    $this->_helper->layout->disableLayout();
    //    $this->_helper->viewRenderer->setNoRender(TRUE);
    }
}
