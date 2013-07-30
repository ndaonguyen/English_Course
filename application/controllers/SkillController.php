<?php

class SkillController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
        
    }
    
    public function addAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        if($strSkill = $this->getRequest()->getParam("strSkill"))
        {
            $arrSkill = 
            echo $strSkill;
        }
        else
            echo Zend_Json::encode(array("result" => "fail"));
    }
}
