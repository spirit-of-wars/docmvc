<?php

namespace DocMVC\Assembly;

use DocMVC\Assembly\Info\DocumentInfo;
use DocMVC\Assembly\Info\DocumentInfoBuilder;
use DocMVC\Assembly\Info\DocumentInfoDirector;
use DocMVC\Cartridge\CartridgeInterface;
use DocMVC\Cartridge\DocCartridge;
use DocMVC\Cartridge\ExcelCartridge;
use DocMVC\Cartridge\PdfCartridge;
use DocMVC\Cartridge\SetupCartridgeInterface;
use DocMVC\Exception\Assembly\AssemblyDocumentFactory\AssemblyDocumentCreateException;
use DocMVC\Exception\Assembly\AssemblyDocumentFactory\AssemblyDocumentFactoryExceptionInterface;
use DocMVC\Exception\Assembly\AssemblyDocumentFactory\DocumentInfoCreateException;
use DocMVC\Exception\Assembly\DocumentInfoBuilder\DocumentInfoBuilderExceptionInterface;

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

        return new $className($documentInfo, $cartridge->getParams());
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