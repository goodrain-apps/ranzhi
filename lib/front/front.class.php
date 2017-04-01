<?php
/**
 * ZenTaoPHP的前端类。
 * The front class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 * 
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

helper::import(dirname(dirname(__FILE__)) . '/base/front/front.class.php');
/**
 * html类，生成html标签。
 * The html class, to build html tags.
 * 
 * @package framework
 */
class html extends baseHTML
{
    /**
     * 生成单选按钮。
     * Create tags like "<input type='radio' />"
     *
     * @param  string $name       the name of the radio tag.
     * @param  array  $options    the array to create radio tag from.
     * @param  string $checked    the value to checked by default.
     * @param  string $attrib     other attribs.
     * @param  string $type       inline or block
     * @static
     * @access public
     * @return string
     */
    static public function radio($name = '', $options = array(), $checked = '', $attrib = '', $type = 'inline')
    {
        $options = (array)($options);
        if(!is_array($options) or empty($options)) return false;
        $isBlock = $type == 'block';

        $string  = '';
        foreach($options as $key => $value)
        {
            if($isBlock) $string .= "<div class='radio'><label>";
            else $string .= "<label>";
            $string .= "<input type='radio' name='$name' value='$key' ";
            $string .= ($key == $checked) ? " checked ='checked'" : "";
            $string .= $attrib;
            $string .= " id='$name$key' /> ";
            $string .= $value;
            if($isBlock) $string .= '</label></div>';
            else $string .= '</label>';
        }
        return $string;
    }

    /**
     * 生成多选按钮。
     * Create tags like "<input type='checkbox' />"
     *
     * @param  string $name      the name of the checkbox tag.
     * @param  array  $options   the array to create checkbox tag from.
     * @param  string $checked   the value to checked by default, can be item1,item2
     * @param  string $attrib    other attribs.
     * @param  string $type       inline or block
     * @static
     * @access public
     * @return string
     */
    static public function checkbox($name, $options, $checked = "", $attrib = "", $type = 'inline')
    {
        $options = (array)($options);
        if(!is_array($options) or empty($options)) return false;

        if(is_array($checked)) $checked = implode(',', $checked);
        $string  = '';
        $checked = ",$checked,";
        $isBlock = $type == 'block';

        foreach($options as $key => $value)
        {
            $key     = str_replace('item', '', $key);
            if($isBlock) $string .= "<div class='checkbox'><label>";
            else $string .= "<label class='checkbox'>";
            $string .= "<input type='checkbox' name='{$name}[]' value='$key' ";
            $string .= (strpos($checked, ",$key,") !== false) ? " checked ='checked'" : "";
            $string .= $attrib;
            $string .= " id='$name$key' /> ";
            $string .= $value;
            if($isBlock) $string .= '</label></div>';
            else $string .= '</label>';
        }
        return $string;
    }
}

/**
 * JS类。
 * JS class.
 * 
 * @package front
 */
class js extends baseJS
{

    /**
     * Export the config vars for createLink() js version.
     *
     * @static
     * @access public
     * @return void
     */
    static public function exportConfigVars()
    {
        global $app, $config, $lang;
        $defaultViewType = $app->getViewType();
        $themeRoot       = $app->getWebRoot() . 'theme/';
        $moduleName      = $app->getModuleName();
        $methodName      = $app->getMethodName();
        $clientLang      = $app->getClientLang();
        $runMode         = RUN_MODE;
        $requiredFields  = '';
        if(isset($config->$moduleName->require->$methodName)) $requiredFields = str_replace(' ', '', $config->$moduleName->require->$methodName);

        $jsConfig = new stdclass();
        $jsConfig->webRoot        = $config->webRoot;
        $jsConfig->appName        = $app->getAppName();
        $jsConfig->cookieLife     = ceil(($config->cookieLife - time()) / 86400);
        $jsConfig->requestType    = $config->requestType;
        $jsConfig->requestFix     = $config->requestFix;
        $jsConfig->moduleVar      = $config->moduleVar;
        $jsConfig->methodVar      = $config->methodVar;
        $jsConfig->viewVar        = $config->viewVar;
        $jsConfig->defaultView    = $defaultViewType;
        $jsConfig->themeRoot      = $themeRoot;
        $jsConfig->currentModule  = $moduleName;
        $jsConfig->currentMethod  = $methodName;
        $jsConfig->clientLang     = $clientLang;
        $jsConfig->requiredFields = $requiredFields;
        $jsConfig->save           = $lang->save;
        $jsConfig->router         = $app->server->SCRIPT_NAME;
        $jsConfig->runMode        = $runMode;
        $jsConfig->timeout        = $config->timeout;
        $jsConfig->pingInterval   = $config->pingInterval;

        $js  = self::start(false);
        $js .= 'var config=' . json_encode($jsConfig);
        $js .= self::end();
        echo $js;
    }
}

/**
 * css类。
 * css class.
 *
 * @package front
 */
class css extends baseCSS
{
    /**
     * 引入css文件。
     * Import a css file.
     * 
     * @param  string $url 
     * @access public
     * @return void
     */
    public static function import($url, $attrib = '')
    {
        global $config;
        if(!empty($attrib)) $attrib = ' ' . $attrib;
        echo "<link rel='stylesheet' href='$url?v={$config->version}' type='text/css' media='screen'{$attrib} />\n";
        echo "<link rel='stylesheet' href='$url?v={$config->version}' type='text/css' media='print'{$attrib} />\n";
    }
}
