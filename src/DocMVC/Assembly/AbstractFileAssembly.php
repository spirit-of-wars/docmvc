<?php

namespace DocMVC\Assembly;

use DocMVC\Assembly\Info\FileInfo;
use DocMVC\Exception\Assembly\AssemblyFile\BuildFileException;
use DocMVC\Exception\Assembly\AssemblyFile\CreateDriverException;
use DocMVC\Exception\DocMVCException;

abstract class AbstractFileAssembly
    implements FileAssemblyInterface, FileAssemblySystemInterface
{
    /**
     * Object to work with file
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
     * @var FileInfo
     */
    protected $fileInfo;

    /**
     * @var FileRenderer
     */
    protected $fileRenderer;

    /**
     * Prepare file for next working
     *
     * @param array
     *
     * @throws CreateDriverException
     */
    public function __construct(FileInfo $fileInfo)
    {
        $this->setErrorHandler();
        $this->fileInfo = $fileInfo;
        $this->fileRenderer = new FileRenderer();

        try {
            $this->driver = $this->createDriver();
        } catch (\Throwable $e) {
            throw new CreateDriverException($e->getMessage(), $e->getCode(), $e);
        }
    }


    /**
     * @return FileInfo
     */
    public function getFileInfo(): FileInfo
    {
        return $this->fileInfo;
    }

    /**
     * Get object to work with file
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
    public function getTmpFilePath(): string
    {
        return $this->getFileInfo()->getTmpFilePath();
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->getFileInfo()->getFileName();
    }

    /**
     * @return object
     */
    protected abstract function createDriver(): object ;

    /**
     * @param string $filePath
     *
     * @throws BuildFileException
     */
    protected function initContentFromFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new BuildFileException(sprintf("Content file is not existed: '%s'", $filePath));
        }
        $fileContent = file_get_contents($filePath);
        if ($fileContent === false) {
            throw new BuildFileException(sprintf("Can't get content from file: '%s'", $filePath));
        }

        $this->setContent($fileContent);
    }
    /**
     * Set error handler
     * Stop generate file, if founded warning, notice or error
     *
     * @throws DocMVCException
     */
    private function setErrorHandler() //@todo разобраться нужен ли он здесь, или перенести в FileManager
    {
        set_error_handler(function ($err_severity, $err_msg, $err_file, $err_line) {
            $errMessage = 'DocMVC Error! Generate document is aborted. ';
            $errMessage .= sprintf("Error message: '%s' from file '%s' line '%s'", $err_msg, $err_file, $err_line);
            throw new DocMVCException($errMessage);
        });
    }
}
