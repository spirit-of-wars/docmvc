<?php

namespace DocMVC\Assembly;

use DocMVC\Exception\Assembly\AssemblyDocument\AssemblyDocumentExceptionInterface;

interface DocumentAssemblyInterface
{
    /**
     * @throws AssemblyDocumentExceptionInterface
     */
    public function download(): void;

    /**
     * Build document.
     * Create driver object, render content and save document.
     *
     * @throws AssemblyDocumentExceptionInterface
     */
    public function buildDocument(): void;

    //@todo вот это безобразие тут явно надо выносить куда-то как-то
    public function getTmpDocumentPath(): string;
    public function getDocumentName(): string;

}