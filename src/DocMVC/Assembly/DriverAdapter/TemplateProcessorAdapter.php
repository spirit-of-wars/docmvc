<?php

namespace DocMVC\Assembly\DriverAdapter;

use PhpOffice\PhpWord\TemplateProcessor;

class TemplateProcessorAdapter extends TemplateProcessor implements SaveDocumentAdapterInterface
{
    public function saveDocument($documentPath): void
    {
        $this->saveAs($documentPath);
    }
}