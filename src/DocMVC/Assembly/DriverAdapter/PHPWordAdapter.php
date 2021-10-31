<?php

namespace DocMVC\Assembly\DriverAdapter;

use PhpOffice\PhpWord\PhpWord;

class PHPWordAdapter extends PhpWord implements SaveDocumentAdapterInterface
{
    public function saveDocument($documentPath): void
    {
        $this->save($documentPath);
    }
}