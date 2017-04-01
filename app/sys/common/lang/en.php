<?php
/**
 * The English file of common module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     common 
 * @version     $Id: en.php 4194 2016-10-21 09:23:53Z daitingting $
 * @link        http://www.ranzhico.com
 */
$lang->colon      = ' : ';
$lang->prev       = '‹';
$lang->next       = '›';
$lang->percent    = '%';
$lang->laquo      = '&laquo;';
$lang->raquo      = '&raquo;';
$lang->minus      = ' - ';
$lang->hyphen     = '-';
$lang->slash      = ' / ';
$lang->RMB        = '￥';
$lang->divider    = "<span class='divider'>{$lang->raquo}</span> ";
$lang->at         = ' At ';
$lang->by         = ' By ';
$lang->ditto      = 'Ditto';
$lang->etc        = 'Etc.';
$lang->importIcon = "<i class='icon-download-alt'> </i>";
$lang->exportIcon = "<i class='icon-upload-alt'> </i>";

/* Apps lang items.*/
$lang->apps = new stdclass();
$lang->apps->crm        = 'CRM';
$lang->apps->cash       = 'CASH';
$lang->apps->oa         = 'OA';
$lang->apps->doc        = 'DOC';
$lang->apps->proj       = 'PROJ';
$lang->apps->sys        = 'SYSTEM';
$lang->apps->team       = 'TEAM';
$lang->apps->superadmin = 'ADMIN';

/* Lang items for ranzhi. */
$lang->ranzhi    = 'Ranger';
$lang->agreement = "I have read and agreed to  <a href='http://zpl.pub/page/zplv11.html' target='_blank'>Z PUBLIC LICENSE 1.2</a>, <span class='text-danger'>and will keep the logos and links of Ranger.</span>";
$lang->poweredBy = "<a href='http://www.ranzhico.com/?v=%s' target='_blank'>{$lang->ranzhi} %s</a>";
$lang->ipLimited = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>Sorry, your current IP is blocked. Please contact the Administrator to get privilege.</body></html>";

/* IE6 alert.  */
$lang->IE6Alert = <<<EOT
    <div class='alert alert-danger' style='margin-top:100px;'>
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
      <h2>Please use IE(>8), firefox, chrome, safari, opera to visit this site.</h2>
      <p>Stop using IE6!</p>
      <p>IE6 is too outdated that we should stop using it. <br/></p>
      <a href='https://www.google.com/intl/zh-hk/chrome/browser/' class='btn btn-primary btn-lg' target='_blank'>Chrome</a>
      <a href='http://www.firefox.com/' class='btn btn-primary btn-lg' target='_blank'>Firefox</a>
      <a href='http://www.opera.com/download' class='btn btn-primary btn-lg' target='_blank'>Opera</a>
      <p></p>
    </div>
EOT;

/* Themes. */
$lang->theme             = 'Theme';
$lang->themes['default'] = 'Default';
$lang->themes['clear']   = 'Clear';

/* Global lang items. */
$lang->home           = 'Home';
$lang->welcome        = '%s Ranger';
$lang->messages       = "<strong><i class='icon-comment-alt'></i> %s</strong>";
$lang->todayIs        = 'Today is %s, ';
$lang->today          = 'Today';
$lang->aboutUs        = 'About';
$lang->about          = 'About';
$lang->link           = 'Links';
$lang->frontHome      = 'Front';
$lang->forumHome      = 'Forum';
$lang->bookHome       = 'Book';
$lang->register       = 'Register';
$lang->logout         = 'Logout';
$lang->login          = 'Login';
$lang->account        = 'Account';
$lang->password       = 'Password';
$lang->all            = 'All';
$lang->changePassword = 'Change password';
$lang->currentPos     = 'Current Page';
$lang->categoryMenu   = 'Manage Category';
$lang->basicInfo      = 'Basic Info';

