<?php
/**
 * The class file of export2excel of Yidou.
 *
 * @copyright   Copyright 2014-2015 青岛亿斗网络信息有限公司(QingDao Yidou Network Infomation Co,LTD, www.yidou.com.cn)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      yaozeyuan <yaozeyuan@yidou.biz>
 * @package     export2excel 
 * @version     $Id$
 * @link        http://www.yidou.com.cn
 */
class export2excel
{
    public function export($data, $fileType = 'xls', $savePath = '')
    {
        $xlsFile = $fileType == 'xls' ? new export2Xls() : new export2Xlsx();
        $xlsFile->export($data, $savePath);
    }
}

/* 
 * The API of export2excel 
 *
 * Here are some tips of excelData(named $data) structure maybe uesful. you can use these to create xls/xlsx file . The API is interchangeable.
 * 
 * Base property:
 * data->fileName set the fileName
 * data->kind set the kind of excel module
 * data->fields is array like ($fieldsKey => $fieldsTitle). The order of $fieldsKey is also the column's order
 * data->row is array like ($lineNumber => array($excelKey => $value)).This is the data of Excel file. System will fill $value as data into every cell according to $lineNumber and $excelKey 
 *
 * Merge cell
 * if there is set data->rowspan[$num] and ",$key," in data->rowspan[$num]['rows'], then the cells will be merge
 *      data->rowspan[$num]['rows'] => merge excelKey[$lineKey]:$lineNumber to excelKey[$lineKey]:($lineNumber + data->rowspan[$num]) into one cell
 *      data->colspan[$num]['cols'] => merge excelKey[$lineKey]:$lineNumber to transIntoExcelKey(int(excelKey[$lineKey]) + colspan[$num]['cols']):$lineNumber into one cell
 *
 * html content
 *      if you set config->excel->editor[$this->rawExcelData->kind] like 'excelKey1,excelKey2...', and excelKey in that, then $value of all column's cell will be process to remove html tag
 *
 * write sysData and use droplist style on cell
 * sysData is an array like (excelKey => valueList), system use this sheet to store extraData.
 * if you want to have droplist style on some column in sheet1, you can set data->($exceKey . 'List'), data->listStyle and  data->sysData sothat the data will be writen into the sysData sheet and you can see the droplist style is enable.
 * the data->listStyle and data->sysData is an  array of series value like ['dropListStyleColumnName' . 'List'] , like ('nameList', 'categoryList', ...) the dropListStyleColumnName used to indicate witch column need that style and data->[dropListStyleColumnName . 'List'] use to transfer data for system get real data to build datalist in sysdata sheet.
 *
 * FreezePane
 * if you set config->excel->freeze->{$this->data->kind}->targetColumnName, this column will be freezed 
 *
 * Set width
 * You can set $data->customWidth like array($fieldKey1 => width, $fieldKey2 => width, $fieldKey3 => width,...) to set width by you like
 * or modify
 *       config->excel->titleFields
 *       config->excel->centerFields
 *       config->excel->dateField
 * to have default style
 *
 * color
 * if you set data->nocolor, the excel file won't have color
 *
 * File title
 * The lang->excel->title->{data->kind} is the title of data->kind excel file
 *
 * SysData title
 * The lang->excel->title->sysValue is the name of sysData sheet , this is only can use for xls file.
 */
class export2Xls extends model
{
    /**
     * Set area style.
     *
     * @param  object $excelSheet
     * @param  array  $style
     * @param  string $area
     * @access public
     * @return void
     */
    public function setAreaStyle($excelSheet, $style, $area)
    {
        $styleObj = new PHPExcel_Style();
        $styleObj->applyFromArray($style);
        $excelSheet->setSharedStyle($styleObj, $area);
    }

    /**
     * Init for excel data.
     *
     * @param  int    $data
     * @access public
     * @return void
     */
    public function init($data)
    {
        $this->phpExcel     = $this->app->loadClass('phpexcel');
        $this->file         = $this->loadModel('file');
        $this->rawExcelData = $data;
        $this->fields       = $this->rawExcelData->fields;
        $this->rows         = $this->rawExcelData->rows;
        $this->fieldsKey    = array_keys($this->fields);

        if(!$this->rawExcelData->fileName) $this->rawExcelData->fileName = $this->rawExcelData->kind;
    }

