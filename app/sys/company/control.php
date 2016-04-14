<?php
/**
 * The control file of company module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     company 
 * @version     $Id: control.php 3385 2015-12-18 08:02:09Z liugang $
 * @link        http://www.ranzhico.com
 */
class company extends control
{
    /**
     * The index page.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $this->view->title   = isset($this->config->company->name) ? $this->config->company->name : '';
        $this->view->company = $this->config->company;
        $this->display();
    }

    /**
     * set company basic info.
     * 
     * @access public
     * @return void
     */
    public function setBasic()
    {
        if(!empty($_POST))
        {
            $now = helper::now();
            $company = fixer::input('post')->stripTags('content', $this->config->allowedTags->admin)->get();
            $company = $this->loadModel('file')->processEditor($company, $this->config->company->editor->setbasic['id']);

            $result = $this->loadModel('setting')->setItems('system.sys.common.company', $company);
            if($result) $this->send(array('result' => 'success', 'message' => $this->lang->setSuccess));
            $this->send(array('result' => 'fail', 'message' => $this->lang->fail));
        }

        $this->view->title = $this->lang->company->setBasic;
        $this->display();
    }
}
