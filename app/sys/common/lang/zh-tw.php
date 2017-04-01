<?php
/**
 * The zh-tw file of common module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     common 
 * @version     $Id: zh-tw.php 4194 2016-10-21 09:23:53Z daitingting $
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
$lang->at         = ' 于 ';
$lang->by         = ' 由 ';
$lang->ditto      = '同上';
$lang->etc        = '等';
$lang->importIcon = "<i class='icon-download-alt'> </i>";
$lang->exportIcon = "<i class='icon-upload-alt'> </i>";

/* Apps lang items.*/
$lang->apps = new stdclass();
$lang->apps->crm        = '客戶';
$lang->apps->cash       = '財務';
$lang->apps->oa         = '辦公';
$lang->apps->doc        = '文檔';
$lang->apps->proj       = '項目';
$lang->apps->sys        = '通用';
$lang->apps->team       = '團隊';
$lang->apps->superadmin = '後台';

/* Lang items for ranzhi. */
$lang->ranzhi    = '然之協同';
$lang->agreement = "已閲讀並同意<a href='http://zpl.pub/page/zplv11.html' target='_blank'>《Z PUBLIC LICENSE授權協議1.2》</a>。<span class='text-danger'>未經許可，不得去除、隱藏或遮掩然之系統的任何標誌及連結。</span>";
$lang->poweredBy = "<a href='http://www.ranzhico.com/?v=%s' target='_blank'>{$lang->ranzhi} %s</a>";
$lang->ipLimited = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>抱歉，管理員限制當前IP登錄，請聯繫管理員解除限制。</body></html>";

/* IE6 alert.  */
$lang->IE6Alert = <<<EOT
    <div class='alert alert-danger' style='margin-top:100px;'>
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
      <h2>請使用其他瀏覽器訪問本站。</h2>
      <p>珍愛上網，遠離IE！</p>
      <p>我們檢測到您正在使用Internet Explorer 6 ——  IE6 瀏覽器, IE6 于2001年8月27日推出，而現在它已十分脫節。速度慢、不安全、不能很好的展示新一代網站。<br/></p>
      <a href='https://www.google.com/intl/zh-hk/chrome/browser/' class='btn btn-primary btn-lg' target='_blank'>谷歌瀏覽器</a>
      <a href='http://www.firefox.com/' class='btn btn-primary btn-lg' target='_blank'>火狐瀏覽器</a>
      <a href='http://www.opera.com/download' class='btn btn-primary btn-lg' target='_blank'>Opera瀏覽器</a>
      <p></p>
    </div>
EOT;

/* Themes. */
$lang->theme             = '主題';
$lang->themes['default'] = '預設';
$lang->themes['clear']   = '清晰';

/* Global lang items. */
$lang->home           = '首頁';
$lang->welcome        = "%s協同管理系統";
$lang->messages       = "<strong><i class='icon-comment-alt'></i> %s</strong>";
$lang->todayIs        = '今天是%s，';
$lang->today          = '今天';
$lang->aboutUs        = '關於我們';
$lang->about          = '關於';
$lang->link           = '友情連結';
$lang->frontHome      = '前台';
$lang->forumHome      = '論壇';
$lang->bookHome       = '手冊';
$lang->register       = '註冊';
$lang->logout         = '退出';
$lang->login          = '登錄';
$lang->account        = '帳號';
$lang->password       = '密碼';
$lang->all            = '全部';
$lang->changePassword = '修改密碼';
$lang->currentPos     = '當前位置';
$lang->categoryMenu   = '分類導航';
$lang->basicInfo      = '基本信息';

