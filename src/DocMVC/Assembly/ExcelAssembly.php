<?php

namespace DocMVC\Assembly;

use DocMVC\Exception\Assembly\AssemblyDocument\AssemblyDocumentException;
use DocMVC\Exception\Assembly\AssemblyDocument\BuildDocumentException;
use DocMVC\Exception\Assembly\AssemblyDocument\CreateDriverException;
use DocMVC\Exception\Assembly\AssemblyDocument\DownloadDocumentException;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

class ExcelAssembly extends AbstractDocumentAssembly
{
    /**
     * Extension types
     *
     * @const string
     */
    public const TYPE_XLS = 'xls';
    public const TYPE_XLSX = 'xlsx';

    private const Xlsx = 'Xlsx';

    /**
     * Object to work with document
     *
     * @var Spreadsheet
     */
    protected $driver;

    /**
     * Get allowed extensions
     *
     * @return array
     */
    public static function allowedExt(): array
    {
        return [self::TYPE_XLS, self::TYPE_XLSX];
    }

    /**
     * @return string
     */
    public static function defaultExt(): string
    {
        return self::TYPE_XLSX;
    }

    /**
     * Build document.
     * Create driver object and render content.
     *
     * @throws BuildDocumentException
     */
    public function buildDocument(): void
    {
        try {
            $this->documentRenderer->renderFromView($this->getDriver(), $this->getDocumentInfo()->getModel(), $this->getDocumentInfo()->getViewPath(), $this->getDocumentInfo()->getParams());
            $this->getWriter()->save($this->getDocumentInfo()->getTmpDocumentPath());
            $this->initContentFromFile($this->getDocumentInfo()->getTmpDocumentPath());
        } catch (\Throwable $e) {
            throw new BuildDocumentException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function download(): void
    {
        $this->DLHeaders();
        $this->DL();
    }

    /**
     * Create driver object for work with document
     *
     * @return Spreadsheet
     * @throws AssemblyDocumentException
     *
     */
    protected function createDriver(): object
    {
        try {
            if ($tmp = $this->getDocumentInfo()->getTemplatePath()) {
                return IOFactory::load($tmp);
            }

            return new Spreadsheet();
        } catch (\Throwable $e) {
            throw new CreateDriverException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Generate headers for download document
     */
    protected function DLHeaders(): void
    {
        $documentName = $this->getDocumentInfo()->getDocumentName();
        header('Content-Type: application/vnd.ms-excel');
        header(sprintf('Content-Disposition: attachment;filename="%s"', $documentName));
        header('Cache-Control: max-age=0');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
    }

    /**
     * Echo document content
     *
     * @throws DownloadDocumentException
     */
    protected function DL(): void
    {
        echo $this->getContent();
//        try {
//            $this->getWriter()->save('php://output');
//        } catch (\Throwable $e) {
//            throw new DownloadFileException($e->getMessage(), $e->getCode(), $e);
//        }
    }

    /**
     * Get reader object
     *
     * @param string $tmp
     *
     * @return Spreadsheet
     * @throws AssemblyDocumentException
     *
     */
    private function getReader(string $tmp): Spreadsheet
    {
        try {
            $reader = IOFactory::createReader(self::Xlsx);

            return $reader->load($tmp);
        } catch (\Throwable $e) {
            throw new AssemblyDocumentException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get writer object
     *
     * @return \PhpOffice\PhpSpreadsheet\Writer\IWriter
     *@throws AssemblyDocumentException
     *
     */
    private function getWriter(): IWriter
    {
        try {
            return IOFactory::createWriter($this->driver, self::Xlsx);
        } catch (\Throwable $e) {
            throw new AssemblyDocumentException($e->getMessage(), $e->getCode(), $e);
        }
    }
}