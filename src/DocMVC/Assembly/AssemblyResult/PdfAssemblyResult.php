<?php
namespace DocMVC\Assembly\AssemblyResult;

use DocMVC\Exception\Assembly\AssemblyDocument\DownloadDocumentException;

class PdfAssemblyResult extends AbstractDocumentAssemblyResult
{
    /**
     * Streams the PDF to the client.
     *
     * @throws DownloadDocumentException
     */
    public function download(): void
    {
        try {
            $this->driver->stream($this->documentInfo->getDocumentName(), [
                'Attachment' => true,
                'compression' => true
            ]);
        } catch (\Throwable $e) {
            throw new DownloadDocumentException($e->getMessage(), $e->getCode(), $e);
        }
    }
}