<?php
/**
 * The control file of notice module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Xiying Guan <gunaxiying@xirangit.com>
 * @package     notice 
 * @version     $Id: control.php 7417 2013-12-23 07:51:50Z wwccss $
 * @link        http://www.ranzhico.com
 */
class notice extends control
{
    /**
     * The notice page.
     * 
     * @access public
     * @return void
     */
    public function index($type, $locate = '')
    {
        $this->view->title   = $this->lang->notice->common;
        $this->view->message = isset($this->lang->notice->typeList[$type]) ? $this->lang->notice->typeList[$type] : $this->lang->notice->typeList['notFound'];
        $this->view->locate  = helper::safe64Decode($locate);

        $this->display();
    }
}
