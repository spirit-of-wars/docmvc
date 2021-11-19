<?php

namespace SpiritOfWars\DocMVC\Assembly\DriverAdapter;

use PhpOffice\PhpWord\PhpWord;

class PHPWordAdapter extends PhpWord implements SaveDocumentAdapterInterface
{
    /**
     * @param string $documentPath
     */
    public function saveDocument(string $documentPath): void
    {
        $this->save($documentPath);
    }
}