    /**
     * Export data to Excel. This is main function.
     * 
     * @param  object $data 
     * @param  string $savePath, if $savePath != '', then the file will save in $savePath
     * @access public
     * @return void
     */
    public function export($data, $savePath = '')
    {
        $this->init($data);
        $this->excelKey = array();
        for($i = 0; $i < count($this->fieldsKey); $i++) $this->excelKey[$this->fieldsKey[$i]] = $this->setExcelField($i);

        /* Set file base property */
        $excelProps = $this->phpExcel->getProperties();
        $excelProps->setCreator('YiDou');
        $excelProps->setLastModifiedBy('YiDou');
        $excelProps->setTitle('Office XLS Document');
        $excelProps->setSubject('Office XLS Document');
        $excelProps->setDescription('Document generated by PHPExcel.');
        $excelProps->setKeywords('office excel PHPExcel');
        $excelProps->setCategory('Result file');

        /* Set file title by lang->excel->title->moduleKind */
        $this->phpExcel->setActiveSheetIndex(0);
        $sheetTitle = isset($this->lang->excel->title->{$this->rawExcelData->kind}) ? $this->lang->excel->title->{$this->rawExcelData->kind} : $this->rawExcelData->kind;
        $excelSheet = $this->phpExcel->getActiveSheet();
        if($sheetTitle) $excelSheet->setTitle($sheetTitle);
        foreach($this->fields as $key => $field) $excelSheet->setCellValueExplicit($this->excelKey[$key] . '1', $field, PHPExcel_Cell_DataType::TYPE_STRING);

        /* Write system data in excel.*/
        $this->writeSysData();

        $i = 1;
        foreach($this->rows as $num => $row)
        {
            $i++;
            foreach($row as $key => $value)
            {
                if(isset($this->excelKey[$key]))
                {
                    /* Merge Cells.*/
                    if(isset($this->rawExcelData->rowspan[$num]) and (strpos($this->rawExcelData->rowspan[$num]['rows'], ",$key,") !== false))
                    {
                        $excelSheet->mergeCells($this->excelKey[$key] . $i . ":" . $this->excelKey[$key] . ($i + $this->rawExcelData->rowspan[$num]['num'] - 1));
                    }
                    if(isset($this->rawExcelData->colspan[$num]) and strpos($this->rawExcelData->colspan[$num]['cols'], ",$key,") !== false)
                    {
                        $excelSheet->mergeCells($this->excelKey[$key] . $i . ":" . chr(ord($this->excelKey[$key]) + $this->rawExcelData->colspan[$num]['num'] - 1) . $i);
                    }

                    /* Wipe off html tags.*/
                    if(isset($this->config->excel->editor[$this->rawExcelData->kind]) and in_array($key, $this->config->excel->editor[$this->rawExcelData->kind])) $value = $this->file->excludeHtml($value);
                    $excelSheet->setCellValueExplicit($this->excelKey[$key] . $i, $value, PHPExcel_Cell_DataType::TYPE_STRING);
                }

                /* Build excel list.*/
                if(isset($this->rawExcelData->listStyle) and in_array($key, $this->rawExcelData->listStyle)) $this->buildList($excelSheet, $key, $i);
            }
        }

        if(isset($this->lang->excel->help->{$this->post->kind})) $excelSheet->setCellValue("A" . $i, $this->lang->excel->help->{$this->post->kind});
        $this->setStyle($excelSheet, $i);

        /* urlencode the filename for ie. */
        $fileName = $this->rawExcelData->fileName;
        if(strpos($this->server->http_user_agent, 'MSIE') !== false || strpos($this->server->http_user_agent, 'Trident') !== false) $fileName = urlencode($fileName);

        $excelWriter = PHPExcel_IOFactory::createWriter($this->phpExcel, 'Excel5');
        $excelWriter->setPreCalculateFormulas(false);
        if($savePath == '')
        {
            setcookie('downloading', 1);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=\"{$fileName}.xls\"");
            header('Cache-Control: max-age=0');

            $excelWriter->save('php://output');
        }
        else
        {
            $excelWriter->save($savePath);
        }
    }

    /**
     * Set excel style.
     *
     * @param  object $excelSheet
     * @param  int    $i
     * @access public
     * @return void
     */
    public function setStyle($excelSheet, $i)
    {
        $endColumn = $this->setExcelField(count($this->excelKey) - 1);
        if(isset($this->lang->excel->help->{$this->rawExcelData->kind}) and isset($this->rawExcelData->extraNum)) $i--;
        /* Freeze column.*/
        if(isset($this->config->excel->freeze->{$this->rawExcelData->kind}))
        {
            $column = $this->excelKey[$this->config->excel->freeze->{$this->rawExcelData->kind}];
            $excelSheet->FreezePane($column . '2');
        }

        $excelSheet->getRowDimension(1)->setRowHeight(20);

        /* Set content style for this table.*/
        $contentStyle = array(
            'font'    => array(
                'size' => 9
            ),
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'     => true
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '808080')
                )
            )
        );
        $this->setAreaStyle($excelSheet, $contentStyle, 'A2:' . $endColumn . $i);

        /* Set header style for this table.*/
        $headerStyle = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '808080')
                )
            ),
            'font' => array(
                'bold'  => true,
                'color' => array('rgb' => 'ffffff')
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'fill' => array(
                 'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array('rgb' => '343399')
            )
        );
        if(isset($this->rawExcelData->nocolor)) 
        {
            $headerStyle['font']['color']['rgb']      = '000000';
            $headerStyle['fill']['startcolor']['rgb'] = 'ffffff';
        }
        $this->setAreaStyle($excelSheet, $headerStyle, 'A1:' . $endColumn . '1');
        $customWidth = isset($this->rawExcelData->customWidth) ? array_keys($this->rawExcelData->customWidth) : array();
        foreach($this->excelKey as $key => $letter)
        {
            $titleWidth = $this->config->excel->width->title;
            $contWidth  = $this->config->excel->width->content;

            if(strpos($key, 'Date') !== false) $excelSheet->getColumnDimension($letter)->setWidth(12);
            if(in_array($key, $this->config->excel->titleFields)) $excelSheet->getColumnDimension($letter)->setWidth($titleWidth);
            if(isset($this->config->excel->editor[$this->rawExcelData->kind]) and in_array($key, $this->config->excel->editor[$this->rawExcelData->kind])) $excelSheet->getColumnDimension($letter)->setWidth($contWidth);
            if(in_array($key, $this->config->excel->centerFields))
            {
                $centerStyle = array(
                    'font'    => array(
                        'size' => 9
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '808080')
                        )
                    )
                );
                $this->setAreaStyle($excelSheet, $centerStyle, $letter . '2:' . $letter . $i);
            }

            if(strpos($key, 'Date') !== false or in_array($key, $this->config->excel->dateField))
            {
                $numberFormat = array(
                    'font'    => array(
                        'size' => 9
                    ),
                    'alignment' => array(
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'wrap'     => true
                    ),
                    'numberformat' => array(
                        'code' => PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '808080')
                        )
                    )
                );
                $this->setAreaStyle($excelSheet, $numberFormat, $letter . '2:' . $letter . $i);
            }
            if(in_array($key, $customWidth)) $excelSheet->getColumnDimension($letter)->setWidth($this->rawExcelData->customWidth[$key]);
        }

        /* Set interlaced color for this table. */
        if(!isset($this->rawExcelData->nocolor))
        {
            for($row = 2; $row <= $i; $row++)
            {
                $excelSheet->getRowDimension($row)->setRowHeight(20);

                $area  = "A$row:$endColumn$row";
                $color = $row % 2 == 0 ? 'FFB2D7EA' : 'FFdee6fb';
                $excelStyle = $excelSheet->getStyle($area);
                $excelFill  = $excelStyle->getFill();
                $excelFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $excelFill->getStartColor()->setARGB($color);
            }
        }
    }

    /**
     * Set excel filed name.
     *
     * @param  int    $count
     * @access public
     * @return string
     */
    public function setExcelField($count)
    {
        $letter = 'A';
        for($i = 1; $i <= $count; $i++) $letter++;
        return $letter;
    }

    /**
     * Write SysData sheet in xls
     *
     * @access public
     * @return void
     */
    public function writeSysData()
    {
        if(!isset($this->rawExcelData->SysDataList)) return;

        if($this->phpExcel->getSheetCount() < 2)
        {
            $this->phpExcel->createSheet();
            $this->phpExcel->getSheet(1)->setTitle($this->lang->excel->title->sysValue);
        }
        $i = 0;
        foreach($this->rawExcelData->SysDataList as $key)
        {
            $colIndex = $this->setExcelField($i);
            $key = $key . 'List';
            if(!isset($this->rawExcelData->$key)) continue;
            foreach($this->rawExcelData->$key as $index => $value) $this->phpExcel->getSheet(1)->setCellValueExplicit("$colIndex" . ($index + 1), $value, PHPExcel_Cell_DataType::TYPE_STRING);
            $i++;
        }
    }

    /**
     * Build dropmenu list.
     * For a tip , if you want to modify that function , search "phpExcel DataValidation namedRange" in stackoverflow.com maybe helpful.
     *
     * @param  int    $excelSheet
     * @param  int    $field
     * @param  int    $row
     * @access public
     * @return void
     */
    public function buildList($excelSheet, $field, $row)
    {
        $listName = $field . 'List';
        $index    = array_search($field, $this->rawExcelData->SysDataList);
        $colIndex = $this->setExcelField($index);
        if(isset($this->rawExcelData->$listName))
        {
            $itemCount = count($this->rawExcelData->$listName);
            if($itemCount == 0) $itemCount = 1;
            $range = "{$this->lang->excel->title->sysValue}!\${$colIndex}\$1:\${$colIndex}\$" . $itemCount;
        }
        else
        {
            $range = is_array($this->rawExcelData->$listName) ? '' : '"' . $this->rawExcelData->$listName . '"';
        }
        $objValidation = $excelSheet->getCell($this->excelKey[$field] . $row)->getDataValidation();
        $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
            ->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
            ->setAllowBlank(false)
            ->setShowErrorMessage(false)
            ->setShowDropDown(true)
            ->setErrorTitle($this->lang->excel->error->title)
            ->setError($this->lang->excel->error->info)
            ->setFormula1($range);
    }
}

