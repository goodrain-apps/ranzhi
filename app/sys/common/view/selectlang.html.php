<?php
/**
 * The selectLang view of common module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     common 
 * @version     $Id: selectlang.html.php 4029 2016-08-26 06:50:41Z liugang $
 * @link        http://www.ranzhico.com
 */
?>
<?php $clientLang = $this->app->getClientLang();?>
<a href='###' data-toggle='dropdown' class='dropdown-toggle'><i class='icon icon-globe'></i> <?php echo $config->langs[$clientLang]?></a>
<ul class='dropdown-menu'>
  <?php
  $langs = $config->langs;
  unset($langs[$clientLang]);
  foreach($langs as $langKey => $currentLang) echo "<li><a href='javascript:selectLang(\"$langKey\")'>$currentLang</a></li>";
  ?>
</ul>
