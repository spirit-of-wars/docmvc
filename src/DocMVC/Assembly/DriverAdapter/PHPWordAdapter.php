<?php

namespace DocMVC\Assembly\DriverAdapter;

use PhpOffice\PhpWord\PhpWord;

class PHPWordAdapter extends PhpWord implements SaveFileAdapterInterface
{
    public function saveFile($filePath): void
    {
        $this->save($filePath);
    }
}