class export2Xlsx extends model
{
    /**
     * Data record in sharedStrings.
     *
     * @var int
     * @access public
     */
    public $record          = 0;

    /**
     * Style setting
     *
     * @var array
     * @access public
     */
    public $styleSetting    = array();

    /**
     * rels about link
     *
     * @var string
     * @access public
     */
    public $rels            = '';

    /**
     * sheet1 sheetData
     *
     * @var string
     * @access public
     */
    public $sheet1SheetData = '';

    /**
     * sheet1 params like cols mergeCells ...
     *
     * @var array
     * @access public
     */
    public $sheet1Params = array();

    /**
     * every counts in need count.
     *
     * @var array
     * @access public
     */
    public $counts = array();

    /**
     * init for excel data.
     *
     * @param  int    $data
     * @access public
     * @return void
     */
    public function init($data)
    {
        $this->rawExcelData = $data;
        $this->app->loadClass('pclzip', true);
        $this->zfile  = $this->app->loadClass('zfile');
        $this->file   = $this->loadModel('file');
        $this->fields = $this->rawExcelData->fields;
        $this->rows   = $this->rawExcelData->rows;

        if(!$this->rawExcelData->fileName) $this->rawExcelData->fileName = $this->rawExcelData->kind;

        /* Init excel file. */
        $this->exportPath = $this->app->getCacheRoot() . $this->app->user->account . $this->rawExcelData->kind . $this->rawExcelData->fileName . '/';
        if(is_dir($this->exportPath)) $this->zfile->removeDir($this->exportPath);
        $this->zfile->mkdir($this->exportPath);
        $this->zfile->copyDir($this->app->getCoreLibRoot() . 'phpexcel/xlsx', $this->exportPath);

        $this->sharedStrings = file_get_contents($this->exportPath . 'xl/sharedStrings.xml');

        $this->fieldsKey = array_keys($this->fields);

        $this->sheet1Params['dataValidations'] = '';
        $this->sheet1Params['cols']            = '';
        $this->sheet1Params['mergeCells']      = '';
        $this->sheet1Params['hyperlinks']      = '';

        $this->counts['dataValidations'] = 0;
        $this->counts['mergeCells']      = 0;
        $this->counts['hyperlinks']      = 0;
    }

