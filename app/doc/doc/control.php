<?php
/**
 * The control file of doc module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     doc 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class doc extends control
{
    /**
     * Construct function, load user, tree, action auto.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->session->set('docFrom', 'doc');

        $this->libs = $this->doc->getLibPairs();
        $this->loadModel('user');
        $this->loadModel('tree');
        $this->loadModel('action');
        $this->loadModel('project', 'proj');
    }

    /**
     * Go to browse page.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $this->doc->setMainMenu();

        $projects   = $this->doc->getLimitLibs('project', '9');
        $customLibs = $this->doc->getLimitLibs('custom', '9');
        $subLibs    = $this->doc->getSubLibGroups(array_keys($projects));

        $this->view->title      = $this->lang->doc->common . $this->lang->colon . $this->lang->doc->index;
        $this->view->projects   = $projects;
        $this->view->customLibs = $customLibs;
        $this->view->subLibs    = $subLibs;
        $this->display();
    }

    /**
     * Show all libs.
     * 
     * @param  int    $type 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function allLibs($type, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->doc->setMainMenu();

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $libs    = $this->doc->getAllLibsByType($type, $pager);
        $subLibs = array();
        if($type == 'project') $subLibs = $this->doc->getSubLibGroups(array_keys($libs));

        $this->view->title   = $this->lang->doc->allLibs;
        $this->view->type    = $type;
        $this->view->libs    = $libs;
        $this->view->subLibs = $subLibs;
        $this->view->pager   = $pager;
        $this->display();
    }

    /**
     * Browse docs.
     * 
     * @param  string|int $libID    project or the int id of custom library
     * @param  int    $moduleID 
     * @param  int    $projectID 
     * @param  string $browseType 
     * @param  int    $param 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function browse($libID = '0', $moduleID = 0, $projectID = 0, $browseType = 'bymodule', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {  
        $this->doc->setMainMenu();

        $libID = $libID ? $libID : key((array)$this->libs);
        if(!$libID) $this->locate(inlink('createLib'));

        $lib = $this->doc->getLibByID($libID);
        if(!$lib) die(js::error($this->lang->doc->libNotFound) . js::locate(inlink('browse')));

        /* Set browseType.*/ 
        $browseType = strtolower($browseType);
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Set menu, save session. */
        $this->session->set('docList', $this->app->getURI(true));

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
 
        /* Get docs. */
        $modules = '';
        $docs    = array();
        if($browseType == 'bymodule')
        {
            if($moduleID) $modules = $this->tree->getFamily($moduleID, 'doc', (int)$libID);
            $docs = $this->doc->getDocList($libID, $projectID, $modules, $orderBy, $pager);
        }
        elseif($browseType == 'bysearch')
        {
            $docs = $this->doc->getDocListBySearch($orderBy, $pager);
        }
        else
        {
            $docs = $this->doc->getDocList($libID, $projectID, $modules, $orderBy, $pager);
        }

        /* Get the tree menu. */
        $moduleTree = $this->tree->getTreeMenu($type = 'doc', $startModuleID = 0, array('treeModel', 'createDocLink'), $libID);

        /* Build the search form. */
        $this->loadModel('search', 'sys');
        $this->config->doc->search['actionURL'] = $this->createLink('doc', 'browse', "libID=$libID&moduleID=$moduleID&projectID=$projectID&browseType=bySearch");
        $this->config->doc->search['params']['lib']['values']    = array('' => '') + $this->libs;
        $this->config->doc->search['params']['type']['values']   = array('' => '') + $this->config->doc->search['params']['type']['values'];
        $this->config->doc->search['params']['module']['values'] = array('' => '') + $this->tree->getOptionMenu('doc', $startModuleID = 0, false, $libID);
        $this->search->setSearchParams($this->config->doc->search);

        $this->view->fixedMenu = false;
        if(isset($this->config->customMenu->doc))
        {
            $customMenu = json_decode($this->config->customMenu->doc);
            foreach($customMenu as $menu)
            {
                if($menu->name == 'custom' . $libID) $this->view->fixedMenu = true;
            }
        }

        if($this->cookie->browseType == 'bymenu' or $this->app->viewType === 'mhtml')
        {
            $this->view->modules = $this->doc->getDocMenu($libID, $moduleID, $orderBy == 'title_asc' ? 'name_asc' : 'id_desc');
            $this->view->parents = $this->loadModel('tree')->getFamily($moduleID);
        }
        elseif($this->cookie->browseType == 'bytree')
        {
            $this->view->tree = $this->doc->getDocTree($libID);
        }
        else
        {
            $this->view->moduleTree = $moduleTree;
        }

       
        $this->view->title         = $this->lang->doc->common . $this->lang->colon . $this->libs[$libID];
        $this->view->libID         = $libID;
        $this->view->lib           = $lib;
        $this->view->libName       = $this->libs[$libID];
        $this->view->moduleID      = $moduleID;
        $this->view->parentModules = $this->tree->getFamily($moduleID);
        $this->view->docs          = $docs;
        $this->view->pager         = $pager;
        $this->view->users         = $this->loadModel('user')->getPairs();
        $this->view->orderBy       = $orderBy;
        $this->view->projectID     = $projectID;
        $this->view->browseType    = $browseType;
        $this->view->param         = $param;

        $this->display();
    }

    /**
     * Create a library.
     * 
     * @access public
     * @return void
     */
    public function createLib($type = '', $projectID = 0)
    {
        if(!empty($_POST))
        {
            $libID = $this->doc->createLib();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('docLib', $libID, 'Created');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse', "libID=$libID")));
        }

        $this->view->title     = $this->lang->doc->createLib;
        $this->view->users     = $this->loadModel('user')->getPairs('nodeleted,noforbidden,noclosed');
        $this->view->groups    = $this->loadModel('group')->getPairs();
        $this->view->projects  = $this->project->getPairs();
        $this->view->type      = $type;
        $this->view->projectID = $projectID;
        $this->display();
    }

    /**
     * Edit a library.
     * 
     * @param  int    $libID 
     * @access public
     * @return void
     */
    public function editLib($libID)
    {
        if(!empty($_POST))
        {
            $changes = $this->doc->updateLib($libID); 
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('docLib', $libID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse', "libID=$libID")));
        }
        
        $lib = $this->doc->getLibByID($libID);
        if(!$lib) die(js::error($this->lang->doc->libNotFound) . js::locate('back'));

        if(!empty($lib->project)) $this->view->project = $this->project->getByID($lib->project);

        $this->view->libID  = $libID;
        $this->view->title  = $this->lang->doc->editLib;
        $this->view->lib    = $lib;
        $this->view->users  = $this->loadModel('user')->getPairs('nodeleted,noforbidden,noclosed');
        $this->view->groups = $this->loadModel('group')->getPairs();
        
        $this->display();
    }

    /**
     * Delete a library.
     * 
     * @param  int    $libID 
     * @access public
     * @return void
     */
    public function deleteLib($libID)
    {
        if($libID == 'project') die();
        $lib = $this->doc->getLibById($libID);

        if(!$lib) $this->send(array('result' => 'fail', 'message' => $this->lang->doc->libNotFound));
        if(!empty($lib->main)) $this->send(array('result' => 'fail', 'message' => $this->lang->doc->errorMainLib));

        if($this->doc->deleteLib($libID)) $this->send(array('result' => 'success', 'locate' => inlink('browse')));
        $this->send(array('result' => 'fail', 'message' => dao::getError()));
    }
    
    /**
     * Create a doc.
     * 
     * @param  int|string   $libID 
     * @param  int          $moduleID 
     * @param  int          $projectID 
     * @access public
     * @return void
     */
    public function create($libID, $moduleID = 0, $projectID = 0)
    {
        $this->doc->setMainMenu();

        $projectID = (int)$projectID;
        if(!empty($_POST))
        {
            $docID = $this->doc->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->action->create('doc', $docID, 'Created');

            $projectID = intval($this->post->project);
            $vars = "libID=$libID&moduleID={$this->post->module}&projectID=$projectID";
            $link = $this->createLink('doc', 'browse', $vars);
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
        }

        /* Get the modules. */
        $moduleOptionMenu = $this->tree->getOptionMenu('doc', $startModuleID = 0, false, $libID);

        $this->view->title            = $this->libs[$libID] . $this->lang->colon . $this->lang->doc->create;
        $this->view->libID            = $libID;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->moduleID         = $moduleID;
        $this->view->projectID        = $projectID;
        $this->view->users            = $this->loadModel('user')->getPairs('nodeleted,noforbidden,noclosed');
        $this->view->groups           = $this->loadModel('group')->getPairs();

        $this->display();
    }

    /**
     * Edit a doc.
     * 
     * @param  int    $docID 
     * @access public
     * @return void
     */
    public function edit($docID)
    {
        $this->doc->setMainMenu();

        if(!empty($_POST))
        {
            $return = $this->doc->update($docID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $changes = isset($return['changes']) ? $return['changes'] : '';
            $files   = isset($return['files']) ? $return['files'] : '';

            if($this->post->comment != '' or !empty($changes) or !empty($files))
            {
                $action = !empty($changes) ? 'Edited' : 'Commented';
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->action->create('doc', $docID, $action, $fileAction . $this->post->comment);
                if($changes) $this->action->logHistory($actionID, $changes);
            }
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('doc', 'view', "docID=$docID")));
        }

        /* Get doc and set menu. */
        $doc = $this->doc->getById($docID);
        if(!$doc) die(js::error($this->lang->doc->notFound) . js::locate('back'));
        $libID = $doc->lib;

        /* Get modules. */
        $moduleOptionMenu = $this->tree->getOptionMenu('doc', $startModuleID = 0, false, $libID);

        if($doc->contentType == 'markdown') $this->config->doc->markdown->edit = array('id' => 'content', 'tools' => 'toolbar');

        $this->view->title            = $this->libs[$libID] . $this->lang->colon . $this->lang->doc->edit;
        $this->view->doc              = $doc;
        $this->view->libID            = $libID;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->users            = $this->loadModel('user')->getPairs('nodeleted,noforbidden,noclosed');
        $this->view->groups           = $this->loadModel('group')->getPairs();
        $this->display();
    }

    /**
     * View a doc.
     * 
     * @param  int    $docID 
     * @access public
     * @return void
     */
    public function view($docID, $version = 0)
    {
        $this->doc->setMainMenu();

        /* Get doc. */
        $doc = $this->doc->getById($docID, $version, true);
        if(!$doc) die(js::error($this->lang->doc->notFound) . js::locate('back'));
        if($doc->project != 0 and !$this->project->checkPriv($this->project->getById($doc->project)))
        {
            echo(js::alert($this->lang->error->accessDenied));
            die(js::locate('back'));
        }

        if($doc->contentType == 'markdown')
        {
            $hyperdown    = $this->app->loadClass('hyperdown');
            $doc->content = $hyperdown->makeHtml($doc->content);
            $doc->digest  = $hyperdown->makeHtml($doc->digest);
        }

        /* Get library. */
        $lib = $doc->libName;
        if($doc->lib == 'project') $lib = $doc->projectName;

        $this->view->title      = "DOC #$doc->id $doc->title - " . $this->libs[$doc->lib];
        $this->view->doc        = $doc;
        $this->view->lib        = $lib;
        $this->view->version    = $version ? $version : $doc->version;
        $this->view->projects   = $this->project->getPairs();
        $this->view->users      = $this->user->getPairs();
        $this->view->keTableCSS = $this->doc->extractKETableCSS($doc->content);

        $this->display();
    }

    /**
     * Delete a doc.
     * 
     * @param  int    $docID 
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($docID)
    {
        $doc = $this->doc->getById($docID);
        if(!$doc) die(js::error($this->lang->doc->notFound));
        $this->doc->delete(TABLE_DOC, $docID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'locate' => inlink('browse')));
    }

    /**
     * Show libs for project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function projectLibs($projectID)
    {
        $this->doc->setMainMenu();

        $project = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch();

        $this->view->title   = $project->name;
        $this->view->project = $project;
        $this->view->libs    = $this->doc->getLibsByProject($projectID);
        $this->display();
    }

    /**
     * Show files for project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function showFiles($projectID, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->doc->setMainMenu();

        $uri = $this->app->getURI(true);
        $this->app->session->set('taskList',  $uri);
        $this->app->session->set('docList',   $uri);

        $project = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch();

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title   = $project->name;
        $this->view->project = $project;
        $this->view->files   = $this->doc->getLibFiles($projectID, $pager);
        $this->view->pager   = $pager;
        $this->display();
    }

    /**
     * Ajax fixed menu.
     * 
     * @param  int    $libID 
     * @param  string $type 
     * @access public
     * @return void
     */
    public function ajaxFixedMenu($libID, $type = 'fixed')
    {
        $customMenus = $this->loadModel('setting')->getItem("owner={$this->app->user->account}&app=sys&module=common&section=customMenu&key=doc");
        if($customMenus) $customMenus = json_decode($customMenus);

        if(empty($customMenus) and $type == 'remove') $this->send(array('result' => 'success'));

        if(!empty($customMenus))
        {
            foreach($customMenus as $i => $customMenu)
            {   
                if(isset($customMenu->name) and $customMenu->name == "custom{$libID}") unset($customMenus[$i]);
            }   
        }

        $customMenu = new stdclass();
        $customMenu->name  = "custom{$libID}";
        $customMenu->order = count($customMenus); 
        if($type == 'fixed') $customMenus[] = $customMenu;

        $this->setting->setItem("{$this->app->user->account}.sys.common.customMenu.doc", json_encode($customMenus));
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success'));
    }

    /**
     * Sort.
     * 
     * @access public
     * @return void
     */
    public function sort()
    {
        if($_POST)
        {
            $orders = $_POST;
            foreach($orders as $id => $order)
            {    
                $this->dao->update(TABLE_DOCLIB)->set('order')->eq($order)->where('id')->eq($id)->exec();
            }    

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        }
    }
}
