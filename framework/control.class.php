<?php
/**
 * Control类从baseControl类继承而来，每个模块的control对象从control类集成。
 * 您可以对baseControl类进行扩展，扩展的逻辑可以定义在这个文件中。
 *
 * This control class extends from the baseControl class and extened by every module's control. 
 * You can extend the baseControl class by change this file.
 *
 * @package framework
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */
include FRAME_ROOT . '/base/control.class.php';
class control extends baseControl
{
    /**
     * 获取一个方法的输出内容，这样我们可以在一个方法里获取其他模块方法的内容。
     * 如果模块名为空，则调用该模块、该方法；如果设置了模块名，调用指定模块指定方法。
     *
     * Get the output of one module's one method as a string, thus in one module's method, can fetch other module's content.
     * If the module name is empty, then use the current module and method. If set, use the user defined module and method.
     *
     * @param   string  $moduleName    module name.
     * @param   string  $methodName    method name.
     * @param   array   $params        params.
     * @access  public
     * @return  string  the parsed html.
     */
    public function fetch($moduleName = '', $methodName = '', $params = array(), $appName = '')
    {
        if($moduleName == '') $moduleName = $this->moduleName;
        if($methodName == '') $methodName = $this->methodName;
        if($appName == '')    $appName    = $this->appName;
        if($moduleName == $this->moduleName and $methodName == $this->methodName) 
        {
            $this->parse($moduleName, $methodName);
            return $this->output;
        }

        $currentPWD = getcwd();

        /**
         * 设置引用的文件和路径。
         * Set the pathes and files to included.
         **/
        $modulePath        = $this->app->getModulePath($appName, $moduleName);
        $moduleControlFile = $modulePath . 'control.php';
        $actionExtPath     = $this->app->getModuleExtPath($appName, $moduleName, 'control');
        $file2Included     = $moduleControlFile;

        if(!empty($actionExtPath))
        {
            $commonActionExtFile = $actionExtPath['common'] . strtolower($methodName) . '.php';
            $file2Included       = file_exists($commonActionExtFile) ? $commonActionExtFile : $moduleControlFile;

            if(!empty($actionExtPath['site']))
            {
                $siteActionExtFile = $actionExtPath['site'] . strtolower($methodName) . '.php';
                $file2Included     = file_exists($siteActionExtFile) ? $siteActionExtFile : $file2Included;
            }
        }

        /**
         * 加载控制器文件。
         * Load the control file. 
         */
        if(!is_file($file2Included)) $this->app->triggerError("The control file $file2Included not found", __FILE__, __LINE__, $exit = true);
        chdir(dirname($file2Included));
        if($moduleName != $this->moduleName) helper::import($file2Included);

        /**
         * 设置调用的类名。
         * Set the name of the class to be called. 
         */
        $className = class_exists("my$moduleName") ? "my$moduleName" : $moduleName;
        if(!class_exists($className)) $this->app->triggerError(" The class $className not found", __FILE__, __LINE__, $exit = true);

        /**
         * 解析参数，创建模块control对象。
         * Parse the params, create the $module control object. 
         */
        if(!is_array($params)) parse_str($params, $params);
        $module = new $className($moduleName, $methodName, $appName);

        /**
         * 调用对应方法，使用ob方法获取输出内容。
         * Call the method and use ob function to get the output. 
         */
        ob_start();
        call_user_func_array(array($module, $methodName), $params);
        $output = ob_get_contents();
        ob_end_clean();

        /**
         * 返回内容。
         * Return the content. 
         */
        unset($module);

        chdir($currentPWD);
        return $output;
    }
}
