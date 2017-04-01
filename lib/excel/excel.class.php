<?php
/**
 * The excel library of Ranzhi, can be used to export excel file.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com> 
 * @author      yaozeyuan <yaozeyuan@cnezsoft.com>
 * @package     Excel
 * @version     $Id$
 * @link        http://www.ranzhico.com
 *
 * excel - a library to export excel file, depends on phpexcel.
 *
 * Here are some tips of excelData(named $data) structure maybe uesful. you can use these to create xls/xlsx file . The API is interchangeable.
 * 
 * Base property:
 * data->fileName set the fileName
 * data->kind set the kind of excel module
 * data->fields is array like ($field => $fieldTitle). The order of data->fields is also the column's order
 * data->row is array like ($rowNumber => array($field => $value)).This is the data of Excel file. System will fill $value as data into every cell according to $rowNumber and $field 
 *
 * Merge cell
 * if there is set data->rowspan[$num][$field] or data->colspan[$num][$field], the cells will be merge
 *      data->rowspan[$num][$field] => merge excelKey[$field]:$rowNumber to excelKey[$field]:($rowNumber + data->rowspan[$num][$field]) into one cell
 *      data->colspan[$num][$field] => merge excelKey[$field]:$rowNumber to transIntoExcelKey(int(excelKey[$field]) + colspan[$num][$field]):$rowNumber into one cell
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
 * You can set $data->customWidth like array($field => width, $field2 => width, $field3 => width,...) to set width by you like
 * or modify
 *       config->excel->titleFields
 *       config->excel->centerFields
 *       config->excel->dateFields
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
class excel extends model
{
    /**
     * __construct 
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->phpExcel        = $this->app->loadClass('phpexcel');
        $this->file            = $this->loadModel('file');
        $this->sysDataColIndex = 0;
        $this->hasSysData      = false;
    }

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
        $this->rawExcelData = $data;
        $this->fields       = $this->rawExcelData->fields;
        $this->rows         = $this->rawExcelData->rows;
        $this->fieldsKey    = array_keys($this->fields);
    }

    /**
     * Export data to Excel. This is main function.
     * 
     * @param  object $data 
     * @param  string $fileType xls | xlsx
     * @param  string $savePath, if $savePath != '', then the file will save in $savePath
     * @access public
     * @return void
     */
    public function export($excelData, $fileType = 'xls', $savePath = '')
    {
        $index = 0;
        /* Create sheets. */
        for($i = 0; $i < count($excelData->dataList); $i++)
        {
            $this->phpExcel->createSheet();
        }
        foreach($excelData->dataList as $data)
        {
            $this->init($data);
            $this->excelKey = array();
            for($i = 0; $i < count($this->fieldsKey); $i++) $this->excelKey[$this->fieldsKey[$i]] = $this->setExcelField($i);

            /* Set file base property */
            $excelProps = $this->phpExcel->getProperties();
            $excelProps->setCreator('Ranzhi');
            $excelProps->setLastModifiedBy('Ranzhi');
            $excelProps->setTitle('Office XLS Document');
            $excelProps->setSubject('Office XLS Document');
            $excelProps->setDescription('Document generated by PHPExcel.');
            $excelProps->setKeywords('office excel PHPExcel');
            $excelProps->setCategory('Result file');

            $excelSheet = $this->phpExcel->getSheet($index);
            $sheetTitle = isset($this->rawExcelData->title) ? $this->rawExcelData->title : $this->rawExcelData->kind;
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
                        if(isset($this->rawExcelData->rowspan[$num][$key]) && is_int($this->rawExcelData->rowspan[$num][$key]))
                        {
                            $excelSheet->mergeCells($this->excelKey[$key] . $i . ":" . $this->excelKey[$key] . ($i + $this->rawExcelData->rowspan[$num][$key]));
                        }
                        if(isset($this->rawExcelData->colspan[$num][$key]) && is_int($this->rawExcelData->colspan[$num][$key]))
                        {
                            $excelSheet->mergeCells($this->excelKey[$key] . $i . ":" . chr(ord($this->excelKey[$key]) + $this->rawExcelData->colspan[$num][$key] - 1) . $i);
                        }

                        /* Wipe off html tags.*/
                        if(isset($this->config->excel->editor[$this->rawExcelData->kind]) and in_array($key, $this->config->excel->editor[$this->rawExcelData->kind])) $value = $this->file->excludeHtml($value);
                        if(isset($this->rawExcelData->numberFields) && in_array($key, $this->rawExcelData->numberFields))
                        {
                            $excelSheet->setCellValueExplicit($this->excelKey[$key] . $i, $value, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        }
                        else
                        {
                            $excelSheet->setCellValueExplicit($this->excelKey[$key] . $i, $value, PHPExcel_Cell_DataType::TYPE_STRING);
                        }
                        /* Add comments to cell, Excel5 don't work, must be Excel2007. */
                        if(isset($this->rawExcelData->comments[$num][$key])) 
                        {
                            $excelSheet->getComment($this->excelKey[$key] . $i)->getText()->createTextRun($this->rawExcelData->comments[$num][$key]);
                        }
                    }

                    /* Build excel list.*/
                    if(isset($this->rawExcelData->listStyle) and in_array($key, $this->rawExcelData->listStyle)) $this->buildList($excelSheet, $key, $i);
                }
            }

            $this->setStyle($excelSheet, $i);
            $i++;
            if(isset($this->rawExcelData->help)) 
            {
                $excelSheet->mergeCells("A" . $i . ":" . end($this->excelKey) . $i);
                $excelSheet->setCellValue("A" . $i, $this->rawExcelData->help);
            }
            $index++;
        }
        /* If hasn't sys data remove the last sheet. */
        if(!$this->hasSysData) $this->phpExcel->removeSheetByIndex($this->phpExcel->getSheetCount() - 1);
        $this->phpExcel->setActiveSheetIndex(0);

        /* urlencode the filename for ie. */
        $fileName = $excelData->fileName;
        if(strpos($this->server->http_user_agent, 'MSIE') !== false || strpos($this->server->http_user_agent, 'Trident') !== false) $fileName = urlencode($fileName);

        $writer      = $fileType == 'xls' ? 'Excel5' : 'Excel2007';
        $excelWriter = PHPExcel_IOFactory::createWriter($this->phpExcel, $writer);
        $excelWriter->setPreCalculateFormulas(false);
        if($savePath == '')
        {
            setcookie('downloading', 1);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=\"{$fileName}.{$fileType}\"");
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
        if(isset($this->rawExcelData->help) and isset($this->rawExcelData->extraNum)) $i--;
        /* Freeze column.*/
        if(isset($this->config->excel->freeze->{$this->rawExcelData->kind}))
        {
            $column = $this->excelKey[$this->config->excel->freeze->{$this->rawExcelData->kind}];
            $column++;
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

            if(strpos($key, 'Date') !== false or in_array($key, $this->config->excel->dateFields))
            {
                $dateFormat = array(
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
                $this->setAreaStyle($excelSheet, $dateFormat, $letter . '2:' . $letter . $i);
            }
            if(in_array($key, $customWidth)) $excelSheet->getColumnDimension($letter)->setWidth($this->rawExcelData->customWidth[$key]);
        }

        if(isset($this->rawExcelData->colors))
        {
            foreach($this->rawExcelData->colors as $row => $color)
            {
                $beginColumn = $this->excelKey[$color->begin];
                $endColumn   = $this->excelKey[$color->end];
                $excelSheet->getStyle("$beginColumn$row:$endColumn$row")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $excelSheet->getStyle("$beginColumn$row:$endColumn$row")->getFill()->getStartColor()->setRGB($color->color);
            }
        }
        elseif(!isset($this->rawExcelData->nocolor))
        {
            /* Set interlaced color for this table. */
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
    public function writeSysData($dataCount = 0)
    {
        if(!isset($this->rawExcelData->sysDataList)) return;
        $this->hasSysData = true;

        $sheetIndex = $this->phpExcel->getSheetCount() - 1;
        $this->phpExcel->getSheet($sheetIndex)->setTitle($this->lang->excel->title->sysValue);

        foreach($this->rawExcelData->sysDataList as $key)
        {
            $colIndex = $this->setExcelField($this->sysDataColIndex);
            $key = $key . 'List';
            if(!isset($this->rawExcelData->$key)) continue;
            $index = 1;
            foreach($this->rawExcelData->$key as $value) 
            {
                $this->phpExcel->getSheet($sheetIndex)->setCellValueExplicit("$colIndex$index", $value, PHPExcel_Cell_DataType::TYPE_STRING);
                $index++;
            }
            $this->sysDataColIndex++;
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
        $index    = array_search($field, $this->rawExcelData->sysDataList);
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
