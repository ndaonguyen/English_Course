<?php

class MaterialController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
        
    }
    
    public function getMaterialArray($strMaterial)
    {
        /**
         * @return get String from Request String --> put in array and omit ',' and '+'
         */ 
        $materials      = explode(",",$strMaterial);
        $materialReturn = array();
        for($i=0;$i<sizeof($materials)-1;$i++)
        {
            $materialReturn[$i] = substr(rtrim(ltrim($materials[$i])),1);
        }
        return $materialReturn;
    }
    
    public function addAction()
    /**
     * $materialGroup[0][] --> skill ids
     * $materialGroup[1][] --> materials
     */
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        if($strSkillIds = $this->getRequest()->getParam('arrSkillIds'))
        {   
            $materialMapper       = new Application_Model_MaterialMapper();
            $skillMatMapper       = new Application_Model_SkillmaterialMapper();
            $arrMaterialIds       = array();
            $strMaterial = "";// material of the # id is separated by ";", same is separated by ","
            
            
            $lastIdMaterialBefore = $materialMapper->getLastInsert();
             
            $arrSkillIds   = explode(",",$strSkillIds);
            $arrSkills     = explode(",",$this->getRequest()->getParam("arrSkills"));
            $materialGroup = $this->getRequest()->getParam('materialGroup'); 
            for($i=0;$i<sizeof($materialGroup[0]);$i++)
            {   
                $arrMaterial = $this->getMaterialArray($materialGroup[1][$i]);
                foreach($arrMaterial as $material)
                {
                    $materialMapper->setName($material);
                    $materialMapper->save();    
                    $lastIdMaterialAfter = $materialMapper->getLastInsert();
                    array_push($arrMaterialIds,$lastIdMaterialAfter);
                   
                    if($lastIdMaterialBefore==$lastIdMaterialAfter)
                    {
                        echo Zend_Json::encode(array( "result" => "fail"));
                        return false;
                    }
                    else
                    {
                        $strMaterial .=$material.",";
                        $skillMatMapper->setMaterialid($lastIdMaterialAfter);
                        $skillMatMapper->setSkillid($materialGroup[0][$i]);
                        $skillMatMapper->save();
                        
                        $lastIdMaterialBefore = $lastIdMaterialAfter;                        
                    }
                }
                $strMaterial .=";";
            }            
            echo Zend_json::encode(array( "result" => "success", "arrMaterialIds" => $arrMaterialIds, "strMaterial" => $strMaterial ));
            return true;
        }
        echo Zend_Json::encode(array( "result" => "fail"));
        return false;
    }
}
