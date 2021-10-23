<?php

namespace DocMVC\Cartridge;

abstract class AbstractCartridge implements SetupCartridgeInterface
{
    protected $params = [];

    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setupModel()
    {
        return [];
    }

    public function setupFileExt()
    {
        return null;
    }

    public function setupRequiredParams()
    {
        return [];
    }

    public function setupDocName()
    {
        return null;
    }
}