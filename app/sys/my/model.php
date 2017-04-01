<?php
/**
 * The model file of my module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     my
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class myModel extends model
{
    public function createModuleMenu($method)
    {
        if(!isset($this->lang->my->$method->menu)) return false;

        $isMobile = $this->app->getViewType() === 'mhtml';
        $string   = $isMobile ? '' : "<nav id='menu'><ul class='nav'>\n";
        
        $menuOrder = isset($this->lang->my->{$method}->menuOrder) ? $this->lang->my->{$method}->menuOrder : array();  

        /* Get menus of current module. */
        $moduleMenus = new stdclass(); 
        if(!empty($menuOrder))
        {
            ksort($menuOrder);
            foreach($this->lang->my->{$method}->menu as $methodName => $methodMenu)
            {
                if(!in_array($methodName, $menuOrder)) $menuOrder[] = $methodName;
            }

            foreach($menuOrder as $name)
            {
                if(isset($this->lang->my->{$method}->menu->$name)) $moduleMenus->$name = $this->lang->my->{$method}->menu->$name;
            }

            foreach($this->lang->my->{$method}->menu as $key => $value)
            {
                if(!isset($moduleMenus->$key)) $moduleMenus->$key = $value;
            }
        }
        else
        {
            $moduleMenus = $this->lang->my->$method->menu;  
        }

        /* Get menus of current module and current method. */
        $currentMethod = $this->app->getMethodName();

        /* Cycling to print every menus of current module. */
        foreach($moduleMenus as $methodName => $methodMenu)
        {
            /* Split the methodMenu to label, module, method, vars. */
            list($label, $module, $method, $vars) = explode('|', $methodMenu);

            if($method == 'review')
            {
                if($methodName == 'leave') $methodName = 'attend';
                if(!commonModel::hasPriv($methodName, 'review')) continue;
            }

            $class = '';
            if($method == $currentMethod) $class = "class='active'";

            $hasPriv = commonModel::hasPriv($module, $method);
            if($module == 'my' and $method == 'order')    $hasPriv = commonModel::hasPriv('order', 'browse');
            if($module == 'my' and $method == 'contract') $hasPriv = commonModel::hasPriv('contract', 'browse');
            if($hasPriv)
            {
                $link    = html::a(helper::createLink($module, $method, $vars), $label, $isMobile ? $class : '');
                $string .= $isMobile ? $link : "<li $class>$link</li>\n";
            }
        }

        $string .= $isMobile ? '' : "</ul></nav>\n";
        return $string;
    }
}
