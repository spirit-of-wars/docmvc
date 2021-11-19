<?php

namespace SpiritOfWars\DocMVC\Assembly\DriverAdapter;

interface SaveDocumentAdapterInterface
{
    /**
     * @param string $documentPath
     */
    public function saveDocument(string $documentPath): void;
}