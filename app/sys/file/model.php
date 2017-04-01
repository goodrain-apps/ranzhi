<?php
/**
 * The model file of file module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     file 
 * @version     $Id: model.php 4182 2016-10-20 09:31:02Z liugang $
 * @link        http://www.ranzhico.com
 */
?>
<?php
class fileModel extends model
{
    public $savePath = '';
    public $webPath  = '';
    public $now      = 0;

    /**
     * The construct function, set the save path and web path.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->now = time();
        $this->setSavePath();
        $this->setWebPath();
        $this->setMaxFileSize();
    }

    /**
     * Print files.
     * 
     * @param  object $files 
     * @access public
     * @return void
     * @todo fix style.
     */
    public function printFiles($files)
    {
        if(empty($files)) return false;

        $imagesHtml = '';
        $filesHtml  = '';
        foreach($files as $file)
        {
            $file->title = $file->title . ".$file->extension";
            if($file->isImage)
            {
                $imagesHtml .= "<li class='file-image file-{$file->extension}'>" . html::a(helper::createLink('file', 'download', "fileID=$file->id&mouse=left"), html::image($file->smallURL), "target='_blank' data-toggle='lightbox' data-width='{$file->width}' data-height='{$file->height}'") . '</li>';
            }
            else
            {
                $filesHtml .= "<li class='file file-{$file->extension}'>" . html::a(helper::createLink('file', 'download', "fileID=$file->id&mouse=left"), $file->title, "target='_blank'") . '</li>';
            }
        }
        echo "<ul class='article-files clearfix'>" . $imagesHtml . $filesHtml . '</ul>';
    }

    /**
     * Get files of an object list.
     * 
     * @param   string  $objectType 
     * @param   mixed   $objectID 
     * @param   bool    $isImage 
     * @access public
     * @return array
     */
    public function getByObject($objectType, $objectID, $isImage = false)
    {
        /* Get files group by objectID. */
        $files = $this->dao->select('*')
            ->from(TABLE_FILE)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->in($objectID)
            ->beginIf($isImage)->andWhere('extension')->in($this->config->file->imageExtensions)->orderBy('`primary`')->fi() 
            ->fetchGroup('objectID');

        /* Process these files. */
        foreach($files as $objectFiles) $this->batchProcessFile($objectFiles);

        /* If object is only an objectID, return it's files, else return all. */
        if(is_numeric($objectID) and !empty($files[$objectID])) return $files[$objectID];
        return $files;
    }

    /**
     * processFile just is image and add smallURL and middleURL if necessary.
     *
     * @param  object $file
     * @return object
     */    
    public function processFile($file)
    {
        $file->fullURL   = $this->webPath . $file->pathname;
        $file->middleURL = '';
        $file->smallURL  = '';
        $file->isImage   = false;

        if(in_array(strtolower($file->extension), $this->config->file->imageExtensions) !== false)
        {
            $file->middleURL = $this->webPath . str_replace('f_', 'm_', $file->pathname);
            $file->smallURL  = $this->webPath . str_replace('f_', 's_', $file->pathname);
            $file->largeURL  = $this->webPath . str_replace('f_', 'l_', $file->pathname);

            if(!file_exists(str_replace($this->webPath, $this->savePath, $file->middleURL))) $file->middleURL = $file->fullURL;
            if(!file_exists(str_replace($this->webPath, $this->savePath, $file->smallURL)))  $file->smallURL  = $file->fullURL;
            if(!file_exists(str_replace($this->webPath, $this->savePath, $file->largeURL)))  $file->largeURL  = $file->fullURL;

            $file->isImage   = true;
        }

        return $file;
    }
    
    /**
     * batch run processFile function.
     * 
     * @param array $files
     * @return array
     */
    public function batchProcessFile($files)
    {
        foreach($files as &$file) $file = $this->processFile($file);
        return $files;
    }

    /**
     * Get info of a file.
     * 
     * @param string $fileID 
     * @access public
     * @return void
     */
    public function getByID($fileID)
    {
        $file = $this->dao->findById($fileID)->from(TABLE_FILE)->fetch();
        $file->realPath = $this->savePath . $file->pathname;
        $file->webPath  = $this->webPath . $file->pathname;

        return $this->processFile($file);
    }

