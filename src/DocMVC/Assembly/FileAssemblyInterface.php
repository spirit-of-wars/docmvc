<?php

namespace DocMVC\Assembly;

use DocMVC\Exception\Assembly\AssemblyFile\AssemblyFileExceptionInterface;

interface FileAssemblyInterface
{
    /**
     * @throws AssemblyFileExceptionInterface
     */
    public function download(): void;

    /**
     * Build file.
     * Create driver object, render content and save file.
     *
     * @throws AssemblyFileExceptionInterface
     */
    public function buildFile(): void;

    //@todo вот это безобразие тут явно надо выносить куда-то как-то
    public function getTmpFilePath(): string;
    public function getFileName(): string;

}