/* Global action items. */
$lang->reset          = 'Reset';
$lang->add            = 'Add';
$lang->edit           = 'Edit';
$lang->copy           = 'Copy';
$lang->and            = 'And';
$lang->or             = 'Or';
$lang->hide           = 'Hide';
$lang->delete         = 'Delete';
$lang->close          = 'Close';
$lang->finish         = 'Finish';
$lang->cancel         = 'Cancel';
$lang->import         = 'Import';
$lang->export         = 'Export';
$lang->setFileName    = 'File Name';
$lang->setFileNum     = 'File Number';
$lang->setFileType    = 'File Type';
$lang->save           = 'Save';
$lang->saved          = 'Saved';
$lang->confirm        = 'Confirm';
$lang->preview        = 'Preview';
$lang->goback         = 'Back';
$lang->assign         = 'Assign';
$lang->start          = 'Start';
$lang->create         = 'Add';
$lang->forbid         = 'Forbid';
$lang->activate       = 'Activate';
$lang->ignore         = 'Ignore';
$lang->view           = 'View';
$lang->detail         = 'Details';
$lang->more           = 'More';
$lang->actions        = 'Actions';
$lang->history        = 'History';
$lang->reverse        = 'Reverse';
$lang->switchDisplay  = 'Switch Display';
$lang->feature        = 'Features';
$lang->year           = 'Year';
$lang->month          = 'Month';
$lang->day            = 'Day';
$lang->loading        = 'Loading...';
$lang->saveSuccess    = 'Saved.';
$lang->setSuccess     = 'Saved.';
$lang->sendSuccess    = 'Sent.';
$lang->fail           = 'Failed.';
$lang->noResultsMatch = 'No match found.';
$lang->alias          = 'for SEO, use numbers and letters.';
$lang->unfold         = '+';
$lang->fold           = '-';
$lang->files          = 'Files';
$lang->addFiles       = 'Add Files ';
$lang->comment        = 'Comment';
$lang->selectAll      = 'All';
$lang->selectReverse  = 'Inverse';
$lang->continueSave   = 'Continue To Save';
$lang->submitting     = 'Saving...';
$lang->yes            = 'YES';
$lang->no             = 'NO';
$lang->signIn         = 'Sign in';
$lang->signOut        = 'Sign out';
$lang->sort           = 'Ranking';
$lang->required       = 'Required';
$lang->custom         = 'Custom';

$lang->exportAll      = 'Export All';
$lang->exportThisPage = 'Export This Page';
$lang->exportTemplate = 'Export Template';
$lang->exportExcel    = 'Export Excel';
$lang->exportWord     = 'Export Word';
$lang->importFile     = 'File';
$lang->importSuccess  = 'Imported.';
$lang->importFail     = 'Failed.';

/* Items for lifetime. */
$lang->lifetime = new stdclass();
$lang->lifetime->createdBy    = 'Created By';
$lang->lifetime->assignedTo   = 'Assigned to';
$lang->lifetime->signedBy     = 'Signed By';
$lang->lifetime->closedBy     = 'Closed By';
$lang->lifetime->closedReason = 'Closed Reason';
$lang->lifetime->lastEdited   = 'Last Edited';

$lang->setOkFile = <<<EOT
<h5>For security reason, please do these steps. </h5>
<p>Create %s file. If this file exists already, reopen it and save again.</p>
EOT;

/* Items for javascript. */
$lang->js = new stdclass();
$lang->js->confirmDelete         = 'Do you want to delete it?';
$lang->js->deleteing             = 'Deleting...';
$lang->js->doing                 = 'Processing...';
$lang->js->timeout               = 'Timeout';
$lang->js->confirmDiscardChanges = 'Discard changes?';
$lang->js->yes                   = 'Yes';
$lang->js->no                    = 'No';

/* Contact fields*/
$lang->company = new stdclass();
$lang->company->contactUs = 'Contact';
$lang->company->address   = 'Address';
$lang->company->phone     = 'Phone';
$lang->company->email     = 'Email';
$lang->company->fax       = 'Fax';
$lang->company->qq        = 'QQ';
$lang->company->weibo     = 'Weibo';
$lang->company->weixin    = 'Wechat';
$lang->company->wangwang  = 'Wangwang';

/* The main menus. */
$lang->menu = new stdclass();

$lang->index   = new stdclass();
$lang->user    = new stdclass();
$lang->file    = new stdclass();
$lang->admin   = new stdclass();
$lang->tree    = new stdclass();
$lang->mail    = new stdclass();
$lang->dept    = new stdclass();
$lang->thread  = new stdclass();
$lang->block   = new stdclass();
$lang->action  = new stdclass();
$lang->effort  = new stdclass();
$lang->setting = new stdclass();
$lang->task    = new stdclass();
$lang->schema  = new stdclass();
$lang->package = new stdclass();

