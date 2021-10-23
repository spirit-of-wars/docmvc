<?php

namespace DocMVC\Assembly;

use DocMVC\Assembly\Info\FileInfo;
use DocMVC\Assembly\Info\FileInfoBuilder;
use DocMVC\Assembly\Info\FileInfoDirector;
use DocMVC\Cartridge\DocCartridge;
use DocMVC\Cartridge\ExcelCartridge;
use DocMVC\Cartridge\PdfCartridge;
use DocMVC\Cartridge\SetupCartridgeInterface;
use DocMVC\Exception\Assembly\AssemblyFileFactory\AssemblyCreateException;
use DocMVC\Exception\Assembly\AssemblyFileFactory\AssemblyFileFactoryExceptionInterface;
use DocMVC\Exception\Assembly\AssemblyFileFactory\FileInfoCreateException;
use DocMVC\Exception\Assembly\FileInfoBuilder\FileInfoBuilderExceptionInterface;

class AssemblyFileFactory
{

    private const ASSEMBLIES = [
        DocCartridge::class => DocAssembly::class,
        ExcelCartridge::class => ExcelAssembly::class,
        PdfCartridge::class => PdfAssembly::class,
    ];

    /**
     * @param SetupCartridgeInterface $cartridge
     * @return FileAssemblyInterface
     *
     * @throws AssemblyFileFactoryExceptionInterface
     */
    public static function createAssemblyFileByCartridge(SetupCartridgeInterface $cartridge): FileAssemblyInterface
    {
        $className = self::getAssemblyFileClassByCartridge($cartridge);
        $fileInfo = self::createFileInfo($cartridge);

        return new $className($fileInfo, $cartridge->getParams());
    }

    /**
     * @param SetupCartridgeInterface $cartridge
     * @return string
     *
     * @throws AssemblyCreateException
     */
    public static function getAssemblyFileClassByCartridge(SetupCartridgeInterface $cartridge): string
    {
        foreach (self::ASSEMBLIES as $cartridgeClassName => $assemblyClassName) {
            if ($cartridge instanceof $cartridgeClassName) {
                return $assemblyClassName;
            }
        }

        throw new AssemblyCreateException("No assembly found for cartridge " . get_class($cartridge));
    }

    /**
     * @param SetupCartridgeInterface $cartridge
     * @return Info\FileInfo
     *
     * @throws FileInfoCreateException
     */
    private static function createFileInfo(SetupCartridgeInterface $cartridge): FileInfo
    {
        try {
            $builder = new FileInfoBuilder($cartridge);
            $fileInfoDirector = new FileInfoDirector();
            $builder = $fileInfoDirector->buildFileInfo($builder);
        } catch (FileInfoBuilderExceptionInterface $e) {
            throw new FileInfoCreateException($e->getMessage(), $e->getCode(), $e);
        }

        return $builder->getFileInfo();
    }
}