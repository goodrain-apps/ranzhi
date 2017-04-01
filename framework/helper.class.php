<?php
/**
 * Helper类从baseHelper类继承而来，您可以在这个文件中对其进行扩展。
 * This helper class extends from the baseHelper class, and you can
 * extend it by change this helper.class.php file.
 *
 * @package framework
 *
 * The author disclaims copyright to this source code. In place of
 * a legal notice, here is a blessing:
 * 
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */
include FRAME_ROOT . '/base/helper.class.php';
class helper extends baseHelper
{
    /**
     * Merge config items in database and config files.
     * 
     * @param  array  $dbConfig 
     * @param  string $moduleName 
     * @static
     * @access public
     * @return void
     */
    public static function mergeConfig($dbConfig, $moduleName = 'common')
    {
        global $config;

        $config2Merge = $config;
        if($moduleName != 'common') $config2Merge = $config->$moduleName;

        foreach($dbConfig as $item)
        {
            foreach($item as $record)
            {
                if(!is_object($record))
                {
                    $config2Merge->{$item->key} = $item->value;
                    break;
                }

                if(!isset($config2Merge->{$record->section})) $config2Merge->{$record->section} = new stdclass();
                if($record->key) $config2Merge->{$record->section}->{$record->key} = $record->value;
            }
        }
    }

    /**
     * Encode json for $.parseJSON
     * 
     * @param  array  $data 
     * @param  int    $options 
     * @static
     * @access public
     * @return string
     */
    static public function jsonEncode4Parse($data, $options = 0)
    {
        $json = json_encode($data);
        if($options) $json = str_replace(array("'", '"'), array('\u0027', '\u0022'), $json);

        $escapers     = array("\\",  "/",   "\"", "'", "\n",  "\r",  "\t", "\x08", "\x0c", "\\\\u");
        $replacements = array("\\\\", "\\/", "\\\"", "\'", "\\n", "\\r", "\\t",  "\\f",  "\\b", "\\u");
        return str_replace($escapers, $replacements, $json);
    }

    /**
     * Unify string to standard chars.
     * 
     * @param  string    $string 
     * @param  string    $to 
     * @static
     * @access public
     * @return string
     */
    public static function unify($string, $to = ',')
    {
        $labels = array('_', '、', ' ', '-', '?', '@', '&', '%', '~', '`', '+', '*', '/', '\\', '，', '。');
        $string = str_replace($labels, $to, $string);
        return preg_replace("/[{$to}]+/", $to, trim($string, $to));
    }
}

/**
 * Check exist onlybody param.
 * 
 * @access public
 * @return void
 */
function isonlybody()
{
    return (isset($_GET['onlybody']) and $_GET['onlybody'] == 'yes');
}

/**
 * Format money.
 * 
 * @param  float    $money 
 * @access public
 * @return string
 */
function formatMoney($money)
{
    if($money == 0) return '';
    return trim(preg_replace('/\.0*$/', '', number_format($money, 2)));
}

/**
 * Format time.
 * 
 * @param  int    $time 
 * @param  string $format 
 * @access public
 * @return void
 */
function formatTime($time, $format = '')
{
    $time = str_replace('0000-00-00', '', $time);
    $time = str_replace('00:00:00', '', $time);
    if(trim($time) == '') return ;
    if($format) return date($format, strtotime($time));
    return trim($time);
}

$basical  = array(0 => "零","壹","贰","叁","肆","伍","陆","柒","捌","玖");
$advanced = array(1 => "拾","佰","仟");
 
/**
 * Parse money from number to Chinese character. 
 * 
 * @param  int    $number 
 * @static
 * @access public
 * @return void
 */
function parseNumber($number)
{
    $number = trim($number);
    if ($number > 999999999999) return "数字太大，无法处理。抱歉！";
    if ($number == 0) return "零";
    if(strpos($number, '.'))
    {
        $number  = round($number, 2);
        $data    = explode(".", $number);
        $data[0] = parseInteger($data[0]);
        if(count($data) > 1) 
        {
            $data[1] = parseDecimal($data[1]);
            return $data[0] . $data[1];
        }
        return $data[0] . '整';
    } 
    else 
    {
      return parseInteger($number) . '整';
    }
}

/**
 * Parse the integer part of money. 
 * 
 * @param  int    $number 
 * @static
 * @access public
 * @return void
 */
function parseInteger($number)
{
    global $basical;
    global $advanced;
    if($number == 0) return;
    $arr      = array_reverse(str_split($number));
    $data     = '';
    $zero     = false;
    $zero_num = 0;
    foreach ($arr as $key => $value)
    {
        $_chinese = '';
        $zero     = ($value == 0) ? true : false;
        $index        = $key % 4;
        if($index && $zero && $zero_num>1)continue;
        switch($index)
        {
            case 0:
                if($zero)
                {
                    $zero_num = 0;
                } 
                else 
                {
                    $_chinese = $basical[$value];
                    $zero_num = 1;
                }
                if($key == 8)
                {
                    $_chinese .= '亿';
                } 
                elseif($key == 4)
                {
                    $_chinese .= '万';
                }
                break;  
            default:
                if($zero)
                {
                    if($zero_num == 1)
                    {
                        $_chinese = $basical[$value];
                        $zero_num++;
                    }
                }
                else
                {
                    $_chinese  = $basical[$value];
                    $_chinese .= $advanced[$index];
                }
        }
        $data = $_chinese . $data;
    }
    return $data . '元';
}
 
/**
 * Parse the decimal part of money. 
 * 
 * @param  int    $number 
 * @static
 * @access public
 * @return void
 */
function parseDecimal($number)
{
    global $basical;
    if(strlen($number) < 2) $number .= '0';
    $arr      = array_reverse(str_split($number));
    $data     = '';
    $zero_num = false;
    foreach ($arr as $key => $value)
    {
        $zero     = ($value == 0) ? true : false;
        $_chinese = '';
        if($key == 0)
        {
            if(!$zero)
            {
                $_chinese  = $basical[$value];
                $_chinese .= '分';
                $zero_num  = true;
            }
        } 
        else
        {
            if($zero)
            {
                if($zero_num) $_chinese = $basical[$value];
            } 
            else
            {
                $_chinese  = $basical[$value];
                $_chinese .= '角';
            }
        }
        $data = $_chinese . $data;
    }
    return $data;
}
