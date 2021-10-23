<?php

namespace DocMVC\FileManager;

use DocMVC\Assembly\FileAssemblyInterface;
use DocMVC\Exception\Assembly\AssemblyFile\AssemblyFileExceptionInterface;
use DocMVC\Exception\FileManager\AssembledFileProcessor\BuildFileException;
use DocMVC\Exception\FileManager\AssembledFileProcessor\CopyFileException;
use DocMVC\Exception\FileManager\AssembledFileProcessor\DownloadFileException;
use DocMVC\Exception\FileManager\AssembledFileProcessor\RemoveFileException;
use DocMVC\Utils\FileHelper;

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

    public function __construct(FileAssemblyInterface $fileAssembly, AssembledFileProcessorConfig $config)
    {
        $this->fileAssembly = $fileAssembly;
        $this->config = $config;

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
            FileHelper::removeFile($this->fileAssembly->getTmpFilePath());
        } catch (\Throwable $e) {
            throw new RemoveFileException($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Copy file to new folder from property saveFilePath
     */
    protected function copyFile($newFilePath): void
    {
        try {
            FileHelper::copyFile($this->fileAssembly->getTmpFilePath(), $newFilePath . $this->fileAssembly->getFileExt());
        } catch (\Throwable $e) {
            throw new CopyFileException($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Download file (generate headers then echo file content)
     */
    public function download(): void
    {
        try {
            $this->fileAssembly->download();
        } catch (AssemblyFileExceptionInterface|\Throwable $e) {
            throw new DownloadFileException($e->getCode(), $e->getMessage(), $e);
        }
        exit();
    }

    public function __destruct()
    {
        if (!$this->config->getIsSaveFile()) {
            $this->removeFile();
        }
    }
}