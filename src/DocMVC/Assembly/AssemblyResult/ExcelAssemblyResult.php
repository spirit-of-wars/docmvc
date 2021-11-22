<?php

namespace SpiritOfWars\DocMVC\Assembly\AssemblyResult;

use SpiritOfWars\DocMVC\Exception\Assembly\AssemblyDocument\DownloadDocumentException;
use SpiritOfWars\DocMVC\Exception\FileOperations\FileOperationException;
use SpiritOfWars\DocMVC\Utils\FileOperations;

class ExcelAssemblyResult extends AbstractDocumentAssemblyResult
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
        header('Content-Type: application/vnd.ms-excel');
        header(sprintf('Content-Disposition: attachment;filename="%s"', $documentName));
        header('Cache-Control: max-age=0');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
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