<?php

namespace DocMVC\Assembly;

use DocMVC\Exception\Assembly\AssemblyFile\AssemblyFileException;
use DocMVC\Exception\Assembly\AssemblyFile\BuildFileException;
use DocMVC\Exception\Assembly\AssemblyFile\CreateDriverException;
use DocMVC\Exception\Assembly\AssemblyFile\DownloadFileException;
use DocMVC\Exception\RenderException;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

class ExcelAssembly extends AbstractFileAssembly
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
     * Object to work with file
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
     * Build file.
     * Create driver object and render content.
     *
     * @throws BuildFileException
     */
    public function buildFile(): void
    {
        try {
            $this->fileRenderer->render($this->getDriver(), $this->getFileInfo()->getModel(), $this->getFileInfo()->getViewPath(), $this->getFileInfo()->getParams());
            $this->getWriter()->save($this->getFileInfo()->getTmpFilePath());
            $this->initContentFromFile($this->getFileInfo()->getTmpFilePath());
        } catch (RenderException $e) {
            throw new BuildFileException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function download(): void
    {
        $this->DLHeaders();
        $this->DL();
    }

    /**
     * Create driver object for work with file
     *
     * @throws AssemblyFileException
     *
     * @return Spreadsheet
     */
    protected function createDriver(): object
    {
        try {
            if ($tmp = $this->getFileInfo()->getTemplatePath()) {
                return IOFactory::load($tmp);
            }

            return new Spreadsheet();
        } catch (\Throwable $e) {
            throw new CreateDriverException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Generate headers for download file
     */
    protected function DLHeaders(): void
    {
        $fileName = $this->getFileInfo()->getFileName();
        header('Content-Type: application/vnd.ms-excel');
        header(sprintf('Content-Disposition: attachment;filename="%s"', $fileName));
        header('Cache-Control: max-age=0');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
    }

    /**
     * Echo file content
     *
     * @throws DownloadFileException
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
     * @param string $tmp The fully qualified template filename
     *
     * @throws AssemblyFileException
     *
     * @return Spreadsheet
     */
    private function getReader($tmp): Spreadsheet
    {
        try {
            $reader = IOFactory::createReader(self::Xlsx);

            return $reader->load($tmp);
        } catch (\Throwable $e) {
            throw new AssemblyFileException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get writer object
     *
     * @throws AssemblyFileException
     *
     * @return \PhpOffice\PhpSpreadsheet\Writer\IWriter
     */
    private function getWriter(): IWriter
    {
        try {
            return IOFactory::createWriter($this->driver, self::Xlsx);
        } catch (\Throwable $e) {
            throw new AssemblyFileException($e->getMessage(), $e->getCode(), $e);
        }
    }
}