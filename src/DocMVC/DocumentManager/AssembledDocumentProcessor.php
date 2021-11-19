<?php

namespace DocMVC\DocumentManager;

use DocMVC\Assembly\AssemblyResult\DocumentAssemblyResultInterface;
use DocMVC\Assembly\DocumentAssemblyInterface;
use DocMVC\Exception\DocMVCException;
use DocMVC\Exception\DocumentManager\AssembledDocumentProcessor\DownloadDocumentException;
use DocMVC\Exception\DocumentManager\AssembledDocumentProcessor\RemoveDocumentException;
use DocMVC\Exception\DocumentManager\AssembledDocumentProcessor\SaveDocumentException;
use DocMVC\Exception\FileOperations\FileOperationsExceptionInterface;
use DocMVC\Utils\FileOperations;
use Psr\Log\LoggerInterface;

class AssembledDocumentProcessor
{
    /**
     * @var DocumentAssemblyResultInterface
     */
    private $documentAssemblyResult;

    /**
     * @var AssembledDocumentProcessorConfig
     */
    private $config;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param DocumentAssemblyInterface $documentAssembly
     * @param AssembledDocumentProcessorConfig $config
     * @param LoggerInterface $logger
     */
    public function __construct(DocumentAssemblyResultInterface $documentAssemblyResult, AssembledDocumentProcessorConfig $config, LoggerInterface $logger)
    {
        $this->documentAssemblyResult = $documentAssemblyResult;
        $this->config = $config;
        $this->logger = $logger;

        $this->setErrorHandler();
    }

    /**
     * Remove file from filePath
     * @param string $filePath
     */
    public function remove(string $filePath): void
    {
        try {
            FileOperations::removeFile($filePath);
        } catch (FileOperationsExceptionInterface $e) {
            $this->logger->error('DocMVC remove document error', ['error' => $e->getMessage(), 'exception' => $e]);
            throw new RemoveDocumentException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Save document to new folder from property saveDocumentPath
     */
    public function saveAs(string $saveDocumentPath): self
    {
        try {
            FileOperations::copyFile($this->documentAssemblyResult->getTmpDocumentPath(), $saveDocumentPath, $this->config->getRewritableMode());
        } catch (FileOperationsExceptionInterface $e) {
            $this->logger->error('DocMVC save document error', ['error' => $e->getMessage(), 'exception' => $e]);
            throw new SaveDocumentException($e->getMessage(), $e->getCode(), $e);
        }

        return $this;
    }

    /**
     * @param string $dir
     */
    public function saveToDir(string $dir): self
    {
        try {
            $dirPath = rtrim($dir, '/');
            $saveDocumentPath = $dirPath . DIRECTORY_SEPARATOR . $this->documentAssemblyResult->getDocumentName();
            FileOperations::copyFile($this->documentAssemblyResult->getTmpDocumentPath(), $saveDocumentPath, $this->config->getRewritableMode());
        } catch (FileOperationsExceptionInterface $e) {
            $this->logger->error('DocMVC save document error', ['error' => $e->getMessage(), 'exception' => $e]);
            throw new SaveDocumentException($e->getMessage(), $e->getCode(), $e);
        }

        return $this;
    }

    /**
     * Download document (generate headers then echo document content)
     */
    public function download(): self
    {
        try {
            $this->documentAssemblyResult->download();
        } catch (\Throwable $e) {
            $this->logger->error('DocMVC download document error', ['error' => $e->getMessage(), 'exception' => $e]);
            throw new DownloadDocumentException($e->getMessage(), $e->getCode(), $e);
        }
        exit();
    }

    /**
     * Set error handler
     * Stop generate document, if founded warning, notice or error
     *
     * @throws DocMVCException
     */
    private function setErrorHandler()
    {
        set_error_handler(function ($err_severity, $err_msg, $err_file, $err_line) {
            $errMessage = 'DocMVC Error! Generate document has been aborted. ';
            $errMessage .= sprintf("Error message: '%s' from file '%s' line '%s'", $err_msg, $err_file, $err_line);

            throw new DocMVCException($errMessage);
        });
    }
}