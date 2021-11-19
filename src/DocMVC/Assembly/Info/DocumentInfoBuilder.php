<?php

namespace DocMVC\Assembly\Info;

use DocMVC\Assembly\DocumentAssemblyFactory;
use DocMVC\Assembly\AbstractDocumentAssembly;
use DocMVC\Cartridge\SetupCartridgeInterface;
use DocMVC\Cartridge\SetupTemplateInterface;
use DocMVC\Exception\Assembly\DocumentInfoBuilder\DocumentInfoBuilderExceptionInterface;
use DocMVC\Exception\Assembly\DocumentInfoBuilder\FolderPathException;
use DocMVC\Exception\Assembly\DocumentInfoBuilder\InitExtException;
use DocMVC\Exception\Assembly\DocumentInfoBuilder\InitModelException;
use DocMVC\Exception\Assembly\DocumentInfoBuilder\InitParamsException;
use DocMVC\Exception\Assembly\DocumentInfoBuilder\InitTemplateException;
use DocMVC\Exception\Assembly\DocumentInfoBuilder\InitViewException;
use \DocMVC\Exception\Assembly\AssemblyDocumentFactory\AssemblyDocumentCreateException;

class DocumentInfoBuilder implements DocumentInfoBuilderInterface
{
    private const DEFAULT_VIEW_FOLDER_NAME = 'view';
    private const DEFAULT_TEMPLATE_FOLDER_NAME = 'template';

    /**
     * @var SetupCartridgeInterface
     */
    private $cartridge;

    /**
     * @var DocumentInfo
     */
    private $documentInfo;

    /**
     * @var string
     */
    private $userCartridgeFolderPath;

    /**
     * @param SetupCartridgeInterface $cartridge
     */
    public function __construct(SetupCartridgeInterface $cartridge)
    {
        $this->cartridge = $cartridge;
        $this->documentInfo = new DocumentInfo();
    }

    /**
     * @return DocumentInfo
     */
    public function getDocumentInfo(): DocumentInfo
    {
        return $this->documentInfo;
    }

    /**
     * Init document extension
     * Set documentExt, check for allowed in child class method allowedExt
     *
     * @throws InitExtException|AssemblyDocumentCreateException
     */
    public function initDocumentExt(): void
    {
        /** @var AbstractDocumentAssembly $assemblyClassName */
        $assemblyClassName = DocumentAssemblyFactory::getAssemblyDocumentClassByCartridge($this->cartridge);
        $documentExt = $this->cartridge->setupDocumentExt() ?: $assemblyClassName::defaultExt();
        if (!in_array($documentExt, $assemblyClassName::allowedExt())) {
            throw new InitExtException(sprintf("Document extension '%s' is not allowed", $documentExt));
        }

        $this->documentInfo->setDocumentExt($documentExt);
        $this->documentInfo->setDocumentName($this->generateDocumentName($documentExt));
    }

    /**
     * Init model data, viewPath, templatePath (if exist)
     *
     * @throws DocumentInfoBuilderExceptionInterface
     */
    public function init(): void
    {

        $model = $this->cartridge->setupModel();
        if (!$model) {
            throw new InitModelException('Model data was not founded');
        }

        $this->documentInfo->setModel($model);

        $viewPath = $this->cartridge->setupView() ? $this->getViewFolderPath() . $this->cartridge->setupView() : null;
        if ($viewPath && !file_exists($viewPath)) {
            throw new InitViewException(sprintf("View file was not founded: '%s'", $viewPath));
        }

        $this->documentInfo->setViewPath($viewPath);

        if ($this->cartridge instanceof SetupTemplateInterface) {
            $templatePath = $this->cartridge->setupTemplate() ? $this->getTemplateFolderPath() . $this->cartridge->setupTemplate() : null;
            if ($templatePath && !file_exists($templatePath)) {
                throw new InitTemplateException(sprintf("Template file was not founded: '%s'", $templatePath));
            }

            $this->documentInfo->setTemplatePath($templatePath);
        }

    }

    /**
     * Init temp document path
     */
    public function initTmpDocumentPath(): void
    {
        $tmpDocumentPath = realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . uniqid() . '.' . $this->documentInfo->getDocumentExt();

        $this->documentInfo->setTmpDocumentPath($tmpDocumentPath);
    }

    /**
     * Validate and init user params
     */
    public function initParams(): void
    {
        if ($this->validateParams($this->cartridge->getParams())) {
            $this->documentInfo->setParams($this->cartridge->getParams());
        }
    }

    /**
     * Get full path to class instance view folder
     *
     * @return string
     * @throws FolderPathException
     */
    private function getViewFolderPath(): string
    {
        return $this->getUserCartridgeFolderPath() . DIRECTORY_SEPARATOR .  self::DEFAULT_VIEW_FOLDER_NAME . DIRECTORY_SEPARATOR;
    }

    /**
     * Get full path to class instance template folder
     *
     * @return string
     * @throws FolderPathException
     */
    private function getTemplateFolderPath(): string
    {
        return $this->getUserCartridgeFolderPath() . DIRECTORY_SEPARATOR .  self::DEFAULT_TEMPLATE_FOLDER_NAME . DIRECTORY_SEPARATOR;
    }

    /**
     * Get folder path of class instance
     *
     * @return string
     * @throws FolderPathException
     */
    private function getUserCartridgeFolderPath(): string
    {
        if (!$this->userCartridgeFolderPath) {
            try {
                $class_info = new \ReflectionClass($this->cartridge);
                $fileName = preg_replace('/\\\\/',DIRECTORY_SEPARATOR, $class_info->getFileName());
                $this->userCartridgeFolderPath = dirname($fileName);
            } catch (\Throwable $e) {
                new FolderPathException($e->getMessage(), $e->getCode(), $e);
            }
        }

        return $this->userCartridgeFolderPath;
    }

    /**
     * @param string $documentExt
     *
     * @return string
     */
    private function generateDocumentName(string $documentExt): string
    {
        $docName = $this->cartridge->setupDocumentName() ?: time();

        return $docName . '.' . $documentExt;
    }

    /**
     * Check params to equal in cartridge method setupRequiredParams
     * @param array $params
     *
     * @return bool
     * @throws InitParamsException
     */
    private function validateParams(array $params): bool
    {
        $requiredParams = $this->cartridge->setupRequiredParams();
        foreach ($requiredParams as $paramName) {
            if (!array_key_exists($paramName, $params)) {
                throw new InitParamsException(sprintf("Required params '%s' weren't passed", $paramName));
            }
        }

        return true;
    }
}