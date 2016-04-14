<?php
/**
 * The header view of common module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     common 
 * @version     $Id: header.html.php 3381 2015-12-18 06:43:59Z daitingting $
 * @link        http://www.ranzhico.com
 */
?>
<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php include 'header.lite.html.php';?>
<style>body {padding-top: 58px}</style>
<nav class='navbar navbar-inverse navbar-fixed-top' role='navigation' id='mainNavbar'>
  <div class='navbar-header'>
    <button type='button' class='navbar-toggle' data-toggle='collapse' data-target='.navbar-ex1-collapse'>
      <span class='icon-bar'></span>
      <span class='icon-bar'></span>
      <span class='icon-bar'></span>
    </button>
    <?php
    if(isset($lang->app))
    {
        echo html::a($this->createLink($this->config->default->module), $lang->app->name, "class='navbar-brand'");
    }
    else
    {
        echo html::a($this->createLink('admin'), $lang->admin->common, "class='navbar-brand'");
    }
    ?>
  </div>
  <div class='collapse navbar-collapse'>
    <?php echo commonModel::createMainMenu($this->moduleName);?>
  </div>
</nav>
<?php 
if(!isset($moduleMenu)) $moduleMenu = commonModel::createModuleMenu($this->moduleName);
if($moduleMenu) echo "$moduleMenu\n<div class='row page-content with-menu'>\n"; else echo "<div class='row page-content'>";
?>
