<?php

namespace SpiritOfWars\DocMVC\Assembly;

use SpiritOfWars\DocMVC\Assembly\AssemblyResult\DocumentAssemblyResultInterface;
use SpiritOfWars\DocMVC\Exception\Assembly\AssemblyDocument\AssemblyDocumentExceptionInterface;

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