/* Global action items. */
$lang->reset          = '重填';
$lang->add            = '添加';
$lang->edit           = '編輯';
$lang->copy           = '複製';
$lang->and            = '並且';
$lang->or             = '或者';
$lang->hide           = '隱藏';
$lang->delete         = '刪除';
$lang->close          = '關閉';
$lang->finish         = '完成';
$lang->cancel         = '取消';
$lang->import         = '導入';
$lang->export         = '導出';
$lang->setFileName    = '檔案名';
$lang->setFileNum     = '記錄數';
$lang->setFileType    = '檔案類型';
$lang->save           = '保存';
$lang->saved          = '已保存';
$lang->confirm        = '確認';
$lang->preview        = '預覽';
$lang->goback         = '返回';
$lang->assign         = '指派';
$lang->start          = '開始';
$lang->create         = '新建';
$lang->forbid         = '禁用';
$lang->activate       = '激活';
$lang->ignore         = '忽略';
$lang->view           = '查看';
$lang->detail         = '詳情';
$lang->more           = '更多';
$lang->actions        = '操作';
$lang->history        = '歷史記錄';
$lang->reverse        = '切換順序';
$lang->switchDisplay  = '切換顯示';
$lang->feature        = '未來';
$lang->year           = '年';
$lang->month          = '月';
$lang->day            = '日';
$lang->loading        = '稍候...';
$lang->saveSuccess    = '保存成功';
$lang->setSuccess     = '設置成功';
$lang->sendSuccess    = '發送成功';
$lang->fail           = '失敗';
$lang->noResultsMatch = '沒有匹配的選項';
$lang->alias          = '搜索引擎優化使用，可使用英文、數字';
$lang->unfold         = '+';
$lang->fold           = '-';
$lang->files          = '附件';
$lang->addFiles       = '上傳了附件 ';
$lang->comment        = '備註';
$lang->selectAll      = '全選';
$lang->selectReverse  = '反選';
$lang->continueSave   = '繼續保存';
$lang->submitting     = '稍候...';
$lang->yes            = '是';
$lang->no             = '否';
$lang->signIn         = '簽到';
$lang->signOut        = '簽退';
$lang->sort           = '排序';
$lang->required       = '必填';
$lang->custom         = '自定義';

$lang->exportAll      = '導出全部記錄';
$lang->exportThisPage = '導出本頁記錄';
$lang->exportTemplate = '導出模板';
$lang->exportExcel    = '導出Excel';
$lang->exportWord     = '導出Word';
$lang->importFile     = '導入檔案';
$lang->importSuccess  = '導入成功';
$lang->importFail     = '導入失敗';

/* Items for lifetime. */
$lang->lifetime = new stdclass();
$lang->lifetime->createdBy    = '由誰創建';
$lang->lifetime->assignedTo   = '指派給';
$lang->lifetime->signedBy     = '由誰簽約';
$lang->lifetime->closedBy     = '由誰關閉';
$lang->lifetime->closedReason = '關閉原因';
$lang->lifetime->lastEdited   = '最後修改';

$lang->setOkFile = <<<EOT
<h5>請按照下面的步驟操作以確認您的管理員身份。</h5>
<p>創建 %s 檔案。如果存在該檔案，使用編輯軟件打開，重新保存一遍。</p>
EOT;

/* Items for javascript. */
$lang->js = new stdclass();
$lang->js->confirmDelete         = '您確定要執行刪除操作嗎？';
$lang->js->deleteing             = '刪除中';
$lang->js->doing                 = '處理中';
$lang->js->timeout               = '網絡超時,請重試';
$lang->js->confirmDiscardChanges = '表單已更改，確定關閉？';
$lang->js->yes                   = '是';
$lang->js->no                    = '否';

/* Contact fields*/
$lang->company = new stdclass();
$lang->company->contactUs = '聯繫我們';
$lang->company->address   = '地址';
$lang->company->phone     = '電話';
$lang->company->email     = 'Email';
$lang->company->fax       = '傳真';
$lang->company->qq        = 'QQ';
$lang->company->weibo     = '微博';
$lang->company->weixin    = '微信';
$lang->company->wangwang  = '旺旺';

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

$lang->admin->common = '後台管理';

$lang->menu->sys = new stdclass();
$lang->menu->sys->company   = '公司|company|setbasic|';
$lang->menu->sys->user      = '組織|user|admin|';
$lang->menu->sys->group     = '權限|group|browse|';
$lang->menu->sys->entry     = '應用|entry|admin|';
$lang->menu->sys->system    = '系統|mail|admin|';
$lang->menu->sys->package   = '擴展|package|browse|';

$lang->message = new stdclass(); 
$lang->blog    = new stdclass(); 
$lang->group   = new stdclass(); 

/* Menu entry. */
$lang->entry       = new stdclass();
$lang->entry->menu = new stdclass();
$lang->entry->menu->admin    = array('link' => '應用列表|entry|admin|', 'alias' => 'edit, integration, style, zentaoAdmin');
$lang->entry->menu->create   = '添加應用|entry|create|';
$lang->entry->menu->webapp   = 'WEB應用|webapp|obtain|';
$lang->entry->menu->category = '分組|entry|category|';

/* Menu system. */
$lang->system       = new stdclass();
$lang->system->menu = new stdclass();
$lang->system->menu->mail   = array('link' => '發信|mail|admin|', 'alias' => 'detect,edit,save,test');
$lang->system->menu->trash  = array('link' => '資源回收筒|action|trash|');
$lang->system->menu->cron   = '計劃任務|cron|index|';
$lang->system->menu->backup = '備份|backup|index|';

