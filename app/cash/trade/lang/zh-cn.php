<?php
/**
 * The trade module zh-cn file of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     trade
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
if(!isset($lang->trade)) $lang->trade = new stdclass();
$lang->trade->common      = '记账';
$lang->trade->id          = '编号';
$lang->trade->depositor   = '账号';
$lang->trade->type        = '交易';
$lang->trade->currency    = '货币';
$lang->trade->trader      = '商户';
$lang->trade->customer    = '客户';
$lang->trade->money       = '金额';
$lang->trade->status      = '状态';
$lang->trade->rate        = '投资回报率';
$lang->trade->desc        = '说明';
$lang->trade->product     = '产品';
$lang->trade->order       = '订单';
$lang->trade->contract    = '合同';
$lang->trade->category    = '科目';
$lang->trade->date        = '时间';
$lang->trade->handlers    = '经手人';
$lang->trade->dept        = '部门';
$lang->trade->receipt     = '收款账户';
$lang->trade->payment     = '付款账户';
$lang->trade->fee         = '手续费';
$lang->trade->transferIn  = '转入金额';
$lang->trade->transferOut = '转出金额';
$lang->trade->schema      = '模板';
$lang->trade->importFile  = '导入文件';
$lang->trade->encode      = '编码';
$lang->trade->createdBy   = '由谁创建';
$lang->trade->createdDate = '创建时间';
$lang->trade->editedBy    = '由谁编辑';
$lang->trade->editedDate  = '编辑时间';
$lang->trade->month       = '月份';
$lang->trade->uploadFile  = '上传附件';
$lang->trade->productLine = '产品线';
$lang->trade->area        = '客户区域';
$lang->trade->industry    = '客户行业';
$lang->trade->level       = '客户级别';
$lang->trade->size        = '客户规模';
$lang->trade->interest    = '借贷利息';
$lang->trade->loanID      = '借贷';
$lang->trade->investID    = '投资';
$lang->trade->loanrate    = '利率';

$lang->trade->all           = '所有';
$lang->trade->create        = '记账';
$lang->trade->in            = '收入';
$lang->trade->out           = '支出';
$lang->trade->invest        = '投资';
$lang->trade->redeem        = '赎回';
$lang->trade->loan          = '借贷';
$lang->trade->repay         = '还贷';
$lang->trade->createIn      = '记收入';
$lang->trade->createOut     = '记支出';
$lang->trade->transfer      = '转账';
$lang->trade->edit          = '编辑账目';
$lang->trade->detail        = '明细';
$lang->trade->view          = '详情';
$lang->trade->browse        = '账目列表';
$lang->trade->delete        = '删除记录';
$lang->trade->batchCreate   = '批量记账';
$lang->trade->batchEdit     = '批量编辑';
$lang->trade->newTrader     = '新建';
$lang->trade->import        = '导入';
$lang->trade->export        = '导出';
$lang->trade->showImport    = '导入确认';
$lang->trade->fullYear      = '全年';
$lang->trade->quarter       = '季度';
$lang->trade->export2Excel  = '导出Excel';
$lang->trade->compare       = '年度对比表';
$lang->trade->setReportUnit = '设置报表单位';

$lang->trade->report = new stdclass();
$lang->trade->report->common      = '报表'; 
$lang->trade->report->annual      = '年度收支表'; 
$lang->trade->report->month       = '月度收支表'; 
$lang->trade->report->compare     = '年度对比表';
$lang->trade->report->create      = '生成报表';
$lang->trade->report->selectYears = '选择年份';
$lang->trade->report->undefined   = '未定义';
$lang->trade->report->compareTip  = '必须选择两个年份进行比较';
$lang->trade->report->unit        = '单位';

$lang->trade->report->unitList[1]       = '元';
$lang->trade->report->unitList[1000]    = '千元';
$lang->trade->report->unitList[10000]   = '万元';
$lang->trade->report->unitList[1000000] = '百万';

$lang->trade->report->typeList['annual']  = '年度收支表'; 
$lang->trade->report->typeList['compare'] = '年度对比表'; 

$lang->trade->typeList['in']          = '收入';
$lang->trade->typeList['out']         = '支出';
$lang->trade->typeList['transferout'] = '转出';
$lang->trade->typeList['transferin']  = '转入';
$lang->trade->typeList['invest']      = '投资';
$lang->trade->typeList['redeem']      = '赎回';
$lang->trade->typeList['loan']        = '借贷';
$lang->trade->typeList['repay']       = '还贷';

$lang->trade->quarters = new stdclass();
$lang->trade->quarters->Q4 = '10,11,12';
$lang->trade->quarters->Q3 = '07,08,09';
$lang->trade->quarters->Q2 = '04,05,06';
$lang->trade->quarters->Q1 = '01,02,03';

$lang->trade->quarterList['Q1'] = '第一季度';
$lang->trade->quarterList['Q2'] = '第二季度';
$lang->trade->quarterList['Q3'] = '第三季度';
$lang->trade->quarterList['Q4'] = '第四季度';

$lang->trade->monthList['last']  = '上年结转';
$lang->trade->monthList['01']    = '一月';
$lang->trade->monthList['02']    = '二月';
$lang->trade->monthList['03']    = '三月';
$lang->trade->monthList['04']    = '四月';
$lang->trade->monthList['05']    = '五月';
$lang->trade->monthList['06']    = '六月';
$lang->trade->monthList['07']    = '七月';
$lang->trade->monthList['08']    = '八月';
$lang->trade->monthList['09']    = '九月';
$lang->trade->monthList['10']    = '十月';
$lang->trade->monthList['11']    = '十一月';
$lang->trade->monthList['12']    = '十二月';
$lang->trade->monthList['total'] = '总计';

$lang->trade->categoryList['transferin']  = '转入';
$lang->trade->categoryList['transferout'] = '转出';
$lang->trade->categoryList['invest']      = '投资';
$lang->trade->categoryList['redeem']      = '赎回';
$lang->trade->categoryList['loan']        = '借贷';
$lang->trade->categoryList['repay']       = '还贷';

$lang->trade->transferCategoryList['transferin']  = '转入';
$lang->trade->transferCategoryList['transferout'] = '转出';

$lang->trade->objectTypeList['customer'] = '客户支出';
$lang->trade->objectTypeList['order']    = '订单支出';
$lang->trade->objectTypeList['contract'] = '合同支出';

$lang->trade->investTypeList['invest'] = '投资';
$lang->trade->investTypeList['redeem'] = '赎回';

$lang->trade->loanTypeList['loan']  = '借贷';
$lang->trade->loanTypeList['repay'] = '还贷';

$lang->trade->encodeList['gbk']  = 'GBK';
$lang->trade->encodeList['utf8'] = 'UTF-8';

$lang->trade->notEqual = '付款账号不能与收款账号相同。';
$lang->trade->feeDesc  = '%s %s 转入 %s';
$lang->trade->fileNode = '文件格式为csv';

$lang->trade->importedFields = array();
$lang->trade->importedFields['category'] = '项目';
$lang->trade->importedFields['type']     = '交易类型';
$lang->trade->importedFields['trader']   = '商户';
$lang->trade->importedFields['in']       = '收入';
$lang->trade->importedFields['out']      = '支出';
$lang->trade->importedFields['date']     = '时间';
$lang->trade->importedFields['category'] = '科目';
$lang->trade->importedFields['dept']     = '部门';
$lang->trade->importedFields['desc']     = '备注';
$lang->trade->importedFields['fee']      = '手续费';
$lang->trade->importedFields['product']  = '产品';

$lang->trade->statusList['returned']   = '已赎回';
$lang->trade->statusList['returning']  = '赎回中';
$lang->trade->statusList['unReturned'] = '未赎回';
$lang->trade->statusList['repaied']    = '已还贷';
$lang->trade->statusList['repaying']   = '还贷中';
$lang->trade->statusList['unRepaied']  = '未还贷';

$lang->trade->totalIn       = '%s收入%s；';
$lang->trade->totalOut      = '%s支出%s；';
$lang->trade->totalAmount   = '%s收入%s，支出%s，%s；';
$lang->trade->totalInvest   = '%s投资%s，赎回%s，未赎回%s，%s；';
$lang->trade->profit        = '盈';
$lang->trade->loss          = '亏';
$lang->trade->balance       = '收支平衡';
$lang->trade->total         = '总计';

$lang->trade->noTraderMatch = '没有匹配到相应的商户，点击新建';
$lang->trade->unique        = '今天已经有相同金额的账目';
$lang->trade->ignore        = '忽略';
$lang->trade->denied        = '您没有权限浏览此类账目，请联系管理员设置权限。';

$lang->trade->chartList['productLine'] = '按产品线统计';
$lang->trade->chartList['category']    = '按科目统计';
$lang->trade->chartList['area']        = '按客户区域统计';
$lang->trade->chartList['industry']    = '按客户行业统计';
$lang->trade->chartList['size']        = '按客户规模统计';
$lang->trade->chartList['dept']        = '按部门统计';

$lang->trade->excel = new stdclass();
$lang->trade->excel->title = new stdclass();
$lang->trade->excel->title->depositor = '账号盈亏表';

$lang->trade->excel->help = new stdclass();
$lang->trade->excel->help->depositor = '本报表不区分币种。';