    /**
     * Export data to Excel. This is main function.
     *
     * @param  object $data 
     * @param  string $savePath , define the path to save xlsx file
     * @access public
     * @return void
     */
    public function export($data, $savePath = '')
    {
        $this->init($data);
        $this->setDocProps();
        $this->excelKey = array();
        for($i = 0; $i < count($this->fieldsKey); $i++)
        {
            $field = $this->fieldsKey[$i];
            $this->excelKey[$field] = $this->setExcelField($i);
            if(in_array($field, $this->config->excel->centerFields)) $this->styleSetting['center'][$this->excelKey[$field]] = 1;
            if((strpos($field, 'Date') !== false) || (in_array($field, $this->config->excel->dateField))) $this->styleSetting['date'][$this->excelKey[$field]] = 1;
        }

        /* Show header data. */
        $this->sheet1SheetData = '<row r="1" spans="1:%colspan%" ht="20" customHeight="1">';
        foreach($this->fields as $key => $field) $this->sheet1SheetData .= $this->setCellValue($this->excelKey[$key], '1', $field, !isset($this->rawExcelData->nocolor));
        $this->sheet1SheetData .= '</row>';

        /* Write system data in excel.*/
        $this->writeSysData();

        $i = 1;
        $excelData = array();
        foreach($this->rows as $num => $row)
        {
            $i++;
            $columnData = array();
            $this->sheet1SheetData .= '<row r="' . $i . '" spans="1:%colspan%" ht="20" customHeight="1">';
            foreach($this->excelKey as $key => $letter)
            {
                $value = isset($row[$key]) ? $row[$key] : '';
                /* Merge Cells.*/
                if(isset($this->rawExcelData->rowspan[$num]) and strpos($this->rawExcelData->rowspan[$num]['rows'], ",$key,") !== false)
                {
                    $this->mergeCells($letter . $i, $letter . ($i + $this->rawExcelData->rowspan[$num]['num'] - 1));
                }
                if(isset($this->rawExcelData->colspan[$num]) and strpos($this->rawExcelData->colspan[$num]['cols'], ",$key,") !== false)
                {
                    $this->mergeCells($letter . $i , chr(ord($letter) + $this->rawExcelData->colspan[$num]['num'] - 1) . $i);
                }

                /* Wipe off html tags. Use to export html message like company->selfDesc via js::kindeditor */
                if(isset($this->config->excel->editor[$this->rawExcelData->kind]) and in_array($key, $this->config->excel->editor[$this->rawExcelData->kind])) $value = $this->file->excludeHtml($value);
                if(isset($value[1])) $value = $value[1] == ':' ? $substr($value, 2) : $value;
                $this->sheet1SheetData .= $this->setCellValue($letter, $i, $value, !isset($this->rawExcelData->nocolor));

                /* Build excel list.*/
                if(isset($this->rawExcelData->listStyle) and in_array($key, $this->rawExcelData->listStyle)) $this->buildList($key, $i);
            }
            $this->sheet1SheetData .= '</row>';
        }

        $this->sheet1Params['colspan'] = count($this->excelKey) - 1;
        /*Add help lang in end.*/
        if(isset($this->lang->excel->help->{$this->post->kind}))
        {
            $this->mergeCells('A' . $i, $this->setExcelFiled($this->sheet1Params['colspan'] - 1) . $i);
            $this->sheet1SheetData .= '<row r="' . $i . '" spans="1:%colspan%">';
            $this->sheet1SheetData .= $this->setCellValue("A", $i, $this->lang->excel->help->{$this->post->kind});
            $this->sheet1SheetData .= '</row>';
        }
        $this->setStyle($i);

        if(!empty($this->sheet1Params['cols'])) $this->sheet1Params['cols'] = '<cols>' . $this->sheet1Params['cols'] . '</cols>';
        if(!empty($this->sheet1Params['mergeCells'])) $this->sheet1Params['mergeCells'] = '<mergeCells count="' . $this->counts['mergeCells'] . '">' . $this->sheet1Params['mergeCells'] . '</mergeCells>';
        if(!empty($this->sheet1Params['dataValidations'])) $this->sheet1Params['dataValidations'] = '<dataValidations count="' . $this->counts['dataValidations'] . '">' . $this->sheet1Params['dataValidations'] . '</dataValidations>';
        if(!empty($this->sheet1Params['hyperlinks'])) $this->sheet1Params['hyperlinks'] = '<hyperlinks>' . $this->sheet1Params['hyperlinks'] . '</hyperlinks>';

        /* Save sheet1*/
        $sheet1 = file_get_contents($this->exportPath . 'xl/worksheets/sheet1.xml');
        $sheet1 = str_replace(array('%area%', '%xSplit%', '%topLeftCell%', '%cols%', '%sheetData%', '%mergeCells%', '%dataValidations%', '%hyperlinks%', '%colspan%'),
            array($this->sheet1Params['area'], $this->sheet1Params['xSplit'], $this->sheet1Params['topLeftCell'], $this->sheet1Params['cols'], $this->sheet1SheetData, $this->sheet1Params['mergeCells'], $this->sheet1Params['dataValidations'], $this->sheet1Params['hyperlinks'], $this->sheet1Params['colspan']), $sheet1);
        file_put_contents($this->exportPath . 'xl/worksheets/sheet1.xml', $sheet1);

        /* Save sharedStrings file. */
        $this->sharedStrings .= '</sst>';
        $this->sharedStrings  = str_replace('%count%', $this->record, $this->sharedStrings);
        file_put_contents($this->exportPath . 'xl/sharedStrings.xml', $this->sharedStrings);

        /* Save link message. */
        if($this->rels)
        {
            $this->rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"? ><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' . $this->rels . '</Relationships>';
            if(!is_dir($this->exportPath . 'xl/worksheets/_rels/')) mkdir($this->exportPath . 'xl/worksheets/_rels/');
            file_put_contents($this->exportPath . 'xl/worksheets/_rels/sheet1.xml.rels', $this->rels);
        }
        
        /* urlencode the filename for ie. */
        $fileName = $this->rawExcelData->fileName . '.xlsx';

        /* Zip to xlsx. */
        helper::cd($this->exportPath);
        $files = array('[Content_Types].xml', '_rels', 'docProps', 'xl');
        $zip   = new pclzip($fileName);
        $zip->create($files);

        $fileData = file_get_contents($this->exportPath . $fileName);
        $this->zfile->removeDir($this->exportPath);
        if($savePath == '') 
        {
            $this->sendDownHeader($fileName, 'xlsx', $fileData);
        }
        else
        {
            file_put_contents($savePath, $fileData);
        }
    }

