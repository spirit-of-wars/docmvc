<?php

namespace DocMVC\Assembly;

interface DocumentAssemblySystemInterface
{
    public static function allowedExt(): array;
    public static function defaultExt(): string;
}