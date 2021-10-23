<?php

namespace DocMVC\Assembly;

use DocMVC\Assembly\Info\FileInfo;
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
     * Optional user data for use between methods
     *
     * @var array
     */
    protected $chosenParams = array();

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
    public function getFileExt(): string
    {
        return $this->getFileInfo()->getFileExt();
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
            $errMessage .= 'Error message: "' . $err_msg . '" in "' . $err_file . '": ' . $err_line;
            throw new DocMVCException($errMessage);
        });
    }

    /**
     * @return object
     */
    protected abstract function createDriver(): object ;

}
