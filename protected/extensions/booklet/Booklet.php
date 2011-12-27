<?php

/*
 * Booklet jQuery Extension - jQuery plugin
 * @yiiVersion 1.1.7
 */

/**
 * Description of Tipsy 
 * Per the http://http://builtbywill.com/code/booklet/ 
 * @author Kamarul Ariffin Ismail <kamarul.ismail@gmail.com>
 * @version 1.0
 */
class Booklet extends CWidget
{
    public $id;
    public $options;
    public $closed;
    public $tabs;
    public $shadows;
    public $arrows;
    public $menu;
    public $callbacks;
    
    private $_baseUrl;

    public function init()
    {
        if(!$this->isValidID())
        {
            Yii::log('Empty ID.', CLogger::LEVEL_ERROR, __CLASS__.'.'.__FUNCTION__);
            return false;
        }
        
        // GET RESOURCE PATH
        $resources = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';

        // PUBLISH FILES
        $this->_baseUrl = Yii::app()->assetManager->publish($resources, false, -1, YII_DEBUG);
    }

    public function run()
    {
        if(!$this->isValidID())
        {
            return false;
        }
        
        // REGISTER JS SCRIPT
        $cs = Yii::app()->clientScript;
        
        //CORE: jQuery UI
        $cs->registerCoreScript('jquery.ui');
        
        $cs->registerScriptFile($this->_baseUrl . '/jquery.booklet.js');
        $cs->registerScriptFile($this->_baseUrl . '/jquery.easing.js');        

        // REGISTER CSS
        $cs->registerCssFile($this->_baseUrl . '/jquery.booklet.css');
        
        //ID
        $bookletID = $this->id; FB::info($bookletID, 'bookletID');
        
        //OPTION
        $bookletOption = $this->options;
        
        //OPTION -> TABS 
        if(isset($this->tabs))
        {
            FB::info($this->tabs, 'tabs');
            $bookletOption['tabs'] = true;
            
            if(!empty($this->tabs))
            {
                $bookletOption = CMap::mergeArray($bookletOption, $this->tabs);
            }
        }
                
        //OPTION -> SHADOWS 
        if(isset($this->shadows))
        {
            FB::info($this->shadows, 'shadows');
            $bookletOption['shadows'] = true;
            
            if(!empty($this->shadows))
            {
                $bookletOption = CMap::mergeArray($bookletOption, $this->shadows);
            }
        }
        
        //OPTION -> ARROWS 
        if(isset($this->arrows))
        {
            FB::info($this->arrows, 'arrows');
            $bookletOption['arrows'] = true;
            
            if(!empty($this->arrows))
            {
                $bookletOption = CMap::mergeArray($bookletOption, $this->arrows);
            }
        }
        
        $bookletOption['arrows'] = false;
        $bookletOption['arrowsHide'] = true;
        
        FB::info(json_encode($bookletOption));
                
        // GENERATE INIT FUNCTION
        $jsCode = "\n$(\"{$bookletID}\").booklet(". json_encode($bookletOption) .");\n";
        
        $cs->registerScript(__CLASS__ . '.' . $bookletID, $jsCode, CClientScript::POS_END);
        
    }
    
    private function isValidID()
    {
        if(empty($this->id))
        {
            return false;
        }
        
        return true;
    }

}

?>