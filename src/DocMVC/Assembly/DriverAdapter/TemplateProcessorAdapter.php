<?php

namespace DocMVC\Assembly\DriverAdapter;

use PhpOffice\PhpWord\TemplateProcessor;

class TemplateProcessorAdapter extends TemplateProcessor implements SaveFileAdapterInterface
{
    public function saveFile($filePath): void
    {
        $this->saveAs($filePath);
    }
}