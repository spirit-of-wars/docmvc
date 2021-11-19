<?php

namespace DocMVC\Test\Assembly;

use DocMVC\Assembly\DocAssembly;
use DocMVC\Assembly\DocumentAssemblyFactory;
use DocMVC\Assembly\DocumentAssemblyInterface;
use DocMVC\Assembly\ExcelAssembly;
use DocMVC\Assembly\PdfAssembly;
use DocMVC\Cartridge\CartridgeInterface;
use DocMVC\Cartridge\PdfCartridge;
use DocMVC\Cartridge\SetupCartridgeInterface;
use DocMVC\Exception\Assembly\AssemblyDocumentFactory\AssemblyDocumentCreateException;
use DocMVC\Exception\Assembly\AssemblyDocumentFactory\DocumentInfoCreateException;
use DocMVC\Exception\LogicException;
use DocMVC\Test\Data\CartridgeTestFactory;
use PHPUnit\Framework\TestCase;

class DocumentAssemblyFactoryTest extends TestCase
{

    public function testFactory()
    {
        $docCartridge = CartridgeTestFactory::createTestDocCartridge();
        $excelCartridge = CartridgeTestFactory::createTestExcelCartridge();
        $pdfCartridge = CartridgeTestFactory::createTestPdfCartridge();

        $this->checkDocumentAssemblyClassName($docCartridge, DocAssembly::class);
        $this->checkDocumentAssemblyClassName($excelCartridge, ExcelAssembly::class);
        $this->checkDocumentAssemblyClassName($pdfCartridge, PdfAssembly::class);

        $this->checkCreateAssemblyDocumentByCartridge($docCartridge, DocAssembly::class);
        $this->checkCreateAssemblyDocumentByCartridge($excelCartridge, ExcelAssembly::class);
        $this->checkCreateAssemblyDocumentByCartridge($pdfCartridge, PdfAssembly::class);
    }


    /**
     * @param CartridgeInterface $cartridge
     * @param string $expectedClass
     */
    private function checkDocumentAssemblyClassName(CartridgeInterface $cartridge, string $expectedClass)
    {
        $documentAssemblyClassName = DocumentAssemblyFactory::getAssemblyDocumentClassByCartridge($cartridge);
        $this->assertIsString($documentAssemblyClassName);
        $this->assertEquals($expectedClass, $documentAssemblyClassName);
    }

    /**
     * @param SetupCartridgeInterface $cartridge
     * @param string $expectedClass
     */
    private function checkCreateAssemblyDocumentByCartridge(SetupCartridgeInterface $cartridge, string $expectedClass)
    {
        $documentAssembly = DocumentAssemblyFactory::createAssemblyDocumentByCartridge($cartridge);
        $this->assertInstanceOf(DocumentAssemblyInterface::class, $documentAssembly);
        $this->assertInstanceOf($expectedClass, $documentAssembly);
    }

    public function testExceptionGetAssemblyDocument()
    {
        $this->expectException(AssemblyDocumentCreateException::class);

        $mockCartridge = $this->createMock(SetupCartridgeInterface::class);
        DocumentAssemblyFactory::getAssemblyDocumentClassByCartridge($mockCartridge);

    }

    public function testExceptionCreateAssemblyDocument()
    {
        $this->expectException(DocumentInfoCreateException::class);

        $mockCartridge = $this->createMock(PdfCartridge::class);
        DocumentAssemblyFactory::createAssemblyDocumentByCartridge($mockCartridge);
    }
}