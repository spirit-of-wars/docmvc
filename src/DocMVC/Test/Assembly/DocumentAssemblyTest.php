<?php

namespace DocMVC\Test\Assembly;

use DocMVC\Assembly\AssemblyResult\DocAssemblyResult;
use DocMVC\Assembly\AssemblyResult\DocumentAssemblyResultInterface;
use DocMVC\Assembly\AssemblyResult\ExcelAssemblyResult;
use DocMVC\Assembly\AssemblyResult\PdfAssemblyResult;
use DocMVC\Assembly\DocAssembly;
use DocMVC\Assembly\DocumentAssemblyFactory;
use DocMVC\Assembly\ExcelAssembly;
use DocMVC\Assembly\Info\DocumentInfo;
use DocMVC\Assembly\PdfAssembly;
use DocMVC\Cartridge\SetupCartridgeInterface;
use DocMVC\Exception\Assembly\AssemblyDocument\BuildDocumentException;
use DocMVC\Exception\Assembly\AssemblyDocument\CreateDriverException;
use DocMVC\Test\Data\AccessibleReflectionTrait;
use DocMVC\Test\Data\CartridgeTestFactory;
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