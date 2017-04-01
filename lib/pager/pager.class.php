<?php
/**
 * ZenTaoPHP的分页类。
 * The pager class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 * 
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

helper::import(dirname(dirname(__FILE__)) . '/base/pager/pager.class.php');
/**
 * pager类.
 * Pager class.
 * 
 * @package framework
 */
class pager extends basePager
{
    /**
     * 创建limit语句。
     * Create the limit string.
     * 
     * @access public
     * @return string
     */
    public function limit()
    {
        $limit = '';
        if($this->pageTotal > 1) $limit = ' limit ' . ($this->pageID - 1) * $this->recPerPage . ", $this->recPerPage";
        return $limit;
    }

    /**
     * Print show more link
     *
     * @access public
     * @return void
     */
    public function showMore($class = 'pager-more')
    {
        $link = '';
        if($this->recTotal === 0) $link = "<a class='$class disabled'>{$this->lang->pager->noRecord}</a>";
        else if($this->pageTotal <= $this->pageID) $link = "<a class='$class disabled'><span>" . sprintf($this->lang->pager->showTotal, $this->recTotal, $this->recTotal) . " &nbsp; {$this->lang->pager->noMore}</span></a>";
        else
        {
            $this->setParams();
            $this->params['pageID'] = $this->pageID + 1;
            $url  = helper::createLink($this->moduleName, $this->methodName, $this->params);
            $link = "<a class='$class' data-more='$url'><span>" . sprintf($this->lang->pager->showTotal, $this->recPerPage * $this->pageID, $this->recTotal) . " &nbsp; <span class='text-link'>{$this->lang->pager->showMore}</span></span></a>";
        }
        echo $link;
    }

    /**
     * 创建链接。
     * Create link.
     * 
     * @param  string    $title 
     * @access public
     * @return string
     */
    public function createLink($title)
    {
        return html::a(helper::createLink($this->moduleName, $this->methodName, $this->params), $title);
    }
}
