<?php

namespace SpiritOfWars\DocMVC\Test\Assembly\AssemblyResult;

use SpiritOfWars\DocMVC\Assembly\AssemblyResult\DocAssemblyResult;
use SpiritOfWars\DocMVC\Assembly\AssemblyResult\DocumentAssemblyResultInterface;
use SpiritOfWars\DocMVC\Assembly\AssemblyResult\ExcelAssemblyResult;
use SpiritOfWars\DocMVC\Assembly\AssemblyResult\PdfAssemblyResult;
use SpiritOfWars\DocMVC\Assembly\DocumentAssemblyFactory;
use SpiritOfWars\DocMVC\Cartridge\SetupCartridgeInterface;
use SpiritOfWars\DocMVC\Test\Data\CartridgeTestFactory;
use PHPUnit\Framework\TestCase;

class DocumentAssemblyResultTest extends TestCase
{

    public function testDocumentAssemblyResult()
    {
        $docCartridge = CartridgeTestFactory::createTestDocCartridge();
        $excelCartridge = CartridgeTestFactory::createTestExcelCartridge();
        $pdfCartridge = CartridgeTestFactory::createTestPdfCartridge();

        $this->checkDocumentAssemblyResult($docCartridge, DocAssemblyResult::class);
        $this->checkDocumentAssemblyResult($excelCartridge, ExcelAssemblyResult::class);
        $this->checkDocumentAssemblyResult($pdfCartridge, PdfAssemblyResult::class);
    }


    private function checkDocumentAssemblyResult(SetupCartridgeInterface $cartridge, string $documentAssemblyResultClass)
    {
        $documentAssembly = DocumentAssemblyFactory::createAssemblyDocumentByCartridge($cartridge);

        $documentAssemblyResult = $documentAssembly->buildDocument();

        $this->assertInstanceOf(DocumentAssemblyResultInterface::class, $documentAssemblyResult);
        $this->assertInstanceOf($documentAssemblyResultClass, $documentAssemblyResult);

        $this->assertIsString($documentAssemblyResult->getTmpDocumentPath());
        $this->assertIsString($documentAssemblyResult->getDocumentName());
    }
}