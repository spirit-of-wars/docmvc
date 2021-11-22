<?php

namespace SpiritOfWars\DocMVC\Cartridge;

abstract class DocCartridge extends AbstractCartridge implements SetupTemplateInterface
{
    public function setupTemplate()
    {
        return null;
    }
}