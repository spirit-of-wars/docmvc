<?php

namespace DocMVC\Src;

use \Exception;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\IOFactory;

abstract class Excel extends BaseDoc
{
    /**
     * Extension types
     *
     * @const string
     */
    const TYPE_XLS = 'xls';
    const TYPE_XLSX = 'xlsx';

    const Xlsx = 'Xlsx';

    /**
     * Object to work with file
     *
     * @var Spreadsheet
     */
    protected $driver;

    /**
     * Build file.
     * Create driver object and render content.
     *
     * @throws Exception
     */
    protected function buildDoc()
    {
        $this->driver = $this->createDriver();
        $this->render();
    }

    /**
     * Get allowed extensions
     *
     * @return array
     */
    protected function allowedExt()
    {
        return [self::TYPE_XLS, self::TYPE_XLSX];
    }

    /**
     * Implement abstract method from parent class.
     * Specify the file extension.
     * Can be redefined in child class.
     * Must match one of the values in method allowedExt.
     *
     * @return string
     */
    protected function setupFileExt()
    {
        return self::TYPE_XLSX;
    }

    /**
     * Get reader object
     *
     * @param string $tmp The fully qualified template filename
     *
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws Exception
     *
     * @return Spreadsheet
     */
    protected function getReader($tmp)
    {
        $reader = IOFactory::createReader(self::Xlsx);

        return $reader->load($tmp);
    }

    /**
     * Get writer object
     *
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws Exception
     *
     * @return \PhpOffice\PhpSpreadsheet\Writer\IWriter
     */
    protected function getWriter()
    {
        return IOFactory::createWriter($this->driver, self::Xlsx);
    }

    /**
     * Create driver object for work with file
     *
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     *
     * @return Spreadsheet
     */
    protected function createDriver()
    {
        if($tmp = $this->getTemplatePath()) {
            return IOFactory::load($tmp);
        }
        return new Spreadsheet();
    }

    /**
     * Generate headers for download file
     */
    protected function DLHeaders()
    {
        $fileName = $this->generateFileName();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName);
        header('Cache-Control: max-age=0');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
    }

    /**
     * Echo file content
     *
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    protected function DL()
    {
        $this->getWriter()->save('php://output');
    }
}