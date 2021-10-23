<?php

namespace DocMVC\Assembly\DriverAdapter;

interface SaveFileAdapterInterface
{
    public function saveFile($filePath): void;
}