<?php

namespace DocMVC\Test;

use DocMVC\DocumentManager\AssembledDocumentProcessor;
use DocMVC\Test\Data\CartridgeTestFactory;
use PHPUnit\Framework\TestCase;

class DocumentManagerTest extends TestCase
{
    public function testDocumentManager()
    {
        $docCartridge = CartridgeTestFactory::createTestDocCartridge();

        $fileManager = new \DocMVC\DocumentManager($docCartridge);

        $documentProcessor = $fileManager->build();

        $this->assertInstanceOf(AssembledDocumentProcessor::class, $documentProcessor);
    }
}