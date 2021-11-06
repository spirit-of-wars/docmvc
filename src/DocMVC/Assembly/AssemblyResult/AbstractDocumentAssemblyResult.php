<?php

namespace DocMVC\Assembly\AssemblyResult;

use DocMVC\Assembly\Info\DocumentInfo;

abstract class AbstractDocumentAssemblyResult implements DocumentAssemblyResultInterface
{
    /**
     * Object to work with document
     *
     * @var object
     */
    protected $driver;

    /**
     * @var DocumentInfo
     */
    protected $documentInfo;

    /**
     * @param DocumentInfo $documentInfo
     * @param object $driver
     * @param string $content
     */
    public function __construct(DocumentInfo $documentInfo, ?object $driver = null)
    {
        $this->documentInfo = $documentInfo;
        $this->driver = $driver;
    }

    /**
     * @return string
     */
    public function getTmpDocumentPath(): string
    {
        return $this->documentInfo->getTmpDocumentPath();
    }

    /**
     * @return string
     */
    public function getDocumentName(): string
    {
        return $this->documentInfo->getDocumentName();
    }
}