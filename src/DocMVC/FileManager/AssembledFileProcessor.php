<?php

namespace DocMVC\FileManager;

use DocMVC\Assembly\FileAssemblyInterface;
use DocMVC\Exception\Assembly\AssemblyFile\AssemblyFileExceptionInterface;
use DocMVC\Exception\FileManager\AssembledFileProcessor\BuildFileException;
use DocMVC\Exception\FileManager\AssembledFileProcessor\DownloadFileException;
use DocMVC\Exception\FileManager\AssembledFileProcessor\RemoveFileException;
use DocMVC\Exception\FileManager\AssembledFileProcessor\SaveFileException;
use DocMVC\Exception\FileOperations\FileOperationsExceptionInterface;
use DocMVC\Utils\FileOperations;
use Psr\Log\LoggerInterface;

class AssembledFileProcessor
{
    /**
     * @var FileAssemblyInterface
     */
    private $fileAssembly;

    /**
     * @var AssembledFileProcessorConfig
     */
    private $config;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param FileAssemblyInterface $fileAssembly
     * @param AssembledFileProcessorConfig $config
     * @param LoggerInterface $logger
     */
    public function __construct(FileAssemblyInterface $fileAssembly, AssembledFileProcessorConfig $config, LoggerInterface $logger)
    {
        $this->fileAssembly = $fileAssembly;
        $this->config = $config;
        $this->logger = $logger;

        try {
            $this->fileAssembly->buildFile();
        } catch (AssemblyFileExceptionInterface $e) {
            $this->removeFile();
            throw new BuildFileException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Remove file for tmpFilePath
     */
    public function removeFile(): void
    {
        try {
            FileOperations::removeFile($this->fileAssembly->getTmpFilePath());
        } catch (FileOperationsExceptionInterface $e) {
            $this->logger->error('DocMVC remove file error', ['error' => $e->getMessage(), 'exception' => $e]);
            throw new RemoveFileException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Save file to new folder from property saveFilePath
     */
    public function saveAs(string $saveFilePath): self
    {
        try {
            FileOperations::copyFile($this->fileAssembly->getTmpFilePath(), $saveFilePath, $this->config->getRewritableMode());
        } catch (FileOperationsExceptionInterface $e) {
            $this->logger->error('DocMVC save file error', ['error' => $e->getMessage(), 'exception' => $e]);
            throw new SaveFileException($e->getMessage(), $e->getCode(), $e);
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
            $saveFilePath = $dirPath . DIRECTORY_SEPARATOR . $this->fileAssembly->getFileName();
            FileOperations::copyFile($this->fileAssembly->getTmpFilePath(), $saveFilePath, $this->config->getRewritableMode());
        } catch (FileOperationsExceptionInterface $e) {
            $this->logger->error('DocMVC save file error', ['error' => $e->getMessage(), 'exception' => $e]);
            throw new SaveFileException($e->getMessage(), $e->getCode(), $e);
        }

        return $this;
    }

    /**
     * Download file (generate headers then echo file content)
     */
    public function download(): self
    {
        try {
            $this->fileAssembly->download();
        } catch (AssemblyFileExceptionInterface|\Throwable $e) {
            $this->logger->error('DocMVC download file error', ['error' => $e->getMessage(), 'exception' => $e]);
            throw new DownloadFileException($e->getMessage(), $e->getCode(), $e);
        }
        exit();
    }
}