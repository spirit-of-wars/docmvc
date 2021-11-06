<?php

namespace DocMVC\Assembly;

interface DocumentAssemblySystemInterface
{
    /**
     * Define allowed extensions for current document
     *
     * @return array
     */
    public static function allowedExt(): array;

    /**
     * Define default extension for current document (if not set setupExt in cartridge class)
     *
     * @return string
     */
    public static function defaultExt(): string;
}