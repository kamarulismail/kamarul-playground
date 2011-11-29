<?php
/*
 * TextCounter jQuery Extension  - add character/word count to input
 * @yiiVersion 1.1.6
 */

/**
 * Description of TextCounter
 *
 *
 * @author Kamarul Ariffin Ismail <kamarul.ismail@gmail.com>
 * @version 1.0
 */

class TextCounter extends CWidget
{
  public $items       = array();
  public $htmlOptions = array();
  
  private $_baseUrl;

  public function init()
  {
    $this->htmlOptions['id'] = $this->getId();

    // GET RESOURCE PATH
		$resources = dirname(__FILE__).DIRECTORY_SEPARATOR.'resources';
    
		// PUBLISH FILES
    $this->_baseUrl = Yii::app()->assetManager->publish($resources, false, -1, YII_DEBUG);

  }
  
  public function run()
  {
    // REGISTER JS SCRIPT
    $cs = Yii::app()->clientScript;
    $cs->registerScriptFile($this->_baseUrl.'/jquery.textCounter.js');
    
    // GENERATE JS CODE
    $items = $this->items;
    foreach($items as $item){
      $textCounterID    = '';
      $displayCounterID = '';      
      $htmlOptions      = ( isset($item['htmlOptions']) ) ? $item['htmlOptions'] : '';
      $params           = array();
      
      if(isset($item['maxCharacterSize']))
      {
        $params['maxCharacterSize'] = $item['maxCharacterSize'];
      }

      if(isset($item['displayFormat']))
      {
        $params['displayFormat'] = $item['displayFormat'];
      }
      
      if(isset($item['id']))
      {
        if(is_array($item['id']))
        {
          $model     = $item['id']['model'];
          $attribute = $item['id']['attribute'];
          CHtml::resolveNameID($model, $attribute, $htmlOptions);

          $textCounterID = $htmlOptions['id'];
        }
        else
        {
          $textCounterID = $item['id'];
        }
      }      

      if(isset($item['displayCounter']))
      {        
        $displayCounterID = trim($item['displayCounter']);

        if($displayCounter == '')
        {
          $displayCounterID = '#'.$textCounterID.'_counter';
        }
      }
      else
      {
        $displayCounterID = '#'.$textCounterID.'_counter';
      }
      $params['displayCounter'] = $displayCounterID;
      
      if(isset($item['afterErrorMessage']))
      {
        if ($item['afterErrorMessage'] != '') $errorMessageID = '#'.$textCounterID.'_em_';
        else $errorMessageID = '';
      }
      else
      {
        $errorMessageID = '';
      }
      $params['errorMessageID'] = $errorMessageID;
      
      if(isset($item['displayTemplate']))
      {
        $displayTemplate = trim($item['displayTemplate']);
        if($displayTemplate == '')
        {
          $displayTemplate = '<div>{displayFormat}</div>';
        }
      }
      else
      {
        $displayTemplate = '<div>{displayFormat}</div>';
      }
      $params['displayTemplate'] = $displayTemplate;

      if(!empty($textCounterID))
      {
        $jsCode = "\n\$('#".$textCounterID."').TextCounter(".CJavaScript::encode($params).");";
        $cs->registerScript(__CLASS__.'#'.$textCounterID, $jsCode, CClientScript::POS_READY);
      }
    }    
  }  
}
?>