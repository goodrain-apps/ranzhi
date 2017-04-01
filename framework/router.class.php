<?php
/**
 * router类从baseRouter类集成而来，您可以通过修改这个文件实现对baseRouter类的扩展。
 * The router class extend from baseRouter class, you can extend baseRouter class by change this file.
 *
 * @package framework
 *
 * The author disclaims copyright to this source code. In place of 
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */
define('FRAME_ROOT', dirname(__FILE__));
include FRAME_ROOT . '/base/router.class.php';
class router extends baseRouter
{
    /**
     * The root directory of static.
     * 
     * @var string
     * @access public 
     */
    public $staticRoot;

    /**
     * 构造方法, 设置路径，类，超级变量等。注意：
     * 1.应该使用createApp()方法实例化router类；
     * 2.如果$appRoot为空，框架会根据$appName计算应用路径。
     *
     * The construct function.
     * Prepare all the paths, classes, super objects and so on.
     * Notice: 
     * 1. You should use the createApp() method to get an instance of the router.
     * 2. If the $appRoot is empty, the framework will compute the appRoot according the $appName
     *
     * @param string $appName   the name of the app 
     * @param string $appRoot   the root path of the app
     * @access public
     * @return void
     */
    public function __construct($appName = 'demo', $appRoot = '')
    {
        parent::__construct($appName, $appRoot);
        $this->setAppName($appName);
        $this->setStaticRoot();
        $this->setDataRoot();
        $this->setThemeRoot();
    }

    /**
     * 创建一个应用。
     * Create an application.
     * 
     * @param string $appName   应用名称。  The name of the app.
     * @param string $appRoot   应用根路径。The root path of the app.
     * @param string $className 应用类名，如果对router类做了扩展，需要指定类名。When extends router class, you should pass in the child router class name.
     * @static
     * @access public
     * @return object   the app object
     */
    public static function createApp($appName = 'demo', $appRoot = '', $className = '')
    {
        if(empty($className)) $className = __CLASS__;
        return new $className($appName, $appRoot);
    }

    /**
     * Set the static root.
     * 
     * @access protected
     * @return void
     */
    public function setStaticRoot()
    {
        $this->staticRoot = $this->basePath . 'www' . DS;
    }

    /**
     * 设置www的根目录。
     * Set the www root.
     * 
     * @access public
     * @return void
     */
    public function setWwwRoot()
    {
        $this->wwwRoot = rtrim(dirname(dirname($_SERVER['SCRIPT_FILENAME'])), DS) . DS;
    }

    /**
     * Set the data root.
     * 
     * @access protected
     * @return void
     */
    public function setDataRoot()
    {
        $this->dataRoot = $this->staticRoot . 'data' . DS;
    }

    /**
     * Set the theme root.
     * 
     * @access protected
     * @return void
     */
    public function setThemeRoot()
    {
        $this->themeRoot = $this->staticRoot . 'theme' . DS;
    }

    /**
     * 设置模块的根目录。
     * Set the module root.
     * 
     * @access public
     * @return void
     */
    public function setModuleRoot()
    {
        $this->moduleRoot = $this->appRoot;
    }

   /**
     * 设置客户端的设备类型。
     * Set client device.
     * 
     * @access public
     * @return void
     */
    public function setClientDevice()
    {
        $mobile = new mobile();
        $this->clientDevice = ($mobile->isMobile() and !$mobile->isTablet()) ? 'mobile' : 'desktop';
    }

    /**
     * Get the $staticRoot var
     * 
     * @access public
     * @return string
     */
    public function getStaticRoot()
    {
        return $this->staticRoot;
    }

    /**
     * 获取$URL。
     * Get the $URL.
     * 
     * @param  bool $full  true, the URI contains the webRoot, else only hte URI.
     * @access public
     * @return string
     */
    public function getURI($full = false)
    {
        if($full and $this->config->requestType == 'PATH_INFO')
        {
            if($this->URI) return $this->config->webRoot . $this->appName . '/' . $this->URI . '.' . $this->viewType;
            return $this->config->webRoot . $this->appName . '/';
        }
        return $this->URI;
    }

