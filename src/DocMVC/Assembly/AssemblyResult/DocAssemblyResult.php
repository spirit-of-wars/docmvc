<?php

namespace DocMVC\Assembly\AssemblyResult;

use DocMVC\Exception\Assembly\AssemblyDocument\DownloadDocumentException;
use DocMVC\Exception\FileOperations\FileOperationException;
use DocMVC\Utils\FileOperations;

class DocAssemblyResult extends AbstractDocumentAssemblyResult
{
    public function download(): void
    {
        $this->DLHeaders();
        $this->DL();
    }

    /**
     * Generate headers for download document
     */
    protected function DLHeaders(): void
    {
        $documentName = $this->documentInfo->getDocumentName();
        header("Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header(sprintf('Content-Disposition: attachment; filename="%s"', $documentName));
    }

    /**
     * Streaming document
     */
    protected function DL(): void
    {
        try {
            FileOperations::downloadStreamFile($this->documentInfo->getTmpDocumentPath());
        } catch (FileOperationException $e) {
            throw new DownloadDocumentException($e->getMessage(), $e->getCode(), $e);
        }
    }
}