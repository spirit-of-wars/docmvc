<?php

namespace SpiritOfWars\DocMVC\Assembly;

use SpiritOfWars\DocMVC\Assembly\Info\DocumentInfo;
use SpiritOfWars\DocMVC\Assembly\Info\DocumentInfoBuilder;
use SpiritOfWars\DocMVC\Assembly\Info\DocumentInfoDirector;
use SpiritOfWars\DocMVC\Cartridge\CartridgeInterface;
use SpiritOfWars\DocMVC\Cartridge\DocCartridge;
use SpiritOfWars\DocMVC\Cartridge\ExcelCartridge;
use SpiritOfWars\DocMVC\Cartridge\PdfCartridge;
use SpiritOfWars\DocMVC\Cartridge\SetupCartridgeInterface;
use SpiritOfWars\DocMVC\Exception\Assembly\AssemblyDocumentFactory\AssemblyDocumentCreateException;
use SpiritOfWars\DocMVC\Exception\Assembly\AssemblyDocumentFactory\AssemblyDocumentFactoryExceptionInterface;
use SpiritOfWars\DocMVC\Exception\Assembly\AssemblyDocumentFactory\DocumentInfoCreateException;
use SpiritOfWars\DocMVC\Exception\Assembly\DocumentInfoBuilder\DocumentInfoBuilderExceptionInterface;

class DocumentAssemblyFactory
{

    private const ASSEMBLIES = [
        DocCartridge::class => DocAssembly::class,
        ExcelCartridge::class => ExcelAssembly::class,
        PdfCartridge::class => PdfAssembly::class,
    ];

    /**
     * @param SetupCartridgeInterface $cartridge
     *
     * @return DocumentAssemblyInterface
     * @throws AssemblyDocumentFactoryExceptionInterface
     */
    public static function createAssemblyDocumentByCartridge(SetupCartridgeInterface $cartridge): DocumentAssemblyInterface
    {
        $className = self::getAssemblyDocumentClassByCartridge($cartridge);
        $documentInfo = self::createDocumentInfo($cartridge);

        return new $className($documentInfo);
    }

    /**
     * @param CartridgeInterface $cartridge
     *
     * @return string
     * @throws AssemblyDocumentCreateException
     */
    public static function getAssemblyDocumentClassByCartridge(CartridgeInterface $cartridge): string
    {
        foreach (self::ASSEMBLIES as $cartridgeClassName => $assemblyClassName) {
            if ($cartridge instanceof $cartridgeClassName) {
                return $assemblyClassName;
            }
        }

        throw new AssemblyDocumentCreateException(sprintf("No assembly found for cartridge '%s'", get_class($cartridge)));
    }

    /**
     * @param SetupCartridgeInterface $cartridge
     *
     * @return Info\DocumentInfo
     * @throws DocumentInfoCreateException
     */
    private static function createDocumentInfo(SetupCartridgeInterface $cartridge): DocumentInfo
    {
        try {
            $builder = new DocumentInfoBuilder($cartridge);
            $documentInfoDirector = new DocumentInfoDirector();

            return $documentInfoDirector->buildDocumentInfo($builder);
        } catch (DocumentInfoBuilderExceptionInterface $e) {
            throw new DocumentInfoCreateException($e->getMessage(), $e->getCode(), $e);
        }
    }
}