<?php

namespace SpiritOfWars\DocMVC\Assembly;

use SpiritOfWars\DocMVC\Assembly\AssemblyResult\DocumentAssemblyResultInterface;
use SpiritOfWars\DocMVC\Assembly\AssemblyResult\ExcelAssemblyResult;
use SpiritOfWars\DocMVC\Exception\Assembly\AssemblyDocument\AssemblyDocumentException;
use SpiritOfWars\DocMVC\Exception\Assembly\AssemblyDocument\BuildDocumentException;
use SpiritOfWars\DocMVC\Exception\Assembly\AssemblyDocument\CreateDriverException;
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
    public function buildDocument(): DocumentAssemblyResultInterface
    {
        try {
            $driver = $this->createDriver();
            $this->renderFromView($driver, $this->documentInfo);
            $this->getWriter($driver)->save($this->documentInfo->getTmpDocumentPath());

            return new ExcelAssemblyResult($this->documentInfo, $driver);
        } catch (\Throwable $e) {
            throw new BuildDocumentException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Create driver object for work with document
     *
     * @return Spreadsheet
     * @throws AssemblyDocumentException
     */
    protected function createDriver(): object
    {
        try {
            if ($tmp = $this->documentInfo->getTemplatePath()) {
                return IOFactory::load($tmp);
            }

            return new Spreadsheet();
        } catch (\Throwable $e) {
            throw new CreateDriverException($e->getMessage(), $e->getCode(), $e);
        }
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
     * @param Spreadsheet $driver
     *
     * @return \PhpOffice\PhpSpreadsheet\Writer\IWriter
     * @throws AssemblyDocumentException
     *
     */
    private function getWriter(Spreadsheet $driver): IWriter
    {
        try {
            return IOFactory::createWriter($driver, self::Xlsx);
        } catch (\Throwable $e) {
            throw new AssemblyDocumentException($e->getMessage(), $e->getCode(), $e);
        }
    }
}