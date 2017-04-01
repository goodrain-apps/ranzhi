<?php
/**
 * ZenTaoPHP的dao和sql类。
 * The dao and sql class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 * 
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

helper::import(dirname(dirname(__FILE__)) . '/base/dao/dao.class.php');
/**
 * DAO类。
 * DAO, data access object.
 * 
 * @package framework
 */
class dao extends baseDAO
{
}

/**
 * SQL类。
 * The SQL class.
 * 
 * @package framework
 */
class sql extends baseSQL
{
    /**
     * 创建ORDER BY部分。
     * Create the order by part.
     * 
     * @param  string $order 
     * @access public
     * @return object the sql object.
     */
    public function orderBy($order)
    {
        if(strpos($order, 'convert(') !== false)
        {
            if($this->inCondition and !$this->conditionIsTrue) return $this;

            $order = str_replace(array('|', '', '_'), ' ', $order);
            $this->sql .= ' ' . DAO::ORDERBY . " $order";
            return $this;
        }
        else
        {
            return parent::orderBy($order);
        }
    }
}