$lang->admin->common = 'Admin';

$lang->menu->sys = new stdclass();
$lang->menu->sys->company   = 'Company|company|setbasic|';
$lang->menu->sys->user      = 'User|user|admin|';
$lang->menu->sys->group     = 'Privilege|group|browse|';
$lang->menu->sys->entry     = 'App|entry|admin|';
$lang->menu->sys->system    = 'System|mail|admin|';
$lang->menu->sys->package   = 'Extension|package|browse|';

$lang->message = new stdclass(); 
$lang->blog    = new stdclass(); 
$lang->group   = new stdclass(); 

/* Menu entry. */
$lang->entry       = new stdclass();
$lang->entry->menu = new stdclass();
$lang->entry->menu->admin    = array('link' => 'Entries|entry|admin|', 'alias' => 'edit, integration, style, zentaoAdmin');
$lang->entry->menu->create   = array('link' => 'Create|entry|create|');
$lang->entry->menu->webapp   = 'Web App|webapp|obtain|';
$lang->entry->menu->category = 'Category|entry|category|';

/* Menu system. */
$lang->system       = new stdclass();
$lang->system->menu = new stdclass();
$lang->system->menu->mail   = array('link' => 'EMail|mail|admin|', 'alias' => 'detect,edit,save,test');
$lang->system->menu->trash  = array('link' => 'Trash|action|trash|');
$lang->system->menu->cron   = 'Cron|cron|index|';
$lang->system->menu->backup = 'Backup|backup|index|';

$lang->article = new stdclass();
$lang->article->menu = new stdclass();
$lang->article->menu->admin  = 'Browse|article|admin|';
$lang->article->menu->tree   = 'Category|tree|browse|type=article';
$lang->article->menu->create = array('link' => 'Add|article|create|type=article', 'float' => 'right');

$lang->menuGroups = new stdclass();

$lang->menu->dashboard = new stdclass();
$lang->menu->dashboard->todo     = 'To-do|todo|calendar|';
$lang->menu->dashboard->task     = 'Task|my|task|';
$lang->menu->dashboard->project  = 'Project|my|project|';
$lang->menu->dashboard->order    = 'Order|my|order|';
$lang->menu->dashboard->contract = 'Contract|my|contract|';
$lang->menu->dashboard->review   = 'Review|my|review|';
$lang->menu->dashboard->company  = 'Company|my|company|';
$lang->menu->dashboard->dynamic  = 'Dynamic|my|dynamic|';

/* Menu of customer module. */
if(!isset($lang->customer)) $lang->customer = new stdclass();
$lang->customer->menu = new stdclass();
$lang->customer->menu->browse       = 'All Customers|customer|browse|mode=all';
$lang->customer->menu->assignedTo   = 'Assigned To Me|customer|browse|mode=assignedtome';
$lang->customer->menu->past         = 'Urgently need contacted|customer|browse|mode=past';
$lang->customer->menu->today        = 'Contact Today|customer|browse|mode=today';
$lang->customer->menu->tomorrow     = 'Contact Tomorrow|customer|browse|mode=tomorrow';
$lang->customer->menu->thisweek     = 'Contact This Week|customer|browse|mode=thisweek';
$lang->customer->menu->thismonth    = 'Contact This Month|customer|browse|mode=thismonth';
$lang->customer->menu->public       = 'Public Customers|customer|browse|mode=public';
$lang->customer->menu->report       = 'Report|report|browse|module=customer';

/* Menu of provider module. */
if(!isset($lang->provider)) $lang->provider = new stdclass();
$lang->provider->menu = new stdclass();
$lang->provider->menu->browse = array('link' => 'All Providers|provider|browse|', 'alias' => 'create,edit,view');

/* Menu of product module. */
if(!isset($lang->product)) $lang->product = new stdclass();
$lang->product->menu = new stdclass();
$lang->product->menu->browse     = 'All Products|product|browse|mode=all';
$lang->product->menu->normal     = 'Normal|product|browse|mode=normal';
$lang->product->menu->developing = 'Developing|product|browse|mode=developing';
$lang->product->menu->offline    = 'Offline|product|browse|mode=offline';

