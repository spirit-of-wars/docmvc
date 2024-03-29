<?php

namespace SpiritOfWars\DocMVC\Cartridge;

abstract class AbstractCartridge implements SetupCartridgeInterface
{
    protected $params = [];

    /**
     * Optional user data for use between methods
     *
     * @var array
     */
    protected $commonData = array();

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

    public function setupDocumentExt()
    {
        return null;
    }

    public function setupRequiredParams()
    {
        return [];
    }

    public function setupDocumentName()
    {
        return null;
    }
}