<?php
/**
 * The model file of refund module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     refund
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class refundModel extends model
{
    /**
     * Get a refund by id.
     * 
     * @param  int    $ID 
     * @access public
     * @return void
     */
    public function getByID($ID)
    {
        $refund  = $this->dao->select('*')->from(TABLE_REFUND)->where('id')->eq($ID)->fetch();
        $details = $this->dao->select('*')->from(TABLE_REFUND)->where('parent')->eq($ID)->fetchAll('id');
        $refund->detail = $details;
        $refund->files  = $this->loadModel('file')->getByObject('refund', $ID);
        return $refund;
    }

    /**
     * Get refund list. 
     * 
     * @param  string $mode 
     * @param  string $date
     * @param  string $deptID 
     * @param  string $status 
     * @param  string $createdBy 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getList($mode = 'company', $type = '', $date = '', $deptID = '', $status = '', $createdBy = '', $orderBy = 'id_desc', $pager = null)
    {
        if($this->session->refundQuery == false) $this->session->set('refundQuery', ' 1 = 1');
        $refundQuery = $this->loadModel('search', 'sys')->replaceDynamic($this->session->refundQuery);

        $users = $this->loadModel('user')->getPairs('noclosed,noempty', $deptID);
        $refunds = $this->dao->select('*')->from(TABLE_REFUND)
            ->where('parent')->eq('0')
            ->beginIf($deptID != '')->andWhere('createdBy')->in(array_keys($users))->fi()
            ->beginIf($status != '')->andWhere('status')->in($status)->fi()
            ->beginIf($createdBy != '')->andWhere('createdBy')->in($createdBy)->fi()
            ->beginIf($mode != 'personal')->andWhere('status')->ne('draft')->fi()
            ->beginIf($date != '')->andWhere('date')->like("$date%")->fi()
            ->beginIf($mode == 'browseReview' and $status == 'pass,finish')
            ->andWhere('firstReviewer', true)->eq($this->app->user->account)
            ->orWhere('secondReviewer')->eq($this->app->user->account)
            ->markRight(1)
            ->fi()
            ->beginIF($type == 'bysearch')->andWhere($refundQuery)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        /* Set pre and next condition. */
        $this->session->set('refundQueryCondition', $this->dao->get());

        $details = $this->dao->select('*')->from(TABLE_REFUND)->where('parent')->in(array_keys($refunds))->fetchGroup('parent', 'id');
        foreach($refunds as $key => $refund) $refund->detail = isset($details[$key]) ? $details[$key] : array();

        return $refunds;
    }

    /**
     * Get all month of refund.
     * 
     * @access public
     * @return array
     */
    public function getAllMonth()
    {
        $monthList = array();
        $dateList  = $this->dao->select('date')->from(TABLE_REFUND)->groupBy('date')->orderBy('date_asc')->fetchAll('date');
        foreach($dateList as $date)
        {
            $year  = substr($date->date, 0, 4);
            $month = substr($date->date, 5, 2);
            if(!isset($monthList[$year][$month])) $monthList[$year][$month] = $month;
        }
        return $monthList;
    }

    /**
     * Create a refund.
     * 
     * @access public
     * @return bool
     */
    public function create()
    {
        $now    = helper::now();
        $refund = fixer::input('post')
            ->add('status', 'wait')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', $now) 
            ->setDefault('date', helper::today())
            ->join('related', ',')
            ->remove('firstReviewer,firstReviewDate,sencondReviewer,secondReviewDate,refundBy,refundDate')
            ->remove('dateList,moneyList,currencyList,categoryList,descList,relatedList')
            ->get();

        if(!empty($this->config->refund->firstReviewer) && $this->config->refund->firstReviewer == $this->app->user->account) 
        {
            $refund->status          = 'doing';
            $refund->firstReviewer   = $this->app->user->account;
            $refund->firstReviewDate = $now;

            if(empty($this->config->refund->secondReviewer) or (isset($this->config->refund->money) and $refund->money < $this->config->refund->money)) $refund->status = 'pass';
            if(!empty($this->config->refund->secondReviewer) && $this->config->refund->secondReviewer == $this->app->user->account)
            {
                $refund->status = 'pass';
                $refund->secondReviewer   = $this->app->user->account;
                $refund->secondReviewDate = $now;
            }
        }

        $this->dao->insert(TABLE_REFUND)
            ->data($refund, $skip='files,labels')
            ->autoCheck()
            ->batchCheck($this->config->refund->require->create, 'notempty')
            ->exec();

        if(dao::isError()) return false;
        $refundID = $this->dao->lastInsertID();
        $this->loadModel('file')->saveUpload('refund', $refundID);

        /* Insert detail */
        if(!empty($_POST['moneyList']))
        {
            foreach($this->post->moneyList as $key => $money)
            {
                if(empty($money)) continue;
                $detail = new stdclass();
                $detail->parent      = $refundID;
                $detail->status      = $refund->status;
                $detail->createdBy   = $this->app->user->account;
                $detail->createdDate = $now;
                $detail->money       = $money;
                $detail->date        = empty($_POST['dateList'][$key]) ? helper::today() : $_POST['dateList'][$key];
                $detail->currency    = $refund->currency;
                $detail->category    = $_POST['categoryList'][$key];
                $detail->desc        = $_POST['descList'][$key];
                $detail->related     = join(',', $_POST['relatedList'][$key]);

                $this->dao->insert(TABLE_REFUND)->data($detail)->autoCheck()->exec();
            }

            if(dao::isError()) return false;
        }

        return $refundID;
    } 

    /**
     * update a refund.
     * 
     * @param  int    $refundID 
     * @access public
     * @return object|bool
     */
    public function update($refundID)
    {
        $now = helper::now();
        $oldRefund = $this->getByID($refundID);
        $refund = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->setDefault('date', helper::today())
            ->join('related', ',')
            ->remove('status,firstReviewer,firstReviewDate,sencondReviewer,secondReviewDate,refundBy,refundDate,files,labels')
            ->remove('idList,dateList,moneyList,currencyList,categoryList,descList,relatedList')
            ->get();

        if(!empty($this->config->refund->firstReviewer) && $this->config->refund->firstReviewer == $this->app->user->account) 
        {
            $refund->status          = 'doing';
            $refund->firstReviewer   = $this->app->user->account;
            $refund->firstReviewDate = $now;

            if(empty($this->config->refund->secondReviewer)) $refund->status = 'pass';
            
            if(!empty($this->config->refund->secondReviewer) && $this->config->refund->secondReviewer == $this->app->user->account)
            {
                $refund->status = 'pass';
                $refund->secondReviewer   = $this->app->user->account;
                $refund->secondReviewDate = $now;
            }
        }

        $this->dao->update(TABLE_REFUND)
            ->data($refund)
            ->autoCheck()
            ->batchCheck($this->config->refund->require->edit, 'notempty')
            ->where('id')->eq($refundID)
            ->exec();

        /* update details. */
        if(!empty($_POST['moneyList']))
        {
            $newDetails = array();
            foreach($this->post->moneyList as $key => $money)
            {
                if(empty($money)) continue;
                $detail = new stdclass();
                $detail->id          = empty($_POST['idList'][$key]) ? '0' : $_POST['idList'][$key];
                $detail->parent      = $refundID;
                $detail->status      = 'wait';
                $detail->createdBy   = $this->app->user->account;
                $detail->createdDate = $now;
                $detail->money       = $money;
                $detail->date        = empty($_POST['dateList'][$key]) ? $refund->date : $_POST['dateList'][$key];
                $detail->currency    = $refund->currency;
                $detail->category    = !empty($_POST['categoryList'][$key]) ? $_POST['categoryList'][$key] : '';
                $detail->desc        = $_POST['descList'][$key];

                if($detail->id == '0') 
                {
                    $this->dao->insert(TABLE_REFUND)->data($detail, 'id')->autoCheck()->exec();
                    $detail->id = $this->dao->lastInsertID();
                }
                else
                {
                    $this->dao->update(TABLE_REFUND)->data($detail, 'id,createdBy,createdDate')->autoCheck()->where('id')->eq($detail->id)->exec();
                }
                $newDetails[$detail->id] = $detail;
            }
            $refund->detail = $newDetails;

            /* remove old details. */
            foreach($oldRefund->detail as $detail)
            {
                if(!isset($newDetails[$detail->id])) $this->dao->delete()->from(TABLE_REFUND)->where('id')->eq($detail->id)->exec();
            }
        }
        else
        {
            /* remove old details. */
            foreach($oldRefund->detail as $detail)
            {
                $this->dao->delete()->from(TABLE_REFUND)->where('id')->eq($detail->id)->exec();
            }
        }

        return commonModel::createChanges($oldRefund, $refund);
    }

    /**
     * delete a refund.
     * 
     * @param  int    $refundID 
     * @param  null   $null 
     * @access public
     * @return bool
     */
    public function delete($refundID, $null = null)
    {
        $oldRefund = $this->getByID($refundID);
        $this->dao->delete()->from(TABLE_REFUND)->where('id')->eq($refundID)->exec();

        /* remove old details. */
        if(!empty($oldRefund->detail))
        {
            foreach($oldRefund->detail as $detail)
            {
                $this->dao->delete()->from(TABLE_REFUND)->where('id')->eq($detail->id)->exec();
            }
        }
        return dao::isError();
    }

    /**
     * Set refund category. 
     * 
     * @parsm  array   $expenseIdList 
     * @access public
     * @return void
     */
    public function setCategory($expenseIdList)
    {
        $refundCategories   = $this->post->refundCategories ? $this->post->refundCategories : array();
        $unRefundCategories = array_diff($expenseIdList, $refundCategories);

        foreach($refundCategories as $refundCategory) $this->dao->update(TABLE_CATEGORY)->set('refund')->eq(1)->where('id')->eq($refundCategory)->exec();
        foreach($unRefundCategories as $unRefundCategory) $this->dao->update(TABLE_CATEGORY)->set('refund')->eq(0)->where('id')->eq($unRefundCategory)->exec();

        return !dao::isError();
    }

    /**
     * Get refund categories.
     * 
     * @access public
     * @return void
     */
    public function getCategory()
    {
        return $this->dao->select('*')->from(TABLE_CATEGORY)->where('type')->eq('out')->andWhere('refund')->eq(1)->fetchAll('id');
    }

    /**
     * Get refund category pairs.
     * 
     * @access public
     * @return void
     */
    public function getCategoryPairs()
    {
        $categories       = $this->loadModel('tree')->getOptionMenu('out', 0, $removeRoot = true);
        $refundCategories = $this->dao->select('*')->from(TABLE_CATEGORY)->where('type')->eq('out')->andWhere('refund')->eq(1)->fetchPairs('id', 'name');
        foreach($refundCategories as $key => $category)
        {
            $refundCategories[$key] = $categories[$key];
        }
        return $refundCategories;
    }

    /**
     * Review a refund.
     * 
     * @param  int    $refundID 
     * @param  int    $status 
     * @access public
     * @return bool
     */
    public function review($refundID)
    {
        $refund  = $this->getByID($refundID);
        $account = $this->app->user->account;
        $now     = helper::now();
        $data    = new stdclass();

        if($this->post->allReject or $this->post->status == 'reject')
        {
            $status = 'reject';
            $data->reason = $this->post->reason;
        }
        else
        {
            $status = 'pass';
            $data->money = $this->post->money;
            if(!empty($this->config->refund->secondReviewer) and isset($this->config->refund->money) and $data->money > $this->config->refund->money)
            {
                $status = 'doing';
                if($this->config->refund->secondReviewer == $account) $status = 'pass';
                if($this->config->refund->secondReviewer == $refund->createdBy) $status = 'pass';
            } 
        }

        $data->status = $status;
        if($refund->status == 'wait')
        {
            $data->firstReviewer   = $account;
            $data->firstReviewDate = $now;

            if($status == 'pass' && !empty($this->config->refund->secondReviewer) and isset($this->config->refund->money) and $data->money > $this->config->refund->money)
            {
                if($this->config->refund->secondReviewer == $account) $data->secondReviewer = $account;
                if($this->config->refund->secondReviewer == $refund->createdBy) $data->secondReviewer = $refund->createdBy;
                $data->secondReviewDate = $now;
            } 
        }

        if($refund->status == 'doing')
        {
            $data->secondReviewer   = $account;
            $data->secondReviewDate = $now;
        }

        if(isset($data->money) and $data->money > $refund->money) return array('result' => 'fail', 'message' => $this->lang->refund->correctMoney);
        $this->dao->update(TABLE_REFUND)->data($data)->check('money', 'notempty')->where('id')->eq($refundID)->exec();

        if(!empty($refund->detail))
        {
            foreach($refund->detail as $detail)
            {
                $data->status = $status;
                if($_POST["status{$detail->id}"] == 'reject') $data->status = 'reject';
                $this->dao->update(TABLE_REFUND)->data($data, $skip = 'money')->where('id')->eq($detail->id)->exec();
            }
        }

        return !dao::isError();
    }

    /**
     * Refund a reimbursement.
     * 
     * @param  int    $refundID 
     * @access public
     * @return void
     */
    public function reimburse($refundID)
    {
        $refund = $this->getByID($refundID);

        $data = new stdclass();
        $data->status     = 'finish';
        $data->refundBy   = $this->app->user->account;
        $data->refundDate = helper::now(); 

        $this->dao->update(TABLE_REFUND)->data($data)->where('id')->eq($refundID)->exec();
        foreach($refund->detail as $detail)
        {
            if($detail->status != 'reject') $this->dao->update(TABLE_REFUND)->data($data)->where('id')->eq($detail->id)->exec();
        }
        return !dao::isError();
    }

    /**
     * Create a trade for a reimbursement.
     * 
     * @param  int    $refundID 
     * @access public
     * @return void
     */
    public function createTrade($refundID)
    {
        $refund = $this->getByID($refundID);
        $user   = $this->loadModel('user')->getByAccount($refund->createdBy);

        $trade = new stdclass();
        $trade->type        = 'out';
        $trade->depositor   = $this->post->depositor;
        $trade->trader      = $this->post->customer ? $this->post->customer : 0;
        $trade->order       = $this->post->order ? $this->post->order : 0;
        $trade->contract    = $this->post->contract ? $this->post->contract : 0;
        $trade->money       = $refund->money;
        $trade->currency    = $refund->currency;
        $trade->date        = date('Y-m-d');
        $trade->handlers    = $refund->related;
        $trade->category    = $refund->category;
        $trade->desc        = $refund->desc;
        $trade->dept        = $user->dept;
        $trade->createdBy   = $this->app->user->account;
        $trade->createdDate = helper::now();
        $trade->editedBy    = $this->app->user->account;
        $trade->editedDate  = helper::now();

        $this->dao->insert(TABLE_TRADE)->data($trade)->autoCheck()->exec();
        $tradeID = $this->dao->lastInsertID();

        if(!empty($refund->detail))
        {
            foreach($refund->detail as $detail)
            {
                if($detail->status != 'pass') continue;

                $tradeDetail = new stdclass();
                $tradeDetail->type        = 'out';
                $tradeDetail->parent      = $tradeID;
                $tradeDetail->money       = $detail->money;
                $tradeDetail->date        = $detail->date;
                $tradeDetail->handlers    = $detail->related;
                $tradeDetail->category    = $detail->category;
                $tradeDetail->desc        = $detail->desc;
                $tradeDetail->createdBy   = $this->app->user->account;
                $tradeDetail->editedBy    = $this->app->user->account;
                $tradeDetail->createdDate = helper::now();
                $tradeDetail->editedDate  = helper::now();

                $this->dao->insert(TABLE_TRADE)->data($tradeDetail)->exec();
            }
        }

        return !dao::isError();
    }

    /**
     * Total refund list.
     * 
     * @param  array    $refunds 
     * @access public
     * @return string
     */
    public function total($refunds)
    {

        $totalMoney  = array();
        $currencyList = $this->loadModel('common', 'sys')->getCurrencySign();

        foreach($currencyList as $key => $currency)
        {
            $totalMoney[$key] = 0;
            foreach($refunds as $refund)
            {
                if($refund->currency != $key) continue;
                $totalMoney[$key] += $refund->money;
            }
        }

        $totalInfo = '';
        foreach($totalMoney as $currency => $money)
        {
            if(!$money) continue;
            $tidyMoney = "<span title='" . $money . "'>" . commonModel::tidyMoney($money) . '</span>';
            $totalInfo .= sprintf($this->lang->refund->totalMoney, $currencyList[$currency], $tidyMoney);
        }

        return $totalInfo;
    }
}
