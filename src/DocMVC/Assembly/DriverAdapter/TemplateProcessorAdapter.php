<?php

namespace SpiritOfWars\DocMVC\Assembly\DriverAdapter;

use PhpOffice\PhpWord\TemplateProcessor;

class TemplateProcessorAdapter extends TemplateProcessor implements SaveDocumentAdapterInterface
{
    /**
     * @param string $documentPath
     */
    public function saveDocument(string $documentPath): void
    {
        $this->saveAs($documentPath);
    }
}