$lang->todo = new stdclass();
$lang->todo->menu = new stdclass();
$lang->todo->menu->calendar        = 'Calendar|todo|calendar|';
$lang->todo->menu->all             = 'All|todo|browse|mode=all';
$lang->todo->menu->assignedToOther = 'Assigned to others|todo|browse|mode=assignedtoother';
$lang->todo->menu->assignedToMe    = 'Assigned to me|todo|browse|mode=assignedtome';
$lang->todo->menu->undone          = 'Undone|todo|browse|mode=undone';
$lang->todo->menu->future          = 'Pending|todo|browse|mode=future';
 
$lang->my = new stdclass();
$lang->my->review = new stdclass();
$lang->my->review->menu = new stdclass();
$lang->my->review->menu->attend   = 'Attend|my|review|type=attend';
$lang->my->review->menu->leave    = 'Leave|my|review|type=leave';
$lang->my->review->menu->overtime = 'Overtime|my|review|type=overtime';
$lang->my->review->menu->lieu     = 'Lieu|my|review|type=lieu';
$lang->my->review->menu->refund   = 'Refund|my|review|type=refund';

$lang->my->order = new stdclass();
$lang->my->order->menu = new stdclass();
$lang->my->order->menu->past       = 'Urgent|my|order|type=past';
$lang->my->order->menu->today      = 'Today|my|order|type=today';
$lang->my->order->menu->tomorrow   = 'Tomorrow|my|order|type=tomorrow';
$lang->my->order->menu->assignedTo = 'Assigned To Me|my|order|type=assignedTo';
$lang->my->order->menu->createdBy  = 'Created By Me|my|order|type=createdBy';
$lang->my->order->menu->signedBy   = 'Signed By Me|my|order|type=signedBy';
$lang->my->order->menu->all        = 'All|my|order|type=all';

$lang->my->contract = new stdclass();
$lang->my->contract->menu = new stdclass();
$lang->my->contract->menu->unfinished  = 'Unfinished|my|contract|type=unfinished';
$lang->my->contract->menu->finished    = 'Finished|my|contract|type=finished';
$lang->my->contract->menu->canceled    = 'Cancelled|my|contract|type=canceled';
$lang->my->contract->menu->returnedBy  = 'Paid|my|contract|type=returnedBy';
$lang->my->contract->menu->deliveredBy = 'Delivered|my|contract|type=deliveredBy';

$lang->my->task = new stdclass();
$lang->my->task->menu = new stdclass();
$lang->my->task->menu->assignedToMe = 'Assigned To Me|my|task|type=assignedTo';
$lang->my->task->menu->createdByMe  = 'Created By Me|my|task|type=createdBy';
$lang->my->task->menu->finishedByMe = 'Finished By Me|my|task|type=finishedBy';
$lang->my->task->menu->closedByMe   = 'Closed By Me|my|task|type=closedBy';
$lang->my->task->menu->canceledByMe = 'Cancelled By Me|my|task|type=canceledBy';
$lang->my->task->menu->unclosed     = 'Unclosed|my|task|type=unclosed';

$lang->my->dynamic = new stdclass();
$lang->my->dynamic->menu = new stdclass();
$lang->my->dynamic->menu->today      = 'Today|my|dynamic|period=today';
$lang->my->dynamic->menu->yesterday  = 'Yesterday|my|dynamic|period=yesterday';
$lang->my->dynamic->menu->twodaysago = 'The Day Before Yesterday|my|dynamic|period=twodaysago';
$lang->my->dynamic->menu->thisweek   = 'This Week|my|dynamic|period=thisweek';
$lang->my->dynamic->menu->lastweek   = 'Last Week|my|dynamic|period=lastweek';
$lang->my->dynamic->menu->thismonth  = 'This Month|my|dynamic|period=thismonth';
$lang->my->dynamic->menu->lastmonth  = 'Last Month|my|dynamic|period=lastmonth';
$lang->my->dynamic->menu->all        = 'All|my|dynamic|period=all';

$lang->my->company = new stdclass();

/* Menu of mail module. */
$lang->mail = new stdclass();
$lang->mail->menu = $lang->system->menu;
$lang->menuGroups->mail = 'system';

/* Menu of action module. */
$lang->action = new stdclass();
$lang->action->menu = $lang->system->menu;
$lang->menuGroups->action = 'system';

