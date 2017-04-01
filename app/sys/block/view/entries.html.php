<div id='allEntriesBlock' class='all-entries'>
  <table class='table'>
    <tr>
    <?php 
    foreach($entries as $entry)
    {
        $class  = !$entry->buildin ? "class='iframe'" : '';
        $size   = $entry->size != 'max' ? json_decode($entry->size) : '';
        $width  = isset($size->width) ? "width=$size->width" : '';
        $height = isset($size->height) ? "height=$size->height" : '';

        $image = html::image($entry->logo, "width=18");

        if(!$entry->logo)
        {
            $hue = $entry->id * 47 % 360;
            $name = $entry->abbr ? $entry->abbr : $entry->name;
            $entryName = validater::checkCode(substr($name, 0, 1)) ? strtoupper(substr($name, 0, 1)) : substr($name, 0, 3);
            if(validater::checkCode(substr($name, 0, 1)) and validater::checkCode(substr($name, 1, 1)))   $entryName .= strtoupper(substr($name, 1, 1));
            if(validater::checkCode(substr($name, 0, 1)) and !validater::checkCode(substr($name, 1, 1)))  $entryName .= strtoupper(substr($name, 1, 3));
            if(!validater::checkCode(substr($name, 0, 1)) and validater::checkCode(substr($name, 3, 1)))  $entryName .= strtoupper(substr($name, 3, 1));
            if(!validater::checkCode(substr($name, 0, 1)) and !validater::checkCode(substr($name, 3, 1))) $entryName .= substr($name, 3, 3);
            $image = "<i class='icon icon-default' style='background-color: hsl($hue, 100%, 40%)'> <span>" . $entryName . "</span></i> ";
        }

        echo "<td class='pull-left' width='33%'>" . html::a($entry->login, $image . $entry->name, "$class $width $height") . "</td>";
    }
    ?>
    </tr>
  </table>
</div>