    /**
     * 设置要被调用的控制器文件。
     * Set the control file of the module to be called.
     * 
     * @param   bool    $exitIfNone     没有找到该控制器文件的情况：如果该参数为true，则终止程序；如果为false，则打印错误日志
     *                                  If control file not foundde, how to do. True, die the whole app. false, log error.
     * @access  public
     * @return  bool
     */
    public function setControlFile($exitIfNone = true)
    {
        $this->controlFile = $this->moduleRoot . $this->moduleName . DS . 'control.php';
        if(!is_file($this->controlFile)) $this->controlFile = $this->basePath . 'app' . DS . 'sys' . DS . $this->moduleName . DS . 'control.php';
        if(!is_file($this->controlFile))
        {
            $this->triggerError("the control file $this->controlFile not found.", __FILE__, __LINE__, $exitIfNone);
            return false;
        }
        return true;
    }

    /**
     * 获取一个模块的路径。
     * Get the path of one module.
     * 
     * @param  string $appName    the app name
     * @param  string $moduleName    the module name
     * @access public
     * @return string the module path
     */
    public function getModulePath($appName = '', $moduleName = '')
    {
        if($moduleName == '') $moduleName = $this->moduleName;
        $modulePath = parent::getModulePath($appName, $moduleName);
        if(!is_dir($modulePath)) $modulePath = parent::getModulePath('sys', $moduleName);

        return $modulePath;
    }

    /**
     * 获取一个模块的扩展路径。 Get extension path of one module.
     * 
     * If the extensionLevel == 0, return empty array.
     * If the extensionLevel == 1, return the common extension directory.
     * If the extensionLevel == 2, return the common and site extension directories.
     *
     * @param   string $appName        the app name
     * @param   string $moduleName     the module name
     * @param   string $ext            the extension type, can be control|model|view|lang|config
     * @access  public
     * @return  string the extension path.
     */
    public function getModuleExtPath($appName, $moduleName, $ext)
    {
        $paths    = parent::getModuleExtPath($appName, $moduleName, $ext);
        $sysPaths = parent::getModuleExtPath('sys', $moduleName, $ext);
        if(isset($paths['common']) and !is_dir($paths['common'])) $paths['common'] = $sysPaths['common'];
        if(isset($paths['site']) and !is_dir($paths['site']))     $paths['site']   = $sysPaths['site'];
        return $paths;
    }

    /**
     * 设置Action的扩展文件。 Set the action extension file.
     * 
     * @access  public
     * @return  bool
     */
    public function setActionExtFile()
    {
        $moduleExtPaths = $this->getModuleExtPath($this->appName, $this->moduleName, 'control');
        
        /* 如果扩展目录为空，不包含任何扩展文件。If there's no ext pathes return false.*/
        if(empty($moduleExtPaths)) $moduleExtPaths = $this->getModuleExtPath('sys', $this->moduleName, 'control');
        if(empty($moduleExtPaths)) return false;

        /* 如果extensionLevel == 2，且扩展文件存在，返回该站点扩展文件。If extensionLevel == 2 and site extensionFile exists, return it. */
        if($this->config->framework->extensionLevel == 2 and !empty( $moduleExtPaths['site']))
        {
            $this->extActionFile = $moduleExtPaths['site'] . $this->methodName . '.php';
            if(file_exists($this->extActionFile)) return true;
        }

        /* 然后再尝试寻找公共扩展文件。Then try to find the common extension file. */
        $this->extActionFile = $moduleExtPaths['common'] . $this->methodName . '.php';
        return file_exists($this->extActionFile);
    }

    /**
     * 设置一个模块的model文件，如果存在model扩展，一起合并。
     * Set the model file of one module. If there's an extension file, merge it with the main model file.
     * 
     * @param   string $moduleName the module name
     * @param   string $appName the app name
     * @static
     * @access  public
     * @return  string the model file
     */
    public function setModelFile($moduleName, $appName = '')
    {
        if($appName == '') $appName = $this->getAppName();

        /* 设置主model文件。 Set the main model file. */
        $mainModelFile = $this->getModulePath($appName, $moduleName) . 'model.php';
        if(!file_exists($mainModelFile)) $appName = 'sys';
        return parent::setModelFile($moduleName, $appName);
    }

