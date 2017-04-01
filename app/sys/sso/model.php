<?php
/**
 * The model file of sso module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     sso 
 * @version     $Id: model.php 4029 2016-08-26 06:50:41Z liugang $
 * @link        http://www.ranzhico.com
 */
class ssoModel extends model
{
    /**
     * Identify user.
     * 
     * @param  string $entry 
     * @access public
     * @return bool | object 
     */
    public function identify($code, $account, $authcode)
    {
        if(!$this->checkIP($code)) return false;
        $key = $this->getAppKey($code);
        if(!$account or !$authcode or !$key) return false;
  
        $user = $this->dao->select('*')->from(TABLE_USER)->where('account')->eq($account)->fetch();
        if($user)
        {
            $auth = md5($user->password . $key);
            if($auth == $authcode) return $user;
        }

        return false;
    }

    /**
     * Check ip if is allowed.
     * 
     * @param  string $entry 
     * @access public
     * @return bool 
     */
    public function checkIP($code)
    {
        $entry = $this->loadModel('entry')->getByCode($code);
        $ipParts  = explode('.', $_SERVER['REMOTE_ADDR']);
        $allowIPs = explode(',', $entry->ip);

        foreach($allowIPs as $allowIP)
        {
            if($allowIP == '*') return true;
            $allowIPParts = explode('.', $allowIP);
            foreach($allowIPParts as $key => $allowIPPart)
            {
                if($allowIPPart == '*') $allowIPParts[$key] = $ipParts[$key];
            }
            if(implode('.', $allowIPParts) == $_SERVER['REMOTE_ADDR']) return true;
        }
        return false;
    }

    /**
     * Get app key by code.
     * 
     * @param  string    $code 
     * @access public
     * @return string
     */
    public function getAppKey($code)
    {
        return $this->dao->select('`key`')->from(TABLE_ENTRY)->where('code')->eq($code)->fetch('key');
    }

    /**
     * Get sso info by token.
     * 
     * @param  string    $token 
     * @access public
     * @return object
     */
    public function getByToken($token)
    {
        return $this->dao->select('*')->from(TABLE_SSO)->where('token')->eq($token)->fetch();
    }

    /**
     * Create token.
     * 
     * @param  int    $sid 
     * @param  int    $entryID 
     * @access public
     * @return string
     */
    public function createToken($sid, $entryID)
    {
        $data  = new stdClass();
        $data->sid   = $sid;
        $data->entry = $entryID;
        $data->time  = helper::now();
        $data->token = md5($sid . $entryID . helper::now());
        $this->dao->delete()->from(TABLE_SSO)->where('sid')->eq($sid)->andWhere('entry')->eq($entryID)->exec();
        /* Delete the overdue token. */
        $this->dao->delete()->from(TABLE_SSO)->where('time')->lt(date('Y-m-d H:i:s', strtotime("-2 hour")))->exec();
        $this->dao->insert(TABLE_SSO)->data($data)->exec();
        return $data->token;
    }

    /**
     * Init zentao sso settings; 
     * 
     * @param  string $config 
     * @param  string $zentaoUrl 
     * @param  string $account 
     * @param  string $password 
     * @param  string $code 
     * @param  string $key 
     * @access public
     * @return object
     */
    public function initZentaoSSO($config, $zentaoUrl, $account, $password, $code, $key)
    {
        /* login. */
        $password = md5(md5($password) . $config->rand);
        $loginUrl = $this->createZentaoLink($config, $zentaoUrl, 'user', 'login') . "&account={$account}&password={$password}";
        $result = $this->fetchZentaoAPI($loginUrl);
        if(!$result) return array('result' => 'fail', 'message' => $this->lang->entry->error->admin);

        /* Init settings. */
        $settingUrl = $this->createZentaoLink($config, $zentaoUrl, 'sso', 'ajaxSetConfig');
        $data = new stdclass();
        $data->addr = $this->loadModel('common', 'sys')->getSysURL() . helper::createLink('sys.sso', 'check');
        $data->code = $code;
        $data->key  = $key;
        $result = $this->fetchZentaoAPI($settingUrl, $data);
        if(!$result) return array('result' => 'fail', 'message' => $this->lang->entry->error->zentaoSetting);
        if($result == 'deny') return array('result' => 'fail', 'message' => $this->lang->entry->error->accessDenied);

        return array('result' => 'success');
    }

