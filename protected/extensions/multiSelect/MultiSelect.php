<?php
/*
 * multiSelect jQuery Extension - jQuery plugin
 * @yiiVersion 1.1.6
 */

/**
 * Description of multiSelect 
 * URL: http://quasipartikel.at/multiselect_next/
 * @author Kamarul Ariffin Ismail <kamarul.ismail@gmail.com>
 * @version 1.0
 */

Yii::import('zii.widgets.jui.CJuiWidget');

class MultiSelect extends CJuiWidget
{
  public $sortable   = true;
  public $searchable = true;
  public $droppable;
  public $searchDelay;
  public $doubleClickable;
  public $dividerLocation = 0.5;
  public $remoteUrl;
  public $remoteParams;
  public $eventSelected;
  public $eventMessages;
  public $eventDeselected;
  public $id;
  public $model;
  public $cssFiles;
  public $callMethod = 'auto';
  public $type;
  public $loadDelay;
  public $dataLimiter;
    
  public function init()
  {
    parent::init();
    $this->registerScripts();
  }
      
  protected function registerScripts()
  {    
    if(empty($this->id))
    {
      return false;
    }
    
    $basePath = Yii::getPathOfAlias('application.extensions.multiSelect.resources');
    $baseUrl  = Yii::app()->getAssetManager()->publish($basePath, false, -1, YII_DEBUG);
    
    $cs = Yii::app()->getClientScript();    
    if(!empty($this->cssFiles))
    {
      $cs->registerCssFile($this->cssFiles);
    }
    else
    {
      $cs->registerCssFile($baseUrl.'/css/ui.multiselect.css');
    }
    
    
    $this->scriptUrl = $baseUrl;   
    $this->registerScriptFile('js/plugins/tmpl/jquery.tmpl.1.1.1.js');
    $this->registerScriptFile('js/plugins/blockUI/jquery.blockUI.js');
    $this->registerScriptFile('js/ui.multiselect.js');

    // make use of the locale feature to include custom text
    if($this->type == 'group') 
      $this->registerScriptFile('js/locale/ui.multiselect-en-group.js');
    elseif($this->type == 'storyline-episode') 
      $this->registerScriptFile('js/locale/ui.multiselect-en-storyline-episode.js');
            
    if(is_array($this->id))
    {
      $parameters  = $this->id;
      $htmlOptions = array();
      $model     = $parameters['model'];
      $attribute = $parameters['attribute'];
      CHtml::resolveNameID($model, $attribute, $htmlOptions);        
      $multiSelectID = '[name="'.$htmlOptions['name'].'"]';       
    }
    else
    {
      $multiSelectID = $this->id;
    }
    
    $parameterList = array();
    if($this->sortable) {
      $parameterList[] = 'sortable:true';
    } 
    
    if($this->searchable) {
      $parameterList[] = 'searchable:true';
    } 
    
    if(!empty($this->searchDelay) || intval($this->searchDelay) >= 0){
      $searchDelay     = intval($this->searchDelay);
      $parameterList[] = "searchDelay:{$searchDelay}";
    }
    
    if($this->droppable) {
      $parameterList[] = 'droppable:true';
    }
    
    if($this->doubleClickable){
      $parameterList[] = 'doubleClickable:true';
    }
            
    if(!empty($this->dividerLocation))
    {
      $parameterList[] = "dividerLocation : {$this->dividerLocation} ";
    }
    
    if($this->dataLimiter){
      $parameterList[] = 'dataLimiter:true';
    }
    
    if(!empty($this->remoteUrl)){
      $parameterList[] = "remoteUrl: '{$this->remoteUrl}'";
      
      if(!empty($this->remoteParams)){
        $remoteParams = array();
        foreach($this->remoteParams as $key => $value){
          $remoteParams[] = "{$key}:{$value}";
        }
        $parameterList[] = "remoteParams : { ".implode(',', $remoteParams)." } ";
      }      
    }
            
    if(!empty($this->eventSelected)){
      $parameterList[] = "selected: {$this->eventSelected} ";
    }
    
    if(!empty($this->eventDeselected)){
      $parameterList[] = "deselected: {$this->eventDeselected} ";
    }
    
    if(!empty($this->eventMessages)){
      $parameterList[] = "messages: {$this->eventMessages} ";
    }
            
    if(empty($multiSelectID))
    {
      return false;
    }

    $parameters = '{' .implode(', ', $parameterList). '}'; 
        
    if($this->callMethod == 'function'){
      $jsFunction = "\nfunction initMultiSelect(){ \n".
                  "$('{$multiSelectID}').multiselect({$parameters});\n".
                  "}\n".
                  "\nfunction destroyMultiSelect(){ \n".
                  "$('{$multiSelectID}').multiselect('destroy');\n".
                  "}\n";
      
      Yii::app()->clientScript->registerScript("MultiSelect_{$multiSelectID}", $jsFunction, CClientScript::POS_END);      
    }
    else{
      $jsFunction     = "$('{$multiSelectID}').multiselect({$parameters});";
      $scriptPosition = CClientScript::POS_READY;
      
      if(!empty($this->loadDelay)){
        $delayTime  = intval($this->loadDelay);
        $jsFunction = "setTimeout(function(){ {$jsFunction} }, {$delayTime});";
        $scriptPosition = CClientScript::POS_END;
    }
            
      Yii::app()->clientScript->registerScript("MultiSelect_{$multiSelectID}", $jsFunction, $scriptPosition);      
    }

  }
  
}
?>
