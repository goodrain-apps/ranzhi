<?php
/**
 * The selectTheme view of common module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     common 
 * @version     $Id: selecttheme.html.php 3138 2015-11-09 07:32:18Z chujilu $
 * @link        http://www.ranzhico.com
 */
?>
<?php $clientTheme = $app->cookie->theme ? $app->cookie->theme : 'default';?>
<a href='###' data-toggle='dropdown' class='dropdown-toggle'><i class='icon icon-adjust'></i> <?php echo $lang->theme?></a>
<ul class='dropdown-menu'>
  <?php
  $themes = $lang->themes;
  foreach($themes as $themeKey => $currentTheme)
  {
      echo "<li class='theme-option" . ($clientTheme == $themeKey ? " active" : '') . "'><a href='javascript:selectTheme(\"$themeKey\");' data-value='" . $themeKey . "'>" . $currentTheme . "</a></li>";
  }
  ?>
</ul>