    /**
     * Set excel style
     *
     * @param  int    $excelSheet
     * @access public
     * @return void
     */
    public function setStyle($i)
    {
        $endColumn = $this->setExcelField(count($this->excelKey) - 1);
        $this->sheet1Params['area'] = "A1:$endColumn$i";
        $letters   = array_values($this->excelKey);

        /* Freeze column.*/
        if(isset($this->config->excel->freeze->{$this->rawExcelData->kind}))
        {
            $column = $this->excelKey[$this->config->excel->freeze->{$this->rawExcelData->kind}];
            $column ++;
            $this->sheet1Params['topLeftCell'] = $column . '2';
            $this->sheet1Params['xSplit'] = array_search($column, $letters);
        }

        /* Set column width */
        $customWidth = isset($this->rawExcelData->customWidth) ? array_keys($this->rawExcelData->customWidth) : array();
        foreach($this->excelKey as $key => $letter)
        {
            $titleWidth = $this->config->excel->width->title;
            $contWidth  = $this->config->excel->width->content;
            $postion    = array_search($letter, $letters) + 1;

            if(strpos($key, 'Date') !== false) $this->setWidth($postion, 12);
            if(in_array($key, $this->config->excel->titleFields)) $this->setWidth($postion, $titleWidth);
            if(isset($this->config->excel->editor[$this->rawExcelData->kind]) and in_array($key, $this->config->excel->editor[$this->rawExcelData->kind])) $this->setWidth($postion, $contWidth);
            if(in_array($key, $customWidth)) $this->setWidth($postion, $this->rawExcelData->customWidth[$key]);
        }
    }

