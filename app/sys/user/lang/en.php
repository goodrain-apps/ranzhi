<?php
/**
 * The user module english file of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: en.php 4029 2016-08-26 06:50:41Z liugang $
 * @link        http://www.ranzhico.com
 */
$lang->user->common    = 'User';

$lang->user->id        = 'ID';
$lang->user->account   = 'Account';
$lang->user->super     = 'Admin';
$lang->user->password  = 'Password';
$lang->user->password2 = 'Repeat';
$lang->user->realname  = 'Name';
$lang->user->nickname  = 'Nickname';
$lang->user->dept      = 'Department';
$lang->user->role      = 'Roles Settings';    
$lang->user->avatar    = 'Avatar';
$lang->user->birthyear = 'Birthyear';
$lang->user->birthday  = 'Birthday';
$lang->user->gender    = 'Gender';
$lang->user->email     = 'Email';
$lang->user->msn       = 'MSN';
$lang->user->qq        = 'QQ';
$lang->user->yahoo     = 'Y!';
$lang->user->gtalk     = 'GTalk';
$lang->user->wangwang  = 'Wangwang';
$lang->user->mobile    = 'Mobile';
$lang->user->phone     = 'Phone';
$lang->user->dept      = 'Department';
$lang->user->address   = 'Address';
$lang->user->zipcode   = 'Zipcode';
$lang->user->join      = 'Join date';
$lang->user->visits    = 'Visits';
$lang->user->ip        = 'Last IP';
$lang->user->last      = 'Last login';
$lang->user->allowTime = 'Allow time';
$lang->user->status    = 'Status';
$lang->user->alert     = 'Your account has been disabled';
$lang->user->keepLogin = 'Keep Login';
$lang->user->ignore    = 'Ignore';

$lang->user->admin           = 'Admin';
$lang->user->list            = 'Users';
$lang->user->colleague       = 'Colleagues';
$lang->user->view            = "User info";
$lang->user->create          = "Add User";
$lang->user->edit            = "Edit";
$lang->user->changePassword  = "Change password";
$lang->user->recoverPassword = "Forgot password";
$lang->user->newPassword     = "New password";
$lang->user->update          = "Edit";
$lang->user->delete          = "Delete";
$lang->user->browse          = "Borwse";
$lang->user->deny            = "Access denied.";
$lang->user->confirmDelete   = "Do you want to delete this user?";
$lang->user->confirmActivate = "Do you want to activate this user?";
$lang->user->relogin         = "Login again";
$lang->user->asGuest         = "Guest login";
$lang->user->goback          = "Back";
$lang->user->allUsers        = 'All';
$lang->user->submit          = "Submit";
$lang->user->forbid          = 'Disable';
$lang->user->forbidList      = 'Disabled Users';
$lang->user->active          = 'Active';
$lang->user->setReferer      = 'Set referer';
$lang->user->vcard           = 'Vcard';
$lang->user->uploadAvatar    = 'Upload avatar';
$lang->user->cropAvatar      = 'Crop avatar';
$lang->user->cropAvatarTip   = 'Drag border to crop avatar';
$lang->user->adminUser       = 'User';

$lang->user->profile     = 'Profile';
$lang->user->editProfile = 'Edit';
$lang->user->thread      = 'My threads';
$lang->user->reply       = 'My replies';
$lang->user->message     = 'My message';

$lang->user->inputAccount   = 'Please enter account';
$lang->user->inputColleague = "Please enter colleauge's name";
$lang->user->inputPassword  = 'Please enter password';
$lang->user->searchUser     = 'Search';

$lang->user->errorDeny     = "Sorry, you don't have the permission to access <b>%s</b>'s<b>%s</b>. Please contact the administrator.<br/> This page will jump to homepage after 5 seconds";
$lang->user->loginFailed   = "Login failed. Check you account and password.";
$lang->user->locked        = "Failed too many times. Please login ten minutes later.";
$lang->user->lockedForEver = "User has been forbidden permanently.";
$lang->user->forbidSuccess = 'Forbidden.';
$lang->user->actionFail    = 'Failed.';
$lang->user->uploadSuccess = 'Successfully uploaded.';

