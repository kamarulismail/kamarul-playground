<?php

class FlexPaper extends CWidget
{
  public $viewerHeight;
  public $viewerWidth;
  public $viewerContainer;  
  public $sourceFile;
  public $options;  
    
  private $_baseUrl;
  
  public function init()
  {        
    // GET RESOURCE PATH
    $extensionPath = Yii::getPathOfAlias('ext.flexPaper');
    $resources     = $extensionPath.'/resources';
    
    // PUBLISH FILES
    $this->_baseUrl = Yii::app()->assetManager->publish($resources, false, -1, YII_DEBUG);
  }
  
  public function run()
  {
    if(empty($this->sourceFile))
    {
      Yii::log('Source File is not set.', CLogger::LEVEL_ERROR, 'FlexPaper');
      return false;
    }
    
    // REGISTER JS SCRIPT
    $cs = Yii::app()->clientScript;
    $cs->registerCoreScript('jquery');
        
    $serverName = $_SERVER['SERVER_NAME'];
    if(preg_match("/(yakimbi.com)$/im", $serverName))
    {      
      // LICENSE VERSION - DOMAIN(yakimbi.com)
      $cs->registerScriptFile($this->_baseUrl.'/flexpaper_flash.js');    
    }
    else
    {
      // FREE VERSION - ANY DOMAIN
      $cs->registerScriptFile($this->_baseUrl.'/flexpaper_flash_free.js');
    }
    
    // VIEWER SCRIPT OPTIONS
    $options = array_merge(
            array(
                'SwfFile' => $this->sourceFile,
                'Scale'   => 0.6,
                'ZoomTransition' => 'easeOut',
                'ZoomTime'       => 0.5,
                'ZoomInterval'   => 0.2,
                'FitPageOnLoad'  => true,
                'FitWidthOnLoad' => false,
                'PrintEnabled'   => true,
                'FullScreenAsMaxWindow' => false,
                'ProgressiveLoading'    => false,
                'MinZoomSize'    => 0.2,
                'MaxZoomSize'    => 5,
                'SearchMatchAll' => false,
                'InitViewMode'   => 'Portrait',
                'ViewModeToolsVisible' => true,
                'ZoomToolsVisible'     => true,
                'NavToolsVisible'      => true,
                'CursorToolsVisible'   => true,
                'SearchToolsVisible'   => true,
                'localeChain' => 'en_US',                                
            ),
            $this->options
        );
    
    // FLEXPAPER LICENSE - DOMAIN (yakimbi.com)
    if(preg_match("/(yakimbi.com)$/im", $serverName))
    {            
      $options['key'] = '$bc56dc79aa2da4ec9df';    
    }    
    
    $viewerHeight = $this->viewerHeight;
    if(empty($viewerHeight))
    {
      $viewerHeight = '800px';
    }
        
    $viewerWidth = $this->viewerWidth;
    if(empty($viewerWidth))
    {
      $viewerWidth = '600px';
    }
    
    $viewerContainer = $this->viewerContainer;
    if(empty($viewerContainer))
    {
      $viewerContainer = 'viewerPlaceHolder';
    }
        
    $htmlOptions = array(
        'id'    => $viewerContainer,
        'style' => "width:{$viewerWidth}; height:{$viewerHeight}; display:block;",
    );
    
    // FLEXPAPER VIEWER
    $viewerPath = $this->_baseUrl.'/FlexPaperViewerFree'; 
    // LICENSE VERSION - DOMAIN (yakimbi.com)
    if(preg_match("/(yakimbi.com)$/im", $serverName)) 
    {      
      $viewerPath = $this->_baseUrl.'/FlexPaperViewerNoPrint'; 
      //Enable Printing
      if($options['PrintEnabled'] == true)
      {
        $viewerPath = $this->_baseUrl.'/FlexPaperViewer';
      }
    }
    
    // GENERATE VIEWER    
    echo CHtml::tag('div', $htmlOptions, '&nbsp;');
    echo chr(13);
    
    // GENERATE VIEWER SCRIPT
    echo CHtml::openTag('script', array('type' => 'text/javascript'));
    echo chr(13);
    echo 'var fp = new FlexPaperViewer( ';
    echo "'{$viewerPath}', ";
    echo "'{$viewerContainer}', ";
    echo '{ config : { ';
    
    $optionCount = count($options);
    $index = 1;
    foreach($options as $parameter => $value)
    {
      $value = self::paramsFormatter($parameter, $value);
      echo "{$parameter} : {$value} ";
      echo ($index < $optionCount) ? ', ' : '';      
      $index++;
    }
    echo ' } }); '.chr(13);
    echo 'function onDocumentLoadedError(errMessage){ $("#viewerPlaceHolder").html(errMessage); }'.chr(13);
    echo CHtml::closeTag('script');            
  }
  
  private function paramsFormatter($parameter, $value)
  {
    switch($parameter)
    {
      case 'SwfFile':
        $value = "escape('{$value}')";
        break;
      
      case 'ZoomTransition':
      case 'InitViewMode':
      case 'localeChain':
      case 'key':
        $value = "'{$value}'";
        break;
      
      case 'FitPageOnLoad':
      case 'FitWidthOnLoad':
      case 'PrintEnabled':
      case 'FullScreenAsMaxWindow':
      case 'ProgressiveLoading':
      case 'SearchMatchAll':
      case 'ViewModeToolsVisible':
      case 'ZoomToolsVisible':
      case 'NavToolsVisible':
      case 'CursorToolsVisible':
      case 'SearchToolsVisible':
        $value = ( ($value == true) || ($value == 'true') ) ? 'true' : 'false';
        break;
      
      default:
        break;
    }
    
    return $value;
  }
}
?>