/* Menu of cron module. */
$lang->cron = new stdclass();
$lang->cron->menu = $lang->system->menu;
$lang->menuGroups->cron = 'system';

/* Menu of backup module. */
$lang->backup = new stdclass();
$lang->backup->menu = $lang->system->menu;
$lang->menuGroups->backup = 'system';

/* The error messages. */
$lang->error = new stdclass();
$lang->error->length       = array("<strong>%s</strong> length should be <strong>%s</strong>", "<strong>%s</strong> length should between <strong>%s</strong> and <strong>%s</strong>.");
$lang->error->reg          = "<strong>%s</strong> should like <strong>%s</strong>";
$lang->error->unique       = "<strong>%s</strong> has <strong>%s</strong> already. If you are sure this record has been deleted, you can restore it in admin panel, trash page.";
$lang->error->notempty     = "<strong>%s</strong> can not be empty.";
$lang->error->empty        = "<strong>%s</strong> must be empty.";
$lang->error->equal        = "<strong>%s</strong> must be <strong>%s</strong>.";
$lang->error->gt           = "<strong>%s</strong> should be greater than <strong>%s</strong>.";
$lang->error->ge           = "<strong>%s</strong> should be not less than <strong>%s</strong>.";
$lang->error->lt           = "<strong>%s</strong> should be less than <strong>%s</strong>";
$lang->error->le           = "<strong>%s</strong> should be no greater than <strong>%s</strong>.";
$lang->error->in           = '<strong>%s</strong> must in<strong>%s</strong>。';
$lang->error->int          = array("<strong>%s</strong> should be interger", "<strong>%s</strong> should between <strong>%s - %s</strong>.");
$lang->error->float        = "<strong>%s</strong> should be a interger or float.";
$lang->error->email        = "<strong>%s</strong> should be email.";
$lang->error->URL          = "<strong>%s</strong> should be url.";
$lang->error->date         = "<strong>%s</strong> should be date";
$lang->error->code         = '<strong>%s</strong> should be a combination of letters or numbers.';
$lang->error->account      = "<strong>%s</strong> should be a valid account.";
$lang->error->passwordsame = "Passwords must be the same";
$lang->error->passwordrule = "Password should 6 characters at least.";
$lang->error->captcha      = 'Captcah error.';
$lang->error->noWritable   = '%s cannot write. Please modify permissions!';
$lang->error->noConvertFun = 'Iconv and mb_convert_encoding do not exist. You cannot convert data into the desired coding!';
$lang->error->noCurlExt    = 'No curl extension.';
$lang->error->notInt       = '<strong>%s</strong> should be not a interger.';
$lang->error->pasteImg     = 'Your browser does not support paste pictures.';

/* The pager items. */
$lang->pager = new stdclass();
$lang->pager->noRecord     = "No records yet.";
$lang->pager->digest       = "<strong>%s</strong> records, <strong>%s</strong> per page, <strong>%s/%s</strong> ";
$lang->pager->recPerPage   = "<strong>%s</strong> per page";
$lang->pager->first        = " First";
$lang->pager->pre          = " Prev";
$lang->pager->next         = " Next";
$lang->pager->last         = " Last";
$lang->pager->locate       = "GO!";
$lang->pager->showMore     = 'Show more <i class="icon icon-double-angle-down"></i>';
$lang->pager->noMore       = 'No more';
$lang->pager->showTotal    = 'Show <strong>%s</strong> of <strong>%s</strong>';
$lang->pager->previousPage = "Previous";
$lang->pager->nextPage     = "Next";
$lang->pager->summery      = "<strong>%s-%s</strong> of <strong>%s</strong>.";

/* The excel items. */
$lang->excel = new stdClass();
$lang->excel->canNotRead = 'Cannot resolve this file.';

$lang->excel->error = new stdclass();
$lang->excel->error->info  = 'The value you entered is not in the drop-down list.';
$lang->excel->error->title = 'Input error';

$lang->excel->title = new stdclass();
$lang->excel->title->contact  = 'Contact';
$lang->excel->title->sysValue = 'System value';

$lang->excel->help = new stdclass();
$lang->excel->help->contact = "'realname' is required. This data will be ignored if empty.";

$lang->date = new stdclass();
$lang->date->minute = 'minute';
$lang->date->day    = 'day';

