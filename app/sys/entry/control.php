<?php
/**
 * The control file of entry module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entry 
 * @version     $Id: control.php 4227 2016-10-25 08:27:56Z liugang $
 * @link        http://www.ranzhico.com
 */
class entry extends control
{
    /**
     * Manage all entries.
     * 
     * @access public
     * @return void
     */
    public function admin($category = 0)
    {
        $entries      = $this->entry->getEntries($type = 'custom', $category);
        $categories   = $this->dao->select('id, name')->from(TABLE_CATEGORY)->where('type')->eq('entry')->orderBy('`order`')->fetchPairs();
        $tmpEntries   = array();
        $showCategory = false;
        foreach($entries as $key => $entry)
        {
            /* Remove category. */
            if($entry->code == '') 
            {
                unset($entries[$key]);
                continue;
            }
            if(isset($categories[$entry->category]))
            {
                $showCategory = true;
                $tmpEntries[$entry->category][] = $entry;
                unset($entries[$key]);
            }
        }
        foreach($tmpEntries as $categoryEntries)
        {
            foreach($categoryEntries as $entry)
            {
                $entries[] = $entry;
            }
        }

        /* add web root if logo not start with /  */
        foreach($entries as $entry) if(!empty($entry->logo) && substr($entry->logo, 0, 1) != '/') $entry->logo = $this->config->webRoot . $entry->logo;
        
        $this->view->title        = $this->lang->entry->common . $this->lang->colon . $this->lang->entry->admin;
        $this->view->treeMenu     = $this->loadModel('tree')->getTreeMenu('entry', 0, array('treeModel', 'createEntryAdminLink'));
        $this->view->entries      = $entries;
        $this->view->categories   = $categories;
        $this->view->showCategory = $showCategory;
        $this->display();
    }

    /**
     * Create auth.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if(!empty($_POST))
        {
            if((!$this->post->buildin) and (strpos($this->post->login, '/') !== 0) and (!preg_match('/https?\:\/\//Ui', $this->post->login))) $this->send(array('result' => 'fail', 'message' => $this->lang->entry->error->url));

            if($this->post->zentao) 
            {
                /* Check admin account, password and code. */
                $error = array();
                if($this->post->adminPassword == '') $error['adminPassword'] = sprintf($this->lang->error->notempty, $this->lang->password);
                if($this->post->adminAccount == '') $error['adminAccount'] = sprintf($this->lang->error->notempty, $this->lang->account);
                if($this->post->code == '') $error['code'] = sprintf($this->lang->error->notempty, $this->lang->entry->code);
                if(!empty($error)) $this->send(array('result' => 'fail', 'message' => $error));

                /* Get zentao url. */
                $loginUrl = $this->post->login;
                if(strpos($loginUrl, '&') === false) $zentaoUrl = substr($loginUrl, 0, strrpos($loginUrl, '/') + 1);
                if(strpos($loginUrl, '&') !== false) $zentaoUrl = substr($loginUrl, 0, strpos($loginUrl, '?'));

