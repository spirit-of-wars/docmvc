<?php

namespace DocMVC\Test\DocumentManager;

use DocMVC\Assembly\DocumentAssemblyFactory;
use DocMVC\DocumentManager\AssembledDocumentProcessor;
use DocMVC\DocumentManager\AssembledDocumentProcessorConfig;
use DocMVC\Exception\FileOperations\DirectoryPermissionException;
use DocMVC\Exception\FileOperations\FileNotExistedException;
use DocMVC\Test\Data\CartridgeTestFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class AssembledDocumentProcessorTest extends TestCase
{
    public function testCreateAssembledDocumentProcessor(): AssembledDocumentProcessor
    {
        $docCartridge = CartridgeTestFactory::createTestDocCartridge();
        $documentAssembly = DocumentAssemblyFactory::createAssemblyDocumentByCartridge($docCartridge);
        $documentAssemblyResult = $documentAssembly->buildDocument();

        $assembledDocumentProcessor =  new AssembledDocumentProcessor($documentAssemblyResult, new AssembledDocumentProcessorConfig(), new NullLogger());

        $this->assertInstanceOf(AssembledDocumentProcessor::class, $assembledDocumentProcessor);

        return $assembledDocumentProcessor;
    }

    /**
     * @depends testCreateAssembledDocumentProcessor
     */
    public function testSaveFileAs(AssembledDocumentProcessor $assembledDocumentProcessor)
    {
        $path = realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'testFile.docx';

        try {
            $assembledDocumentProcessor->saveAs($path);

            $this->assertTrue(file_exists($path));
            $this->assertTrue(is_file($path));
            unlink($path);
        } catch (FileNotExistedException | DirectoryPermissionException $e) {
            $this->markTestSkipped($e->getMessage());
        } catch (\Throwable $e) {
            $this->fail($e->getMessage());
        }
    }
}