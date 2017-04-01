<?php
if(!isset($lang->attend)) $lang->attend = new stdclass();
$lang->attend->common       = '考勤';
$lang->attend->personal     = '我的考勤';
$lang->attend->department   = '部门考勤';
$lang->attend->company      = '公司考勤';
$lang->attend->detail       = '考勤明细';
$lang->attend->edit         = '补录';
$lang->attend->edited       = '已补录';
$lang->attend->leave        = '请假';
$lang->attend->leaved       = '已请假';
$lang->attend->lieu         = '调休';
$lang->attend->lieud        = '已调休';
$lang->attend->trip         = '出差';
$lang->attend->egress       = '外出';
$lang->attend->overtime     = '加班';
$lang->attend->overtimed    = '已加班';
$lang->attend->review       = '补录审核';
$lang->attend->settings     = '设置';
$lang->attend->export       = '导出';
$lang->attend->stat         = '统计';
$lang->attend->saveStat     = '保存考勤统计';
$lang->attend->exportStat   = '导出考勤统计表';
$lang->attend->exportDetail = '导出考勤明细';
$lang->attend->browseReview = '补录列表';

$lang->attend->id            = '编号';
$lang->attend->date          = '日期';
$lang->attend->account       = '用户';
$lang->attend->signIn        = '签到';
$lang->attend->signOut       = '签退';
$lang->attend->status        = '状态';
$lang->attend->ip            = 'IP';
$lang->attend->device        = '设备';
$lang->attend->desc          = '描述';
$lang->attend->dayName       = '星期';
$lang->attend->report        = '考勤表';
$lang->attend->AM            = '上午';
$lang->attend->PM            = '下午';
$lang->attend->ipList        = 'IP列表';
$lang->attend->noAttendUsers = '无需考勤者';

$lang->attend->user          = '用户';
$lang->attend->begin         = '开始';
$lang->attend->end           = '截至';
$lang->attend->search        = '搜索';

$lang->attend->manualIn     = '签到时间';
$lang->attend->manualOut    = '签退时间';
$lang->attend->reason       = '原因';
$lang->attend->reviewStatus = '补录状态';
$lang->attend->reviewedBy   = '审核人';
$lang->attend->reviewedDate = '审核时间';
$lang->attend->deserveDays  = '应出勤天数';
$lang->attend->actualDays   = '实际出勤天数';

$lang->attend->statusList['normal']   = '正常';
$lang->attend->statusList['late']     = '迟到';
$lang->attend->statusList['early']    = '早退';
$lang->attend->statusList['both']     = '迟到+早退';
$lang->attend->statusList['absent']   = '旷工';
$lang->attend->statusList['leave']    = '请假';
$lang->attend->statusList['makeup']   = '补班';
$lang->attend->statusList['overtime'] = '加班';
$lang->attend->statusList['lieu']     = '调休';
$lang->attend->statusList['trip']     = '出差';
$lang->attend->statusList['egress']   = '外出';
$lang->attend->statusList['rest']     = '休息日';

$lang->attend->abbrStatusList['normal']   = '√';
$lang->attend->abbrStatusList['late']     = '迟';
$lang->attend->abbrStatusList['early']    = '早';
$lang->attend->abbrStatusList['both']     = '迟+早';
$lang->attend->abbrStatusList['absent']   = '旷';
$lang->attend->abbrStatusList['leave']    = '假';
$lang->attend->abbrStatusList['makeup']   = '补';
$lang->attend->abbrStatusList['overtime'] = '加';
$lang->attend->abbrStatusList['lieu']     = '调';
$lang->attend->abbrStatusList['trip']     = '差';
$lang->attend->abbrStatusList['egress']   = '出';
$lang->attend->abbrStatusList['rest']     = '休';

$lang->attend->markStatusList['normal']   = '√';
$lang->attend->markStatusList['late']     = '=';
$lang->attend->markStatusList['early']    = '>';
$lang->attend->markStatusList['both']     = '=>';
$lang->attend->markStatusList['absent']   = 'x';
$lang->attend->markStatusList['leave']    = '!';
$lang->attend->markStatusList['makeup']   = '↑';
$lang->attend->markStatusList['overtime'] = '+';
$lang->attend->markStatusList['lieu']     = '↓';
$lang->attend->markStatusList['trip']     = '$';
$lang->attend->markStatusList['egress']   = '#';
$lang->attend->markStatusList['rest']     = '~';

$lang->attend->reasonList['normal']   = '准点上下班';
$lang->attend->reasonList['leave']    = '请假';
$lang->attend->reasonList['makeup']   = '补班';
$lang->attend->reasonList['overtime'] = '加班';
$lang->attend->reasonList['lieu']     = '调休';
$lang->attend->reasonList['trip']     = '出差';
$lang->attend->reasonList['egress']   = '外出';

$lang->attend->reviewStatusList['wait']   = '等待审核';
$lang->attend->reviewStatusList['pass']   = '通过';
$lang->attend->reviewStatusList['reject'] = '拒绝';

$lang->attend->inSuccess  = '签到成功';
$lang->attend->inFail     = '签到失败';
$lang->attend->outSuccess = '签退成功';
$lang->attend->outFail    = '签退失败';

$lang->attend->signInLimit  = '最晚签到';
$lang->attend->signOutLimit = '最早签退';
$lang->attend->workingDays  = '每周工作天数';
$lang->attend->workingHours = '每天工作工时';
$lang->attend->mustSignOut  = '必须签退';

$lang->attend->workingDaysList['5']  = "周一～周五";
$lang->attend->workingDaysList['6']  = "周一～周六";
$lang->attend->workingDaysList['7']  = "周一～周日";
$lang->attend->workingDaysList['12'] = "周日～周四";
$lang->attend->workingDaysList['13'] = "周日～周五";

$lang->attend->mustSignOutList['yes'] = '需要';
$lang->attend->mustSignOutList['no']  = '不需要';

$lang->attend->weeks = array('第一周', '第二周', '第三周', '第四周', '第五周', '第六周');

$lang->attend->notice['today']    = "<p>您今天的考勤状态为：%s，<a href='%s' %s>去补录</a>。</p>";
$lang->attend->notice['yestoday'] = "<p>您昨天的考勤状态为：%s，<a href='%s' %s>去补录</a>。</p>";
$lang->attend->notice['absent']   = "没有记录";

$lang->attend->confirmReview['pass']   = '您确定要执行通过操作吗？';
$lang->attend->confirmReview['reject'] = '您确定要执行拒绝操作吗？';

$lang->attend->settings         = '公司考勤设置';
$lang->attend->personalSettings = '个人考勤设置';
$lang->attend->setManager       = '部门经理设置';
$lang->attend->setDept          = '部门设置';

$lang->attend->beginDate = new stdClass();
$lang->attend->beginDate->company  = '公司开始考勤日期';
$lang->attend->beginDate->personal = '个人开始考勤日期';

$lang->attend->note = new stdClass();
$lang->attend->note->ip        = "允许签到的ip，多个ip用逗号隔开。支持IP段，如192.168.1.*";
$lang->attend->note->allip     = '无限制';
$lang->attend->note->IPDenied  = '签到IP受限，无法签到';
$lang->attend->note->beginDate = '设置开始考勤的日期，在该日期之前不记录考勤状态。默认使用公司开始考勤日期计算考勤状态，如果设置了个人开始考勤日期则使用个人日期。';

$lang->attend->h = '小时';
$lang->attend->m = '分';
$lang->attend->s = '秒';
