<?php

namespace SpiritOfWars\DocMVC\Test\Unit\Assembly\Info;

use SpiritOfWars\DocMVC\Assembly\Info\DocumentInfoBuilder;
use SpiritOfWars\DocMVC\Cartridge\SetupCartridgeInterface;
use SpiritOfWars\DocMVC\Cartridge\SetupTemplateInterface;
use SpiritOfWars\DocMVC\Test\Data\CartridgeTestFactory;
use PHPUnit\Framework\TestCase;

class DocumentInfoBuilderTest extends TestCase
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

    public function testInit()
    {
        foreach (self::$cartridges as $cartridge) {
            $builder = $this->getBuilderFromCartridge($cartridge);
            $builder->init();

            $model = $builder->getDocumentInfo()->getModel();
            $this->assertIsArray($model);
            $this->assertNotEmpty($model);
            $this->assertEquals($cartridge->setupModel(), $model);

            $viewPath = $builder->getDocumentInfo()->getViewPath();
            $this->assertNotEmpty($viewPath);
            $this->assertIsString($viewPath);
            $this->assertStringContainsString($cartridge->setupView(), $viewPath);

            $templatePath = $builder->getDocumentInfo()->getTemplatePath();
            if ($cartridge instanceof SetupTemplateInterface && $cartridge->setupTemplate()) {
                $this->assertNotEmpty($templatePath);
                $this->assertIsString($templatePath);
                $this->assertStringContainsString($cartridge->setupTemplate(), $templatePath);
            } else {
                $this->assertEmpty($templatePath);
            }
        }

    }

    public function testInitParams()
    {
        foreach (self::$cartridges as $cartridge) {
            $builder = $this->getBuilderFromCartridge($cartridge);
            $builder->initParams();

            $params = $builder->getDocumentInfo()->getParams();

            $this->assertIsArray($params);
            if ($cartridge->getParams()) {
                $this->assertNotEmpty($params); //todo уронить валидацию
                $this->assertEquals($cartridge->getParams(), $params);
            }
        }
    }

    public function testInitDocumentExt()
    {
        foreach (self::$cartridges as $cartridge) {
            $builder = $this->getBuilderFromCartridge($cartridge);
            $builder->initDocumentExt();

            $documentExt = $builder->getDocumentInfo()->getDocumentExt();
            $this->assertNotEmpty($documentExt);
            $this->assertIsString($documentExt);
            if ($cartridge->setupDocumentExt()) {
                $this->assertEquals($cartridge->setupDocumentExt(), $documentExt);
            }

            $documentName = $builder->getDocumentInfo()->getDocumentName();
            $this->assertNotEmpty($documentName);
            $this->assertIsString($documentName);
            if ($cartridge->setupDocumentName()) {
                $cartridgeDocumentName = $cartridge->setupDocumentName() . '.' . $documentExt;
                $this->assertEquals($cartridgeDocumentName, $documentName);
            }
        }
    }

    public function testInitTmpDocumentPath()
    {
        foreach (self::$cartridges as $cartridge) {
            $builder = $this->getBuilderFromCartridge($cartridge);
            $builder->initTmpDocumentPath();
            $this->assertNotEmpty($builder->getDocumentInfo()->getTmpDocumentPath());
            $this->assertIsString($builder->getDocumentInfo()->getTmpDocumentPath());
        }
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