$lang->article = new stdclass();
$lang->article->menu = new stdclass();
$lang->article->menu->admin  = '瀏覽|article|admin|';
$lang->article->menu->tree   = '模組|tree|browse|type=article';
$lang->article->menu->create = array('link' => '添加文章|article|create|type=article', 'alias' => 'edit');

$lang->menuGroups = new stdclass();

$lang->menu->dashboard = new stdclass();
$lang->menu->dashboard->todo     = '待辦|todo|calendar|';
$lang->menu->dashboard->task     = '任務|my|task|';
$lang->menu->dashboard->project  = '項目|my|project|';
$lang->menu->dashboard->order    = '訂單|my|order|';
$lang->menu->dashboard->contract = '合同|my|contract|';
$lang->menu->dashboard->review   = '審批|my|review|';
$lang->menu->dashboard->company  = '組織|my|company|';
$lang->menu->dashboard->dynamic  = '動態|my|dynamic|';

/* Menu of customer module. */
if(!isset($lang->customer)) $lang->customer = new stdclass();
$lang->customer->menu = new stdclass();
$lang->customer->menu->browse       = '所有客戶|customer|browse|mode=all';
$lang->customer->menu->assignedTo   = '指派給我|customer|browse|mode=assignedTo';
$lang->customer->menu->past         = '亟需聯繫|customer|browse|mode=past';
$lang->customer->menu->today        = '今天聯繫|customer|browse|mode=today';
$lang->customer->menu->tomorrow     = '明天聯繫|customer|browse|mode=tomorrow';
$lang->customer->menu->thisweek     = '本週內聯繫|customer|browse|mode=thisweek';
$lang->customer->menu->thismonth    = '本月內聯繫|customer|browse|mode=thismonth';
$lang->customer->menu->public       = '客戶池|customer|browse|mode=public';
$lang->customer->menu->report       = '報表|report|browse|module=customer';

/* Menu of provider module. */
if(!isset($lang->provider)) $lang->provider = new stdclass();
$lang->provider->menu = new stdclass();
$lang->provider->menu->browse = array('link' => '供應商列表|provider|browse|', 'alias' => 'create,edit,view');

/* Menu of product module. */
if(!isset($lang->product)) $lang->product = new stdclass();
$lang->product->menu = new stdclass();
$lang->product->menu->browse     = '所有產品|product|browse|mode=all';
$lang->product->menu->normal     = '正常|product|browse|mode=normal';
$lang->product->menu->developing = '研發中|product|browse|mode=developing';
$lang->product->menu->offline    = '下線|product|browse|mode=offline';

$lang->todo = new stdclass();
$lang->todo->menu = new stdclass();
$lang->todo->menu->calendar        = '日曆|todo|calendar|';
$lang->todo->menu->all             = '所有|todo|browse|mode=all';
$lang->todo->menu->assignedToOther = '指派他人|todo|browse|mode=assignedtoother';
$lang->todo->menu->assignedToMe    = '指派給我|todo|browse|mode=assignedtome';
$lang->todo->menu->undone          = '未完成|todo|browse|mode=undone';
$lang->todo->menu->future          = '待定|todo|browse|mode=future';

$lang->my = new stdclass();
$lang->my->review = new stdclass();
$lang->my->review->menu = new stdclass();
$lang->my->review->menu->attend   = '考勤|my|review|type=attend';
$lang->my->review->menu->leave    = '請假|my|review|type=leave';
$lang->my->review->menu->overtime = '加班|my|review|type=overtime';
$lang->my->review->menu->lieu     = '調休|my|review|type=lieu';
$lang->my->review->menu->refund   = '報銷|my|review|type=refund';

$lang->my->order = new stdclass();
$lang->my->order->menu = new stdclass();
$lang->my->order->menu->past       = '亟需聯繫|my|order|type=past';
$lang->my->order->menu->today      = '今天聯繫|my|order|type=today';
$lang->my->order->menu->tomorrow   = '明天聯繫|my|order|type=tomorrow';
$lang->my->order->menu->assignedTo = '指派給我|my|order|type=assignedTo';
$lang->my->order->menu->createdBy  = '由我創建|my|order|type=createdBy';
$lang->my->order->menu->signedBy   = '由我簽約|my|order|type=signedBy';
$lang->my->order->menu->all        = '所有|my|order|type=all';
 
$lang->my->contract = new stdclass();
$lang->my->contract->menu = new stdclass();
$lang->my->contract->menu->unfinished  = '未完成|my|contract|type=unfinished';
$lang->my->contract->menu->finished    = '已完成|my|contract|type=finished';
$lang->my->contract->menu->canceled    = '已取消|my|contract|type=canceled';
$lang->my->contract->menu->returnedBy  = '由我回款|my|contract|type=returnedBy';
$lang->my->contract->menu->deliveredBy = '由我交付|my|contract|type=deliveredBy';

