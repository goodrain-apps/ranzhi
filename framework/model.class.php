<?php
/**
 * model类从baseModel类继承而来，每个模块的model对象从model类集成。
 * 您可以对baseModel类进行扩展，扩展的逻辑可以定义在这个文件中。
 *
 * This model class extends from the baseModel class and extened by every module's model. 
 * You can extend the baseModel class by change this file.
 *
 * @package framework
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 * 
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */
include FRAME_ROOT . '/base/model.class.php';
class model extends baseModel
{
    /**
     * Delete one record.
     * 
     * @param  string    $table  the table name
     * @param  string    $id     the id value of the record to be deleted
     * @access public
     * @return void
     */
    public function delete($table, $id)
    {
        $this->dao->update($table)->set('deleted')->eq(1)->where('id')->eq($id)->exec();
        $object = ltrim(strstr(trim($table, '`'), '_'), '_');
        $this->loadModel('action')->create($object, $id, 'deleted', '', $extra = ACTIONMODEL::CAN_UNDELETED);
    }
}
