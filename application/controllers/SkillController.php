<?php

class SkillController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
        
    }
    
    public function skillInclude($allExistSkills,$itemCheck)
    {
        /**
         * @return != -1 : not include; -1: include 
         * @param  $arr -> array need to check ; $itemCheck -> item check to include or not 
         */
          
        foreach($allExistSkills as $item)
        {        
            if (rtrim(ltrim($item->getName())) == rtrim(ltrim($itemCheck)))
            {
                return $item->getId();
            }                
        }
        return -1;
    }
    
    public function getSkillsArray($strSkill)
    {
        /**
         * @return get String from Request String --> put in array and omit ','
         */ 
        $skills      = explode(",",$strSkill);
        $skillReturn = array();
        for($i=0;$i<sizeof($skills)-1;$i++)
        {
            $skillReturn[$i] = substr(rtrim(ltrim($skills[$i])),1);
        }
        return $skillReturn;
    }
    
    public function saveNewSkill($strSkillRequest,&$arrIds,&$arrSkills)
    {
        /**
         * save new skill from request of that course
         * @return "arrIds" :Ids of skill (co van de) , "arrSkills" : array of Skill
         * @param  $strSkillRequest : string of skill from request ; &$arrIds,&$arrSkills : 2 array return
         */          
        $arrSkillTemp = $this->getSkillsArray($strSkillRequest);   
        $skillMapper    = new Application_Model_SkillMapper();
        $allExistSkills = $skillMapper->fetchAll();
        $lastIdBefore = $skillMapper->getLastInsert();
        for($i=0;$i<sizeof($arrSkillTemp);$i++)
        {
            $arrSkills[$i] = $arrSkillTemp[$i];
            if( ( $skillId = $this->skillInclude($allExistSkills,$arrSkillTemp[$i]) ) == -1)
            {                                 
                $skillMapper->setName($arrSkillTemp[$i]);
                $skillMapper->save();
                $lastIdAfter = $skillMapper->getLastInsert();
                if($lastIdBefore==$lastIdAfter)
                    return false;
                else
                {
                    $arrIds[$i] =  $lastIdAfter;
                    $lastIdBefore = $lastIdAfter;
                }                    
            }
            else   
                $arrIds[$i] = $skillId ;
        }
        return true;
    }
    
    public function saveCourseSkill($arrSkillIds,$courseId)
    {
        $courseSkillMapper       = new Application_Model_CourseSkillMapper();
        $rowsBeforeInsert        = sizeof($courseSkillMapper->fetchAll(null,null,null,null));
      
        foreach($arrSkillIds as $skillId)
        {
            $courseSkillMapper->setCourseId($courseId);
            $courseSkillMapper->setSkillId($skillId);         
            $courseSkillMapper->save();
        }
        $rowsafterInsert         = sizeof($courseSkillMapper->fetchAll(null,null,null,null));
        if (($rowsafterInsert - $rowsBeforeInsert) == sizeof($arrSkillIds))
            return true;
        return false;
    }
    
    public function addAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
   
        if($strSkillRequest = $this->getRequest()->getParam("strSkill"))
        {
            // ID of inserted course
            $courseId = 8;
            if ($this->getRequest()->getParam("courseId")!="")
                $courseId = $this->getRequest()->getParam("courseId");
                
            // Insert new skills and return (ids, names) of skills in that course
            $arrSkillIds  = array();
            $arrSkills    = array();
          
            $result = $this->saveNewSkill($strSkillRequest,$arrSkillIds,$arrSkills);
            
            //Insert course_skill
            if ($result == false)
            {
                echo Zend_Json::encode(array( "result" => "fail"));
                return false;
            }
            else
            {   
                $result1 = true;                
                try{
                    $result1 = $this->saveCourseSkill($arrSkillIds,$courseId);
                 }
                catch(exception $e)
                {
                    echo Zend_Json::encode(array( "result" => "fail"));
                    return false;
                }                
            
                if($result1 == false)
                    echo Zend_Json::encode(array( "result" => "fail"));
                else                
                    echo Zend_Json::encode(array( "result" => "success", "arrSkillIds" =>$arrSkillIds , "arrSkills" =>$arrSkills ));
             }     
        }
        else
            echo Zend_Json::encode(array("result" => "fail"));
    }
}