                /* Get zentao config. */
                $zentaoConfig = $this->loadModel('sso')->getZentaoServerConfig($zentaoUrl);
                if(empty($zentaoConfig)) $this->send(array('result' => 'fail', 'message' => $this->lang->entry->error->zentaoUrl));
                if(strpos($zentaoConfig->version, 'pro') !== false and version_compare($zentaoConfig->version, 'pro5.0', '<')) $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->entry->error->version, 'pro5.0')));
                if(strpos($zentaoConfig->version, 'pro') === false and version_compare($zentaoConfig->version, '7.4', '<')) $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->entry->error->version, '7.4')));

                $_POST['login']  = $this->sso->createZentaoLink($zentaoConfig, $zentaoUrl, "sso", "login", '', 'html', false);
                $_POST['logout'] = $this->sso->createZentaoLink($zentaoConfig, $zentaoUrl, "sso", "logout", '', 'html', false);
                $_POST['block']  = $this->sso->createZentaoLink($zentaoConfig, $zentaoUrl, "block", "main", '', 'html', false);

                /* Init zentao setting. */
                $result = $this->loadModel('sso')->initZentaoSSO($zentaoConfig, $zentaoUrl, $this->post->adminAccount, $this->post->adminPassword, $this->post->code, $this->post->key);
                if($result['result'] != 'success') $this->send($result);
            }

            $entryID = $this->entry->create();
            if(dao::isError())  $this->send(array('result' => 'fail', 'message' => dao::geterror()));
            $locate = inlink('admin');
            if($this->post->zentao) $locate = inlink('bindUser', "id=$entryID&sessionID=$zentaoConfig->sessionID");
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate, 'entries' => $this->entry->getJSONEntries()));
        }
        $this->view->title      = $this->lang->entry->common . $this->lang->colon . $this->lang->entry->create;
        $this->view->key        = $this->entry->createKey();
        $this->view->groups     = $this->loadModel('group')->getPairs();
        $this->view->categories = array('0' => '') + $this->loadModel('tree')->getOptionMenu('entry', 0, $removeRoot = true);
        $this->display();
    }

    /**
     * Visit entry.
     * 
     * @param  int    $entryID 
     * @param  string $referer 
     * @access public
     * @return void
     */
    public function visit($entryID, $referer = '')
    {
        $referer = !empty($_GET['referer']) ? $this->get->referer : $referer;
        $entry   = $this->entry->getById($entryID);

        /* deny if no this app rights. */
        if(!commonModel::hasAppPriv($entry->code)) $this->loadModel('common', 'sys')->deny($this->app->getModuleName(), $this->app->getMethodName());

        $location = $entry->login;
        $pathinfo = parse_url($location);
        if($entry->integration)
        {
            $token = $this->loadModel('sso')->createToken(session_id(), $entryID);
            if(!empty($pathinfo['query']))
            {
                $location = rtrim($location, '&') . "&token=$token";
            }
            else
            {
                $location = rtrim($location, '?') . "?token=$token";
            }
            if(!empty($referer)) $location .= '&referer=' . $referer;
        }

        $this->locate($location);
    }

    /**
     * Logout 
     * 
     * @param  int    $entryID 
     * @access public
     * @return void
     */
    public function logout($entryID)
    {
        $entry  = $this->entry->getById($entryID);
        $logout = $entry->logout;
        $token  = $this->loadModel('sso')->createToken(session_id(), $entryID);

        if(strpos('&', $logout) !== false)
        {
            $location = rtrim($logout, '&') . "&token=$token";
        }
        else
        {
            $location = rtrim($logout, '?') . "?token=$token";
        }

        $this->locate($location);
    }

    /**
     * Integration entry.
     * 
     * @param  string    $code 
     * @access public
     * @return void
     */
    public function integration($code)
    {
        if(!empty($_POST))
        {
            $this->entry->integration($code);
            if(dao::isError())  $this->send(array('result' => 'fail', 'message' => dao::geterror()));
            $this->send(array('result' => 'success', 'locate' => inlink('admin'), 'entries' => $this->entry->getJSONEntries()));
        }

        $entry = $this->entry->getByCode($code);
        if($entry->size != 'max')
        {
            $size = json_decode($entry->size);
            $entry->size   = 'custom';
            $entry->width  = $size->width;
            $entry->height = $size->height;
        }

        $this->view->title = $this->lang->entry->common . $this->lang->colon . $this->lang->entry->integration;
        $this->view->entry = $entry;
        $this->view->code  = $code;
        $this->display();
    }

    /**
     * Set style for entry. 
     * 
     * @param  string    $code 
     * @access public
     * @return void
     */
    public function style($code)
    {
        if(!empty($_POST))
        {
            $entryID = $this->entry->setStyle($code);
            $this->entry->updateLogo($entryID);
            if(dao::isError())  $this->send(array('result' => 'fail', 'message' => dao::geterror()));
            $this->send(array('result' => 'success', 'locate' => inlink('admin'), 'entries' => $this->entry->getJSONEntries()));
        }

        $entry = $this->entry->getByCode($code);
        if($entry->size != 'max')
        {
            $size = json_decode($entry->size);
            $entry->size   = 'custom';
            $entry->width  = $size->width;
            $entry->height = $size->height;
        }

        $this->view->title = $this->lang->entry->common . $this->lang->colon . $this->lang->entry->style;
        $this->view->entry = $entry;
        $this->view->code  = $code;
        $this->display();
    }

    /**
     * Edit auth.
     * 
     * @param  string $code 
     * @access public
     * @return void
     */
    public function edit($code)
    {
        $entry = $this->entry->getByCode($code);

        if(!empty($_POST))
        {
            if((!$this->post->buildin) and (strpos($this->post->login, '/') !== 0) and (!preg_match('/https?\:\/\//Ui', $this->post->login))) $this->send(array('result' => 'fail', 'message' => $this->lang->entry->error->url));

            if($entry->zentao) 
            {
                /* Get zentao url. */
                $loginUrl = $this->post->login;
                if(strpos($loginUrl, '&') === false) $zentaoUrl = substr($loginUrl, 0, strrpos($loginUrl, '/') + 1);
                if(strpos($loginUrl, '&') !== false) $zentaoUrl = substr($loginUrl, 0, strpos($loginUrl, '?'));

                /* Get zentao config. */
                $zentaoConfig = $this->loadModel('sso')->getZentaoServerConfig($zentaoUrl);
                if(empty($zentaoConfig)) $this->send(array('result' => 'fail', 'message' => $this->lang->entry->error->zentaoUrl));

                $_POST['login']  = $this->sso->createZentaoLink($zentaoConfig, $zentaoUrl, "sso", "login", '', 'html', false);
                $_POST['logout'] = $this->sso->createZentaoLink($zentaoConfig, $zentaoUrl, "sso", "logout", '', 'html', false);
                $_POST['block']  = $this->sso->createZentaoLink($zentaoConfig, $zentaoUrl, "block", "main", '', 'html', false);
            }

            $this->entry->update($code);
            if(dao::isError())  $this->send(array('result' => 'fail', 'message' => dao::geterror()));
            $this->send(array('result' => 'success', 'locate' => inlink('admin'), 'entries' => $this->entry->getJSONEntries()));
        }

        if($entry->size != 'max')
        {
            $size = json_decode($entry->size);
            $entry->size   = 'custom';
            $entry->width  = $size->width;
            $entry->height = $size->height;
        }

        $this->view->title      = $this->lang->entry->common . $this->lang->colon . $this->lang->entry->edit;
        $this->view->entry      = $entry;
        $this->view->code       = $code;
        $this->view->categories = array('0' => '') + $this->loadModel('tree')->getOptionMenu('entry', 0, $removeRoot = true);
        $this->display();
    }

    /**
     * Order entry. 
     * 
     * @access public
     * @return void
     */
    public function order()
    {
        if($_POST)
        {
            /* Sort entries by order and id. */
            $entries = array();
            foreach($this->post->order as $id => $order)
            {
                $entry        = new stdclass();
                $entry->id    = $id;
                $entry->order = $order;
                $entries[$id]    = $entry;
            }
            usort($entries, 'commonModel::sortEntryByOrder');

            /* Update order. */
            $order = 10;
            foreach($entries as $entry)
            {
                $this->dao->update(TABLE_ENTRY)->set('`order`')->eq($order)->where('id')->eq($entry->id)->exec();
                $order += 10;
            }
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* delete custome sort data. */
            $allEntries = isset($this->config->personal->common->customApp) ? json_decode($this->config->personal->common->customApp->value) : new stdclass();
            foreach($allEntries as $entry)
            {
                unset($entry->order);
            }
            $this->loadModel('setting')->setItem("{$this->app->user->account}.sys.common.customApp", json_encode($allEntries));
            /* Refresh config to sort custom apps. */
            $this->config->personal->common->customApp->value = helper::jsonEncode($entries);

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('admin'), 'entries' => $this->entry->getJSONEntries()));
        }
    }

    /**
     * Sort entries.
     * 
     * @access public
     * @return void
     */
    public function sort()
    {
        if(!empty($_POST))
        {
            if(!$this->post->name)  die(js::alert($this->lang->entry->error->name));
            if(!$this->post->ip)    die(js::alert($this->lang->entry->error->ip));

            $this->entry->updateEntry($code);
            if(dao::isError()) die(js::error(dao::getError()));
            $this->send(array('result' => 'success', 'locate'=>inlink('admin')));
        }

        $this->view->title   = $this->lang->entry->common . $this->lang->colon . $this->lang->entry->sort;
        $this->view->entries = $this->entry->getEntries();
        $this->display();
    }

    /**
     * Delete entry.
     * 
     * @param  string $code 
     * @param  string $confirm 
     * @access public
     * @return void
     */
    public function delete($code)
    {
        if($this->entry->delete($code)) $this->send(array('result' => 'success', 'entries' => $this->entry->getJSONEntries()));
        $this->send(array('result' => 'fail', 'message' => dao::getError()));
    }

    /**
     * Get all departments.
     * 
     * @param  string $entry 
     * @access public
     * @return void
     */
    public function depts($entry)
    {
        if($this->post->key) $key = $this->post->key;
        if($this->get->key)  $key = $this->get->key;
        if($this->entry->checkIP($entry) and $this->entry->getAppKey($entry) == $key)
        {
            $depts = $this->entry->getAllDepts();
            $response['status'] = 'success';
            $response['data']   = json_encode($depts);
            $this->send($response);
        }

        $response['status'] = 'fail';
        $response['data']   = 'key error';
        $this->send($response);
    }

    /**
     * Get all users. 
     * 
     * @param  string $entry 
     * @access public
     * @return void
     */
    public function users($entry)
    {
        if($this->post->key) $key = $this->post->key;
        if($this->get->key)  $key = $this->get->key;
        if($this->entry->checkIP($entry) and $this->entry->getAppKey($entry) == $key)
        {
            $depts = $this->entry->getAllUsers();
            $response['status'] = 'success';
            $response['data']   = json_encode($depts);
            $this->send($response);
        }

        $response['status'] = 'fail';
        $response['data']   = 'key error';
        $this->send($response);
    }

    /**
     * Get entry blocks.
     * 
     * @param  int    $entryID 
     * @param  int    $index 
     * @access public
     * @return void
     */
    public function blocks($entryID, $index = 0)
    {
        $entry  = $this->entry->getByCode($entryID);
        if($entry->buildin)
        {
            $this->get->set('mode', 'getblocklist');
            $this->get->set('hash', $entry->key);
            $this->get->set('lang', $this->app->getClientLang());
            $blocks = $this->fetch('block', 'index', array(), $entry->code);
            $blocks = json_decode($blocks, true);
        }
        else
        {
            $blocks = $this->entry->getBlocksByAPI($entry);
        }

        if(empty($blocks)) return false;

        $blockPairs = array('' => '') + $blocks;

        $block = $this->loadModel('block')->getBlock($index);

        echo "<th>{$this->lang->entry->lblBlock}</th>";
        echo '<td>' . html::select('entryBlock', $blockPairs, ($block and $block->source != '') ? $block->block : '', "class='form-control' onchange='getBlockParams(this.value, \"$entryID\")'") . '</td>';
        if(isset($block->source)) echo "<script>$(function(){getBlockParams($('#entryBlock').val(), '{$block->source}')})</script>";
    }

    /**
     * Set block that is from entry.
     * 
     * @param  int    $index 
     * @param  int    $entryID 
     * @param  int    $blockID 
     * @access public
     * @return void
     */
    public function setBlock($index, $entryID, $blockID)
    {
        $block = $this->loadModel('block')->getBlock($index);

        $entry  = $this->entry->getByCode($entryID);
        if($entry->buildin)
        {
            $this->get->set('mode', 'getblockform');
            $this->get->set('blockid', $blockID);
            $this->get->set('hash', $entry->key);
            $this->get->set('lang', $this->app->getClientLang());
            $params = $this->fetch('block', 'index', array(), $entry->code);
            $params = json_decode($params, true);
        }
        else
        {
            $params = $this->entry->getBlockParams($entry, $blockID);
        }

        $this->view->params  = $params;
        $this->view->block   = $block ? $block : array();
        $this->view->index   = $index;
        $this->view->blockID = $blockID;
        $this->view->entryID = $entryID;
        $this->display();
    }

    /**
     * Print buildin entry block.
     * 
     * @param  int    $index 
     * @access public
     * @return void
     */
    public function printBlock($index)
    {
        $block = $this->loadModel('block')->getBlock($index);
        if(empty($block)) return false;
        if($block->source == '') $this->locate($this->createLink('block', 'printBlock', "index=$index"));

        $entry = $this->loadModel('entry')->getByCode($block->source);
        if(!$entry->buildin) $this->locate($this->createLink('block', 'printBlock', "index=$index"));

        $html = '';
        $block->params->account = $this->app->user->account;
        $block->params->uid     = $this->app->user->id;
        $params = base64_encode(json_encode($block->params));

        $this->get->set('mode', 'getblockdata');
        $this->get->set('blockid', $block->block);
        $this->get->set('hash', $entry->key);
        $this->get->set('entry', $entry->id);
        $this->get->set('app', 'sys');
        $this->get->set('lang', $this->app->getClientLang());
        $this->get->set('sso', base64_encode(commonModel::getSysURL() . helper::createLink('entry', 'visit', "entry=$entry->id")));
        $this->get->set('param', $params);
        $html = $this->fetch('block', 'index', array(), $entry->code);
        
        die($html);
    }

    /**
     * custom sort apps. 
     * 
     * @access public
     * @return void
     */
    public function customSort()
    {
        if($_POST)
        {
            $orders = $_POST;
            $allEntries = isset($this->config->personal->common->customApp) ? json_decode($this->config->personal->common->customApp->value) : new stdclass();
            /* Merge entries settings. */
            foreach($orders as $id => $order)
            {
                if(!isset($allEntries->$id))
                {
                    $allEntries->$id = new stdclass();
                    $allEntries->{$id}->id = $id;
                }
                $allEntries->{$id}->order = $order * 10;
            }
            $this->loadModel('setting')->setItem("{$this->app->user->account}.sys.common.customApp", json_encode($allEntries));
            if(dao::isError()) $this->send(array('result' => 'fael', 'message' => dao::getError()));
        }
        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
    }

    /**
     * update entry menu. 
     * 
     * @access public
     * @return void
     */
    public function updateEntryMenu()
    {
        if($_POST)
        {
            $id      = $this->post->id;
            $visible = $this->post->menu == 'all' ? 1 : 0;

            $this->dao->update(TABLE_ENTRY)->set('visible')->eq($visible)->where('id')->eq($id)->exec();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $allEntries = isset($this->config->personal->common->customApp) ? json_decode($this->config->personal->common->customApp->value) : new stdclass();
            if(!isset($allEntries->$id))
            {
                $allEntries->$id = new stdclass();
                $allEntries->{$id}->id = $id;
            }
            $allEntries->{$id}->visible = $visible;
            if(!$visible) unset($allEntries->{$id}->visible);

            $this->loadModel('setting')->setItem("{$this->app->user->account}.sys.common.customApp", json_encode($allEntries));
            if(dao::isError()) $this->send(array('result' => 'fael', 'message' => dao::getError()));
        }
        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
    }

    /**
     * Input admin of zentao. 
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function zentaoAdmin($id)
    {
        $entry = $this->entry->getByID($id);
        if($_POST)
        {
            /* Get zentao url. */
            if(strpos($entry->login, '&') === false) $zentaoUrl = substr($entry->login, 0, strrpos($entry->login, '/') + 1);
            if(strpos($entry->login, '&') !== false) $zentaoUrl = substr($entry->login, 0, strpos($entry->login, '?'));
            /* Get zentao config. */
            $zentaoConfig = $this->loadModel('sso')->getZentaoServerConfig($zentaoUrl);
            $result = $this->loadModel('sso')->initZentaoSSO($zentaoConfig, $zentaoUrl, $this->post->account, $this->post->password, $entry->code, $entry->key);
            if($result['result'] != 'success') $this->send($result);
            $this->send(array('result' => 'success', 'locate' => inlink('bindUser', "id=$id&sessionID=$zentaoConfig->sessionID")));
        }

        $this->view->title = $this->lang->entry->bindUser;
        $this->display();
    }

    /**
     * Bind user with zentao.
     * 
     * @param  int     $id 
     * @param  string  $sessionID 
     * @access public
     * @return void
     */
    public function bindUser($id, $sessionID)
    {
        $entry = $this->entry->getByID($id);

        /* Get zentao url. */
        if(strpos($entry->login, '&') === false) $zentaoUrl = substr($entry->login, 0, strrpos($entry->login, '/') + 1);
        if(strpos($entry->login, '&') !== false) $zentaoUrl = substr($entry->login, 0, strpos($entry->login, '?'));

        /* Get zentao config. */
        $zentaoConfig = $this->loadModel('sso')->getZentaoServerConfig($zentaoUrl);
        $zentaoConfig->sessionID = $sessionID;

        if($_POST)
        {
            foreach($this->post->ranzhiAccounts as $key => $ranzhiAccount)
            {
                if(isset($this->post->createUsers[$key]) and $this->post->createUsers[$key])
                {
                    $user = $this->loadModel('user')->getByAccount($ranzhiAccount);
                    if($user->gender == 'u') $user->gender = 'm';
                    $createUrl = $this->sso->createZentaoLink($zentaoConfig, $zentaoUrl, 'sso', 'createUser');
                    $result = commonModel::http($createUrl, $user);
                    if($result != 'success') $this->send(array('result' => 'fail', 'message' => $result));
                }
                else
                {
                    $data = new stdclass();
                    $data->ranzhiAccount = $ranzhiAccount; 
                    $data->zentaoAccount = $this->post->zentaoAccounts[$key]; 
                    $bindUrl = $this->sso->createZentaoLink($zentaoConfig, $zentaoUrl, 'sso', 'bindUser');
                    $result = commonModel::http($bindUrl, $data);
                    if($result != 'success') $this->send(array('result' => 'fail', 'message' => $result));
                }
            }

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('bindUser', "id=$id&sessionID=$sessionID")));
        }

        $getUsersUrl  = $this->sso->createZentaoLink($zentaoConfig, $zentaoUrl, 'sso', 'getUserPairs');
        $getUsersUrl .= "&hash={$entry->key}";
        $results = commonModel::http($getUsersUrl);
        $zentaoUsers = json_decode($results, true);

        $getBindUsersUrl  = $this->sso->createZentaoLink($zentaoConfig, $zentaoUrl, 'sso', 'getBindUsers');
        $getBindUsersUrl .= "&hash={$entry->key}";
        $results = commonModel::http($getBindUsersUrl);
        $bindUsers = json_decode($results, true);

        $this->view->title       = $this->lang->entry->bindUser;
        $this->view->ranzhiUsers = $this->loadModel('user')->getPairs('noempty,noclosed,nodeleted,noforbidden');
        $this->view->zentaoUsers = $zentaoUsers;
        $this->view->bindUsers   = $bindUsers;
        $this->display();
    }

    /**
     * Set category. 
     * 
     * @access public
     * @return void
     */
    public function category()
    {
        if($_POST)
        {
            $result = $this->loadModel('tree')->manageChildren('entry', 0, $this->post->children);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('admin')));
        }

        $this->view->title    = $this->lang->entry->common . $this->lang->colon . $this->lang->entry->setCategory;
        $this->view->children = $this->loadModel('tree')->getChildren(0, 'entry');
        $this->display();
    }
}
