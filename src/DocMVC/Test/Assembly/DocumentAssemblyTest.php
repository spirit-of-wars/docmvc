<?php

namespace SpiritOfWars\DocMVC\Test\Assembly;

use SpiritOfWars\DocMVC\Assembly\AssemblyResult\DocAssemblyResult;
use SpiritOfWars\DocMVC\Assembly\AssemblyResult\DocumentAssemblyResultInterface;
use SpiritOfWars\DocMVC\Assembly\AssemblyResult\ExcelAssemblyResult;
use SpiritOfWars\DocMVC\Assembly\AssemblyResult\PdfAssemblyResult;
use SpiritOfWars\DocMVC\Assembly\DocAssembly;
use SpiritOfWars\DocMVC\Assembly\DocumentAssemblyFactory;
use SpiritOfWars\DocMVC\Assembly\ExcelAssembly;
use SpiritOfWars\DocMVC\Assembly\Info\DocumentInfo;
use SpiritOfWars\DocMVC\Assembly\PdfAssembly;
use SpiritOfWars\DocMVC\Cartridge\SetupCartridgeInterface;
use SpiritOfWars\DocMVC\Exception\Assembly\AssemblyDocument\BuildDocumentException;
use SpiritOfWars\DocMVC\Exception\Assembly\AssemblyDocument\CreateDriverException;
use SpiritOfWars\DocMVC\Test\Data\AccessibleReflectionTrait;
use SpiritOfWars\DocMVC\Test\Data\CartridgeTestFactory;
use PHPUnit\Framework\TestCase;

class DocumentAssemblyTest extends TestCase
{
    use AccessibleReflectionTrait;

    public function testDocAssembly()
    {
        $docCartridge = CartridgeTestFactory::createTestDocCartridge();
        $excelCartridge = CartridgeTestFactory::createTestExcelCartridge();
        $pdfCartridge = CartridgeTestFactory::createTestPdfCartridge();

        $this->checkDocumentAssembly($docCartridge, DocAssemblyResult::class, DocAssembly::allowedExt());
        $this->checkDocumentAssembly($excelCartridge, ExcelAssemblyResult::class, ExcelAssembly::allowedExt());
        $this->checkDocumentAssembly($pdfCartridge, PdfAssemblyResult::class, PdfAssembly::allowedExt());
    }

    public function testBuildDocumentException()
    {
        $this->expectException(BuildDocumentException::class);

        $documentInfoMock = $this->createMock(DocumentInfo::class);
        $excelAssembly = new ExcelAssembly($documentInfoMock);
        $excelAssembly->buildDocument();
        $docAssembly = new DocAssembly($documentInfoMock);
        $docAssembly->buildDocument();
    }

    public function testCreateDriverException()
    {
        $this->expectException(CreateDriverException::class);

        $documentInfoMock = $this->createMock(DocumentInfo::class);
        $documentInfoMock->method('getTemplatePath')
            ->willReturn('incorrect path');
        $excelAssembly = new ExcelAssembly($documentInfoMock);
        $this->invokeMethod($excelAssembly, 'createDriver');
        $docAssembly = new DocAssembly($documentInfoMock);
        $this->invokeMethod($docAssembly, 'createDriver');
    }

    private function checkDocumentAssembly(SetupCartridgeInterface $cartridge, string $documentAssemblyResultClass, array $allowedExt)
    {
        $documentAssembly = DocumentAssemblyFactory::createAssemblyDocumentByCartridge($cartridge);

        $documentAssemblyResult = $documentAssembly->buildDocument();

        $this->assertInstanceOf(DocumentAssemblyResultInterface::class, $documentAssemblyResult);
        $this->assertInstanceOf($documentAssemblyResultClass, $documentAssemblyResult);
        $this->assertTrue(file_exists($documentAssemblyResult->getTmpDocumentPath()));

        $tmpFileExt = pathinfo($documentAssemblyResult->getTmpDocumentPath(), PATHINFO_EXTENSION);
        $this->assertNotEmpty($tmpFileExt);
        $this->assertTrue(in_array($tmpFileExt, $allowedExt));
    }


}