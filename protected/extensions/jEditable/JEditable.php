<?php
/*
 * jEditable jQuery Extension - jQuery plugin
 * @yiiVersion 1.1.6
 */

/**
 * Description of jEditable 
 * URL: http://www.appelsiini.net/projects/jeditable
 * @author Kamarul Ariffin Ismail <kamarul.ismail@gmail.com>
 * @version 1.0
 */

Yii::import('zii.widgets.jui.CJuiWidget');
class JEditable extends CJuiWidget
{
  public $items;
  public $cssFiles;
  
  public function init()
  {
    parent::init();   
    $this->registerScripts();    
  }
  
  protected function registerScripts()
  {
    parent::registerCoreScripts();
    
    $basePath = Yii::getPathOfAlias('application.widget.jEditable.resources');
    $baseUrl  = Yii::app()->getAssetManager()->publish($basePath, false, -1, YII_DEBUG);
    
    
    $cs = Yii::app()->getClientScript();
    if(!empty($this->cssFiles))
    {
      $cs->registerCssFile($this->cssFiles);
    }
    else
    {
      $cs->registerCssFile($baseUrl.'/jquery.jeditable.css');
    }
    
    $this->scriptUrl = $baseUrl; 
    $this->registerScriptFile('jquery.jeditable.js');
  }
  
  public function run()
  {
    if(empty($this->items)){
      return false;
    }
    
    if(!is_array($this->items)){
      return false;
    }
        
    $itemList = $this->items;
            
    $scriptList = array();
    foreach($itemList as $item)
    {
      if(!array_key_exists('id', $item)){
        continue; //skip loop
      }
                  
      if(!array_key_exists('saveURL', $item)){
        continue; //skip loop
      }
      
      if(empty($item['id'])){
        continue;
      }
      
      if(empty($item['saveURL'])){
        continue;
      }
      
      $id      = $item['id'];
      $saveURL = $item['saveURL'];
      
      // OPTIONAL PARAMETERS
      $parameterList = array();
      
      // TYPE: TEXT | TEXTAREA | SELECT
      if(array_key_exists('type', $item)){
        $parameterList[] = "type:'{$item['type']}' ";
      }
      
      // SUBMIT BUTTON
      if(array_key_exists('btnSubmit', $item)){
        $parameterList[] = "submit:'{$item['btnSubmit']}' ";
      }
      
      // CANCEL BUTTON
      if(array_key_exists('btnCancel', $item)){
        $parameterList[] = "cancel:'{$item['btnCancel']}' ";
      }
      
      // WIDTH
      if(array_key_exists('width', $item)){
        $parameterList[] = "width:'{$item['width']}' ";
      }
      
      // STYLE
      if(array_key_exists('style', $item)){
        $parameterList[] = "style:'{$item['style']}' ";
      }
      
      // TOOLTIP
      if(array_key_exists('tooltip', $item)){
        $parameterList[] = "tooltip:'{$item['tooltip']}' ";
      }
      
      if(array_key_exists('submitdata', $item)){
        $parameterList[] = "submitdata:{ {$item['submitdata']} } ";
      }
      
      if(array_key_exists('eventCallback', $item)){
        $parameterList[] = "callback: {$item['eventCallback']}  ";
      }
                
      //GENERATE JS CODE   
      $jsParameter = implode(', ', $parameterList);
      $jsCode = "jQuery('{$id}').editable('$saveURL', { ".                
                "{$jsParameter} ".
                "});";
      $scriptList[] = $jsCode;
    }
    
    if(!empty($scriptList)){       
      $parameters = implode(" \n", $scriptList);
      Yii::app()->clientScript->registerScript('jEditable', $parameters, CClientScript::POS_READY);      
    }
    
    
  }
    
  
}
?>