$lang->user->forbidUser = 'Manage users';
$lang->user->operate    = 'Operate';

$lang->user->genderList = $lang->genderList;

$lang->user->basicInfo   = 'Basic Info';
$lang->user->contactInfo = 'Contact Info';

$lang->user->statusList = new stdclass();
$lang->user->statusList->locked    = "<label class='label label-danger'>Locked</label>";
$lang->user->statusList->forbidden = "<label class='label label-danger'>Forbidden</label>";
$lang->user->statusList->normal    = "<label class='label label-success'>Normal</label>";

$lang->user->notice = new stdclass();
$lang->user->notice->password = 'Numbers and letters, at least six characters.';

$lang->user->login  = new stdclass();
$lang->user->login->common  = "Login";
$lang->user->login->welcome = 'Welcome';
$lang->user->login->why     = 'Login to enjoy more features.';

$lang->user->control = new stdclass();
$lang->user->control->common      = 'Dashboard';
$lang->user->control->welcome     = 'Welcome, <strong>%s</strong>';
$lang->user->control->lblPassword = "Keep it empty and nothing will be changed.";

$lang->user->control->menus[10] = '<i class="icon-large icon-user"></i> Profile <i class="icon-chevron-right"></i>|user|profile';
$lang->user->control->menus[20] = '<i class="icon-large icon-edit"></i> Edit <i class="icon-chevron-right"></i>|user|edit';
//$lang->user->control->menus[28] = '<i class="icon-large icon-comments-alt"></i> Messages <i class="icon-chevron-right"></i>|user|message';
$lang->user->control->menus[30] = '<i class="icon-large icon-share"></i> Threads <i class="icon-chevron-right"></i>|user|thread';
$lang->user->control->menus[40] = '<i class="icon-large icon-mail-reply-all"></i> Replies <i class="icon-chevron-right"></i>|user|reply';

$lang->user->colleagueMenu = 'Colleague';

$lang->dept = new stdclass();  
$lang->dept->common     = 'Department';
$lang->dept->name       = 'Name';
$lang->dept->alias      = 'Alias';
$lang->dept->edit       = 'Manage Dept';
$lang->dept->parent     = 'Parent';
$lang->dept->children   = 'Children';
$lang->dept->desc       = 'Description';
$lang->dept->keywords   = 'Keywords';
$lang->dept->moderators = 'Dept Manager';
  
$lang->user->roleList['']           = ''; 
$lang->user->roleList['dev']        = 'Developer';
$lang->user->roleList['pm']         = 'Project Manager';
$lang->user->roleList['market']     = 'Marketing';
$lang->user->roleList['sale']       = 'Sales';
$lang->user->roleList['hr']         = 'HR';
$lang->user->roleList['office']     = 'Office';
$lang->user->roleList['service']    = 'Service';
$lang->user->roleList['support']    = 'Support';
$lang->user->roleList['marketmgr']  = 'Marketing Manager';
$lang->user->roleList['salemgr']    = 'Sales Manager';
$lang->user->roleList['hrmgr']      = 'HR Manager';
$lang->user->roleList['adminmgr']   = 'Office Manager';
$lang->user->roleList['servicemgr'] = 'Service Manager';
$lang->user->roleList['supportmgr'] = 'Support Manager';
$lang->user->roleList['top']        = 'Senior Manager';
$lang->user->roleList['others']     = 'Others';

$lang->user->mailContent = <<<EOT
<html>
<head>
<style type='text/css'>
body{margin:0; padding:0;}
div{padding-left:30px;}
</style>
</head>
<body>
<div style='padding-top:20px;height:60px;background:#fafafa;border-bottom:1px solid #ddd;font-size:18px;font-weight:bold'> 密码修改 </div>
<div style='margin-top:20px;'>
<p>
Hello, %s 
<br>
Please click the link to change your password:
<br>
<a href='%s' target='_blank'>%s</a>
</p>
<p>Reset Key: %s</p>
</div>
<div style='height:20px;border-bottom:1px solid #ddd;'></div>
<div style='margin:20px 0 0 0 ;'>
System letter, please do not reply
</div>
</body>
</html>
EOT;
