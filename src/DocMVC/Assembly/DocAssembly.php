<?php

namespace DocMVC\Assembly;

use DocMVC\Assembly\DriverAdapter\PHPWordAdapter;
use DocMVC\Assembly\DriverAdapter\SaveFileAdapterInterface;
use DocMVC\Assembly\DriverAdapter\TemplateProcessorAdapter;
use DocMVC\Exception\Assembly\AssemblyFile\BuildFileException;
use DocMVC\Exception\Assembly\AssemblyFile\CreateDriverException;


class DocAssembly extends AbstractFileAssembly
{
    /**
     * Extension types
     *
     * @const string
     */
    public const TYPE_DOC = 'doc';
    public const TYPE_DOCX = 'docx';

    /**
     * Object to work with file
     *
     * @var SaveFileAdapterInterface
     */
    protected $driver;

    /**
     * Get allowed extensions
     *
     * @return array
     */
    public static function allowedExt(): array
    {
        return [self::TYPE_DOC, self::TYPE_DOCX];
    }

    /**
     * @return string
     */
    public static function defaultExt(): string
    {
        return self::TYPE_DOCX;
    }

    /**
     * Build file.
     * Create driver object, render content and save file.
     *
     * @throws BuildFileException
     */
    public function buildFile(): void
    {
        try {
            $this->fileRenderer->render($this->getDriver(), $this->getFileInfo()->getModel(), $this->getFileInfo()->getViewPath(), $this->getFileInfo()->getParams());
            $this->saveDoc($this->getFileInfo()->getTmpFilePath());
            $this->initContentFromFile($this->getFileInfo()->getTmpFilePath());
        } catch (\Throwable $e) {
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
     * @throws CreateDriverException
     *
     * @return SaveFileAdapterInterface
     */
    protected function createDriver(): object
    {
        try {
            if (!$this->getFileInfo()->getTemplatePath()) {
                return new PHPWordAdapter();
            }

            return new TemplateProcessorAdapter($this->getFileInfo()->getTemplatePath());
        } catch (\Throwable $e) {
            throw new CreateDriverException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Save file to path
     *
     * @throws \Throwable
     */
    protected function saveDoc($savePath): void
    {
        $this->driver->saveFile($savePath);
    }

    /**
     * Generate headers for download file
     */
    protected function DLHeaders(): void
    {
        $fileName = $this->getFileInfo()->getFileName();
        header("Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header(sprintf('Content-Disposition: attachment; filename="%s"', $fileName));
    }

    /**
     * Echo file content
     */
    protected function DL(): void
    {
        echo $this->getContent();
    }
}