    /**
     * Save the files uploaded.
     * 
     * @param string $objectType 
     * @param string $objectID 
     * @param string $extra 
     * @access public
     * @return void
     */
    public function saveUpload($objectType = '', $objectID = '', $extra = '')
    {
        $fileTitles = array();
        $now        = helper::now();
        $files      = $this->getUpload();

        foreach($files as $id => $file)
        {   
            if(!move_uploaded_file($file['tmpname'], $this->savePath . $file['pathname'])) return false;

            $file['objectType']  = $objectType;
            $file['objectID']    = $objectID;
            $file['createdBy']   = $this->app->user->account;
            $file['createdDate'] = $now;
            $file['extra']       = $extra;
            unset($file['tmpname']);
            $this->dao->insert(TABLE_FILE)->data($file)->exec();
            $fileTitles[$this->dao->lastInsertId()] = $file['title'];
        }
        return $fileTitles;
    }

    /**
     * Get the count of uploaded files.
     * 
     * @access public
     * @return void
     */
    public function getCount()
    {
        return count($this->getUpload());
    }

    /**
     * get uploaded files.
     * 
     * @param string $htmlTagName 
     * @access public
     * @return void
     */
    public function getUpload($htmlTagName = 'files')
    {
        $files = array();
        if(!isset($_FILES[$htmlTagName])) return $files;
        /* The tag if an array. */
        if(is_array($_FILES[$htmlTagName]['name']))
        {
            extract($_FILES[$htmlTagName]);
            foreach($name as $id => $filename)
            {
                if(empty($filename)) continue;
                $file['extension'] = $this->getExtension($filename);
                $file['pathname']  = $this->setPathName($id, $file['extension']);
                $file['title']     = !empty($_POST['labels'][$id]) ? htmlspecialchars($_POST['labels'][$id]) : str_replace('.' . $file['extension'], '', $filename);
                $file['size']      = $size[$id];
                $file['tmpname']   = $tmp_name[$id];
                $files[] = $file;
            }
        }
        else
        {
            if(empty($_FILES[$htmlTagName]['name'])) return $files;
            extract($_FILES[$htmlTagName]);
            $file['extension'] = $this->getExtension($name);
            $file['pathname']  = $this->setPathName(0, $file['extension']);
            $file['title']     = !empty($_POST['labels'][0]) ? htmlspecialchars($_POST['labels'][0]) : substr($name, 0, strpos($name, $file['extension']) - 1);
            $file['size']      = $size;
            $file['tmpname']   = $tmp_name;
            return array($file);
        }
        return $files;
    }

    /**
     * Get extension name of a file.
     * 
     * @param string $filename 
     * @access public
     * @return void
     */
    public function getExtension($filename)
    {
        $extension = strtolower(trim(pathinfo($filename, PATHINFO_EXTENSION)));
        if(empty($extension) or !preg_match('/^[a-z0-9]+$/', $extension) or strlen($extension) > 5) return 'txt';
        if(strpos($this->config->file->dangers, $extension) !== false) return 'txt';
        return $extension;
    }

    /**
     * Set the path name.
     * 
     * @param string $fileID 
     * @param string $extension 
     * @access public
     * @return void
     */
    public function setPathName($fileID, $extension)
    {
        $sessionID  = session_id();
        $randString = substr($sessionID, mt_rand(0, strlen($sessionID) - 5), 3);
        $pathName   = date('Ym/dHis', $this->now) . $fileID . mt_rand(0, 10000) . $randString;

        /* rand file name more */
        list($path, $file) = explode('/', $pathName);
        $file = md5(mt_rand(0, 10000) . str_shuffle(md5($file)) . mt_rand(0, 10000));
        return $path . '/f_' . $file . '.' . $extension;
    }

    /**
     * Set the save path.
     * 
     * @access public
     * @return void
     */
    public function setSavePath()
    {
        $savePath = $this->app->getDataRoot() . "upload/" . date('Ym/', $this->now);
        if(!file_exists($savePath)) @mkdir($savePath, 0777, true);
        $this->savePath = dirname($savePath) . '/';
    }
    
    /**
     * Set the web path.
     * 
     * @access public
     * @return void
     */
    public function setWebPath()
    {
        $this->webPath = $this->app->getWebRoot() . "data/upload/";
    }

    /**
     * Edit rile
     * 
     * @param  int    $fileID 
     * @access public
     * @return void
     */
    public function edit($fileID)
    {
        $this->replaceFile($fileID);
        
        $fileInfo = fixer::input('post')->remove('upFile')->get();
        $this->dao->update(TABLE_FILE)->data($fileInfo)->autoCheck()->batchCheck('title', 'notempty')->where('id')->eq($fileID)->exec();
    }
    
