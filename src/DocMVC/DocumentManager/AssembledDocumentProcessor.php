<?php

namespace DocMVC\DocumentManager;

use DocMVC\Assembly\DocumentAssemblyInterface;
use DocMVC\Exception\Assembly\AssemblyDocument\AssemblyDocumentExceptionInterface;
use DocMVC\Exception\DocumentManager\AssembledDocumentProcessor\BuildDocumentException;
use DocMVC\Exception\DocumentManager\AssembledDocumentProcessor\DownloadDocumentException;
use DocMVC\Exception\DocumentManager\AssembledDocumentProcessor\RemoveDocumentException;
use DocMVC\Exception\DocumentManager\AssembledDocumentProcessor\SaveDocumentException;
use DocMVC\Exception\FileOperations\FileOperationsExceptionInterface;
use DocMVC\Utils\FileOperations;
use Psr\Log\LoggerInterface;

class AssembledDocumentProcessor
{
    /**
     * @var DocumentAssemblyInterface
     */
    private $documentAssembly;

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
    public function __construct(DocumentAssemblyInterface $documentAssembly, AssembledDocumentProcessorConfig $config, LoggerInterface $logger)
    {
        $this->documentAssembly = $documentAssembly;
        $this->config = $config;
        $this->logger = $logger;

        try {
            $this->documentAssembly->buildDocument();
        } catch (AssemblyDocumentExceptionInterface $e) {
            throw new BuildDocumentException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Remove document for tmpDocumentPath
     */
    public function removeDocument(): void
    {
        try {
            FileOperations::removeFile($this->documentAssembly->getTmpDocumentPath());
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
            FileOperations::copyFile($this->documentAssembly->getTmpDocumentPath(), $saveDocumentPath, $this->config->getRewritableMode());
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
            $saveDocumentPath = $dirPath . DIRECTORY_SEPARATOR . $this->documentAssembly->getDocumentName();
            FileOperations::copyFile($this->documentAssembly->getTmpDocumentPath(), $saveDocumentPath, $this->config->getRewritableMode());
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
            $this->documentAssembly->download();
        } catch (AssemblyDocumentExceptionInterface|\Throwable $e) {
            $this->logger->error('DocMVC download document error', ['error' => $e->getMessage(), 'exception' => $e]);
            throw new DownloadDocumentException($e->getMessage(), $e->getCode(), $e);
        }
        exit();
    }
}