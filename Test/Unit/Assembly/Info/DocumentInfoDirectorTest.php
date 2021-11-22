<?php

namespace SpiritOfWars\DocMVC\Test\Unit\Assembly\Info;

use SpiritOfWars\DocMVC\Assembly\Info\DocumentInfo;
use SpiritOfWars\DocMVC\Assembly\Info\DocumentInfoBuilder;
use SpiritOfWars\DocMVC\Assembly\Info\DocumentInfoDirector;
use SpiritOfWars\DocMVC\Cartridge\SetupCartridgeInterface;
use SpiritOfWars\DocMVC\Cartridge\SetupTemplateInterface;
use SpiritOfWars\DocMVC\Test\Data\CartridgeTestFactory;
use PHPUnit\Framework\TestCase;

class DocumentInfoDirectorTest extends TestCase
{
    /**
     * @var SetupCartridgeInterface[]
     */
    protected static $cartridges;

    /**
     * @var DocumentInfoBuilder[]
     */
    protected static $documentInfoBuilders;

    public static function setUpBeforeClass(): void
    {
        self::$cartridges = CartridgeTestFactory::createTestCartridges();

        foreach (self::$cartridges as $cartridge) {
            $cartridgeClassName = get_class($cartridge);
            self::$documentInfoBuilders[$cartridgeClassName] = new DocumentInfoBuilder($cartridge);
        }
    }

    public function testDirector()
    {
        $documentInfoDirector = new DocumentInfoDirector();

        foreach (self::$cartridges as $cartridge) {
            $builder = $this->getBuilderFromCartridge($cartridge);
            $documentInfo = $documentInfoDirector->buildDocumentInfo($builder);

            $this->assertInstanceOf(DocumentInfo::class, $documentInfo);
            $this->checkInitFields($documentInfo, $cartridge);
            $this->checkParams($documentInfo, $cartridge);
            $this->checkExtAndName($documentInfo, $cartridge);
            $this->checkTmp($documentInfo);
        }
    }

    /**
     * @param DocumentInfo $documentInfo
     * @param SetupCartridgeInterface $cartridge
     */
    private function checkInitFields(DocumentInfo $documentInfo, SetupCartridgeInterface $cartridge)
    {
        $model = $documentInfo->getModel();
        $this->assertIsArray($model);
        $this->assertNotEmpty($model);
        $this->assertEquals($cartridge->setupModel(), $model);

        $viewPath = $documentInfo->getViewPath();
        $this->assertNotEmpty($viewPath);
        $this->assertIsString($viewPath);
        $this->assertStringContainsString($cartridge->setupView(), $viewPath);

        $templatePath = $documentInfo->getTemplatePath();
        if ($cartridge instanceof SetupTemplateInterface && $cartridge->setupTemplate()) {
            $this->assertNotEmpty($templatePath);
            $this->assertIsString($templatePath);
            $this->assertStringContainsString($cartridge->setupTemplate(), $templatePath);
        } else {
            $this->assertEmpty($templatePath);
        }
    }

    /**
     * @param DocumentInfo $documentInfo
     * @param SetupCartridgeInterface $cartridge
     */
    private function checkParams(DocumentInfo $documentInfo, SetupCartridgeInterface $cartridge)
    {
        $params = $documentInfo->getParams();

        $this->assertIsArray($params);
        if ($cartridge->getParams()) {
            $this->assertNotEmpty($params);
            $this->assertEquals($cartridge->getParams(), $params);
        }
    }

    /**
     * @param DocumentInfo $documentInfo
     * @param SetupCartridgeInterface $cartridge
     */
    private function checkExtAndName(DocumentInfo $documentInfo, SetupCartridgeInterface $cartridge)
    {
        $documentExt = $documentInfo->getDocumentExt();

        $this->assertNotEmpty($documentExt);
        $this->assertIsString($documentExt);
        if ($cartridge->setupDocumentExt()) {
            $this->assertEquals($cartridge->setupDocumentExt(), $documentExt);
        }

        $documentName = $documentInfo->getDocumentName();
        $this->assertNotEmpty($documentName);
        $this->assertIsString($documentName);
        if ($cartridge->setupDocumentName()) {
            $cartridgeDocumentName = $cartridge->setupDocumentName() . '.' . $documentExt;
            $this->assertEquals($cartridgeDocumentName, $documentName);
        }
    }

    /**
     * @param DocumentInfo $documentInfo
     */
    private function checkTmp(DocumentInfo $documentInfo)
    {
        $this->assertNotEmpty($documentInfo->getTmpDocumentPath());
        $this->assertIsString($documentInfo->getTmpDocumentPath());
    }

    /**
     * @param SetupCartridgeInterface $cartridge
     * @return DocumentInfoBuilder
     */
    private function getBuilderFromCartridge(SetupCartridgeInterface $cartridge)
    {
        $cartridgeClass = get_class($cartridge);
        if (!$builder = self::$documentInfoBuilders[$cartridgeClass]) {
            $this->fail(sprintf("Not exist builder for cartridge '%s'", $cartridgeClass));
        }
        return $builder;
    }
}