    /**
     * Replace a file.
     * 
     * @access public
     * @return bool 
     */
    public function replaceFile($fileID, $postName = 'upFile')
    {
        if($files = $this->getUpload($postName))
        {
            $file      = $files[0];
            $fileInfo  = $this->dao->select('pathname, extension')->from(TABLE_FILE)->where('id')->eq($fileID)->fetch();
            $extension = strtolower($file['extension']);

            if($extension != $fileInfo->extension)
            {
                /* Remove old file. */
                if(file_exists($this->savePath . $fileInfo->pathname)) unlink($this->savePath . $fileInfo->pathname);
                $fileInfo->pathname  = str_replace(".{$fileInfo->extension}", ".$extension", $fileInfo->pathname);
                $fileInfo->extension = $extension;
            }

            $realPathName = $this->savePath . $fileInfo->pathname;
            move_uploaded_file($file['tmpname'], $realPathName);

            $fileInfo->createdBy   = $this->app->user->account;
            $fileInfo->createdDate = helper::now();
            $fileInfo->size      = $file['size'];
            $this->dao->update(TABLE_FILE)->data($fileInfo)->where('id')->eq($fileID)->exec();
            return true;
        }
        else
        {
            return false;
        }
    }
 
    /**
     * Delete the record and the file
     * 
     * @param  int    $fileID 
     * @access public
     * @return void
     */
    public function delete($fileID, $null = null)
    {
        $file = $this->getByID($fileID);
        if(file_exists($file->realPath)) unlink($file->realPath);
        $this->dao->delete()->from(TABLE_FILE)->where('id')->eq($file->id)->exec();
        return !dao::isError();
    }

    /**
     * Paste image in kindeditor at firefox and chrome. 
     * 
     * @param  string    $data 
     * @param  string    $uid 
     * @access public
     * @return string
     */
    public function pasteImage($data, $uid)
    {
        if(empty($data)) return '';
        $data = str_replace('\"', '"', $data);

        if(!$this->checkSavePath()) return false;

        $dataLength = strlen($data);
        if(ini_get('pcre.backtrack_limit') < $dataLength) ini_set('pcre.backtrack_limit', $dataLength);
        preg_match_all('/<img src="(data:image\/(\S+);base64,(\S+))".*\/>/U', $data, $out);
        foreach($out[3] as $key => $base64Image)
        {
            $extension = strtolower($out[2][$key]);
            if(!in_array($extension, $this->config->file->imageExtensions)) die();
            $imageData = base64_decode($base64Image);

            $file['extension']   = $extension;
            $file['pathname']    = $this->setPathName($key, $file['extension']);
            $file['size']        = strlen($imageData);
            $file['createdBy']   = $this->app->user->account;
            $file['createdDate'] = helper::today();
            $file['title']       = basename($file['pathname']);
            $file['editor']      = 1;

            file_put_contents($this->savePath . $file['pathname'], $imageData);
            $this->dao->insert(TABLE_FILE)->data($file)->exec();
            $_SESSION['album'][$uid][] = $this->dao->lastInsertID();

            $data = str_replace($out[1][$key], $this->webPath . $file['pathname'], $data);
        }

        return $data;
    }

    /**
     * Check save path is writeable.
     * 
     * @access public
     * @return void
     */
    public function checkSavePath()
    {
        return is_writable($this->savePath);
    }

    /**
     * Update objectType and objectID for file.
     * 
     * @param  string $uid 
     * @param  int    $objectID 
     * @param  string $bojectType 
     * @access public
     * @return void
     */
    public function updateObjectID($uid, $objectID, $objectType)
    {
        $data = new stdclass();
        $data->objectID   = $objectID;
        $data->objectType = $objectType;
        if(isset($_SESSION['album']) and isset($_SESSION['album'][$uid]))
        {
            $this->dao->update(TABLE_FILE)->data($data)->where('id')->in($_SESSION['album'][$uid])->exec();
            if(dao::isError()) return false;
            return !dao::isError(); 
        }
    }