    /**
     * Set excel filed name.
     *
     * @param  int    $count
     * @access public
     * @return void
     */
    public function setExcelField($count)
    {
        $letter = 'A';
        for($i = 1; $i <= $count; $i++) $letter++;
        return $letter;
    }

    /**
     * Write system data to sheet2
     *
     * @access public
     * @return void
     */
    public function writeSysData()
    {
        $count        = 1;
        $sheetData    = '';
        $sysDataCount = 0;
        
        if(isset($this->rawExcelData->SysDataList))
        {
            $sysDataCount = count($this->rawExcelData->SysDataList);
            foreach($this->rawExcelData->SysDataList as $key)
            {
                $key  .= 'List';
                $count = max($count, count($this->rawExcelData->$key));
            }
            for($row = 0; $row < $count; $row++)
            {
                $sheetData .= '<row r="' . (string)($row + 1) . '" spans="1:5">';
                $col = 0;
                foreach($this->rawExcelData->SysDataList as $key)
                {
                    $colIndex   = $this->setExcelField($col);
                    $key       .= 'List';
                    $dropList   = $this->rawExcelData->$key;
                    $sheetData .= $this->setCellValue($colIndex, ($row + 1), isset($dropList[$row]) ? $dropList[$row] : '', false);
                    $col++;
                }
                $sheetData .= '</row>';
            }
        }
        $colIndex = $this->setExcelField($sysDataCount - 1);

        $sheet2 = file_get_contents($this->exportPath . 'xl/worksheets/sheet2.xml');
        $sheet2 = sprintf($sheet2, "A1:$colIndex$count", $sheetData);
        file_put_contents($this->exportPath . 'xl/worksheets/sheet2.xml', $sheet2);
    }

