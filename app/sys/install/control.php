<?php
/**
 * The control file of install module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install 
 * @version     $Id: control.php 4041 2016-09-27 07:09:30Z liugang $
 * @link        http://www.ranzhico.com
 */
class install extends control
{
    /**
     * The construction function , check is install or not.
     * 
     * @access public
     * @return array
     */
    public function __construct()
    {
        if(!defined('RUN_MODE') or RUN_MODE != 'install') die('error');
        parent::__construct();
    }

    /**
     * The index page.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        if(!isset($this->config->installed) or !$this->config->installed) $this->session->set('installing', true);

        $this->view->title = $this->lang->install->welcome;
        $this->display();
    }

    /**
     * Checking agree license.
     * 
     * @access public
     * @return void
     */
    public function step0()
    {
        $this->view->title   = $this->lang->install->welcome;
        $this->view->license = file_get_contents($this->app->getBasePath() . 'doc/LICENSE');
        $this->display();
    }

    /**
     * Checking the system.
     * 
     * @access public
     * @return void
     */
    public function step1()
    {
        $this->view->title             = $this->lang->install->checking;
        $this->view->phpVersion        = $this->install->getPhpVersion();
        $this->view->phpResult         = $this->install->checkPHP();
        $this->view->pdoResult         = $this->install->checkPDO();
        $this->view->pdoMySQLResult    = $this->install->checkPDOMySQL();
        $this->view->tmpRootInfo       = $this->install->getTmpRoot();
        $this->view->tmpRootResult     = $this->install->checkTmpRoot();
        $this->view->dataRootInfo      = $this->install->getDataRoot();
        $this->view->dataRootResult    = $this->install->checkDataRoot();
        $this->view->iniInfo           = $this->install->getIniInfo();
        $this->view->sessionRoot       = session_save_path();
        $this->view->sessionRootResult = $this->install->checkSessionRoot();
        $this->display();
    }

    /**
     * Set the database.
     * 
     * @access public
     * @return void
     */
    public function step2()
    {
        $this->view->title = $this->lang->install->setConfig;
        $this->display();
    }

    /**
     * Create the config file.
     * 
     * @access public
     * @return void
     */
    public function step3()
    {
        if(!empty($_POST))
        {
            $return = $this->install->checkConfig();
            if($return->result == 'ok')
            {
                $result = $this->install->saveMyPHP();
                if($result->saved) $this->locate(inlink('step4'));

                $this->view->title  = $this->lang->install->saveConfig;
                $this->view->result = $result;
                $this->display();
            }
            else
            {
                $this->view->title = $this->lang->install->saveConfig;
                $this->view->error = $return->error;
                $this->display();
            }
        }
        else
        {
            $this->locate($this->createLink('install'));
        }
    }

    /**
     * Step4: create admin password and set the version.
     * 
     * @access public
     * @return array
     */
    public function step4()
    {
        if(!empty($_POST))
        {
            $this->install->installEntry();
            $this->install->createAdmin();

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError() ));
            $this->loadModel('setting')->updateVersion($this->config->version);
            session_destroy();
            $this->send(array('result' => 'success', 'message' => $this->lang->install->success, 'locate' => 'index.php'));
        }

        if(!isset($this->config->installed) or !$this->config->installed)
        {   
            $this->view->title = $this->lang->install->errorNotSaveConfig;
            $this->view->error = $this->lang->install->errorNotSaveConfig;
            $this->display();
        }
        else
        {
            $this->view->domainIP = gethostbyname($_SERVER['HTTP_HOST']);
            $this->view->serverIP = $_SERVER['SERVER_ADDR'];
            try
            {
                $socket   = socket_create(AF_INET, SOCK_STREAM, 6);  
                $ret      = socket_connect($socket,'ns1.dnspod.net',6666);  
                $publicIP = socket_read($socket, 16);  
                socket_close($socket);  
                $this->view->publicIP = $publicIP;
            }
            catch(Exception $exception)
            {
                $this->view->publicIP = '';
            }
            $this->view->title = $this->lang->install->setAdmin;
            $this->display();
        }
    }
}