$lang->genderList = new stdclass();
$lang->genderList->m = 'Male';
$lang->genderList->f = 'Female';
$lang->genderList->u = '';

/* datepicker 时间*/
$lang->datepicker = new stdclass();

$lang->datepicker->dpText = new stdclass();
$lang->datepicker->dpText->TEXT_OR          = 'Or ';
$lang->datepicker->dpText->TEXT_PREV_YEAR   = 'Last Year';
$lang->datepicker->dpText->TEXT_PREV_MONTH  = 'Last Month';
$lang->datepicker->dpText->TEXT_PREV_WEEK   = 'Last Week';
$lang->datepicker->dpText->TEXT_YESTERDAY   = 'Yesterday';
$lang->datepicker->dpText->TEXT_THIS_MONTH  = 'This Month';
$lang->datepicker->dpText->TEXT_THIS_WEEK   = 'This Week';
$lang->datepicker->dpText->TEXT_TODAY       = 'Today';
$lang->datepicker->dpText->TEXT_NEXT_YEAR   = 'Next Year';
$lang->datepicker->dpText->TEXT_NEXT_MONTH  = 'Next Month';
$lang->datepicker->dpText->TEXT_CLOSE       = 'Close';
$lang->datepicker->dpText->TEXT_DATE        = 'select time frame';
$lang->datepicker->dpText->TEXT_CHOOSE_DATE = 'Choose date';

$lang->datepicker->dayNames     = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
$lang->datepicker->abbrDayNames = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
$lang->datepicker->monthNames   = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

/* Set currency items. */
$lang->currencyList['rmb']  = 'RMB Yuan';
$lang->currencyList['usd']  = 'US Dollar';
$lang->currencyList['hkd']  = 'HongKong Dollar';
$lang->currencyList['twd']  = 'New Taiwan Dollar';
$lang->currencyList['euro'] = 'Euro';
$lang->currencyList['dem']  = 'Deutsche Mark';
$lang->currencyList['chf']  = 'Swiss Franc';
$lang->currencyList['frf']  = 'French Franc';
$lang->currencyList['gbp']  = 'Pound';
$lang->currencyList['nlg']  = 'Florin';
$lang->currencyList['cad']  = 'Canadian Dollar';
$lang->currencyList['sur']  = 'Rouble';
$lang->currencyList['inr']  = 'Indian Rupee';
$lang->currencyList['aud']  = 'Australian Dollar';
$lang->currencyList['nzd']  = 'New Zealand Dollar';
$lang->currencyList['thb']  = 'Thai Baht';
$lang->currencyList['sgd']  = 'Singapore Dollar';

/* Currency symbols setting. */
$lang->currencySymbols['rmb']  = '￥';
$lang->currencySymbols['usd']  = '$';
$lang->currencySymbols['hkd']  = 'HK$';
$lang->currencySymbols['twd']  = 'NT$';
$lang->currencySymbols['euro'] = 'ECU';
$lang->currencySymbols['dem']  = 'DM';
$lang->currencySymbols['chf']  = 'SF';
$lang->currencySymbols['frf']  = 'FF';
$lang->currencySymbols['gbp']  = '￡';
$lang->currencySymbols['nlg']  = 'F';
$lang->currencySymbols['cad']  = 'CAN$';
$lang->currencySymbols['sur']  = 'Rbs';
$lang->currencySymbols['inr']  = 'Rs';
$lang->currencySymbols['aud']  = 'A$';
$lang->currencySymbols['nzd']  = 'NZ$';
$lang->currencySymbols['thb']  = 'B';
$lang->currencySymbols['sgd']  = 'S$';

$lang->currencyTip['w'] = '';
$lang->currencyTip['y'] = '';

/* Date times. */
define('DT_DATETIME1',  'Y-m-d H:i:s');
define('DT_DATETIME2',  'y-m-d H:i');
define('DT_MONTHTIME1', 'n/d H:i');
define('DT_MONTHTIME2', 'F j, H:i');
define('DT_DATE1',      'Y-m-d');
define('DT_DATE2',      'Ymd');
define('DT_DATE3',      'F j, Y ');
define('DT_DATE4',      'M j');
define('DT_DATE5',      'M Y');
define('DT_TIME1',      'H:i:s');
define('DT_TIME2',      'H:i');

include (dirname(__FILE__) . '/menuOrder.php');
