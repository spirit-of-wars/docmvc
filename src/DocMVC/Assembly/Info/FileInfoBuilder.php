<?php

namespace DocMVC\Assembly\Info;

use DocMVC\Assembly\AssemblyFileFactory;
use DocMVC\Assembly\AbstractFileAssembly;
use DocMVC\Cartridge\SetupCartridgeInterface;
use DocMVC\Cartridge\SetupTemplateInterface;
use DocMVC\Exception\Assembly\FileInfoBuilder\FileInfoBuilderExceptionInterface;
use DocMVC\Exception\Assembly\FileInfoBuilder\FolderPathException;
use DocMVC\Exception\Assembly\FileInfoBuilder\InitExtException;
use DocMVC\Exception\Assembly\FileInfoBuilder\InitModelException;
use DocMVC\Exception\Assembly\FileInfoBuilder\InitParamsException;
use DocMVC\Exception\Assembly\FileInfoBuilder\InitTemplateException;
use DocMVC\Exception\Assembly\FileInfoBuilder\InitViewException;
use \DocMVC\Exception\Assembly\AssemblyFileFactory\AssemblyCreateException;

class FileInfoBuilder implements FileInfoBuilderInterface
{
    /**
     * @var SetupCartridgeInterface
     */
    private $cartridge;

    /**
     * @var FileInfo
     */
    private $fileInfo;

    /**
     * @param SetupCartridgeInterface $cartridge
     */
    public function __construct(SetupCartridgeInterface $cartridge)
    {
        $this->cartridge = $cartridge;
        $this->fileInfo = new FileInfo();
    }

    /**
     * @return FileInfo
     */
    public function getFileInfo(): FileInfo
    {
        return $this->fileInfo;
    }

    /**
     * Init file extension
     * Set fileExt, check for allowed in child class method allowedExt
     *
     * @throws InitExtException|AssemblyCreateException
     */
    public function initFileExt(): void
    {
        /** @var AbstractFileAssembly $assemblyClassName */
        $assemblyClassName = AssemblyFileFactory::getAssemblyFileClassByCartridge($this->cartridge);
        $fileExt = $this->cartridge->setupFileExt() ?: $assemblyClassName::defaultExt();
        if (!in_array($fileExt, $assemblyClassName::allowedExt())) {
            throw new InitExtException('File Extension ' . $fileExt . 'is not allowed');
        }

        $this->fileInfo->setFileExt($fileExt);
        $this->fileInfo->setFileName($this->generateFileName($fileExt));
    }

    /**
     * Init model data, viewPath, templatePath (if exist)
     *
     * @throws FileInfoBuilderExceptionInterface
     */
    public function init(): void
    {

        $model = $this->cartridge->setupModel();
        if (!$model) {
            throw new InitModelException('Model data is not found');
        }

        $this->fileInfo->setModel($model);

        $viewPath = $this->cartridge->setupView() ? $this->getViewFolderPath() . $this->cartridge->setupView() : null;
        if ($viewPath && !file_exists($viewPath)) {
            throw new InitViewException('View file is not found: ' . $viewPath);
        }

        $this->fileInfo->setViewPath($viewPath);

        if ($this->cartridge instanceof SetupTemplateInterface) {
            $templatePath = $this->cartridge->setupTemplate() ? $this->getTemplateFolderPath() . $this->cartridge->setupTemplate() : null;
            if ($templatePath && !file_exists($templatePath)) {
                throw new InitTemplateException('Template file is not found: ' . $templatePath);
            }

            $this->fileInfo->setTemplatePath($templatePath);
        }

    }

    public function initTmpFilePath(): void
    {
        $tmpFilePath = $this->getFileFolderPath() . uniqid() . '.' . $this->fileInfo->getFileExt();

        $this->fileInfo->setTmpFilePath($tmpFilePath);
    }

    public function initParams(): void
    {
        if ($this->validateParams($this->cartridge->getParams())) {
            $this->params = $this->cartridge->getParams();
        }
    }

    /**
     * Get full path to class instance view folder
     *
     * @throws FolderPathException
     *
     * @return string
     */
    private function getViewFolderPath(): string
    {
        return $this->getFileFolderPath() . '/view/';
    }

    /**
     * Get full path to class instance template folder
     *
     * @throws FolderPathException
     *
     * @return string
     */
    private function getTemplateFolderPath(): string
    {
        return $this->getFileFolderPath() . '/template/';
    }

    /**
     * Get folder path of class instance
     *
     * @throws FolderPathException
     *
     * @return string
     */
    private function getFileFolderPath(): string
    {
        try {
            $class_info = new \ReflectionClass($this->cartridge);
            $fileName = preg_replace('/\\\\/','/',$class_info->getFileName());
            $fileNameArr = explode('/',$fileName);
            array_pop($fileNameArr);
            $currPath = implode('/',$fileNameArr);
        } catch (\Throwable $e) {
            new FolderPathException($e->getMessage(), $e->getCode());
        }

        return $currPath;
    }

    /**
     * @param $fileExt
     * @return string
     */
    private function generateFileName($fileExt): string
    {
        $docName = $this->cartridge->setupDocName() ?: time();

        return $docName . '.' . $fileExt;
    }

    /**
     * Init params
     * Check params to equal in cartridge method setupRequiredParams
     * @return bool
     *
     * @throws InitParamsException
     */
    private function validateParams($params): bool
    {
        $requiredParams = $this->cartridge->setupRequiredParams();
        foreach ($requiredParams as $paramName) {
            if (!array_key_exists($paramName, $params)) {
                throw new InitParamsException('Required params "' . $paramName . '" is not set');
            }
        }

        return true;
    }
}