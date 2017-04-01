<?php
/**
 * The control file of sso module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     sso 
 * @version     $Id: control.php 4029 2016-08-26 06:50:41Z liugang $
 * @link        http://www.ranzhico.com
 */
class sso extends control
{
    /**
     * Check privilege.
     * 
     * @access public
     * @return void
     */
    public function check()
    {
        $token  = $this->get->token;
        $auth   = $this->get->auth;
        $userIP = $this->get->userIP;
        $sso    = $this->sso->getByToken($token); 
        $entry  = $this->loadModel('entry')->getById($sso->entry);

        if(isset($_GET['callback']))
        {
            $callback = urldecode($this->get->callback);
            $sign     = strpos($callback, '&') === false ? '?' : '&';
        }

        if($this->sso->checkIP($entry->code))
        {
            if($auth == md5($entry->code . $userIP . $token . $entry->key))
            {
                if($this->session->user->ip == $userIP)
                {
                    $user = $this->loadModel('user')->getByAccount($this->session->user->account);

                    $data = new stdclass();
                    $data->token    = $token;
                    $data->auth     = $auth;
                    $data->id       = $user->id;
                    $data->dept     = $user->dept;
                    $data->account  = $user->account;
                    $data->password = $this->app->user->password;
                    $data->realname = $user->realname;
                    $data->role     = $user->role;
                    $data->gender   = $user->gender;
                    $data->email    = $user->email;
                    $data->birthday = $user->birthday;
                    $data->mobile   = $user->mobile;
                    $data->phone    = $user->phone;
                    $data->address  = $user->address;
                    $data->skype    = $user->skype;
                    $data->qq       = $user->qq;

                    $response['status'] = 'success';
                    $response['data']   = base64_encode(json_encode($data));
                    $response['md5']    = md5($response['data']);

                    if(!empty($_GET['referer'])) $response['referer'] = $this->get->referer;
                    if(isset($callback)) $this->locate($callback . $sign . http_build_query($response));
                    die(json_encode($response));
                }
            }
        }

        $response['status'] = 'fail';
        $response['data']   = 'check failed.';
        $response['md5']    = md5($response['data']);

        if(!empty($_GET['referer'])) $response['referer'] = $this->get->referer;
        if(isset($callback)) $this->locate($callback . $sign . http_build_query($response));
        die(json_encode($response));
    }

    /**
     * Auth user.
     * 
     * @param  string $entry 
     * @access public
     * @return void
     */
    public function auth($code, $account = '', $authcode = '')
    {
        if($this->post->account)  $account  = $this->post->account;
        if($this->post->authcode) $authcode = $this->post->authcode;

        $user = $this->sso->identify($code, $account, $authcode);
        if($user)
        {
            $response['status'] = 'success';
            $response['data']   = json_encode($user);
            die(json_encode($response));
        }

        $response['status'] = 'fail';
        $response['data']   = 'auth failed.';
        die(json_encode($response));
    }

    /**
     * Get todo list for ranzhi.
     * 
     * @param  string  $code 
     * @param  string  $account 
     * @access public
     * @return void
     */
    public function getTodoList($code = '', $account = '')
    {
        $this->app->loadLang('todo', 'sys');

        if(!$account) $account = $this->app->user->account;
        $datas = $this->sso->getZentaoTodoList($code, $account);

        $boardList = '';
        foreach($datas as $type => $dataList)
        {
            if(empty($dataList)) continue;

            $todos = $this->dao->select('*')->from(TABLE_TODO)->where('type')->eq("{$code}_{$type}")->fetchAll('idvalue');
            foreach($dataList as $id => $data)
            {
                if(isset($todos[$id]))
                {
                    unset($datas[$type][$id]);
                }
                else
                {
                    $datas[$type][$id] = '[' . $this->lang->todo->$type . '] ' . $data;
                }
            }

            $index = $type == 'bug' ? count($datas['task']) : 0;
            $boardList .= $this->loadModel('todo', 'sys')->buildBoardList($datas[$type], $code . '_' . $type, $index);
        }
        die($boardList);
    }
}
