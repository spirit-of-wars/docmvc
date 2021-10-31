<?php

namespace DocMVC\Assembly;

use DocMVC\Assembly\Info\DocumentInfo;
use DocMVC\Exception\Assembly\AssemblyDocument\BuildDocumentException;
use DocMVC\Exception\Assembly\AssemblyDocument\CreateDriverException;
use DocMVC\Exception\DocMVCException;

abstract class AbstractDocumentAssembly
    implements DocumentAssemblyInterface, DocumentAssemblySystemInterface
{
    /**
     * Object to work with document
     *
     * @var object
     */
    protected $driver;

    /**
     * Document content
     *
     * @var string
     */
    protected $content;

    /**
     * @var DocumentInfo
     */
    protected $documentInfo;

    /**
     * @var DocumentRenderer
     */
    protected $documentRenderer;

    /**
     * Prepare document for next working
     *
     * @param array
     *
     * @throws CreateDriverException
     */
    public function __construct(DocumentInfo $documentInfo)
    {
        $this->setErrorHandler();
        $this->documentInfo = $documentInfo;
        $this->documentRenderer = new DocumentRenderer();

        try {
            $this->driver = $this->createDriver();
        } catch (\Throwable $e) {
            throw new CreateDriverException($e->getMessage(), $e->getCode(), $e);
        }
    }


    /**
     * @return DocumentInfo
     */
    public function getDocumentInfo(): DocumentInfo
    {
        return $this->documentInfo;
    }

    /**
     * Get object to work with document
     *
     * @return object
     */
    public function getDriver(): object
    {
        return $this->driver;
    }

    /**
     * Get document content
     *
     * @return string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Set document content
     *
     * @param string $content
     * @return self
     */
    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getTmpDocumentPath(): string
    {
        return $this->getDocumentInfo()->getTmpDocumentPath();
    }

    /**
     * @return string
     */
    public function getDocumentName(): string
    {
        return $this->getDocumentInfo()->getDocumentName();
    }

    /**
     * @return object
     */
    protected abstract function createDriver(): object ;

    /**
     * @param string $filePath
     *
     * @throws BuildDocumentException
     */
    protected function initContentFromFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new BuildDocumentException(sprintf("Content file is not existed: '%s'", $filePath));
        }
        $fileContent = file_get_contents($filePath);
        if ($fileContent === false) {
            throw new BuildDocumentException(sprintf("Can't get content from file: '%s'", $filePath));
        }

        $this->setContent($fileContent);
    }
    /**
     * Set error handler
     * Stop generate document, if founded warning, notice or error
     *
     * @throws DocMVCException
     */
    private function setErrorHandler() //@todo разобраться нужен ли он здесь, или перенести в DocumentManager
    {
        set_error_handler(function ($err_severity, $err_msg, $err_file, $err_line) {
            $errMessage = 'DocMVC Error! Generate document is aborted. ';
            $errMessage .= sprintf("Error message: '%s' from file '%s' line '%s'", $err_msg, $err_file, $err_line);
            throw new DocMVCException($errMessage);
        });
    }
}
