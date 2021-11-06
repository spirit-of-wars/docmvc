<?php

namespace DocMVC\Assembly;

use DocMVC\Assembly\AssemblyResult\DocumentAssemblyResultInterface;
use DocMVC\Exception\Assembly\AssemblyDocument\AssemblyDocumentExceptionInterface;

interface DocumentAssemblyInterface
{
    /**
     * Build document.
     * Create driver object, render content and save document.
     *
     * @throws AssemblyDocumentExceptionInterface
     */
    public function buildDocument(): DocumentAssemblyResultInterface;
}