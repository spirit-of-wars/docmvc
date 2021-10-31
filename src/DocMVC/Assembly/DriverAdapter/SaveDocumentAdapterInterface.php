<?php

namespace DocMVC\Assembly\DriverAdapter;

interface SaveDocumentAdapterInterface
{
    public function saveDocument($documentPath): void;
}