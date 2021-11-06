<?php

namespace DocMVC\Assembly;

use DocMVC\Assembly\Info\DocumentInfo;
use DocMVC\Exception\Assembly\AssemblyDocument\BuildDocumentException;
use DocMVC\Exception\DocMVCException;

abstract class AbstractDocumentAssembly
    implements DocumentAssemblyInterface, DocumentAssemblySystemInterface
{
    /**
     * @var DocumentInfo
     */
    protected $documentInfo;

    /**
     * @var DocumentRenderer
     */
    protected $documentRenderer;

    /**
     * Prepare document for next working
     *
     * @param array
     */
    public function __construct(DocumentInfo $documentInfo)
    {
        $this->documentInfo = $documentInfo;
        $this->documentRenderer = new DocumentRenderer();
    }

    /**
     * @param object $driver
     * @param DocumentInfo $documentInfo
     *
     * @return false|string|null
     */
    protected function renderFromView(object $driver, DocumentInfo $documentInfo)
    {
        return $this->documentRenderer->renderFromView($driver, $documentInfo->getModel(), $documentInfo->getViewPath(), $documentInfo->getParams());
    }
}
