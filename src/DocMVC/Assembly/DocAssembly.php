<?php

namespace SpiritOfWars\DocMVC\Assembly;

use SpiritOfWars\DocMVC\Assembly\AssemblyResult\DocAssemblyResult;
use SpiritOfWars\DocMVC\Assembly\AssemblyResult\DocumentAssemblyResultInterface;
use SpiritOfWars\DocMVC\Assembly\DriverAdapter\PHPWordAdapter;
use SpiritOfWars\DocMVC\Assembly\DriverAdapter\SaveDocumentAdapterInterface;
use SpiritOfWars\DocMVC\Assembly\DriverAdapter\TemplateProcessorAdapter;
use SpiritOfWars\DocMVC\Exception\Assembly\AssemblyDocument\BuildDocumentException;
use SpiritOfWars\DocMVC\Exception\Assembly\AssemblyDocument\CreateDriverException;


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
    public function buildDocument(): DocumentAssemblyResultInterface
    {
        try {
            $driver = $this->createDriver();
            $this->renderFromView($driver, $this->documentInfo);
            $driver->saveDocument($this->documentInfo->getTmpDocumentPath());

            return new DocAssemblyResult($this->documentInfo, $driver);
        } catch (\Throwable $e) {
            throw new BuildDocumentException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Create driver object for work with document
     *
     * @return SaveDocumentAdapterInterface
     * @throws CreateDriverException
     *
     */
    protected function createDriver(): object
    {
        try {
            if (!$this->documentInfo->getTemplatePath()) {
                return new PHPWordAdapter();
            }

            return new TemplateProcessorAdapter($this->documentInfo->getTemplatePath());
        } catch (\Throwable $e) {
            throw new CreateDriverException($e->getMessage(), $e->getCode(), $e);
        }
    }
}