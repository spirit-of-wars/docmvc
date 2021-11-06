<?php

namespace DocMVC\Assembly\AssemblyResult;

use DocMVC\Exception\Assembly\AssemblyDocument\AssemblyDocumentExceptionInterface;

interface DocumentAssemblyResultInterface
{
    /**
     * @throws AssemblyDocumentExceptionInterface
     */
    public function download(): void;

    /**
     * Temp document path from FileInfo
     *
     * @return string
     */
    public function getTmpDocumentPath(): string;

    /**
     * Document name from FileInfo
     *
     * @return string
     */
    public function getDocumentName(): string;
}