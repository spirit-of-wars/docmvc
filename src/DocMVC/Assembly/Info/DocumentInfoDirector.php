<?php

namespace SpiritOfWars\DocMVC\Assembly\Info;

use SpiritOfWars\DocMVC\Exception\Assembly\DocumentInfoBuilder\DocumentInfoBuilderExceptionInterface;

class DocumentInfoDirector
{
    /**
     * @param DocumentInfoBuilderInterface $documentInfoBuilder
     *
     * @return DocumentInfo
     * @throws DocumentInfoBuilderExceptionInterface
     */
    public function buildDocumentInfo(DocumentInfoBuilderInterface $documentInfoBuilder): DocumentInfo
    {
        $documentInfoBuilder->init();
        $documentInfoBuilder->initParams();
        $documentInfoBuilder->initDocumentExt();
        $documentInfoBuilder->initTmpDocumentPath();

        return $documentInfoBuilder->getDocumentInfo();
    }
}