<?php
/**
 * The control file of index module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     index 
 * @version     $Id: control.php 4205 2016-10-24 08:19:13Z liugang $
 * @link        http://www.ranzhico.com
 */
class index extends control
{
    /**
     * Construct.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Index page.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $allEntries = '';
        $entries    = $this->loadModel('entry')->getEntries();

        foreach($entries as $entry)
        {
            $sso     = $this->createLink('entry', 'visit', "entryID=$entry->id");
            $logo    = !empty($entry->logo) ? $entry->logo : '';
            $size    = !empty($entry->size) ? ($entry->size != 'max' ? $entry->size : "'$entry->size'") : "'max'";
            $display = $entry->buildin ? 'fixed' : 'sizeable';
            $menu    = $entry->visible ? 'all' : 'list';
            
            /* add web root if logo not start with /  */
            if($logo != '' && substr($logo, 0, 1) != '/') $logo = $this->config->webRoot . $logo;
            
            if(!isset($entry->control))  $entry->control = '';
            if(!isset($entry->position)) $entry->position = '';
            $allEntries .= "entries.push(
            {
                id:       '$entry->id',
                code:     '$entry->code',
                name:     '$entry->name',
                url:      '$sso',
                open:     '$entry->open', 
                desc:     '$entry->name',
                size:      $size,
                icon:     '$logo',
                control:  '$entry->control',
                position: '$entry->position',
                menu:     '$menu',
                display:  '$display',
                abbr:     '$entry->abbr',
                order:    '$entry->order',
                sys:      '$entry->buildin',
                category: '$entry->category'
            });\n";
        }

        $blocks = $this->loadModel('block')->getBlockList();

        /* Init block when vist index first. */
        if(empty($blocks) and empty($this->config->blockInited))
        {
            if($this->loadModel('block')->initBlock('sys')) die(js::reload());
        }

        foreach($blocks as $key => $block)
        {
            $block->params = json_decode($block->params);
            if(empty($block->params)) $block->params = new stdclass();

            if(strpos('dynamic, allEntries, html, rss', $block->block) !== false) continue;

            if($block->source == 'zentao')
            {
                $block->moreLink = '';
                $block->appid    = 'zentao';
            }
            else
            {
                $moduleName = $block->block;
                if((isset($block->params->type) or isset($block->params->status)) and is_array($this->lang->block->moreLinkList->{$moduleName}))
                {
                    $type = isset($block->params->type) ? $block->params->type : $block->params->status;
                    if(isset($this->lang->block->moreLinkList->{$moduleName}[$type]))
                    {
                        list($label, $app, $module, $method, $vars) = explode('|', $this->lang->block->moreLinkList->{$moduleName}[$type]);
                        $block->moreLink = $this->createLink($app . '.' . $module, $method, $vars);
                        $block->appid    = $app == 'sys' ? 'dashboard' : $app;
                    }
                }
                else
                {
                    if(isset($this->lang->block->moreLinkList->{$moduleName}) and !is_array($this->lang->block->moreLinkList->{$moduleName}))
                    {
                        list($label, $app, $module, $method, $vars) = explode('|', $this->lang->block->moreLinkList->{$moduleName});
                        $block->moreLink = $this->createLink($app . '.' . $module, $method, $vars);
                        $block->appid    = $app == 'sys' ? 'dashboard' : $app;
                    }
                }
            }
        }

        /* Get custom setting about superadmin */
        $customApp = isset($this->config->personal->common->customApp) ? json_decode($this->config->personal->common->customApp->value) : new stdclass();
        if(isset($customApp->superadmin)) $this->view->superadmin = $customApp->superadmin;
        if(isset($customApp->dashboard))  $this->view->dashboard  = $customApp->dashboard;

        /* sign buttons. */
        $signButtons = '';
        $this->loadModel('attend', 'oa');
        if(time() < strtotime(date("Y-m-d") . " " . $this->config->attend->signInLimit . "+4 hour"))
        {
            $signButtons .= "<li>" . html::a('javascript:void(0)', $this->lang->signIn, "class='sign signin'") . "</li>";
        }
        if($this->config->attend->mustSignOut == 'yes' or time() > strtotime(date("Y-m-d") . " " . $this->config->attend->signOutLimit . "-4 hour")) 
        {
            $signButtons .= "<li>" . html::a('javascript:void(0)', $this->lang->signOut, "class='sign signout'") . "</li>";
        }

        $this->view->allEntries  = $allEntries;
        $this->view->blocks      = $blocks;
        $this->view->notice      = commonModel::isAvailable('attend') ? $this->attend->getNotice() : '';
        $this->view->signButtons = $signButtons;
        $this->display();
    }
}
