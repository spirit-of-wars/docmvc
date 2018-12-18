<?php

namespace DocMVC\Src;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\IOFactory;
//use \PHPExcel_Writer_Excel2007;

abstract class Excel extends BaseDoc
{
    const TYPE_XLS = 'xls';
    const TYPE_XLSX = 'xlsx';

    const Xlsx = 'Xlsx';

    public function __construct(array $params)
    {
        parent::__construct($params);
    }

    protected function buildDoc()
    {
        $this->driver = $this->getObjExcel();
        $this->render();
    }

    protected function allowedExt()
    {
        return [self::TYPE_XLS, self::TYPE_XLSX];
    }

    protected function setFileExt()
    {
        return self::TYPE_XLSX;
    }

    protected function getReader($tmp)
    {
        $reader = IOFactory::createReader(self::Xlsx);

        return $reader->load($tmp);
    }

    protected function getWriter()
    {
        return IOFactory::createWriter($this->driver, self::Xlsx);
    }

    protected function getObjExcel()
    {
        if($tmp = $this->getTemplatePath()) {
            return IOFactory::load($tmp);
        }
        return new Spreadsheet();

    }

    protected function DLHeaders()
    {
        $fileName = $this->generateFileName();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName);
        header('Cache-Control: max-age=0');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
    }
    protected function DL()
    {
        $this->getWriter()->save('php://output');
    }
}