$lang->my->task = new stdclass();
$lang->my->task->menu = new stdclass();
$lang->my->task->menu->assignedToMe = '指派給我|my|task|type=assignedTo';
$lang->my->task->menu->createdByMe  = '由我創建|my|task|type=createdBy';
$lang->my->task->menu->finishedByMe = '由我完成|my|task|type=finishedBy';
$lang->my->task->menu->closedByMe   = '由我關閉|my|task|type=closedBy';
$lang->my->task->menu->canceledByMe = '由我取消|my|task|type=canceledBy';
$lang->my->task->menu->unclosed     = '未關閉|my|task|type=unclosed';

$lang->my->dynamic = new stdclass();
$lang->my->dynamic->menu = new stdclass();
$lang->my->dynamic->menu->today      = '今天|my|dynamic|period=today';
$lang->my->dynamic->menu->yesterday  = '昨天|my|dynamic|period=yesterday';
$lang->my->dynamic->menu->twodaysago = '前天|my|dynamic|period=twodaysago';
$lang->my->dynamic->menu->thisweek   = '本週|my|dynamic|period=thisweek';
$lang->my->dynamic->menu->lastweek   = '上周|my|dynamic|period=lastweek';
$lang->my->dynamic->menu->thismonth  = '本月|my|dynamic|period=thismonth';
$lang->my->dynamic->menu->lastmonth  = '上月|my|dynamic|period=lastmonth';
$lang->my->dynamic->menu->all        = '所有|my|dynamic|period=all';

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
$lang->error->length       = array('<strong>%s</strong>長度錯誤，應當為<strong>%s</strong>', '<strong>%s</strong>長度應當不超過<strong>%s</strong>，且不小於<strong>%s</strong>。');
$lang->error->reg          = '<strong>%s</strong>不符合格式，應當為:<strong>%s</strong>。';
$lang->error->unique       = '<strong>%s</strong>已經有<strong>%s</strong>這條記錄了。';
$lang->error->notempty     = '<strong>%s</strong>不能為空。';
$lang->error->empty        = "<strong>%s</strong>必須為空。";
$lang->error->equal        = '<strong>%s</strong>必須為<strong>%s</strong>。';
$lang->error->gt           = "<strong>%s</strong>應當大於<strong>%s</strong>。";
$lang->error->ge           = "<strong>%s</strong>應當不小於<strong>%s</strong>。";
$lang->error->lt           = "<strong>%s</strong>應當小於<strong>%s</strong>。";
$lang->error->le           = "<strong>%s</strong>應當不大於<strong>%s</strong>。";
$lang->error->in           = '<strong>%s</strong>必須為<strong>%s</strong>。';
$lang->error->int          = array('<strong>%s</strong>應當是數字。', '<strong>%s</strong>最小值為%s',  '<strong>%s</strong>應當介於<strong>%s-%s</strong>之間。');
$lang->error->float        = '<strong>%s</strong>應當是數字，可以是小數。';
$lang->error->email        = '<strong>%s</strong>應當為合法的EMAIL。';
$lang->error->URL          = '<strong>%s</strong>應當為合法的URL。';
$lang->error->date         = '<strong>%s</strong>應當為合法的日期。';
$lang->error->code         = '<strong>%s</strong>應當為字母或數字的組合。';
$lang->error->account      = '<strong>%s</strong>應當為字母或數字的組合，至少三位';
$lang->error->passwordsame = '兩次密碼應當相等。';
$lang->error->passwordrule = '密碼應該符合規則，長度至少為六位。';
$lang->error->captcha      = '請輸入正確的驗證碼。';
$lang->error->noWritable   = '%s 可能不可寫，請修改權限！';
$lang->error->noConvertFun = '不存在iconv和mb_convert_encoding轉碼方法，不能將數據轉成想要的編碼！';
$lang->error->noCurlExt    = '沒有加載curl擴展！';
$lang->error->notInt       = '<strong>%s</strong>不能為純數字組合。';
$lang->error->pasteImg     = '您的瀏覽器不支持粘貼圖片！';