    /**
     * Copy file in content from file space.
     * 
     * @param  string $content 
     * @param  int    $objectID 
     * @param  string $bojectType 
     * @access public
     * @return bool
     */
    public function copyFromContent($content, $objectID, $objectType)
    {
        preg_match_all('/<img src="(\/data\/upload\/(\S+)\?fromSpace=y)" .+ \/>/U', $content, $images);

        if(empty($images)) return false;
        foreach($images[2] as $key => $pathname)
        {
            $data = $this->dao->select('*')->from(TABLE_FILE)->where('pathname')->eq($pathname)->fetch();
            if(!$data) $data = new stdclass();

            $data->pathname    = $pathname;
            $data->extension   = $this->getExtension($pathname);
            $data->objectID    = $objectID;
            $data->objectType  = $objectType;
            $data->createdBy   = $this->app->user->account;
            $data->createdDate = helper::now();

            $fileExists = $this->dao->select('count(*) as count')->from(TABLE_FILE)
                ->where('objectType')->eq($objectType)
                ->andWhere('objectID')->eq($objectID)
                ->andWhere('pathname')->eq($pathname)
                ->fetch('count');

            if($fileExists == 0) $this->dao->insert(TABLE_FILE)->data($data, $skip = 'id')->exec();
        }

        return !dao::isError(); 
    }

    /**
     * Compress image to config configured size.
     * 
     * @param  string  $rawImage 
     * @param  string  $target 
     * @param  int     $x
     * @param  int     $y 
     * @param  int     $width 
     * @param  int     $height 
     * @access public
     * @return void
     */
    public function cropImage($rawImage, $target, $x, $y, $width, $height)
    {
        $this->app->loadClass('phpthumb', true);

        if(!extension_loaded('gd')) return false;

        $croper = phpThumbFactory::create($rawImage);
        $croper->crop($x, $y, $width, $height);
        $croper->save($target);
    }

    /**
     * Resize an image.
     * 
     * @param  int    $rawImage 
     * @param  int    $target 
     * @param  int    $width 
     * @param  int    $height 
     * @access public
     * @return void
     */
    public function resizeImage($rawImage, $target, $width, $height)
    {
        $this->app->loadClass('phpthumb', true);

        if(!extension_loaded('gd')) return false;

        $thumber = phpThumbFactory::create($rawImage);
        $thumber->resize($width, $height);
        $thumber->save($target);
    }

    /**
     * Set max file size by php.ini.
     * 
     * @access public
     * @return void
     */
    public function setMaxFileSize()
    {
        global $config;
        $value = ini_get('upload_max_filesize');
        $last  = strtolower(substr($value, strlen($value) - 1));
        $value = substr($value, 0, strlen($value) - 1);
        switch($last)
        {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }
        $config->file->maxSize = $value;
    }

    /**
     * parse excel file into array. 
     * 
     * @param  array  $fields 
     * @access public
     * @return array
     */
    public function parseExcel($fields = array())
    {
        $file = $this->session->importFile;

        $phpExcel  = $this->app->loadClass('phpexcel');
        $phpReader = new PHPExcel_Reader_Excel2007(); 
        if(!$phpReader->canRead($file)) $phpReader = new PHPExcel_Reader_Excel5();

        $phpExcel     = $phpReader->load($file);
        $currentSheet = $phpExcel->getSheet(0); 
        $allRows      = $currentSheet->getHighestRow(); 
        $allColumns   = $currentSheet->getHighestColumn(); 
        /* In php, 'A'++  === 'B', 'Z'++ === 'AA', 'a'++ === 'b', 'z'++ === 'aa'. */
        $allColumns++;
        $currentColumn = 'A';
        $columnKey = array();
        while($currentColumn != $allColumns)
        {
            $title = $currentSheet->getCell($currentColumn . '1')->getValue();
            $field = array_search($title, $fields);
            $columnKey[$currentColumn] = $field ? $field : '';
            $currentColumn++;
        }
        $dataList = array();
        for($currentRow = 2; $currentRow <= $allRows; $currentRow++)
        {
            $currentColumn = 'A'; 
            $data          = new stdclass(); 
            $ignoreRow     = false;
            while($currentColumn != $allColumns)
            {
                $cellValue = $currentSheet->getCell($currentColumn . $currentRow)->getValue();
                $cellValue = trim($cellValue);
                if(empty($columnKey[$currentColumn]))
                {
                    $currentColumn++;
                    continue;
                }
                $field = $columnKey[$currentColumn];
                $currentColumn++;
                $data->$field = empty($cellValue) ? '' : $cellValue;
            }
            foreach(array_keys($fields) as $key) if(!isset($data->$key)) $data->$key = '';
            $dataList[] = $data;
        }
        return $dataList;   
    }

