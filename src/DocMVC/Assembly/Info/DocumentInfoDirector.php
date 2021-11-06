<?php

namespace DocMVC\Assembly\Info;

use DocMVC\Exception\Assembly\DocumentInfoBuilder\DocumentInfoBuilderExceptionInterface;

class DocumentInfoDirector
{
    /**
     * @param DocumentInfoBuilderInterface $documentInfoBuilder
     *
     * @return DocumentInfoBuilderInterface
     * @throws DocumentInfoBuilderExceptionInterface
     */
    public function buildDocumentInfo(DocumentInfoBuilderInterface $documentInfoBuilder): DocumentInfoBuilderInterface
    {
        $documentInfoBuilder->init();
        $documentInfoBuilder->initParams();
        $documentInfoBuilder->initDocumentExt();
        $documentInfoBuilder->initTmpDocumentPath();

        return $documentInfoBuilder;
    }
}