/* The pager items. */
$lang->pager = new stdclass();
$lang->pager->noRecord   = '暫時沒有記錄。';
$lang->pager->digest     = "共 <strong>%s</strong> 條記錄，%s <strong>%s/%s</strong> &nbsp; ";
$lang->pager->recPerPage = "每頁 <strong>%s</strong> 條";
$lang->pager->first      = '首頁';
$lang->pager->pre        = '上頁';
$lang->pager->next       = '下頁';
$lang->pager->last       = '末頁';
$lang->pager->locate     = 'Go!';
$lang->pager->showMore   = '顯示更多 <i class="icon icon-double-angle-down"></i>';
$lang->pager->noMore     = '沒有更多';
$lang->pager->showTotal  = '已顯示 <strong>%s</strong> 項，共 <strong>%s</strong> 項';
$lang->pager->previousPage = "上一頁";
$lang->pager->nextPage     = "下一頁";
$lang->pager->summery      = "第 <strong>%s-%s</strong> 項，共 <strong>%s</strong> 項";

/* The excel items. */
$lang->excel = new stdClass();
$lang->excel->canNotRead = '不能解析該檔案';

$lang->excel->error = new stdclass();
$lang->excel->error->info  = '您輸入的值不在下拉框列表內。';
$lang->excel->error->title = '輸入有誤';

$lang->excel->title = new stdclass();
$lang->excel->title->contact  = '聯繫人';
$lang->excel->title->sysValue = '系統數據';

$lang->excel->help = new stdclass();
$lang->excel->help->contact = "“真實姓名“是必填欄位，如果不填導入時會忽略這條數據。";

$lang->date = new stdclass();
$lang->date->minute = '分鐘';
$lang->date->day    = '天';

$lang->genderList = new stdclass();
$lang->genderList->m = '男';
$lang->genderList->f = '女';
$lang->genderList->u = '';

/* datepicker 時間*/
$lang->datepicker = new stdclass();

$lang->datepicker->dpText = new stdclass();
$lang->datepicker->dpText->TEXT_OR          = '或 ';
$lang->datepicker->dpText->TEXT_PREV_YEAR   = '去年';
$lang->datepicker->dpText->TEXT_PREV_MONTH  = '上月';
$lang->datepicker->dpText->TEXT_PREV_WEEK   = '上周';
$lang->datepicker->dpText->TEXT_YESTERDAY   = '昨天';
$lang->datepicker->dpText->TEXT_THIS_MONTH  = '本月';
$lang->datepicker->dpText->TEXT_THIS_WEEK   = '本週';
$lang->datepicker->dpText->TEXT_TODAY       = '今天';
$lang->datepicker->dpText->TEXT_NEXT_YEAR   = '明年';
$lang->datepicker->dpText->TEXT_NEXT_MONTH  = '下月';
$lang->datepicker->dpText->TEXT_CLOSE       = '關閉';
$lang->datepicker->dpText->TEXT_DATE        = '選擇時間段';
$lang->datepicker->dpText->TEXT_CHOOSE_DATE = '選擇日期';

$lang->datepicker->dayNames     = array('星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六');
$lang->datepicker->abbrDayNames = array('日', '一', '二', '三', '四', '五', '六');
$lang->datepicker->monthNames   = array('一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月');

/* Set currency items. */
$lang->currencyList['rmb']  = '人民幣';
$lang->currencyList['usd']  = '美元';
$lang->currencyList['hkd']  = '港元';
$lang->currencyList['twd']  = '台元';
$lang->currencyList['euro'] = '歐元';
$lang->currencyList['dem']  = '馬克';
$lang->currencyList['chf']  = '瑞士法郎';
$lang->currencyList['frf']  = '法國法郎';
$lang->currencyList['gbp']  = '英鎊';
$lang->currencyList['nlg']  = '荷蘭盾';
$lang->currencyList['cad']  = '加拿大元';
$lang->currencyList['sur']  = '盧布';
$lang->currencyList['inr']  = '盧比';
$lang->currencyList['aud']  = '澳大利亞元';
$lang->currencyList['nzd']  = '新西蘭元';
$lang->currencyList['thb']  = '泰國銖';
$lang->currencyList['sgd']  = '新加坡元';

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

$lang->currencyTip['w'] = '萬';
$lang->currencyTip['y'] = '億';

/* The datetime settings. */
define('DT_DATETIME1',  'Y-m-d H:i:s');
define('DT_DATETIME2',  'y-m-d H:i');
define('DT_MONTHTIME1', 'n/d H:i');
define('DT_MONTHTIME2', 'n月d日 H:i');
define('DT_DATE1',      'Y-m-d');
define('DT_DATE2',      'Ymd');
define('DT_DATE3',      'Y年m月d日');
define('DT_DATE4',      'n月j日');
define('DT_DATE5',      'Y年m月');
define('DT_TIME1',      'H:i:s');
define('DT_TIME2',      'H:i');

include (dirname(__FILE__) . '/menuOrder.php');