    /**
     * Process editor.
     * 
     * @param  object    $data 
     * @param  string    $editorList 
     * @access public
     * @return object
     */
    public function processEditor($data, $editorList)
    {
        $editors = explode(',', $editorList);
        foreach($editors as $editorID)
        {
            $editorID = trim($editorID);
            if(empty($editorID) or !isset($data->$editorID) or !isset($data->uid)) continue;
            $data->$editorID = $this->pasteImage($data->$editorID, $data->uid);
        }
        return $data;
    }

    /**
     * Compress image 
     * 
     * @param  array    $file 
     * @access public
     * @return array
     */
    public function compressImage($file)
    {
        if(!extension_loaded('gd') or !function_exists('imagecreatefromjpeg')) return $file;

        $pathName    = $file['pathname'];
        $fileName    = $this->savePath . $pathName;
        $suffix      = strrchr($fileName, '.');
        $lowerSuffix = strtolower($suffix);

        if(!in_array($lowerSuffix, $this->config->file->image2Compress)) return $file;

        $quality        = 85;
        $newSuffix      = '.jpg';
        $compressedName = str_replace($suffix, $newSuffix, $pathName);

        $res  = $lowerSuffix == '.bmp' ? $this->imagecreatefrombmp($fileName) : imagecreatefromjpeg($fileName);
        imagejpeg($res, $this->savePath . $compressedName, $quality);
        if($fileName != $this->savePath . $compressedName) unlink($fileName);

        $file['pathname']   = $compressedName;
        $file['extension']  = ltrim($newSuffix, '.');
        $file['size']       = filesize($this->savePath . $compressedName);
        return $file;
    }

    /**
     * Read 24bit BMP files
     * Author: de77
     * Licence: MIT
     * Webpage: de77.com
     * Version: 07.02.2010
     * Source : https://github.com/acustodioo/pic/blob/master/imagecreatefrombmp.function.php
     * 
     * @param  string    $filename 
     * @access public
     * @return resource
     */
    public function imagecreatefrombmp($filename) {
        $f = fopen($filename, "rb");

        //read header    
        $header = fread($f, 54);
        $header = unpack('c2identifier/Vfile_size/Vreserved/Vbitmap_data/Vheader_size/'.
            'Vwidth/Vheight/vplanes/vbits_per_pixel/Vcompression/Vdata_size/'.
            'Vh_resolution/Vv_resolution/Vcolors/Vimportant_colors', $header);

        if ($header['identifier1'] != 66 or $header['identifier2'] != 77)
            return false;

        if ($header['bits_per_pixel'] != 24)
            return false;

        $wid2 = ceil((3 * $header['width']) / 4) * 4;

        $wid = $header['width'];
        $hei = $header['height'];

        $img = imagecreatetruecolor($header['width'], $header['height']);

        //read pixels
        for ($y = $hei - 1; $y >= 0; $y--) {
            $row = fread($f, $wid2);
            $pixels = str_split($row, 3);

            for ($x = 0; $x < $wid; $x++) {
                imagesetpixel($img, $x, $y, $this->dwordize($pixels[$x]));
            }
        }
        fclose($f);
        return $img;
    }

    /**
     * Dwordize for imagecreatefrombmp 
     * 
     * @param  streing $str 
     * @access private
     * @return int
     */
    private function dwordize($str)
    {
        $a = ord($str[0]);
        $b = ord($str[1]);
        $c = ord($str[2]);
        return $c * 256 * 256 + $b * 256 + $a;
    }

    /**
     * Exclude html.
     * 
     * @param  string $content 
     * @param  string $extra 
     * @access public
     * @return string
     */
    public function excludeHtml($content, $extra = '')
    {
        $content = str_replace(array('<i>', '&nbsp;', '<br />'), array('', ' ', "\n"),$content);
        $content = preg_replace('/<[^ia\/]+(.*)>/U', '', $content);
        $content = preg_replace('/<\/[^a]{1}.*>/U', '', $content);
        $content = preg_replace('/<i .*>/U', '', $content);
        if($extra != 'noImg') $content = preg_replace('/<img src="data\/"(.*)\/>/U', "<img src=\"" . common::getSysURL() . "data/\"\$1/>", $content);
        return $content;
    }
}
