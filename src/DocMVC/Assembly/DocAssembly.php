<?php

namespace DocMVC\Assembly;

use DocMVC\Assembly\DriverAdapter\PHPWordAdapter;
use DocMVC\Assembly\DriverAdapter\SaveDocumentAdapterInterface;
use DocMVC\Assembly\DriverAdapter\TemplateProcessorAdapter;
use DocMVC\Exception\Assembly\AssemblyDocument\BuildDocumentException;
use DocMVC\Exception\Assembly\AssemblyDocument\CreateDriverException;


class DocAssembly extends AbstractDocumentAssembly
{
    /**
     * Extension types
     *
     * @const string
     */
    public const TYPE_DOC = 'doc';
    public const TYPE_DOCX = 'docx';

    /**
     * Object to work with document
     *
     * @var SaveDocumentAdapterInterface
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
     * Build document.
     * Create driver object, render content and save document.
     *
     * @throws BuildDocumentException
     */
    public function buildDocument(): void
    {
        try {
            $this->documentRenderer->renderFromView($this->getDriver(), $this->getDocumentInfo()->getModel(), $this->getDocumentInfo()->getViewPath(), $this->getDocumentInfo()->getParams());
            $this->saveDoc($this->getDocumentInfo()->getTmpDocumentPath());
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
     * @return SaveDocumentAdapterInterface
     *@throws CreateDriverException
     *
     */
    protected function createDriver(): object
    {
        try {
            if (!$this->getDocumentInfo()->getTemplatePath()) {
                return new PHPWordAdapter();
            }

            return new TemplateProcessorAdapter($this->getDocumentInfo()->getTemplatePath());
        } catch (\Throwable $e) {
            throw new CreateDriverException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Save document to path
     *
     * @throws \Throwable
     */
    protected function saveDoc($savePath): void
    {
        $this->driver->saveDocument($savePath);
    }

    /**
     * Generate headers for download document
     */
    protected function DLHeaders(): void
    {
        $documentName = $this->getDocumentInfo()->getDocumentName();
        header("Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header(sprintf('Content-Disposition: attachment; filename="%s"', $documentName));
    }

    /**
     * Echo document content
     */
    protected function DL(): void
    {
        echo $this->getContent();
    }
}