    /**
     * Build drop list.
     *
     * @param  string    $field
     * @param  int       $row
     * @access public
     * @return void
     */
    public function buildList($field, $row)
    {
        $listName = $field . 'List';
        $index    = array_search($field, $this->rawExcelData->SysDataList);
        $colIndex = $this->setExcelField($index);
        if(isset($this->rawExcelData->$listName))
        {
            $itemCount = count($this->rawExcelData->$listName);
            if($itemCount == 0) $itemCount = 1;
            $range = "{$this->lang->excel->title->sysValue}!\${$colIndex}\$1:\${$colIndex}\$" . $itemCount;
        }
        else
        {
            $range = is_array($this->rawExcelData->$listName) ? '' : '"' . $this->rawExcelData->$listName . '"';
        }
        $this->sheet1Params['dataValidations'] .= '<dataValidation type="list" showErrorMessage="1" errorTitle="' . $this->lang->excel->error->title . '" error="' . $this->lang->excel->error->info . '" sqref="' . $this->excelKey[$field] . $row . '">';
        $this->sheet1Params['dataValidations'] .= '<formula1>' . $range . '</formula1></dataValidation>';
        $this->counts['dataValidations']++;
    }

    /**
     * Merge cells
     *
     * @param  string    $start   like A1
     * @param  string    $end     like B1
     * @access public
     * @return void
     */
    public function mergeCells($start, $end)
    {
        $this->sheet1Params['mergeCells'] .= '<mergeCell ref="' . $start . ':' . $end . '"/>';
        $this->counts['mergeCells']++;
    }

