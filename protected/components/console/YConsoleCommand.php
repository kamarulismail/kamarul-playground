<?php

class YConsoleCommand extends CConsoleCommand
{

    public $basePath;
    public $webRoot;        

    public function __construct($name, $runner)
    {
        parent::__construct($name, $runner);

        //SET BASEPATH
        $this->basePath = realpath(Yii::app()->basePath . '\..');

        //SET WEBROOT
        $webRoot = realpath('.');
        Yii::app()->setAliases(array('webroot' => $webRoot));
        $this->webRoot = $webRoot;
    }
    
    /**
	 * This method is invoked right before an action is to be executed.
	 * You may override this method to do last-minute preparation for the action.
	 * @param string $action the action name
	 * @param array $params the parameters to be passed to the action method.
	 * @return boolean whether the action should be executed.
	 */
	protected function beforeAction($action,$params)
	{
		return true;
	}

	/**
	 * This method is invoked right after an action finishes execution.
	 * You may override this method to do some postprocessing for the action.
	 * @param string $action the action name
	 * @param array $params the parameters to be passed to the action method.
	 */
	protected function afterAction($action,$params)
	{
	}
    
    /**
	 * Parses the command line arguments and determines which action to perform.
	 * @param array $args command line arguments
	 * @return array the action name, named options (name=>value), and unnamed options
	 * @since 1.1.5
	 */
	protected function resolveRequest($args)
	{
		$options=array();	// named parameters
		$params=array();	// unnamed parameters
		foreach($args as $arg)
		{
			if(preg_match('/^--(\w+)(=(.*))?$/',$arg,$matches))  // an option
			{
				$name=$matches[1];
				$value=isset($matches[3]) ? $matches[3] : true;
				if(isset($options[$name]))
				{
					if(!is_array($options[$name]))
						$options[$name]=array($options[$name]);
					$options[$name][]=$value;
				}
				else
					$options[$name]=$value;
			}
			else if(isset($action))
				$params[]=$arg;
			else
				$action=$arg;
		}
		if(!isset($action))
			$action=$this->defaultAction;

		return array($action,$options,$params);
	}

    public function actionIndex()
    {
        echo "\n******************************" .
        "\n*" . __CLASS__ . '.' . __FUNCTION__ .
        "\n******************************\n";
    }

    public function import($alias, $forceInclude=false)
    {
        if (!empty($alias))
        {                        
            $arrAlias = explode('.', $alias);
            $baseFile = array_pop($arrAlias);
            $basePath = implode('.', $arrAlias);                        
            
            if($baseFile == '*')
            {
                $dirPath  = Yii::getPathOfAlias($basePath);
                $fileList = scandir( $dirPath );
                
                foreach($fileList as $file)
                {
                    if(is_file($dirPath . DIRECTORY_SEPARATOR . $file))
                    {
                        $classFile = $basePath.'.'.pathinfo($file, PATHINFO_FILENAME);
                        Yii::import($classFile, $forceInclude);
                    }
                }                                
            }
            else
            {
                Yii::import($alias, $forceInclude);
            }            
        }
    }
    
    public static function generateConsoleConfigFile()
    {        
        $configPath = Yii::getPathOfAlias('application.config');
        $configFile = $configPath . DIRECTORY_SEPARATOR . 'console-private.php';
        
        if(!file_exists($configFile))
        {
            $content = "<?php \n".
                       "return array(\n".
                       "    'components' => array(\n".
                       "        'request' => array(\n".
                       "            'hostInfo' => '". Yii::app()->request->getHostInfo() . "',\n".
                       "            'baseUrl' => '". Yii::app()->request->getBaseUrl() ."', \n".
                       "            'scriptUrl' => '". Yii::app()->request->getScriptUrl() ."',\n".
                       "        ),\n".
                       "        'urlManager' => array(\n".
                       "            'urlFormat' => '". Yii::app()->getUrlManager()->getUrlFormat() . "',\n".
                       "        ),\n".
                       "    ),\n".
                       ");\n";
            
            @file_put_contents($configFile, $content);
        }
        
        
    }

}