    /**
     * 加载模块的config文件，返回全局$config对象。
     * 如果该模块是common，加载$configRoot的配置文件，其他模块则加载其模块的配置文件。
     *
     * Load config and return it as the global config object.
     * If the module is common, search in $configRoot, else in $modulePath.
     *
     * @param   string $moduleName     module name
     * @param   string $appName        app name
     * @param   bool   $exitIfNone     exit or not
     * @access  public
     * @return  object|bool the config object or false.
     */
    public function loadModuleConfig($moduleName, $appName = '')
    {
        global $config;

        if($config and (!isset($config->$moduleName) or !is_object($config->$moduleName))) $config->$moduleName = new stdclass();

        /* 初始化数组。Init the variables. */
        $extConfigFiles       = array();
        $commonExtConfigFiles = array();
        $siteExtConfigFiles   = array();

        /* 先获得模块的主配置文件。Get the main config file for current module first. */
        $mainConfigFile = $this->getModulePath($appName, $moduleName) . 'config.php';

        /* 查找扩展配置文件。Get extension config files. */
        if($config->framework->extensionLevel > 0) $extConfigPath = $this->getModuleExtPath($appName, $moduleName, 'config');
        if($config->framework->extensionLevel >= 1 and !empty($extConfigPath['common'])) $commonExtConfigFiles = helper::ls($extConfigPath['common'], '.php');
        if($config->framework->extensionLevel == 2 and !empty($extConfigPath['site']))   $siteExtConfigFiles   = helper::ls($extConfigPath['site'], '.php');
        $extConfigFiles = array_merge($commonExtConfigFiles, $siteExtConfigFiles);

        /* 将主配置文件和扩展配置文件合并在一起。Put the main config file and extension config files together. */
        $configFiles = array_merge(array($mainConfigFile), $extConfigFiles);

        /* 加载每一个配置文件。Load every config file. */
        static $loadedConfigs = array();
        foreach($configFiles as $configFile)
        {
            if(in_array($configFile, $loadedConfigs)) continue;
            if(file_exists($configFile)) include $configFile;
            $loadedConfigs[] = $configFile;
        }
        
        if($moduleName != 'common' and isset($config->system->$moduleName))
        {   
            helper::mergeConfig($config->system->$moduleName, $moduleName);
        }
        if($moduleName != 'common' and isset($config->personal->$moduleName))
        {   
            helper::mergeConfig($config->personal->$moduleName, $moduleName);
        }
    }

    /**
     * 加载语言文件，返回全局$lang对象。
     * Load lang and return it as the global lang object.
     * 
     * @param   string $moduleName     the module name
     * @param   string $appName     the app name
     * @access  public
     * @return  bool|ojbect the lang object or false.
     */
    public function loadLang($moduleName, $appName = '')
    {
        if($moduleName == 'common' and $appName == '') $appName = 'sys';;
        $lang = parent::loadLang($moduleName, $appName);

        /* Merge from the db lang. */
        if(empty($appName)) $appName = $this->appName;

        $customLang = array();
        if(isset($lang->db->custom[$appName][$moduleName])) $customLang += $lang->db->custom[$appName][$moduleName];
        if(isset($lang->db->custom['sys'][$moduleName])) $customLang += $lang->db->custom['sys'][$moduleName];

        foreach($customLang as $section => $fields)
        {
            if(empty($section))
            {
                foreach($fields as $key => $value)
                {
                    if($moduleName == 'common')
                    {
                        unset($lang->{$key});
                        $lang->{$key} = $value;
                    }
                    else
                    {
                        if(!isset($lang->{$moduleName})) $lang->{$moduleName} = new stdclass();
                        unset($lang->{$moduleName}->{$key});
                        $lang->{$moduleName}->{$key} = $value;
                    }
                }
            }
            else
            {
                foreach($fields as $key => $value)
                {
                    if($moduleName == 'common')
                    {
                        unset($lang->{$section}[$key]);
                        $lang->{$section}[$key] = $value;
                    }
                    else
                    {
                        unset($lang->{$moduleName}->{$section}[$key]);
                        $lang->{$moduleName}->{$section}[$key] = $value;
                    }
                }
            }
        }

        $this->lang = $lang;
        return $lang;
    }
}
