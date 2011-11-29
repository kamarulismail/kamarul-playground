<?php
/*
 * Uploadify Extension - file upload plugin
 * @yiiVersion 1.1.6
 */

/**
 * Description of Uploadify 
 * Per the http://www.uploadify.com/
 * @author Kamarul Ariffin Ismail <kamarul.ismail@gmail.com>
 * @version 1.0
 */
class Uploadify extends CWidget
{
  public $htmlOptions  = array();
  public $options      = array();
  public $queue        = array();
  public $callbacks    = array();
  public $name;
  
  private $_baseUrl;
  
  public function init()
  {    
    // GET RESOURCE PATH
    $resources = dirname(__FILE__).DIRECTORY_SEPARATOR.'resources';
    
    // PUBLISH FILES
    $this->_baseUrl = Yii::app()->assetManager->publish($resources, false, -1, YII_DEBUG);
  }
  
  public function run()
  {
    // REGISTER CLIENT SCRIPT
    $cs = Yii::app()->clientScript;
    
    // REGISTER CSS
    $cs->registerCssFile($this->_baseUrl.'/uploadify.css');
    
    //REGISTER JS
    #$cs->registerScriptFile($this->_baseUrl.'/jquery-1.4.2.min.js', CClientScript::POS_END);
    $cs->registerCoreScript('jquery');
    $cs->registerScriptFile($this->_baseUrl.'/swfobject.js', CClientScript::POS_END);
    $cs->registerScriptFile($this->_baseUrl.'/jquery.uploadify.v2.1.4.js', CClientScript::POS_END);    
    
    $uploadifyID = empty($this->name) ? 'file_upload' : $this->name;
    $queueID     = $uploadifyID.'_queue';
        
    //DEFAULT OPTIONS
    $defaultOptions = array(
      'uploader'       => "{$this->_baseUrl}/uploadify.swf",
      'expressInstall' => "{$this->_baseUrl}/expressInstall.swf",
      'cancelImg' => "{$this->_baseUrl}/images/cancel.png",
      'buttonImg' => "{$this->_baseUrl}/images/selectfiles.png", 'height' => '33', 'width' => '121',
      'multi' => 'false',
      'auto'  => 'false',
      'removeCompleted' => 'false',
      'queueID' => $queueID, //false      
    );
    $options = array_merge($defaultOptions, $this->options);
    $options = array_merge($options, $this->callbacks);
    
    $defaultQueue = array(
      'id'    => $queueID,
      'name'  => $queueID,
      'class' => 'uploadifyQueue',
    );
    $queueOptions = array_merge($defaultQueue, $this->queue);
    $queueOptions['id'] = $queueOptions['name'];
    $options['queueID'] = $queueOptions['id'];
    
    //GENERATE PARAMETERS
    $parameterList = '';
    $optionCount   = count($options);
    $optionIndex   = 1;
    foreach($options as $parameterName => $parameterValue)
    {
      if($parameterName == 'scriptData')
      {
        $scriptDataParameter = '';
        foreach($parameterValue as $key => $data)
        {
          $scriptDataParameter .= "'{$key}' : '{$data}',";
        }
        $scriptDataParameter = substr($scriptDataParameter, 0, -1);
        $parameterValue = '{'.$scriptDataParameter.'}';
      }
      else
      {
        $excludeList = array('true', 'false');
        if(is_string($parameterValue) && !in_array($parameterValue, $excludeList))
        {
          if(substr($parameterName, 0, 2) != 'on')
          {
            $parameterValue = "'{$parameterValue}'"; 
          }        
        }
      }

      $parameterList .= "{$parameterName} : {$parameterValue} ";
      $parameterList .= ($optionIndex < $optionCount) ? ",\n" : '';      
      $optionIndex++;
    }

    $jsCode = "$('#{$uploadifyID}').uploadify({ {$parameterList} });";
                      
    $cs->registerScript(__CLASS__."#{$uploadifyID}", $jsCode, CClientScript::POS_READY);       
  }
  
}

?>