    /**
     * Set column width
     *
     * @param  int    $column
     * @param  int    $width
     * @access public
     * @return void
     */
    public function setWidth($column, $width)
    {
        $this->sheet1Params['cols'] .= '<col min="' . $column . '" max="' . $column . '" width="' . $width . '" customWidth="1"/>';
    }

    /**
     * Set cell value
     *
     * @param  string    $key
     * @param  int       $i
     * @param  int       $value
     * @param  bool      $style
     * @access public
     * @return string
     */
    public function setCellValue($key, $i, $value, $style = true)
    {
        /* Set style. The id number in styles.xml. */
        $s = '';
        if($style)
        {
            $s = $i % 2 == 0 ? '2' : '5';
            $s = $i == 1 ? 1 : $s;
            if($s != 1)
            {
                if(isset($this->styleSetting['center'][$key])) $s = $s == 2 ? 3 : 6;
                if(isset($this->styleSetting['date'][$key])) $s = $s <= 3 ? 4 : 7;
            }
            $s = 's="' . $s . '"';
        }

        $cellValue = '';
        if($value)
        {
            $cellValue .= '<c r="' . $key . $i . '" ' . $s . ' t="s"><v>' . $this->record . '</v></c>';
            $this->appendSharedStrings($value);
        }
        else
        {
            $cellValue .= '<c r="' . $key . $i . '" ' . $s . '/>';
        }

        return $cellValue;
    }

    /**
     * Set doc props
     *
     * @access public
     * @return void
     */
    public function setDocProps()
    {
        $sheetTitle = isset($this->lang->excel->title->{$this->rawExcelData->kind}) ? $this->lang->excel->title->{$this->rawExcelData->kind} : $this->rawExcelData->kind;
        $appFile    = file_get_contents($this->exportPath . 'docProps/app.xml');
        $appFile    = sprintf($appFile, $sheetTitle, $this->lang->excel->title->sysValue);
        file_put_contents($this->exportPath . 'docProps/app.xml', $appFile);

        $coreFile   = file_get_contents($this->exportPath . 'docProps/core.xml');
        $createDate = date('Y-m-d') . 'T' . date('H:i:s') . 'Z';
        $coreFile   = sprintf($coreFile, $createDate, $createDate);
        file_put_contents($this->exportPath . 'docProps/core.xml', $coreFile);

        $workbookFile = file_get_contents($this->exportPath . 'xl/workbook.xml');
        $workbookFile = sprintf($workbookFile, $sheetTitle, $this->lang->excel->title->sysValue);
        file_put_contents($this->exportPath . 'xl/workbook.xml', $workbookFile);
    }

    /**
     * Append shared strings
     *
     * @param  string    $value
     * @access public
     * @return void
     */
    public function appendSharedStrings($value)
    {
        $preserve = strpos($value, "\n") === false ? '' : ' xml:space="preserve"';
        $this->sharedStrings .= '<si><t' . $preserve . '>' . htmlspecialchars($value) . '</t></si>';
        $this->record++;
    }

    /**
     * Send the download header to the client.
     *
     * @param  string    $fileName
     * @param  string    $fileType
     * @param  string    $content
     * @access public
     * @return void
     */
    public function sendDownHeader($fileName, $fileType, $content)
    {
        ob_clean();    // clean the ob content to make sure no space or utf-8 bom output.

        /* Set the downloading cookie, thus the export form page can use it to judge whether to close the window or not. */
        setcookie('downloading', 1);

        /* Append the extension name auto. */
        $extension = '.' . $fileType;
        if(strpos($fileName, $extension) === false) $fileName .= $extension;

        /* urlencode the filename for ie. */
        if(strpos($this->server->http_user_agent, 'MSIE') !== false) $fileName = urlencode($fileName);

        /* Judge the content type. */
        $mimes = $this->config->file->mimes;
        $contentType = isset($mimes[$fileType]) ? $mimes[$fileType] : $mimes['default'];

        header("Content-type: $contentType");
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Pragma: no-cache');
        header('Expires: 0');
        die($content);
    }
}
