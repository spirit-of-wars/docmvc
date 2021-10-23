<?php

namespace DocMVC\Assembly;

interface FileAssemblySystemInterface
{
    public static function allowedExt(): array;
    public static function defaultExt(): string;
}