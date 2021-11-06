<?php

namespace DocMVC\Assembly\Info;

use DocMVC\Exception\Assembly\AssemblyDocumentFactory\AssemblyDocumentCreateException;
use DocMVC\Exception\Assembly\DocumentInfoBuilder\DocumentInfoBuilderExceptionInterface;
use DocMVC\Exception\Assembly\DocumentInfoBuilder\InitExtException;

interface DocumentInfoBuilderInterface
{
    /**
     * Init user params
     */
    public function initParams(): void;

    /**
     * Init document extension
     * Set documentExt, check for allowed in child class method allowedExt
     *
     * @throws InitExtException|AssemblyDocumentCreateException
     */
    public function initDocumentExt(): void;

    /**
     * Init model data, viewPath, templatePath (if exist)
     *
     * @throws DocumentInfoBuilderExceptionInterface
     */
    public function init(): void;

    /**
     * Init temp document path
     */
    public function initTmpDocumentPath(): void;
}