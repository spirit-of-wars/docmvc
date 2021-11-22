<?php

namespace SpiritOfWars\DocMVC\Test\Unit;

use SpiritOfWars\DocMVC\DocumentManager;
use SpiritOfWars\DocMVC\DocumentManager\AssembledDocumentProcessor;
use SpiritOfWars\DocMVC\Test\Data\CartridgeTestFactory;
use PHPUnit\Framework\TestCase;

class DocumentManagerTest extends TestCase
{
    public function testDocumentManager()
    {
        $docCartridge = CartridgeTestFactory::createTestDocCartridge();

        $fileManager = new DocumentManager($docCartridge);

        $documentProcessor = $fileManager->build();

        $this->assertInstanceOf(AssembledDocumentProcessor::class, $documentProcessor);
    }
}