<?php

namespace SpiritOfWars\DocMVC\Test\Data;

use SpiritOfWars\DocMVC\Cartridge\SetupCartridgeInterface;
use sample\test\Doc;
use sample\residentrfact\Excel;
use sample\residentrfact\Pdf;

class CartridgeTestFactory
{
    /**
     * @return SetupCartridgeInterface[]
     */
    public static function createTestCartridges(): array
    {
        $docCartridge = self::createTestDocCartridge([
            'randParam' => 'random param'
        ]);
        $excelCartridge = self::createTestExcelCartridge();
        $pdfCartridge = self::createTestPdfCartridge();

        return [$docCartridge, $excelCartridge, $pdfCartridge];
    }

    /**
     * @param array $params
     * @return Doc
     */
    public static function createTestDocCartridge($params = [])
    {
        $defaultParams = [
            'test' => 'test content'
        ];
        $resultParams = array_merge($defaultParams, $params);

        return new Doc($resultParams);
    }

    /**
     * @param array $params
     * @return Excel
     */
    public static function createTestExcelCartridge($params = [])
    {
        return new Excel($params);
    }

    /**
     * @param array $params
     * @return Pdf
     */
    public static function createTestPdfCartridge($params = [])
    {
        return new Pdf($params);
    }
}