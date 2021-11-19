<?php

namespace sample\test;

use SpiritOfWars\DocMVC\Cartridge\DocCartridge;

class Doc extends DocCartridge
{
    public function setupView()
    {
        return 'doc/view.php';
    }

    public function setupModel()
    {
        $test = $this->getParams()['test']; // required param
        $randParam = $this->getParams();
        return [
            'test' => $test,
            'randParam' => $randParam
        ];
    }

    public function setupRequiredParams()
    {
        return ['test'];
    }

    public function setupDocumentName()
    {
        return 'test-name';
    }
}