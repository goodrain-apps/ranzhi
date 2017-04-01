<?php
/**
 * The control file of misc module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     misc 
 * @version     $Id: control.php 4150 2016-10-17 08:06:43Z liugang $
 * @link        http://www.ranzhico.com
 */
class misc extends control
{
    /**
     * keep logon function.
     * 
     * @access public
     * @return void
     */
    public function ping($notice = '')
    {
        /* Save attend info. */
        if(commonModel::isAvailable('attend'))
        {
            $this->app->loadModuleConfig('attend', 'oa');
            if($this->config->attend->mustSignOut == 'no') $this->loadModel('attend', 'oa')->signOut();
        }

        /* Save online status. */
        $this->loadModel('user')->online();
        /* Get notices. */
        $notices = $this->loadModel('action')->getUnreadNotice('', $skipNotice = $notice);

        $res = new stdclass();
        $res->time    = helper::now();
        $res->notices = $notices;
        die(json_encode($res));
    }

    /**
     * Show about info of zentao.
     * 
     * @access public
     * @return void
     */
    public function about()
    {
        die($this->display());
    }

    /**
     * Support PATH_INFO or not.
     * 
     * @access public
     * @return void
     */
    public function pathinfo()
    {
        die('pathinfo');
    }

    /**
     * ignoreNotice  
     * 
     * @param  string $version 
     * @access public
     * @return void
     */
    public function ignoreNotice($version)
    {
        $ignore = isset($config->ignoreNotice) ? json_decode($config->ignoreNotice) : array();
        $ignore[] = strip_tags(trim($version));
        $this->loadModel('setting')->setItem('system.sys.common.global.ignoreNotice', json_encode($ignore));
        die('success');
    }
}