    /**
     * Fetch data from an api.
     * 
     * @param  string $url 
     * @param  object $data 
     * @access public
     * @return mixed
     */
    public function fetchZentaoAPI($url, $data = null)
    {
        $result = commonModel::http($url, $data);
        if($result == 'deny') return 'deny';
        if($result == 'success') return array('status' => 'success');
        $result = json_decode($result);

        if(!isset($result->status)) return false;
        if($result->status != 'success') return false;
        if(isset($result->data) and md5($result->data) != $result->md5) return false;
        if(isset($result->data)) return json_decode($result->data);
        return $result;
    }

    /**
     * Get zentao server config. 
     * 
     * @param  string $zentaoUrl 
     * @access public
     * @return object
     */
    public function getZentaoServerConfig($zentaoUrl)
    {
        $url = $zentaoUrl . "?mode=getconfig";
        $result = commonModel::http($url);
        $result = json_decode($result);
        return $result;
    }

    /**
     * Create a zentao link url. 
     * 
     * @param  string $config 
     * @param  string $zentaoUrl 
     * @param  string $module 
     * @param  string $method 
     * @param  string $vars 
     * @param  string $viewType       json|html 
     * @param  string $useSession        
     * @access public
     * @return return string
     */
    public function createZentaoLink($config, $zentaoUrl, $module, $method, $vars = '', $viewType = 'json', $useSession = true)
    {
        if($config->requestType == 'GET')
        {
            $url = "{$zentaoUrl}?{$config->moduleVar}={$module}&{$config->methodVar}={$method}&{$config->viewVar}={$viewType}";
            if($vars != '') $url .= "&{$vars}";
            if($useSession) $url .= "&{$config->sessionVar}={$config->sessionID}";
        }
        if($config->requestType == 'PATH_INFO')
        {
            $vars = explode('&', $vars);
            foreach($vars as $key => $var) $vars[$key] = substr($var, strpos($var, '=') + 1);
            $vars = join($config->requestFix, $vars);

            $url = "{$zentaoUrl}{$module}{$config->requestFix}{$method}";
            if($vars != '') $url .= "{$config->requestFix}{$vars}";
            $url .= ".{$viewType}";
            if($useSession) $url .= "?{$config->sessionVar}={$config->sessionID}";
        }
        return $url;
    }

    /**
     * Get zentao todo list for ranzhi.
     * 
     * @param  string $code 
     * @param  string $account 
     * @access public
     * @return void
     */
    public function getZentaoTodoList($code = '', $account = '')
    {
        $entry = $this->dao->select('*')->from(TABLE_ENTRY)->where('zentao')->eq(1)->andWhere('code')->eq($code)->fetch();
        if(strpos($entry->login, '&') === false) $zentaoUrl = substr($entry->login, 0, strrpos($entry->login, '/') + 1);
        if(strpos($entry->login, '&') !== false) $zentaoUrl = substr($entry->login, 0, strpos($entry->login, '?'));

        $zentaoConfig = $this->loadModel('sso')->getZentaoServerConfig($zentaoUrl);
        if(strpos($zentaoConfig->version, 'pro') !== false and version_compare($zentaoConfig->version, 'pro5.2.1', '<')) return false; 
        if(strpos($zentaoConfig->version, 'pro') === false and version_compare($zentaoConfig->version, '8.2.1', '<')) return false;

        $url  = $this->sso->createZentaoLink($zentaoConfig, $zentaoUrl, 'sso', 'getTodoList', "account=$account", 'json', false);
        $url .= "?hash={$entry->key}";
        $results = commonModel::http($url);
        return json_decode($results, true);
    }
}
